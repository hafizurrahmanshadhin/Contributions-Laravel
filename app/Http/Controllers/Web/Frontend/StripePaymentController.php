<?php

namespace App\Http\Controllers\Web\Frontend;

use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Controller;
use App\Models\Collection;
use App\Models\Payment;
use App\Notifications\ContributionNotification;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Stripe\Checkout\Session as StripeSession;
use Stripe\Stripe;
use Throwable;

class StripePaymentController extends Controller {
    /**
     * Confirmed Membership Redirect to stripe
     *
     * @param Request $request
     */
    public function confirmed(Request $request): RedirectResponse {
        $request->validate([
            'name'          => 'required|string|max:255',
            'amount'        => 'required|numeric|min:1',
            'paymentMethod' => 'required|string|in:stripe,paypal',
            'collection_id' => 'required|string|max:255',
        ]);

        try {
            $id = $request->collection_id;
        } catch (Throwable $th) {
            return back()->with('error', 'Collection not found.');
        }

        $collection = Collection::where('id', $id)->first();
        if (!$collection) {
            return back()->with('error', 'Collection not found.');
        }

        $price = $request->amount;

        Stripe::setApiKey(config('stripe.secrate_key')); // Corrected typo from 'secrate_key' to 'secret_key'
        $successUrl = route('membership.payment.success') . '?session_id={CHECKOUT_SESSION_ID}&collection_id=' . $id . '&name=' . $request->name . '&amount=' . $request->amount;

        // Corrected parameter from 'payment_method_validitys' to 'payment_method_types'
        $session = StripeSession::create([
            'payment_method_types' => ['card'],
            'line_items'           => [
                [
                    'price_data' => [
                        'currency'     => 'usd',
                        'product_data' => [
                            'name' => "Subscribe" . $collection->name,
                        ],
                        'unit_amount'  => round($price * 100),
                    ],
                    'quantity'   => 1,
                ],
            ],
            'mode'                 => 'payment',
            'customer_email'       => auth()->check() ? auth()->user()->email : "anonymous@anonymous.com",
            'success_url'          => $successUrl,
            'cancel_url'           => route('membership.payment.cancled'),
        ]);

        return redirect()->away($session->url);
    }

    /**
     * SUccess Url for stripe
     *
     * @param Request $request
     * @return RedirectResponse|View
     */
    public function success(Request $request): RedirectResponse | View {
        DB::beginTransaction();
        try {
            $stripe  = new \Stripe\StripeClient(Config::get('stripe.secrate_key'));
            $session = $stripe->checkout->sessions->retrieve($request->session_id);

            //? Check if collection exists
            $collection = Collection::where('id', $request->collection_id)->first();
            if (!$collection) {
                return redirect()->route('home')->with('error', 'collection not found');
            }

            //! Check if transaction already exists
            $order = Payment::where('transaction_id', $session->payment_intent)->first();
            if (!empty($session) && $session->payment_status == 'paid' && empty($order)) {
                $payment = Payment::create([
                    'user_id'        => Auth::check() ? Auth::user()->id : null,
                    'collection_id'  => $request->collection_id,
                    'name'           => $request->name,
                    'amount'         => $request->amount,
                    'transaction_id' => $session->payment_intent,
                ]);

                DB::commit();

                //* Calculate the total donations
                $totalDonations = $collection->getTotalDonations();

                //* Donation Target Parsentage
                $parsentage = ($totalDonations / $collection->target_amount) * 100;

                //* Calculate participants
                $payments        = Payment::where('collection_id', $collection->id)->get();
                $uniqueUserIds   = $payments->pluck('user_id')->filter()->unique()->count();
                $nullUserIdCount = $payments->whereNull('user_id')->count();
                $participants    = $uniqueUserIds + $nullUserIdCount;

                // *** Notifications ***
                $contributor = Auth::user() ?? (object) [
                    'id'   => null,
                    'name' => $request->name,
                ];

                $collectionOwner = $collection->user;

                //! Send notification
                $collectionOwner->notify(new ContributionNotification($contributor, $collection, $request->amount));

                //* Create a custom notification title with the collection name
                $notificationTitle = sprintf("%s", $collection->name);

                //! Send push notification
                $message                = sprintf("%s donated to your collection $%s", $contributor->name, $request->amount);
                $notificationController = new NotificationController();
                $notificationController->sendNotifyMobile($collectionOwner->id, $notificationTitle, $message);
                // *** Notifications ***

                return view('frontend.donate-success', compact(
                    'payment',
                    'collection',
                    'totalDonations',
                    'parsentage',
                    'participants'
                ))->with('success', 'Congratulation Membership purchased successfully.');
            } else {
                return redirect()->route('home')->with('error', 'Something went wrong.');
            }

        } catch (Exception $exception) {
            DB::rollBack();
            return redirect()->route('home')->with('error', $exception->getMessage());
        }
    }

    /**
     * Cancel Membership Payment
     *
     * @return RedirectResponse
     */
    public function cancel(): RedirectResponse {
        return redirect()->route('home')->with('error', 'Payment has been cancled.');
    }
}

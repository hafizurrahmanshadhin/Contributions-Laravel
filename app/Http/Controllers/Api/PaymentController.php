<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\Collection;
use App\Models\Payment;
use App\Notifications\ContributionNotification;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller {
    /**
     * Store a newly created payment and send notifications.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function Store(Request $request): JsonResponse {
        $request->validate([
            'collection_id'  => 'required|exists:collections,id',
            'amount'         => 'required|numeric|min:1',
            'transaction_id' => 'required|string|unique:payments,transaction_id',
            'name'           => 'nullable|string|max:255',
            'user_id'        => 'nullable|exists:users,id',
        ]);

        try {
            DB::beginTransaction();

            $collection = Collection::findOrFail($request->collection_id);

            //! Check if the collection's deadline has passed
            if (Carbon::now()->greaterThan($collection->deadline)) {
                return Helper::jsonResponse(false, 'The donation period for this collection has ended.', 403);
            }

            $payment = Payment::create([
                'user_id'        => $request->user_id,
                'collection_id'  => $request->collection_id,
                'name'           => $request->name,
                'amount'         => $request->amount,
                'transaction_id' => $request->transaction_id,
            ]);

            //* Retrieve the collection and its owner
            $collectionOwner = $collection->user;

            //! Get the contributor (authenticated user or anonymous)
            $contributor = Auth::user() ?? (object) [
                'id'   => null,
                'name' => $request->name,
            ];

            //* Send the database notification
            $collectionOwner->notify(new ContributionNotification($contributor, $collection, $request->amount));

            //! Send push notification
            $notificationTitle = sprintf("%s", $collection->name);
            $message           = sprintf("%s donated to your collection $%s", $contributor->name, $request->amount);

            //* Use the NotificationController to send push notification
            $notificationController = new NotificationController();
            $notificationController->sendNotifyMobile($collectionOwner->id, $notificationTitle, $message);

            DB::commit();

            return Helper::jsonResponse(true, 'Payment stored successfully', 200, $payment);
        } catch (Exception $e) {
            DB::rollBack();
            return Helper::jsonResponse(false, 'An error occurred while storing the payment', 500, ['error' => $e->getMessage()]);
        }
    }
}

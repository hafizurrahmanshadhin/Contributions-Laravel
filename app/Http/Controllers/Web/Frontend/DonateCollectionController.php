<?php

namespace App\Http\Controllers\Web\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Collection;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class DonateCollectionController extends Controller {
    /**
     * Display Collection Details for Donation.
     *
     * @param $collection_id
     * @return RedirectResponse | View
     */
    public function donate($collection_id): RedirectResponse | View {
        $collection = Collection::where('id', $collection_id)->with('donations')->first();

        //? Check if the collection is found
        if (!$collection) {
            return back()->with('t-error', 'Collection not found.');
        }

        //* Calculate the total donations
        $totalDonations = $collection->getTotalDonations();

        //* Donation Target Percentage
        $percentage = ($totalDonations / $collection->target_amount) * 100;

        //! Calculate the participants
        $payments        = $collection->donations;
        $uniqueUserIds   = $payments->pluck('user_id')->filter()->unique()->count();
        $nullUserIdCount = $payments->whereNull('user_id')->count();
        $participants    = $uniqueUserIds + $nullUserIdCount;

        //? Check if the collection's deadline has passed
        if (Carbon::now()->greaterThan($collection->deadline)) {
            $message = 'The donation period for this collection has ended.';
            return view('frontend.donate', compact(
                'collection',
                'totalDonations',
                'percentage',
                'participants',
                'message'
            ));
        }

        return view('frontend.donate', compact(
            'collection',
            'totalDonations',
            'percentage',
            'participants'
        ));
    }
}

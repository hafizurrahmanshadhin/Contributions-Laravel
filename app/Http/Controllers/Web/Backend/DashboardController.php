<?php

namespace App\Http\Controllers\Web\Backend;

use App\Http\Controllers\Controller;
use App\Models\Collection;
use App\Models\Payment;
use App\Models\User;
use App\Models\Withdraw;

class DashboardController extends Controller {
    public function index() {
        $user = User::where('role', 'user')
            ->where('status', 'active')
            ->whereNotNull('email_verified_at')
            ->whereNull('deleted_at')
            ->count();

        $collection = Collection::where('status', 'active')
            ->whereNull('deleted_at')
            ->count();

        //* Count unique contributors
        $contributer = Payment::whereNull('deleted_at')
            ->get();
        $uniqueUserIds     = $contributer->pluck('user_id')->filter()->unique()->count();
        $nullUserIdCount   = $contributer->whereNull('user_id')->count();
        $totalContributors = $uniqueUserIds + $nullUserIdCount;

        $withdrawRequest = Withdraw::whereNull('deleted_at')
            ->where('status', 'pending')
            ->count();

        return view('backend.layouts.dashboard.index', compact(
            'user',
            'collection',
            'totalContributors',
            'withdrawRequest',
        ));
    }
}

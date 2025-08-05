<?php

namespace App\Http\Controllers\Web\Backend\Settings;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\View\View;
use Throwable;

class StripeSettingController extends Controller {
    public function index() {
        return view('backend.layouts.settings.stripe_settings');
    }

    public function update(Request $request) {
        if (User::find(auth()->user()->id)) {
            $request->validate([
                'STRIPE_PUBLIC_KEY'  => 'nullable|string',
                'STRIPE_SECRATE_KEY' => 'nullable|string',
            ]);
            try {
                $envContent = File::get(base_path('.env'));
                $lineBreak  = "\n";
                $envContent = preg_replace([
                    '/STRIPE_PUBLIC_KEY=(.*)\s/',
                    '/STRIPE_SECRATE_KEY=(.*)\s/',
                ], [
                    'STRIPE_PUBLIC_KEY=' . $request->STRIPE_PUBLIC_KEY . $lineBreak,
                    'STRIPE_SECRATE_KEY=' . $request->STRIPE_SECRATE_KEY . $lineBreak,
                ], $envContent);

                if ($envContent !== null) {
                    File::put(base_path('.env'), $envContent);
                }
                return redirect()->back()->with('t-success', 'Stripe Setting Update successfully.');
            } catch (Throwable) {
                return redirect()->back()->with('t-error', 'Stripe Setting Update Failed');
            }
        }
        return redirect()->back();
    }
}

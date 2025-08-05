<?php

// namespace App\Http\Controllers\Web\Backend\Settings;

// use App\Http\Controllers\Controller;
// use App\Models\User;
// use Exception;
// use Illuminate\Http\RedirectResponse;
// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\File;

// class FacebookSettingsController extends Controller {
//     public function index() {
//         if (User::find(auth()->user()->id)) {
//             return view('backend.layouts.settings.facebook_settings');
//         }
//     }

//     /**
//      * Update the Google Analytics settings in the database.
//      *
//      * @param Request $request
//      * @return RedirectResponse
//      */
//     public function update(Request $request) {
//         if (User::find(auth()->user()->id)) {
//             $request->validate([
//                 'facebook_client'        => 'nullable|string',
//                 'facebook_client_secret' => 'nullable|string',
//                 'facebook_redirect_uri'  => 'nullable|string',
//             ]);
//             try {
//                 $envContent = File::get(base_path('.env'));
//                 $lineBreak  = "\n";
//                 $envContent = preg_replace([
//                     '/FACEBOOK_CLIENT_ID=(.*)\s/',
//                     '/FACEBOOK_CLIENT_SECRET=(.*)\s/',
//                     '/FACEBOOK_REDIRECT_URI=(.*)\s/',
//                 ], [
//                     'FACEBOOK_CLIENT_ID=' . $request->facebook_client . $lineBreak,
//                     'FACEBOOK_CLIENT_SECRET=' . $request->facebook_client_secret . $lineBreak,
//                     'FACEBOOK_REDIRECT_URI=' . $request->facebook_redirect_uri . $lineBreak,
//                 ], $envContent);

//                 if ($envContent !== null) {
//                     File::put(base_path('.env'), $envContent);
//                 }
//                 return back()->with('t-success', 'Updated successfully');
//             } catch (Exception) {
//                 return back()->with('t-error', 'Failed to update');
//             }
//         }
//         return redirect()->back();
//     }
// }

<?php

// namespace App\Http\Controllers\Web\Backend\Settings;

// use App\Http\Controllers\Controller;
// use App\Models\User;
// use Exception;
// use Illuminate\Http\RedirectResponse;
// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\File;
// use Illuminate\View\View;

// class GoogleSettingsController extends Controller {

//     public function index() {
//         if (User::find(auth()->user()->id)) {
//             return view('backend.layouts.settings.google_settings');
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
//                 'google_client'        => 'nullable|string',
//                 'google_client_secret' => 'nullable|string',
//                 'google_redirect_uri'  => 'nullable|string',
//             ]);
//             try {
//                 $envContent = File::get(base_path('.env'));
//                 $lineBreak  = "\n";
//                 $envContent = preg_replace([
//                     '/GOOGLE_CLIENT_ID=(.*)\s/',
//                     '/GOOGLE_CLIENT_SECRET=(.*)\s/',
//                     '/GOOGLE_REDIRECT_URI=(.*)\s/',
//                 ], [
//                     'GOOGLE_CLIENT_ID=' . $request->google_client . $lineBreak,
//                     'GOOGLE_CLIENT_SECRET=' . $request->google_client_secret . $lineBreak,
//                     'GOOGLE_REDIRECT_URI=' . $request->google_redirect_uri . $lineBreak,
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

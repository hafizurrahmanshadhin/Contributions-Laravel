<?php

namespace App\Http\Controllers\Web\Backend\Settings;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller {
    public function index() {
        $userDetails = User::where('id', auth()->user()->id)->first();
        return view('backend.layouts.settings.profile_settings ', compact('userDetails'));
    }

    public function UpdateProfile(Request $request) {
        $validator = Validator::make($request->all(), [
            'name'    => 'nullable|max:100|min:2',
            'email'   => 'nullable|email|unique:users,email,' . auth()->user()->id,
            'phone'   => 'nullable|min:2|max:15',
            'address' => 'nullable|max:255',

        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        try {
            $user          = User::find(auth()->user()->id);
            $user->name    = $request->name;
            $user->email   = $request->email;
            $user->phone   = $request->phone;
            $user->address = $request->address;

            $user->save();
            return redirect()->back()->with('t-success', 'Profile updated successfully');
        } catch (Exception) {
            return redirect()->back()->with('t-error', 'Something went wrong');
        }
    }
    public function UpdatePassword(Request $request) {
        $validator = Validator::make($request->all(), [
            'old_password' => 'required',
            'password'     => 'required|confirmed|min:8',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        try {
            $user = Auth::user();
            if (Hash::check($request->old_password, $user->password)) {
                $user->password = Hash::make($request->password);
                $user->save();

                return redirect()->back()->with('t-success', 'Password updated successfully');
            } else {
                return redirect()->back()->with('t-error', 'Current password is incorrect');
            }
        } catch (Exception) {
            return redirect()->back()->with('t-error', 'Something went wrong');
        }
    }

    public function updateProfilePicture(Request $request) {
        try {
            $request->validate([
                'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:4048',
            ]);

            $userDetails = User::where('id', auth()->user()->id)->first();

            if ($userDetails->avatar) {
                $previousImagePath = public_path($userDetails->avatar);
                Helper::fileDelete($previousImagePath);
            }

            if ($request->hasFile('avatar')) {
                $image               = $request->file('avatar');
                $imageName           = Helper::fileUpload($image, 'avatars', time());
                $userDetails->avatar = $imageName;
            }

            $userDetails->save();

            return response()->json([
                'success'   => true,
                'image_url' => asset($userDetails->avatar),
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while uploading the profile picture.',
            ]);
        }
    }
}

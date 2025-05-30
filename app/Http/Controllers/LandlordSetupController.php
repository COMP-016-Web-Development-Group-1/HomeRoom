<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;

class LandlordSetupController extends Controller
{
    public function edit()
    {
        $user = auth()->user();

        // Only allow landlords or incomplete profiles
        if ($user->role !== 'landlord' || $user->profile_completed) {
            return redirect()->route('dashboard');
        }

        return view('landlord.setup', compact('user'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        if ($user->role !== 'landlord' || $user->profile_completed) {
            return redirect()->route('dashboard');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'password' => ['required', 'confirmed', Password::defaults()],
            'profile_picture' => 'required|image|mimes:jpeg,png|max:2048',
            'gcash_qr' => 'required|image|mimes:jpeg,png|max:2048',
            'maya_qr' => 'required|image|mimes:jpeg,png|max:2048',
        ]);

        // Handle profile picture upload
        if ($request->hasFile('profile_picture')) {
            $profilePicturePath = $request->file('profile_picture')->store('profile_pictures', 'public');
            $user->profile_picture = $profilePicturePath;
        }

        // Update user information
        $user->update([
            'name' => $request->name,
            'profile_completed' => true,
        ]);

        // Update landlord QR codes
        $landlord = $user->landlord;

        if ($request->hasFile('gcash_qr')) {
            $gcashPath = $request->file('gcash_qr')->store('qr_codes', 'public');
            $landlord->gcash_qr = $gcashPath;
        }

        if ($request->hasFile('maya_qr')) {
            $mayaPath = $request->file('maya_qr')->store('qr_codes', 'public');
            $landlord->maya_qr = $mayaPath;
        }

        $landlord->save();

        session()->flash('toast.success', [
            'title' => 'Setup Complete',
            'content' => 'Your landlord profile has been successfully set up.',
        ]);

        return redirect()->route('dashboard')->with('success', 'Profile setup completed successfully!');
    }
}

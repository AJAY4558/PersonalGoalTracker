<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

/**
 * ProfileController
 *
 * Handles user profile management:
 *   - View profile
 *   - Update name/email
 *   - Change password
 *   - Upload avatar
 *
 * Demonstrates: File uploads, validation, session flash messages
 */
class ProfileController extends Controller
{

    /** Show profile page */
    public function index()
    {
        $user = Auth::user();
        return view('profile.index', compact('user'));
    }

    /** Update profile info */
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name'   => ['required', 'string', 'min:2', 'max:100'],
            'email'  => ['required', 'email', 'unique:users,email,' . $user->id],
            'avatar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,gif', 'max:2048'],
        ]);

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            // Delete old avatar
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $avatarPath;
        }

        $user->name  = $request->name;
        $user->email = $request->email;
        $user->save();

        return redirect()->route('profile')->with('success', 'Profile updated successfully!');
    }

    /** Update password */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required'],
            'password'         => ['required', 'confirmed', Password::min(8)],
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('profile')->with('success', 'Password changed successfully!');
    }

    /** Update locale preference */
    public function updateLocale(Request $request)
    {
        $validated = $request->validate(['locale' => ['required', 'in:en,hi']]);

        Auth::user()->update(['locale' => $validated['locale']]);
        session(['locale' => $validated['locale']]);
        app()->setLocale($validated['locale']);

        return back()->with('success', 'Language preference saved!');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProfileController extends Controller
{
    public function show()
    {
        $user = User::find(Auth::id());
        return view('laravel-examples.user-profile', compact('user'));
    }

    public function index()
    {
        $user = User::find(Auth::id());
        return view('laravel-examples.user-profile', compact('user'));
    }

    public function update(Request $request)
    {
        if (config('app.is_demo') && in_array(Auth::id(), [1])) {
            return back()->with('error', "You are in a demo version. You are not allowed to change the email for default users.");
        }

        $request->validate([
            'name' => 'required|min:3|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . Auth::id(),
            'location' => 'max:255',
            'phone' => 'numeric|digits:10',
            'about' => 'max:255',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'name.required' => 'Name is required',
            'email.required' => 'Email is required',
            'avatar.image' => 'File must be an image',
            'avatar.mimes' => 'Avatar must be a file of type: jpeg, png, jpg, gif',
            'avatar.max' => 'Avatar size must not exceed 2MB',
        ]);

        $user = User::find(Auth::id());
        
        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
            'location' => $request->location,
            'phone' => $request->phone,
            'about' => $request->about,
        ];

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($user->avatar && Storage::disk('public')->exists('avatars/' . $user->avatar)) {
                Storage::disk('public')->delete('avatars/' . $user->avatar);
            }

            // Store new avatar
            $avatarFile = $request->file('avatar');
            $avatarName = time() . '_' . Str::random(10) . '.' . $avatarFile->getClientOriginalExtension();
            $avatarFile->storeAs('avatars', $avatarName, 'public');
            $updateData['avatar'] = $avatarName;
        }

        $user->update($updateData);

        return back()->with('success', 'Profile updated successfully.');
    }

    public function removeAvatar()
    {
        $user = User::find(Auth::id());
        
        if ($user->avatar && Storage::disk('public')->exists('avatars/' . $user->avatar)) {
            Storage::disk('public')->delete('avatars/' . $user->avatar);
        }
        
        $user->update(['avatar' => null]);
        
        return back()->with('success', 'Avatar removed successfully.');
    }
}

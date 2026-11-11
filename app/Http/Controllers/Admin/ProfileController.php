<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /**
     * Show admin profile edit view
     */
    public function edit()
    {
        $admin = auth('admin')->user();
        return view('admin.profile.index', compact('admin'));
    }

    /**
     * Update admin name and email
     */
    public function updateProfile(Request $request)
    {
        $admin = auth('admin')->user();

        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:admins,email,' . $admin->id,
        ]);

        $admin->update([
            'name'  => $request->name,
            'email' => $request->email,
        ]);

        return back()->with('success', 'Profile information updated successfully!');
    }

    /**
     * Update admin password
     */
    public function updatePassword(Request $request)
    {
        $admin = auth('admin')->user();

        $request->validate([
            'current_password' => 'required',
            'password'         => 'required|string|min:6|confirmed',
        ]);

        if (!Hash::check($request->current_password, $admin->password)) {
            return back()->withErrors(['current_password' => 'The provided current password does not match our records.'])->withInput();
        }

        $admin->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Admin password changed successfully!');
    }
}

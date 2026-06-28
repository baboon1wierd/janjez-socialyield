<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class ProfileController extends Controller
{
    public function edit()
    {
        return view('profile.edit');
    }

    public function update(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . auth()->id(),
            'bio' => 'nullable|string',
            'company' => 'nullable|string',
            'website' => 'nullable|url'
        ]);

        $user = auth()->user();
        $user->update($request->only('full_name', 'email', 'bio', 'company', 'website'));

        return back()->with('success', 'Profile updated successfully!');
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'password' => 'required|string'
        ]);

        $user = auth()->user();

        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors(['password' => 'Invalid password']);
        }

        $user->delete();

        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Account deleted successfully');
    }
}

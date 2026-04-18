<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.index');
    }

















    public function AdminLogin()
    {
        return view('admin.admin_login');
    }

    public function adminProfile()
    {
        return view('profile.edit', [
            'user' => auth()->user(),
        ]);
    }

    public function adminProfileStore(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . auth()->id(),
        ]);

        auth()->user()->update($validated);

        return back()->with('message', 'Profile updated successfully.')->with('alert-type', 'success');
    }

    public function adminChangePassword()
    {
        return view('profile.edit', [
            'user' => auth()->user(),
        ]);
    }

    public function adminUpdatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if (!Hash::check($validated['current_password'], auth()->user()->password)) {
            return back()->withErrors([
                'current_password' => 'The current password is incorrect.',
            ]);
        }

        auth()->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('message', 'Password updated successfully.')->with('alert-type', 'success');
    }

    public function AdminDestroy(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        $notification = [
            'message' => 'Logout Successfully',
            'alert-type' => 'success'
        ];
        
        return redirect('/login')->with($notification);
    }
}

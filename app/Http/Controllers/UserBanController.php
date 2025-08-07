<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class UserBanController extends Controller
{
    /**
     * Display a listing of users with ban management
     */
    public function index()
    {
        $users = User::with(['bannedBy', 'banLiftedBy'])
            ->where('role', '!=', 'admin') // Don't show admin users
            ->latest()
            ->paginate(20);

        return view('backend.user-management.bans.index', compact('users'));
    }

    /**
     * Show banned users only
     */
    public function bannedUsers()
    {
        $users = User::banned()
            ->with(['bannedBy', 'banLiftedBy'])
            ->where('role', '!=', 'admin')
            ->latest('banned_at')
            ->paginate(20);

        return view('backend.user-management.bans.banned', compact('users'));
    }

    /**
     * Ban a user
     */
    public function banUser(Request $request, $id)
    {
        $request->validate([
            'ban_reason' => 'required|string|max:1000',
        ]);

        $user = User::findOrFail($id);
        $admin = Auth::user();

        // Prevent banning admin users
        // if ($user->role === 'admin') {
        //     return redirect()->back()
        //         ->with('message', 'Cannot ban admin users')
        //         ->with('alert-type', 'error');
        // }

        // Prevent self-banning
        if ($user->id === $admin->id) {
            return redirect()->back()
                ->with('message', 'You cannot ban yourself')
                ->with('alert-type', 'error');
        }

        // Check if user is already banned
        if ($user->isBanned()) {
            return redirect()->back()
                ->with('message', 'User is already banned')
                ->with('alert-type', 'warning');
        }

        // Ban the user
        $success = $user->banUser($request->ban_reason, $admin);

        if ($success) {
            return redirect()->back()
                ->with('message', 'User has been banned successfully')
                ->with('alert-type', 'success');
        }

        return redirect()->back()
            ->with('message', 'Failed to ban user')
            ->with('alert-type', 'error');
    }

    /**
     * Lift a user ban
     */
    public function liftBan(Request $request, $id)
    {
        $request->validate([
            'ban_lift_reason' => 'required|string|max:1000',
        ]);

        $user = User::findOrFail($id);
        $admin = Auth::user();

        // Check if user is actually banned
        if (!$user->isBanned()) {
            return redirect()->back()
                ->with('message', 'User is not currently banned')
                ->with('alert-type', 'warning');
        }

        // Lift the ban
        $success = $user->liftBan($request->ban_lift_reason, $admin);

        if ($success) {
            return redirect()->back()
                ->with('message', 'User ban has been lifted successfully')
                ->with('alert-type', 'success');
        }

        return redirect()->back()
            ->with('message', 'Failed to lift user ban')
            ->with('alert-type', 'error');
    }

    /**
     * Show ban details for a specific user
     */
    public function showBanDetails($id)
    {
        $user = User::with(['bannedBy', 'banLiftedBy'])->findOrFail($id);
        $banDetails = $user->getBanDetails();

        return view('backend.user-management.bans.details', compact('user', 'banDetails'));
    }

    /**
     * Get ban history for all users (for reporting)
     */
    public function banHistory()
    {
        $banHistory = User::where(function($query) {
                $query->where('is_banned', true)
                      ->orWhereNotNull('ban_lifted_at');
            })
            ->with(['bannedBy', 'banLiftedBy'])
            ->where('role', '!=', 'admin')
            ->orderBy('banned_at', 'desc')
            ->paginate(20);

        return view('backend.user-management.bans.history', compact('banHistory'));
    }

    /**
     * Quick ban/unban toggle (for AJAX requests)
     */
    public function toggleBan(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $admin = Auth::user();

        // Prevent banning admin users
        // if ($user->role === 'admin') {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Cannot ban admin users'
        //     ]);
        // }

        // Prevent self-banning
        if ($user->id === $admin->id) {
            return response()->json([
                'success' => false,
                'message' => 'You cannot ban yourself'
            ]);
        }

        if ($user->isBanned()) {
            // Lift ban
            $reason = $request->input('ban_lift_reason', 'Quick unban by admin');
            $success = $user->liftBan($reason, $admin);
            $message = $success ? 'User ban lifted successfully' : 'Failed to lift ban';
        } else {
            // Ban user
            $reason = $request->input('ban_reason', 'Quick ban by admin');
            $success = $user->banUser($reason, $admin);
            $message = $success ? 'User banned successfully' : 'Failed to ban user';
        }

        return response()->json([
            'success' => $success,
            'message' => $message,
            'is_banned' => $user->fresh()->isBanned()
        ]);
    }
}

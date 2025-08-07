<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class AdminManageBanController extends Controller
{
    /**
     * Display all users with ban management interface
     */
    public function index()
    {
        $users = User::with(['bannedBy', 'banLiftedBy'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('backend.user-bans.index', compact('users'));
    }

    /**
     * Display only banned users
     */
    public function bannedUsers()
    {
        $users = User::where('is_banned', true)
            ->with(['bannedBy', 'banLiftedBy'])
            ->orderBy('banned_at', 'desc')
            ->paginate(15);

        return view('backend.user-bans.index', compact('users'));
    }

    /**
     * Display ban history (users who have been banned before)
     */
    public function banHistory()
    {
        $users = User::where(function($query) {
                $query->where('is_banned', true)
                      ->orWhereNotNull('ban_lifted_at');
            })
            ->with(['bannedBy', 'banLiftedBy'])
            ->orderBy('banned_at', 'desc')
            ->paginate(15);

        return view('backend.user-bans.index', compact('users'));
    }

    /**
     * Ban a user (Updated to allow admin-to-admin banning)
     */
    public function banUser(Request $request, $id)
    {
        $request->validate([
            'ban_reason' => 'required|string|max:1000',
        ]);

        $user = User::findOrFail($id);
        $admin = Auth::user();

        // SECURITY CHECK 1: Prevent self-banning (only essential check)
        if ($user->id === $admin->id) {
            return redirect()->back()
                ->with('message', 'You cannot ban yourself')
                ->with('alert-type', 'error');
        }

        // SECURITY CHECK 2: Check if user is already banned
        if ($user->is_banned) {
            return redirect()->back()
                ->with('message', 'User is already banned')
                ->with('alert-type', 'warning');
        }

        // OPTIONAL: Add warning for admin banning (but still allow it)
        $isAdminTarget = ($user->role === 'admin');
        
        // Ban the user using pure Laravel
        DB::beginTransaction();
        try {
            $user->update([
                'is_banned' => true,
                'banned_at' => now(),
                'ban_reason' => $request->ban_reason,
                'banned_by' => $admin->id,
                'ban_lifted_at' => null,
                'ban_lifted_by' => null,
                'ban_lift_reason' => null,
                'is_logged_in' => 0, // Force logout
            ]);

            DB::commit();

            $message = $isAdminTarget 
                ? "Admin user {$user->name} has been banned successfully"
                : "User {$user->name} has been banned successfully";

            return redirect()->back()
                ->with('message', $message)
                ->with('alert-type', 'success');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('message', 'Failed to ban user. Please try again.')
                ->with('alert-type', 'error');
        }
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

        if (!$user->is_banned) {
            return redirect()->back()
                ->with('message', 'User is not currently banned')
                ->with('alert-type', 'warning');
        }

        // Lift the ban using pure Laravel
        DB::beginTransaction();
        try {
            $user->update([
                'is_banned' => false,
                'ban_lifted_at' => now(),
                'ban_lifted_by' => $admin->id,
                'ban_lift_reason' => $request->ban_lift_reason,
            ]);

            DB::commit();

            $isAdminTarget = ($user->role === 'admin');
            $message = $isAdminTarget 
                ? "Ban lifted for admin {$user->name} successfully"
                : "Ban lifted for {$user->name} successfully";

            return redirect()->back()
                ->with('message', $message)
                ->with('alert-type', 'success');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('message', 'Failed to lift user ban. Please try again.')
                ->with('alert-type', 'error');
        }
    }

    /**
     * Show user details page
     */
    public function showDetails($id)
    {
        $user = User::with(['bannedBy', 'banLiftedBy'])->findOrFail($id);
        
        return view('backend.user-bans.details', compact('user'));
    }

    /**
     * Get user details for AJAX (if needed later)
     */
    public function getUserDetails($id)
    {
        $user = User::with(['bannedBy', 'banLiftedBy'])->findOrFail($id);
        
        return response()->json([
            'success' => true,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'status' => $user->status,
                'is_banned' => $user->is_banned,
                'banned_at' => $user->banned_at ? $user->banned_at->format('F d, Y H:i') : null,
                'banned_by' => $user->bannedBy ? $user->bannedBy->name : null,
                'ban_reason' => $user->ban_reason,
                'ban_lifted_at' => $user->ban_lifted_at ? $user->ban_lifted_at->format('F d, Y H:i') : null,
                'ban_lifted_by' => $user->banLiftedBy ? $user->banLiftedBy->name : null,
                'ban_lift_reason' => $user->ban_lift_reason,
                'last_login_at' => $user->last_login_at ? $user->last_login_at->format('F d, Y H:i') : null,
                'created_at' => $user->created_at->format('F d, Y'),
            ]
        ]);
    }

    /**
     * Quick toggle ban status (AJAX) - Updated to allow admin banning
     */
    public function quickToggleBan(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $admin = Auth::user();

        // Only prevent self-banning
        if ($user->id === $admin->id) {
            return response()->json([
                'success' => false,
                'message' => 'You cannot ban yourself'
            ]);
        }

        DB::beginTransaction();
        try {
            if ($user->is_banned) {
                // Lift ban
                $reason = $request->input('reason', 'Quick unban by admin');
                $user->update([
                    'is_banned' => false,
                    'ban_lifted_at' => now(),
                    'ban_lifted_by' => $admin->id,
                    'ban_lift_reason' => $reason,
                ]);
                $message = 'User ban lifted successfully';
                $action = 'unbanned';
            } else {
                // Ban user
                $reason = $request->input('reason', 'Quick ban by admin');
                $user->update([
                    'is_banned' => true,
                    'banned_at' => now(),
                    'ban_reason' => $reason,
                    'banned_by' => $admin->id,
                    'ban_lifted_at' => null,
                    'ban_lifted_by' => null,
                    'ban_lift_reason' => null,
                    'is_logged_in' => 0,
                ]);
                $message = 'User banned successfully';
                $action = 'banned';
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => $message,
                'action' => $action,
                'is_banned' => $user->is_banned
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'An error occurred during the operation'
            ]);
        }
    }

    /**
     * Bulk ban multiple users - Updated to allow admin banning
     */
    public function bulkBan(Request $request)
    {
        $request->validate([
            'users' => 'required|array',
            'users.*' => 'exists:users,id',
            'reason' => 'required|string|max:1000',
        ]);

        $admin = Auth::user();
        $bannedCount = 0;
        $errors = [];

        DB::beginTransaction();
        try {
            foreach ($request->users as $userId) {
                $user = User::find($userId);
                
                if (!$user) {
                    $errors[] = "User with ID {$userId} not found";
                    continue;
                }

                // Skip self only
                if ($user->id === $admin->id) {
                    $errors[] = "Cannot ban yourself";
                    continue;
                }

                // Skip already banned users
                if ($user->is_banned) {
                    $errors[] = "{$user->name} is already banned";
                    continue;
                }

                // Ban the user (including admins)
                $success = $user->update([
                    'is_banned' => true,
                    'banned_at' => now(),
                    'ban_reason' => $request->reason,
                    'banned_by' => $admin->id,
                    'ban_lifted_at' => null,
                    'ban_lifted_by' => null,
                    'ban_lift_reason' => null,
                    'is_logged_in' => 0,
                ]);

                if ($success) {
                    $bannedCount++;
                } else {
                    $errors[] = "Failed to ban {$user->name}";
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Successfully banned {$bannedCount} users" . (count($errors) > 0 ? '. Some errors occurred: ' . implode(', ', $errors) : ''),
                'banned_count' => $bannedCount,
                'errors' => $errors
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'An error occurred during bulk ban operation'
            ]);
        }
    }

    /**
     * Bulk unban multiple users
     */
    public function bulkUnban(Request $request)
    {
        $request->validate([
            'users' => 'required|array',
            'users.*' => 'exists:users,id',
            'reason' => 'required|string|max:1000',
        ]);

        $admin = Auth::user();
        $unbannedCount = 0;
        $errors = [];

        DB::beginTransaction();
        try {
            foreach ($request->users as $userId) {
                $user = User::find($userId);
                
                if (!$user) {
                    $errors[] = "User with ID {$userId} not found";
                    continue;
                }

                // Skip non-banned users
                if (!$user->is_banned) {
                    $errors[] = "{$user->name} is not banned";
                    continue;
                }

                // Lift the ban
                $success = $user->update([
                    'is_banned' => false,
                    'ban_lifted_at' => now(),
                    'ban_lifted_by' => $admin->id,
                    'ban_lift_reason' => $request->reason,
                ]);

                if ($success) {
                    $unbannedCount++;
                } else {
                    $errors[] = "Failed to unban {$user->name}";
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Successfully unbanned {$unbannedCount} users" . (count($errors) > 0 ? '. Some errors occurred: ' . implode(', ', $errors) : ''),
                'unbanned_count' => $unbannedCount,
                'errors' => $errors
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'An error occurred during bulk unban operation'
            ]);
        }
    }

    /**
     * Get ban statistics for dashboard
     */
    public function getBanStatistics()
    {
        $stats = [
            'total_users' => User::count(),
            'active_users' => User::where('status', 'active')->where('is_banned', false)->count(),
            'banned_users' => User::where('is_banned', true)->count(),
            'online_users' => User::where('is_logged_in', 1)->count(),
            'recent_bans' => User::where('is_banned', true)
                ->where('banned_at', '>=', now()->subDays(7))
                ->count(),
            'recent_unbans' => User::whereNotNull('ban_lifted_at')
                ->where('ban_lifted_at', '>=', now()->subDays(7))
                ->count(),
        ];

        return response()->json($stats);
    }

    /**
     * Export banned users list to CSV
     */
    public function exportBannedUsers()
    {
        $bannedUsers = User::where('is_banned', true)
            ->with(['bannedBy', 'banLiftedBy'])
            ->get();

        $filename = 'banned_users_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($bannedUsers) {
            $file = fopen('php://output', 'w');
            
            // Add CSV headers
            fputcsv($file, [
                'ID', 'Name', 'Email', 'Role', 'Banned Date', 'Banned By', 
                'Ban Reason', 'Ban Lifted Date', 'Ban Lifted By', 'Ban Lift Reason'
            ]);

            // Add data rows
            foreach ($bannedUsers as $user) {
                fputcsv($file, [
                    $user->id,
                    $user->name,
                    $user->email,
                    $user->role,
                    $user->banned_at ? $user->banned_at->format('Y-m-d H:i:s') : '',
                    $user->bannedBy ? $user->bannedBy->name : '',
                    $user->ban_reason,
                    $user->ban_lifted_at ? $user->ban_lifted_at->format('Y-m-d H:i:s') : '',
                    $user->banLiftedBy ? $user->banLiftedBy->name : '',
                    $user->ban_lift_reason,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, headers: $headers);
    }

    /**
     * Search users (for autocomplete/AJAX) - Updated to include admins
     */
    public function searchUsers(Request $request)
    {
        $search = $request->get('q');
        
        if (!$search) {
            return response()->json([]);
        }

        $users = User::where(function($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('username', 'like', "%{$search}%");
            })
            ->limit(10)
            ->get(['id', 'name', 'email', 'is_banned', 'role']);

        return response()->json($users);
    }

    /**
     * Get user count by status (for dashboard widgets)
     */
    public function getUserCounts()
    {
        $counts = [
            'total' => User::count(),
            'active' => User::where('status', 'active')->where('is_banned', false)->count(),
            'banned' => User::where('is_banned', true)->count(),
            'online' => User::where('is_logged_in', 1)->count(),
            'admins' => User::where('role', 'admin')->count(),
            'students' => User::where('role', 'student')->count(),
        ];

        return response()->json($counts);
    }

    /**
     * Force logout a user (Updated to allow admin logout except self)
     */
    public function forceLogout($id)
    {
        $user = User::findOrFail($id);
        $admin = Auth::user();

        if ($user->id === $admin->id) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot force logout yourself'
            ]);
        }

        $user->update(['is_logged_in' => 0]);

        return response()->json([
            'success' => true,
            'message' => "User {$user->name} has been logged out"
        ]);
    }
}
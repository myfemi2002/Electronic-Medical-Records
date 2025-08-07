<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Redirect;
use Spatie\Permission\Models\Permission;

class RoleWithPermissionController extends Controller
{
    public function rolesWithPermissionView(){       
       
    // Fetch roles
    $roles = Role::all();

    // Fetch grouped permissions
    $permission_groups = Permission::select('group_name')
        ->groupBy('group_name')
        ->with(['permissions' => function ($query) {
            $query->select('name', 'id', 'group_name', 'sidebar_name');
        }])
        ->get();

        $permission = Permission::all();
       
        return view('backend.roleswithpermission.index',compact('roles','permission','permission_groups'));
    }
    

    public function rolesWithPermissionStore(Request $request)
    {
        // Validate the request
        $request->validate([
            'role_id' => 'required|exists:roles,id', // Adjust role validation as needed
            'permissions' => 'required|array',
            'permissions.*' => 'exists:permissions,id'
        ]);

        $role_id = $request->input('role_id');
        $permissions = $request->input('permissions');

        $existingRecords = [];
        $newPermissions = [];

        foreach ($permissions as $permission) {
            $exists = DB::table('role_has_permissions')
                ->where('role_id', $role_id)
                ->where('permission_id', $permission)
                ->exists();

            if (!$exists) {
                // Store new permission
                DB::table('role_has_permissions')->insert([
                    'role_id' => $role_id,
                    'permission_id' => $permission
                ]);
                $newPermissions[] = $permission;
            } else {
                $existingRecords[] = $permission;
            }
        }

        // Prepare notification
        $notification = [
            'message' => 'Role permissions updated successfully.',
            'alert-type' => 'success'
        ];

        if (count($existingRecords) > 0) {
            $notification['message'] .= ' However, the following permissions already exist: ';
            foreach ($existingRecords as $permissionId) {
                $permission = DB::table('permissions')->where('id', $permissionId)->first();
                if ($permission) {
                    $notification['message'] .= $permission->sidebar_name . ', ';
                }
            }
            $notification['alert-type'] = 'warning';
        }

        return Redirect::route('roleswithpermission.view')->with($notification);
    }

    public function rolesWithPermissionUpdate(Request $request, $id)
    {
        // Validate the request
        $request->validate([
            'name' => 'required|string|max:255',
            'permissions' => 'required|array',
            'permissions.*' => 'exists:permissions,id'
        ]);

        $role = Role::findOrFail($id);
        $newPermissions = $request->input('permissions');

        // Sync the permissions with the role
        $role->permissions()->sync($newPermissions);

        // Prepare notification
        $notification = [
            'message' => 'Role updated successfully.',
            'alert-type' => 'success'
        ];

        return Redirect::route('roleswithpermission.view')->with($notification);
    } 


    public function rolesWithPermissionDelete($id) {
        // Find the role by its ID
        $role = Role::find($id);
    
        if (!$role) {
            // If the role is not found, return an error notification
            $notification = [
                'message' => 'Role not found.',
                'alert-type' => 'danger',
            ];
            return redirect()->back()->with($notification);
        }
    
        // Detach all permissions associated with the role
        $role->permissions()->detach();
    
        // Optional: If you want to delete the role itself after detaching the permissions, uncomment the line below
        // $role->delete();
    
        // Return success notification
        $notification = [
            'message' => 'Role permissions deleted successfully.',
            'alert-type' => 'success',
        ];
    
        return redirect()->back()->with($notification);
    }

}

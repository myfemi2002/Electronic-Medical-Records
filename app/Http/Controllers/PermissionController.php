<?php

namespace App\Http\Controllers;

use App\Models\GroupName;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function permissionView() {
        $allData = Permission::latest()->get();
        $groupname = GroupName::latest()->get();
        return view('backend.permission.index', compact('allData', 'groupname'));
    }

    public function permissionStore(Request $request)
    {
        // dd($request); // Debugging request data
        
        $validatedData = $request->validate([
            'name' => 'required|unique:permissions,name',
            'group_name' => 'required|string|max:255', // Changed to 'group_name'
            'sidebar_name' => 'required|string|max:255',
        ], [
            'name.required' => 'Please input the Permission Name.',
            'name.unique' => 'This Permission Name already exists.',
            'group_name.required' => 'Please input the Group Name.', // Updated error message
            'group_name.string' => 'The Group Name must be a string.',
            'sidebar_name.required' => 'Please input the Sidebar Name.',
        ]);
    
        try {
            $permission = Permission::create([
                'name' => $validatedData['name'],
                'group_name' => $validatedData['group_name'], // Changed to 'group_name'
                'sidebar_name' => $validatedData['sidebar_name'],
                'created_by' => Auth::id(),
                'created_at' => Carbon::now(),
            ]);
    
            Log::info('Permission created successfully', ['permission' => $permission->toArray()]);
    
            return redirect()->route('permission.view')
                ->with('message', 'Permission added successfully.')
                ->with('alert-type', 'success');
        } catch (\Exception $e) {
            Log::error('Error creating permission', ['error' => $e->getMessage()]);
    
            return redirect()->back()
                ->withInput()
                ->with('message', 'An error occurred while adding the permission.')
                ->with('alert-type', 'error');
        }
    }


    public function permissionUpdate(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'group_name' => 'required|string|max:255',
            'sidebar_name' => 'required|string|max:255',
        ]);

        $permission = Permission::findOrFail($id);
        $permission->update([
            'name' => $request->name,
            'group_name' => $request->group_name,
            'sidebar_name' => $request->sidebar_name,
        ]);

        return redirect()->back()->with('message', 'Permission updated successfully.')->with('alert-type', 'success');
    }

    public function permissionDelete($id)
    {
        $permission = Permission::findOrFail($id);
        $permission->delete();

        return redirect()->back()->with('message', 'Permission deleted successfully.')->with('alert-type', 'success');
    }
}

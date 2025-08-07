<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolesController extends Controller
{
    public function rolesView()
    {
        $allData = Role::all();
        return view('backend.roles.index', compact('allData'));
    }

    public function rolesStore(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name',
        ]);

        Role::create(['name' => $request->name]);

        return redirect()->route('roles.view')->with('message', 'Role added successfully.')->with('alert-type', 'success');
        
    }

    public function rolesUpdate(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        $request->validate([
            'name' => 'required|unique:roles,name,'.$role->id,
        ]);

        $role->update(['name' => $request->name]);

        return redirect()->route('roles.view')->with('message', 'Role updated successfully.')->with('alert-type', 'success');
    }

    public function rolesDelete($id)
    {
        $role = Role::findOrFail($id);
        $role->delete();

        return redirect()->route('roles.view')->with('message', 'Role deleted successfully.')->with('alert-type', 'success');
    }
}

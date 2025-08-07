<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;


class AdminManagementController extends Controller
{
    public function adminShowAllAdmin(){
        
        $roles = Role::latest()->get();
        $admins = User::where('role', 'admin')->get();
        return view('backend.admin.all_admin_list',compact('admins', 'roles'));
   }

   
   public function storeAdmin(Request $request)
   {
    // dd($request);
       $request->validate([
           'name' => 'required|string|max:255',
           'phone' => 'required|string',
           'email' => 'required|email|max:255|unique:users,email',
           'gender' => 'required|string',
           'address' => 'required|string|max:255',
           'roles' => 'required|string',
           'password' => 'required|string|min:8',
       ]);
   
       $user = User::create([
           'name' => $request->name,
           'phone' => $request->phone,
           'email' => $request->email,
           'gender' => $request->gender,
           'address' => $request->address,
           'role' => 'admin',
           'password' => Hash::make($request->password),
        //    'status' => '1', // Default status
       ]);
   
       if ($request->roles) {
           $role = Role::findById($request->roles); // Fetch the role by ID
           $user->assignRole($role->name); // Assign the role by name
       }
   
       return redirect()->route('admin.list')->with('message', 'Admin created successfully')->with('alert-type', 'success');
   }

    public function updateAdmin(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'phone' => 'required',
            'email' => 'required|email|unique:users,email,'.$id,
            'gender' => 'required|in:male,female',
            'address' => 'required',
            'roles' => 'required|exists:roles,id',
            'password' => 'nullable|min:6'
        ]);

        $admin = User::findOrFail($id);
        
        $admin->name = $request->name;
        $admin->phone = $request->phone;
        $admin->email = $request->email;
        $admin->gender = $request->gender;
        $admin->address = $request->address;
        
        if($request->filled('password')) {
            $admin->password = Hash::make($request->password);
        }
        
        $admin->save();
        
        // Update role
        $admin->roles()->sync($request->roles);
        
        return redirect()->route('admin.list')->with('message', 'Admin updated successfully')->with('alert-type', 'success');
    }

}

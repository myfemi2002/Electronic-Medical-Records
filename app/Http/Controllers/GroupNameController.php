<?php

namespace App\Http\Controllers;

use App\Models\GroupName;
use Illuminate\Http\Request;

class GroupNameController extends Controller
{
    public function groupnameView()
    {
        $groupnames = GroupName::latest()->get();
        return view('backend.groupname.index', compact('groupnames'));
    }

    public function groupnameStore(Request $request)
    {
        $request->validate([
            'group_name' => 'required|unique:group_names,group_name'
        ]);

        GroupName::insert([
            'group_name' => $request->group_name,
        ]);

        $notification = array(
            'message' => 'Group Name Inserted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('groupname.view')->with($notification);
    }

    public function groupnameUpdate(Request $request, $id)
    {
        $request->validate([
            'group_name' => 'required|unique:group_names,group_name,' . $id
        ]);

        GroupName::findOrFail($id)->update([
            'group_name' => $request->group_name,
        ]);

        $notification = array(
            'message' => 'Group Name Updated Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('groupname.view')->with($notification);
    }

    public function groupnameDelete($id)
    {
        GroupName::findOrFail($id)->delete();

        $notification = array(
            'message' => 'Group Name Deleted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('groupname.view')->with($notification);
    }
}

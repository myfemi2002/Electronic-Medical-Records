<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Hmo;

class HmoController extends Controller
{
    /**
     * Display a listing of HMO providers
     */
    public function index()
    {
        $hmos = Hmo::latest()->paginate(20);
        return view('backend.settings.hmo.index', compact('hmos'));
    }

    /**
     * Store a newly created HMO provider
     */
    public function store(Request $request)
    {
        $request->validate([
            'hmo_name' => 'required|string|unique:hmos,hmo_name',
            'contact_person' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'status' => 'required|in:Active,Inactive',
        ]);

        Hmo::create($request->all());

        return redirect()->back()
            ->with('message', 'HMO provider added successfully')
            ->with('alert-type', 'success');
    }

    /**
     * Update the specified HMO provider
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'hmo_name' => 'required|string|unique:hmos,hmo_name,' . $id,
            'contact_person' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'status' => 'required|in:Active,Inactive',
        ]);

        $hmo = Hmo::findOrFail($id);
        $hmo->update($request->all());

        return redirect()->back()
            ->with('message', 'HMO provider updated successfully')
            ->with('alert-type', 'success');
    }

    /**
     * Remove the specified HMO provider
     */
    public function destroy($id)
    {
        $hmo = Hmo::findOrFail($id);
        $hmo->delete();

        return redirect()->back()
            ->with('message', 'HMO provider deleted successfully')
            ->with('alert-type', 'success');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    /**
     * Display a listing of departments.
     */
    public function index()
    {
        $departments = Department::with('headOfDepartment')
            ->withCount('staff')
            ->orderBy('display_order')
            ->orderBy('name')
            ->get();

        $stats = [
            'total' => Department::count(),
            'active' => Department::active()->count(),
            'medical' => Department::medical()->count(),
            'support' => Department::support()->count(),
            'administrative' => Department::administrative()->count(),
            'can_receive_triage' => Department::canReceiveTriage()->count(),
        ];

        return view('backend.departments.index', compact('departments', 'stats'));
    }

    /**
     * Show the form for creating a new department.
     */
    public function create()
    {
        $users = User::where('status', 'active')->orderBy('name')->get();
        return view('backend.departments.create', compact('users'));
    }

    /**
     * Store a newly created department.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:departments,name',
            'code' => 'required|string|max:10|unique:departments,code',
            'type' => 'required|in:medical,support,administrative',
            'description' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'head_of_department_id' => 'nullable|exists:users,id',
            'display_order' => 'nullable|integer|min:0',
        ]);

        $validated['status'] = 'active';
        $validated['can_receive_triage_patients'] = $request->has('can_receive_triage_patients');
        $validated['requires_appointment'] = $request->has('requires_appointment');
        $validated['display_order'] = $validated['display_order'] ?? Department::max('display_order') + 1;

        Department::create($validated);

        return redirect()->route('admin.departments.index')
            ->with('message', 'Department created successfully')
            ->with('alert-type', 'success');
    }

    /**
     * Display the specified department.
     */
    public function show($id)
    {
        $department = Department::with(['headOfDepartment', 'staff'])
            ->withCount(['staff', 'triageAssessments'])
            ->findOrFail($id);

        $stats = [
            'total_staff' => $department->staff_count,
            'total_forwards' => $department->triage_assessments_count,
            'forwards_today' => $department->triageAssessments()->whereDate('forwarded_at', today())->count(),
            'forwards_this_week' => $department->triageAssessments()
                ->whereBetween('forwarded_at', [now()->startOfWeek(), now()->endOfWeek()])
                ->count(),
        ];

        $recentForwards = $department->triageAssessments()
            ->with(['patient', 'assessedBy'])
            ->latest('forwarded_at')
            ->take(10)
            ->get();

        return view('backend.departments.show', compact('department', 'stats', 'recentForwards'));
    }

    /**
     * Show the form for editing the specified department.
     */
    public function edit($id)
    {
        $department = Department::findOrFail($id);
        $users = User::where('status', 'active')->orderBy('name')->get();
        return view('backend.departments.edit', compact('department', 'users'));
    }

    /**
     * Update the specified department.
     */
    public function update(Request $request, $id)
    {
        $department = Department::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:departments,name,' . $id,
            'code' => 'required|string|max:10|unique:departments,code,' . $id,
            'type' => 'required|in:medical,support,administrative',
            'description' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'head_of_department_id' => 'nullable|exists:users,id',
            'status' => 'required|in:active,inactive',
            'display_order' => 'nullable|integer|min:0',
        ]);

        $validated['can_receive_triage_patients'] = $request->has('can_receive_triage_patients');
        $validated['requires_appointment'] = $request->has('requires_appointment');

        $department->update($validated);

        return redirect()->route('admin.departments.index')
            ->with('message', 'Department updated successfully')
            ->with('alert-type', 'success');
    }

    /**
     * Remove the specified department.
     */
    public function destroy($id)
    {
        $department = Department::findOrFail($id);

        if ($department->staff()->count() > 0) {
            return redirect()->back()
                ->with('message', 'Cannot delete department with assigned staff')
                ->with('alert-type', 'error');
        }

        if ($department->triageAssessments()->count() > 0) {
            return redirect()->back()
                ->with('message', 'Cannot delete department with triage history')
                ->with('alert-type', 'error');
        }

        $department->delete();

        return redirect()->route('admin.departments.index')
            ->with('message', 'Department deleted successfully')
            ->with('alert-type', 'success');
    }

    /**
     * Toggle department status
     */
    public function toggleStatus($id)
    {
        $department = Department::findOrFail($id);
        $newStatus = $department->status === 'active' ? 'inactive' : 'active';
        $department->update(['status' => $newStatus]);

        return redirect()->back()
            ->with('message', 'Department status updated')
            ->with('alert-type', 'success');
    }

    /**
     * Get staff for department (AJAX)
     */
    public function getStaff($id)
    {
        $staff = User::where('department_id', $id)
            ->where('status', 'active')
            ->select('id', 'name', 'staff_type', 'staff_id')
            ->get();

        return response()->json($staff);
    }
}

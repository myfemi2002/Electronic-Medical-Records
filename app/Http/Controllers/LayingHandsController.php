<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\LayingHandsRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\User; // Add this import

class LayingHandsController extends Controller
{
    // =====================================
    // PUBLIC FORM METHODS
    // =====================================

    public function showForm()
    {
        return view('frontend.laying-hands.form');
    }

    public function submitRequest(Request $request)
    {
        // Validation rules
        $rules = [
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'laying_hands_request' => 'required|string',
            'is_rccg_member' => 'required|boolean',
            'state' => 'required|string|max:255',
        ];

        // Additional validation for RCCG members
        if ($request->is_rccg_member) {
            $rules = array_merge($rules, [
                'parish' => 'required|string|max:255',
                'province' => 'required|string|max:255',
                'region' => 'required|string|max:255',
                'gender' => 'required|in:male,female',
                'date_of_birth' => 'required|date|before:today',
                'contact_address' => 'required|string',
                'prayer_category' => 'required|string|max:255',
                'preferred_communication' => 'required|in:email,sms,both',
                'service_attended' => 'nullable|string|max:255',
                'how_heard_about_program' => 'required|string|max:255',
                'additional_notes' => 'nullable|string'
            ]);
        }

        $request->validate($rules);

        // Check for duplicate pending submissions
        $existingRequest = LayingHandsRequest::where(function($query) use ($request) {
            $query->where('email', $request->email)
                  ->orWhere('phone', $request->phone);
        })
        ->where('status', 'pending')
        ->first();

        if ($existingRequest) {
            return redirect()->back()
                ->with('message', 'You already have a pending request awaiting review. Please wait for approval before submitting a new request.')
                ->with('alert-type', 'warning')
                ->withInput();
        }

        // Create the request
        try {
            DB::beginTransaction();

            $layingHandsData = [
                'full_name' => $request->full_name,
                'email' => $request->email,
                'phone' => $request->phone,
                'laying_hands_request' => $request->laying_hands_request,
                'state' => $request->state,
                'is_rccg_member' => $request->is_rccg_member,
                'status' => 'pending'
            ];

            // Add RCCG member specific fields if applicable
            if ($request->is_rccg_member) {
                $layingHandsData = array_merge($layingHandsData, [
                    'parish' => $request->parish,
                    'province' => $request->province,
                    'region' => $request->region,
                    'gender' => $request->gender,
                    'date_of_birth' => $request->date_of_birth,
                    'contact_address' => $request->contact_address,
                    'prayer_category' => $request->prayer_category,
                    'preferred_communication' => $request->preferred_communication,
                    'service_attended' => $request->service_attended,
                    'how_heard_about_program' => $request->how_heard_about_program,
                    'additional_notes' => $request->additional_notes
                ]);
            }

            LayingHandsRequest::create($layingHandsData);

            DB::commit();

            return redirect()->back()
                ->with('message', 'Your Laying of Hands request has been submitted successfully. You will be notified once your request is reviewed.')
                ->with('alert-type', 'success');

        } catch (\Exception $e) {
            DB::rollback();
            
            return redirect()->back()
                ->with('message', 'Sorry, there was an error submitting your request. Please try again.')
                ->with('alert-type', 'error')
                ->withInput();
        }
    }

    // =====================================
    // ADMIN METHODS
    // =====================================

    public function index(Request $request)
    {
        $query = LayingHandsRequest::query();

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Filter by member type
        if ($request->has('member_type') && $request->member_type != '') {
            if ($request->member_type == 'rccg') {
                $query->where('is_rccg_member', true);
            } elseif ($request->member_type == 'non_rccg') {
                $query->where('is_rccg_member', false);
            }
        }

        // Search functionality
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('parish', 'like', "%{$search}%");
            });
        }

        $requests = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('backend.laying-hands.index', compact('requests'));
    }

    public function show($id)
    {
        $request = LayingHandsRequest::findOrFail($id);
        return view('backend.laying-hands.show', compact('request'));
    }

    // FIXED APPROVE METHOD
    public function approve(Request $request, $id)
    {
        $validatedData = $request->validate([
            'admin_notes' => 'nullable|string'
        ]);

        try {
            DB::beginTransaction();

            $layingHandsRequest = LayingHandsRequest::findOrFail($id);
            
            $layingHandsRequest->update([
                'status' => 'approved',
                'approved_at' => Carbon::now(),
                'approved_by' => auth()->id(),
                'admin_notes' => $validatedData['admin_notes'] ?? null
            ]);

            DB::commit();

            return redirect()->back()
                ->with('message', 'Request approved successfully. It has been moved to the notification queue.')
                ->with('alert-type', 'success');

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error approving request: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('message', 'Error approving request. Please try again.')
                ->with('alert-type', 'error');
        }
    }

    // FIXED DECLINE METHOD
    public function decline(Request $request, $id)
    {
        $validatedData = $request->validate([
            'admin_notes' => 'required|string|min:10'
        ], [
            'admin_notes.required' => 'Please provide a reason for declining this request.',
            'admin_notes.min' => 'Please provide a detailed reason (at least 10 characters).'
        ]);

        try {
            DB::beginTransaction();

            $layingHandsRequest = LayingHandsRequest::findOrFail($id);
            
            $layingHandsRequest->update([
                'status' => 'declined',
                'approved_by' => auth()->id(),
                'admin_notes' => $validatedData['admin_notes']
            ]);

            DB::commit();

            return redirect()->back()
                ->with('message', 'Request declined successfully.')
                ->with('alert-type', 'success');

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error declining request: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('message', 'Error declining request. Please try again.')
                ->with('alert-type', 'error');
        }
    }

    public function sendNotifications(Request $request)
    {
        try {
            DB::beginTransaction();

            // Get all approved requests that haven't been notified
            $approvedRequests = LayingHandsRequest::where('status', 'approved')->get();

            if ($approvedRequests->isEmpty()) {
                return redirect()->back()
                    ->with('message', 'No approved requests found for notification.')
                    ->with('alert-type', 'warning');
            }

            // Move to notified status
            foreach ($approvedRequests as $layingHandsRequest) {
                $layingHandsRequest->update([
                    'status' => 'notified',
                    'notified_at' => Carbon::now()
                ]);

                // Here you would implement actual SMS/Email sending
                // For now, we'll just update the status
                // TODO: Implement bulk SMS and Email functionality
            }

            DB::commit();

            $count = $approvedRequests->count();
            return redirect()->back()
                ->with('message', "Successfully sent notifications to {$count} approved applicants.")
                ->with('alert-type', 'success');

        } catch (\Exception $e) {
            DB::rollback();
            
            return redirect()->back()
                ->with('message', 'Error sending notifications. Please try again.')
                ->with('alert-type', 'error');
        }
    }

    public function markAsTreated(Request $request)
    {
        $request->validate([
            'request_ids' => 'required|array',
            'request_ids.*' => 'exists:laying_hands_requests,id'
        ]);

        try {
            DB::beginTransaction();

            LayingHandsRequest::whereIn('id', $request->request_ids)
                ->where('status', 'notified')
                ->update([
                    'status' => 'treated',
                    'treated_at' => Carbon::now()
                ]);

            DB::commit();

            $count = count($request->request_ids);
            return redirect()->back()
                ->with('message', "Successfully marked {$count} requests as treated.")
                ->with('alert-type', 'success');

        } catch (\Exception $e) {
            DB::rollback();
            
            return redirect()->back()
                ->with('message', 'Error updating request status. Please try again.')
                ->with('alert-type', 'error');
        }
    }

    public function destroy($id)
    {
        try {
            $layingHandsRequest = LayingHandsRequest::findOrFail($id);
            $layingHandsRequest->delete();

            return redirect()->back()
                ->with('message', 'Request deleted successfully.')
                ->with('alert-type', 'success');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('message', 'Error deleting request. Please try again.')
                ->with('alert-type', 'error');
        }
    }

    // =====================================
    // STATUS-SPECIFIC PAGES
    // =====================================

    public function pending(Request $request)
    {
        $query = LayingHandsRequest::where('status', 'pending');
        
        // Apply filters
        if ($request->has('member_type') && $request->member_type != '') {
            if ($request->member_type == 'rccg') {
                $query->where('is_rccg_member', true);
            } elseif ($request->member_type == 'non_rccg') {
                $query->where('is_rccg_member', false);
            }
        }
        
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
                ->orWhere('phone', 'like', "%{$search}%")
                ->orWhere('parish', 'like', "%{$search}%");
            });
        }
        
        $requests = $query->orderBy('created_at', 'desc')->paginate(20);
        
        return view('backend.laying-hands.pending', compact('requests'));
    }

    public function approved(Request $request)
    {
        $query = LayingHandsRequest::where('status', 'approved');
        
        // Apply filters similar to pending
        if ($request->has('member_type') && $request->member_type != '') {
            if ($request->member_type == 'rccg') {
                $query->where('is_rccg_member', true);
            } elseif ($request->member_type == 'non_rccg') {
                $query->where('is_rccg_member', false);
            }
        }
        
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
                ->orWhere('phone', 'like', "%{$search}%")
                ->orWhere('parish', 'like', "%{$search}%");
            });
        }
        
        $requests = $query->orderBy('approved_at', 'desc')->paginate(20);
        
        return view('backend.laying-hands.approved', compact('requests'));
    }

    public function declined(Request $request)
    {
        $query = LayingHandsRequest::where('status', 'declined');
        
        // Apply filters
        if ($request->has('member_type') && $request->member_type != '') {
            if ($request->member_type == 'rccg') {
                $query->where('is_rccg_member', true);
            } elseif ($request->member_type == 'non_rccg') {
                $query->where('is_rccg_member', false);
            }
        }
        
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
                ->orWhere('phone', 'like', "%{$search}%")
                ->orWhere('parish', 'like', "%{$search}%");
            });
        }
        
        $requests = $query->orderBy('updated_at', 'desc')->paginate(20);
        
        return view('backend.laying-hands.declined', compact('requests'));
    }

    public function notified(Request $request)
    {
        $query = LayingHandsRequest::where('status', 'notified');
        
        // Apply filters
        if ($request->has('member_type') && $request->member_type != '') {
            if ($request->member_type == 'rccg') {
                $query->where('is_rccg_member', true);
            } elseif ($request->member_type == 'non_rccg') {
                $query->where('is_rccg_member', false);
            }
        }
        
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
                ->orWhere('phone', 'like', "%{$search}%")
                ->orWhere('parish', 'like', "%{$search}%");
            });
        }
        
        $requests = $query->orderBy('notified_at', 'desc')->paginate(20);
        
        return view('backend.laying-hands.notified', compact('requests'));
    }

    public function treated(Request $request)
    {
        $query = LayingHandsRequest::where('status', 'treated');
        
        // Apply filters
        if ($request->has('member_type') && $request->member_type != '') {
            if ($request->member_type == 'rccg') {
                $query->where('is_rccg_member', true);
            } elseif ($request->member_type == 'non_rccg') {
                $query->where('is_rccg_member', false);
            }
        }
        
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
                ->orWhere('phone', 'like', "%{$search}%")
                ->orWhere('parish', 'like', "%{$search}%");
            });
        }
        
        $requests = $query->orderBy('treated_at', 'desc')->paginate(20);
        
        return view('backend.laying-hands.treated', compact('requests'));
    }

    public function reconsider(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $layingHandsRequest = LayingHandsRequest::findOrFail($id);
            
            $layingHandsRequest->update([
                'status' => 'pending',
                'admin_notes' => $request->admin_notes,
                'approved_by' => null,
                'approved_at' => null
            ]);

            DB::commit();

            return redirect()->back()
                ->with('message', 'Request moved back to pending for reconsideration.')
                ->with('alert-type', 'success');

        } catch (\Exception $e) {
            DB::rollback();
            
            return redirect()->back()
                ->with('message', 'Error reconsidering request. Please try again.')
                ->with('alert-type', 'error');
        }
    }

    public function sendIndividualNotification($id)
    {
        try {
            $layingHandsRequest = LayingHandsRequest::findOrFail($id);
            
            if ($layingHandsRequest->status !== 'approved') {
                return redirect()->back()
                    ->with('message', 'Only approved requests can receive notifications.')
                    ->with('alert-type', 'warning');
            }
            
            // Update status to notified
            $layingHandsRequest->update([
                'status' => 'notified',
                'notified_at' => Carbon::now()
            ]);
            
            // TODO: Implement actual SMS/Email sending logic here
            
            return redirect()->back()
                ->with('message', 'Notification sent successfully.')
                ->with('alert-type', 'success');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('message', 'Error sending notification. Please try again.')
                ->with('alert-type', 'error');
        }
    }

    // =====================================
    // BULK METHODS
    // =====================================

    public function bulkApprove(Request $request)
    {
        $request->validate([
            'request_ids' => 'required|array',
            'request_ids.*' => 'exists:laying_hands_requests,id',
            'admin_notes' => 'nullable|string'
        ]);

        try {
            DB::beginTransaction();

            LayingHandsRequest::whereIn('id', $request->request_ids)
                ->where('status', 'pending')
                ->update([
                    'status' => 'approved',
                    'approved_at' => Carbon::now(),
                    'approved_by' => auth()->id(),
                    'admin_notes' => $request->admin_notes
                ]);

            DB::commit();

            $count = count($request->request_ids);
            return redirect()->back()
                ->with('message', "Successfully approved {$count} requests.")
                ->with('alert-type', 'success');

        } catch (\Exception $e) {
            DB::rollback();
            
            return redirect()->back()
                ->with('message', 'Error approving requests. Please try again.')
                ->with('alert-type', 'error');
        }
    }

    public function bulkDecline(Request $request)
    {
        $request->validate([
            'request_ids' => 'required|array',
            'request_ids.*' => 'exists:laying_hands_requests,id',
            'admin_notes' => 'required|string|min:10'
        ]);

        try {
            DB::beginTransaction();

            LayingHandsRequest::whereIn('id', $request->request_ids)
                ->where('status', 'pending')
                ->update([
                    'status' => 'declined',
                    'approved_by' => auth()->id(),
                    'admin_notes' => $request->admin_notes
                ]);

            DB::commit();

            $count = count($request->request_ids);
            return redirect()->back()
                ->with('message', "Successfully declined {$count} requests.")
                ->with('alert-type', 'success');

        } catch (\Exception $e) {
            DB::rollback();
            
            return redirect()->back()
                ->with('message', 'Error declining requests. Please try again.')
                ->with('alert-type', 'error');
        }
    }

    // =====================================
    // UTILITY METHODS
    // =====================================

    public function getStatistics()
    {
        return [
            'total_requests' => LayingHandsRequest::count(),
            'pending_requests' => LayingHandsRequest::pending()->count(),
            'approved_requests' => LayingHandsRequest::approved()->count(),
            'notified_requests' => LayingHandsRequest::notified()->count(),
            'treated_requests' => LayingHandsRequest::treated()->count(),
            'rccg_members' => LayingHandsRequest::rccgMembers()->count(),
            'non_members' => LayingHandsRequest::nonMembers()->count(),
        ];
    }
}
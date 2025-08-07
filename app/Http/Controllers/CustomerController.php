<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Drivers\Gd\Driver;

class CustomerController extends Controller
{
    protected $imageManager;
    protected $manager;
    protected $uploadPath = 'upload/user_images';

    public function __construct()
    {
        $this->imageManager = new ImageManager(new Driver());
        $this->manager = new ImageManager(new Driver());
        
        try {
            if (!File::exists(public_path($this->uploadPath))) {
                File::makeDirectory(public_path($this->uploadPath), 0777, true);
            }
        } catch (\Exception $e) {
            Log::error('Failed to create upload directory: ' . $e->getMessage());
        }
    }

    public function index(Request $request)
    {
        $query = User::where('role', 'user');

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }

        // Verification filter
        if ($request->has('verified') && !empty($request->verified)) {
            if ($request->verified == 'verified') {
                $query->whereNotNull('email_verified_at');
            } else {
                $query->whereNull('email_verified_at');
            }
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('backend.customer.index', compact('users'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'username' => 'nullable|string|max:255|unique:users',
            'password' => 'required|string|min:8',
            'phone' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:active,inactive'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->username = $request->username;
        $user->password = Hash::make($request->password);
        $user->phone = $request->phone;
        $user->address = $request->address;
        $user->role = 'user';
        $user->status = $request->status;

        // Handle photo upload
        if ($request->hasFile('photo')) {
            $image = $request->file('photo');
            $imageName = 'customer_' . time() . '.' . $image->getClientOriginalExtension();
            $imagePath = public_path($this->uploadPath . '/' . $imageName);

            $this->imageManager->read($image->getRealPath())
                ->resize(300, 300)
                ->save($imagePath);

            $user->photo = $this->uploadPath . '/' . $imageName;
        }

        $user->save();

        return redirect()->back()->with('message', 'Customer created successfully')->with('alert-type', 'success');
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'username' => 'nullable|string|max:255|unique:users,username,' . $id,
            'password' => 'nullable|string|min:8',
            'phone' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:active,inactive'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->username = $request->username;
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        $user->phone = $request->phone;
        $user->address = $request->address;
        $user->status = $request->status;

        // Handle photo upload
        if ($request->hasFile('photo')) {
            // Delete old photo
            if ($user->photo && File::exists(public_path($user->photo))) {
                File::delete(public_path($user->photo));
            }

            $image = $request->file('photo');
            $imageName = 'customer_' . time() . '.' . $image->getClientOriginalExtension();
            $imagePath = public_path($this->uploadPath . '/' . $imageName);

            $this->imageManager->read($image->getRealPath())
                ->resize(300, 300)
                ->save($imagePath);

            $user->photo = $this->uploadPath . '/' . $imageName;
        }

        $user->save();

        return redirect()->back()->with('message', 'Customer updated successfully')->with('alert-type', 'success');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // Delete photo if exists
        if ($user->photo && File::exists(public_path($user->photo))) {
            File::delete(public_path($user->photo));
        }

        $user->delete();

        return redirect()->back()->with('message', 'Customer deleted successfully')->with('alert-type', 'success');
    }

    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);
        $user->status = $user->status === 'active' ? 'inactive' : 'active';
        $user->save();

        return redirect()->back()->with('message', 'Customer status updated successfully')->with('alert-type', 'success');
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        return view('backend.customer.view', compact('user'));
    }
    

    public function edit($id)
    {
        try {
            \Log::info('Edit user request for ID: ' . $id);
            
            $user = User::where('id', $id)->where('role', 'user')->first();
            
            if (!$user) {
                \Log::error('User not found with ID: ' . $id);
                return response()->json(['error' => 'User not found'], 404);
            }
            
            \Log::info('User found: ' . $user->name);
            
            return response()->json([
                'id' => $user->id,
                'name' => $user->name,
                'username' => $user->username,
                'email' => $user->email,
                'phone' => $user->phone,
                'address' => $user->address,
                'status' => $user->status,
                'photo' => $user->photo,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error in edit method: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json(['error' => 'Server error: ' . $e->getMessage()], 500);
        }
    }
}

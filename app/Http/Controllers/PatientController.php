<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\ConsultancyPayment;
use App\Models\Hmo;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Facades\File;

class PatientController extends Controller
{
    protected $uploadPath = 'upload/patient_images';
    protected $imageManager;

    public function __construct()
    {
        $this->imageManager = new ImageManager(new Driver());
        
        if (!File::exists(public_path($this->uploadPath))) {
            File::makeDirectory(public_path($this->uploadPath), 0777, true);
        }
    }

    /**
     * Display patient registration landing page with action tiles
     */
    public function index()
    {
        $totalPatients = Patient::count();
        $newToday = Patient::whereDate('created_at', today())->count();
        $hmoPatients = Patient::where('patient_type', 'HMO')->count();
        $privatePatients = Patient::where('patient_type', 'Private')->count();
        $recentPatients = Patient::latest()->take(10)->get();
        
        // Get count of currently open files
        $openFilesCount = Patient::where('file_status', 'open')
            ->whereDate('file_opened_at', today())
            ->count();
        
        return view('backend.records.patients.index', compact(
            'totalPatients',
            'newToday',
            'hmoPatients',
            'privatePatients',
            'recentPatients',
            'openFilesCount'
        ));
    }

    /**
     * Display all patients list
     */
    public function all()
    {
        $patients = Patient::latest()->paginate(15);
        return view('backend.records.patients.all', compact('patients'));
    }

    /**
     * Show the form for creating a new patient
     */
    public function create()
    {
        $hmorg = Hmo::where('status', 'Active')->get();
        return view('backend.records.patients.create', compact('hmorg'));
    }

    /**
     * Store a newly created patient in database
     */
    public function store(Request $request)
    {
        $request->validate([
            'card_number' => 'required|unique:patients,card_number',
            'patient_lastname' => 'required|string|max:255',
            'patient_firstname' => 'required|string|max:255',
            'patient_phone' => 'required|numeric',
            'patient_religion' => 'required|string',
            'patient_gender' => 'required|in:Male,Female',
            'patient_status' => 'required|string',
            'patient_dob' => 'required|date',
            'patient_type' => 'required|string',
            'patient_hmo' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->all();
        $data['file_status'] = 'closed';
        $data['has_active_consultancy'] = false;

        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();
            
            $img = $this->imageManager->read($image);
            $img->resize(400, 400);
            $img->save(public_path($this->uploadPath . '/' . $name_gen));
            
            $data['image'] = $this->uploadPath . '/' . $name_gen;
        }

        Patient::create($data);

        return redirect()->back()
            ->with('message', 'Patient registered successfully')
            ->with('alert-type', 'success');
    }

    /**
     * Display the specified patient (and open file)
     */
    public function show($id)
    {
        $patient = Patient::findOrFail($id);
        
        // Check consultancy status
        $consultancyStatus = $patient->getConsultancyStatus();
        
        // Get consultancy payment history
        $consultancyHistory = $patient->consultancyPayments()->latest('payment_date')->get();
        
        // Open the file when viewing (if consultancy is active)
        if ($patient->hasActiveConsultancy()) {
            if ($patient->file_status === 'closed' || $patient->shouldAutoClose()) {
                $patient->openFile();
            }
        }
        
        return view('backend.records.patients.show', compact('patient', 'consultancyStatus', 'consultancyHistory'));
    }

    /**
     * Show the form for editing the specified patient
     */
    public function edit($id)
    {
        $patient = Patient::findOrFail($id);
        $hmorg = Hmo::where('status', 'Active')->get();
        return view('backend.records.patients.edit', compact('patient', 'hmorg'));
    }

    /**
     * Update the specified patient in database
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'card_number' => 'required|unique:patients,card_number,' . $id,
            'patient_lastname' => 'required|string|max:255',
            'patient_firstname' => 'required|string|max:255',
            'patient_phone' => 'required|numeric',
            'patient_religion' => 'required|string',
            'patient_gender' => 'required|in:Male,Female',
            'patient_status' => 'required|string',
            'patient_dob' => 'required|date',
            'patient_type' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $patient = Patient::findOrFail($id);
        $data = $request->all();

        // Handle image upload
        if ($request->hasFile('image')) {
            if ($patient->image && File::exists(public_path($patient->image))) {
                File::delete(public_path($patient->image));
            }

            $image = $request->file('image');
            $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();
            
            $img = $this->imageManager->read($image);
            $img->resize(400, 400);
            $img->save(public_path($this->uploadPath . '/' . $name_gen));
            
            $data['image'] = $this->uploadPath . '/' . $name_gen;
        }

        $patient->update($data);

        return redirect()->back()
            ->with('message', 'Patient information updated successfully')
            ->with('alert-type', 'success');
    }

    /**
     * Remove the specified patient from database
     */
    public function destroy($id)
    {
        $patient = Patient::findOrFail($id);

        if ($patient->image && File::exists(public_path($patient->image))) {
            File::delete(public_path($patient->image));
        }

        $patient->delete();

        return redirect()->back()
            ->with('message', 'Patient deleted successfully')
            ->with('alert-type', 'success');
    }

    /**
     * Open patient file page
     */
    public function open()
    {
        $openFiles = Patient::where('file_status', 'open')
            ->whereDate('file_opened_at', today())
            ->latest('file_opened_at')
            ->get();
            
        return view('backend.records.patients.open', compact('openFiles'));
    }

    /**
     * Open specific patient file
     */
    public function openFile($id)
    {
        $patient = Patient::findOrFail($id);
        
        // Check consultancy status
        $needsPayment = $patient->needsConsultancyPayment();
        
        if ($needsPayment) {
            return redirect()->route('admin.records.patients.verify-consultancy', $patient->id)
                ->with('message', 'Patient must pay consultancy fee at Accountant Department first, then bring receipt here for verification')
                ->with('alert-type', 'warning');
        }
        
        // Open the file
        if ($patient->shouldAutoClose()) {
            $patient->openFile();
            $message = 'Patient file reopened successfully';
        } elseif ($patient->file_status === 'closed') {
            $patient->openFile();
            $message = 'Patient file opened successfully';
        } else {
            $message = 'Patient file is already open';
        }
        
        return redirect()->route('admin.records.patients.show', $patient->id)
            ->with('message', $message)
            ->with('alert-type', 'success');
    }

    /**
     * Show consultancy verification page
     */
    public function verifyConsultancy($id)
    {
        $patient = Patient::findOrFail($id);
        $consultancyStatus = $patient->getConsultancyStatus();
        
        // Get payment history for reference
        $paymentHistory = $patient->consultancyPayments()->latest('payment_date')->take(5)->get();
        
        return view('backend.records.patients.verify-consultancy', compact('patient', 'consultancyStatus', 'paymentHistory'));
    }

    /**
     * Confirm consultancy payment from receipt
     */
    public function confirmConsultancyPayment(Request $request, $id)
    {
        $patient = Patient::findOrFail($id);
        
        $validated = $request->validate([
            'receipt_number' => 'required|string|unique:consultancy_payments,receipt_number',
            'payment_method' => 'required|in:Cash,POS,Transfer,HMO',
            'amount_paid' => 'required|numeric|min:0',
            'payment_date' => 'required|date',
            'verification_note' => 'nullable|string',
        ]);
        
        // Record payment in consultancy_payments table
        $payment = $patient->recordConsultancyPayment([
            'receipt_number' => $validated['receipt_number'],
            'payment_method' => $validated['payment_method'],
            'amount_paid' => $validated['amount_paid'],
            'payment_date' => $validated['payment_date'],
            'verification_note' => $validated['verification_note'] ?? null,
            'verified_by' => auth()->id(),
        ]);
        
        // Open the file
        $patient->openFile();
        
        return redirect()->route('admin.records.patients.show', $patient->id)
            ->with('message', 'Consultancy payment verified successfully (Receipt: ' . $payment->receipt_number . '). Patient file opened for 7 days.')
            ->with('alert-type', 'success');
    }

    /**
     * View consultancy payment history for a patient
     */
    public function consultancyHistory($id)
    {
        $patient = Patient::findOrFail($id);
        $payments = $patient->consultancyPayments()->latest('payment_date')->paginate(10);
        
        return view('backend.records.patients.consultancy-history', compact('patient', 'payments'));
    }

    /**
     * Close specific patient file manually
     */
    public function closeFile($id)
    {
        $patient = Patient::findOrFail($id);
        $patient->closeFile();
        
        return redirect()->back()
            ->with('message', 'Patient file closed successfully')
            ->with('alert-type', 'info');
    }

    /**
     * Get currently open files
     */
    public function getOpenFiles()
    {
        $openFiles = Patient::getOpenFiles();
        return response()->json($openFiles);
    }

    /**
     * Search patient
     */
    public function search()
    {
        $hmorg = Hmo::where('status', 'Active')->get();
        return view('backend.records.patients.search', compact('hmorg'));
    }

    /**
     * Update patient demographics
     */
    public function updateDemographics()
    {
        $hmorg = Hmo::where('status', 'Active')->get();
        return view('backend.records.patients.update-demographics', compact('hmorg'));
    }

    /**
     * Print patient card
     */
    public function printCard($id)
    {
        $patient = Patient::findOrFail($id);
        return view('backend.records.patients.print-card', compact('patient'));
    }

    /**
     * View patient visit history
     */
    public function visitHistory()
    {
        return view('backend.records.patients.visit-history');
    }

    /**
     * Print patient cards page
     */
    public function printCards()
    {
        $patients = Patient::latest()->get();
        return view('backend.records.patients.print-cards', compact('patients'));
    }

    /**
     * Patient reports page
     */
    public function reports()
    {
        $stats = [
            'total_patients' => Patient::count(),
            'male_patients' => Patient::where('patient_gender', 'Male')->count(),
            'female_patients' => Patient::where('patient_gender', 'Female')->count(),
            'hmo_patients' => Patient::where('patient_type', 'HMO')->count(),
            'private_patients' => Patient::where('patient_type', 'Private')->count(),
            'retainership_patients' => Patient::where('patient_type', 'Retainership')->count(),
            'new_this_month' => Patient::whereMonth('created_at', now()->month)->count(),
            'new_this_year' => Patient::whereYear('created_at', now()->year)->count(),
            'open_files_today' => Patient::where('file_status', 'open')
                ->whereDate('file_opened_at', today())
                ->count(),
            'active_consultancies' => Patient::where('has_active_consultancy', true)->count(),
        ];
        
        // Get consultancy payment stats
        $consultancyStats = ConsultancyPayment::getStats();
        
        return view('backend.records.patients.reports', compact('stats', 'consultancyStats'));
    }
}
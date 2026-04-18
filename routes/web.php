<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminManageBanController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CashierController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\FlagReviewController;
use App\Http\Controllers\GroupNameController;
use App\Http\Controllers\HmoController;
use App\Http\Controllers\HmsDashboardController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\IPManagementController;
use App\Http\Controllers\LaboratoryController;
use App\Http\Controllers\LoginAttemptController;
use App\Http\Controllers\NurseController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\PharmacyController;
use App\Http\Controllers\RadiologyController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\RoleWithPermissionController;
use App\Http\Controllers\SessionReportsController;
use App\Http\Controllers\SmtpController;
use App\Http\Controllers\SystemSettingController;
use App\Http\Controllers\TriageController;
use App\Http\Controllers\TwoFactorController;
use App\Http\Controllers\VisitController;
use Illuminate\Support\Facades\Route;

Route::controller(IndexController::class)->group(function () {
    Route::get('/', 'index')->name('home');
    Route::get('/about', 'about')->name('about');
    Route::get('/contact', 'contact')->name('contact');
    Route::get('/events', 'events')->name('events');
    Route::get('/properties/{slug}', 'show')->name('properties.show');
});

require __DIR__ . '/auth.php';

Route::middleware(['auth', 'checkBanned'])->group(function () {
    Route::get('/admin/dashboard', [HmsDashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/dashboard', fn () => redirect()->route('admin.dashboard'))->name('dashboard');
    Route::post('/admin/logout', [AdminController::class, 'adminDestroy'])->name('admin.logout');
    Route::get('/profile', [AdminController::class, 'adminProfile'])->name('admin.profile');
    Route::post('/profile/store', [AdminController::class, 'adminProfileStore'])->name('admin.profile.store');
    Route::get('/change/password', [AdminController::class, 'adminChangePassword'])->name('admin.change.password');
    Route::post('/update/password', [AdminController::class, 'adminUpdatePassword'])->name('update.password');

    Route::prefix('patients')->controller(PatientController::class)->name('admin.records.patients.')->middleware('permission:records.access')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/all', 'all')->name('all');
        Route::get('/create', 'create')->name('create');
        Route::post('/store', 'store')->name('store');
        Route::get('/show/{id}', 'show')->name('show');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::post('/update/{id}', 'update')->name('update');
        Route::get('/delete/{id}', 'destroy')->name('delete');
        Route::get('/open-file/{id}', 'openFile')->name('open-file');
        Route::get('/close-file/{id}', 'closeFile')->name('close-file');
        Route::get('/get-open-files', 'getOpenFiles')->name('get-open-files');
        Route::get('/verify-consultancy/{id}', 'verifyConsultancy')->name('verify-consultancy');
        Route::post('/confirm-consultancy-payment/{id}', 'confirmConsultancyPayment')->name('confirm-consultancy-payment');
        Route::get('/consultancy-history/{id}', 'consultancyHistory')->name('consultancy-history');
        Route::get('/search', 'search')->name('search');
        Route::get('/open', 'open')->name('open');
        Route::get('/update-demographics', 'updateDemographics')->name('update-demographics');
        Route::get('/print-card/{id}', 'printCard')->name('print-card');
        Route::get('/print-cards', 'printCards')->name('print-cards');
        Route::get('/visit-history', 'visitHistory')->name('visit-history');
        Route::get('/reports', 'reports')->name('reports');
    });

    Route::prefix('visits')->name('admin.hms.visits.')->middleware('permission:records.access')->group(function () {
        Route::get('/', [VisitController::class, 'index'])->name('index');
        Route::post('/', [VisitController::class, 'store'])->name('store');
        Route::get('/{visit}', [VisitController::class, 'show'])->name('show');
        Route::get('/patient/{patient}/create', [VisitController::class, 'createForPatient'])->name('create-for-patient');
    });

    Route::prefix('cashier')->name('admin.cashier.')->middleware('permission:cashier.access')->group(function () {
        Route::get('/', [CashierController::class, 'index'])->name('index');
        Route::get('/{visit}', [CashierController::class, 'show'])->name('show');
        Route::post('/{visit}/invoice', [CashierController::class, 'generateInvoice'])->name('invoice');
        Route::post('/{visit}/payment', [CashierController::class, 'confirmPayment'])->name('payment');
        Route::post('/payment/{payment}/refund', [CashierController::class, 'refund'])->name('refund');
    });

    Route::prefix('triage')->name('admin.triage.')->middleware('permission:triage.access')->group(function () {
        Route::get('/', [TriageController::class, 'index'])->name('index');
        Route::get('/queue', [TriageController::class, 'queueManagement'])->name('queue-management');
        Route::get('/waiting-list', [TriageController::class, 'waitingList'])->name('waiting-list');
        Route::get('/capture-vitals/{queue}', [TriageController::class, 'captureVitals'])->name('capture-vitals');
        Route::post('/store-vitals/{queue}', [TriageController::class, 'storeVitals'])->name('store-vitals');
        Route::get('/assessment/{queue}', [TriageController::class, 'assessment'])->name('assessment');
        Route::post('/store-assessment/{queue}', [TriageController::class, 'storeAssessment'])->name('store-assessment');
        Route::get('/reports', [TriageController::class, 'reports'])->name('reports');
        Route::get('/get-department-staff/{departmentId}', [TriageController::class, 'getDepartmentStaff'])->name('get-department-staff');
    });

    Route::prefix('doctor')->name('admin.doctor.')->middleware('permission:doctor.access')->group(function () {
        Route::get('/', [DoctorController::class, 'index'])->name('index');
        Route::get('/{visit}', [DoctorController::class, 'show'])->name('show');
        Route::post('/{visit}/encounter', [DoctorController::class, 'storeEncounter'])->name('encounter');
        Route::post('/{visit}/prescriptions', [DoctorController::class, 'addPrescription'])->name('prescriptions');
        Route::post('/{visit}/service-orders', [DoctorController::class, 'addServiceOrder'])->name('service-orders');
    });

    Route::prefix('nurse')->name('admin.nurse.')->middleware('permission:nurse.access')->group(function () {
        Route::get('/', [NurseController::class, 'index'])->name('index');
        Route::get('/{visit}', [NurseController::class, 'show'])->name('show');
        Route::post('/{visit}/notes', [NurseController::class, 'storeNote'])->name('notes');
    });

    Route::prefix('pharmacy')->name('admin.pharmacy.')->middleware('permission:pharmacy.access')->group(function () {
        Route::get('/', [PharmacyController::class, 'index'])->name('index');
        Route::get('/visit/{visit}', [PharmacyController::class, 'show'])->name('show');
        Route::post('/prescription/{prescription}/dispense', [PharmacyController::class, 'dispense'])->name('dispense');
        Route::get('/inventory', [PharmacyController::class, 'inventory'])->name('inventory');
        Route::post('/inventory', [PharmacyController::class, 'storeInventory'])->name('inventory.store');
        Route::get('/purchase-orders', [PharmacyController::class, 'purchaseOrders'])->name('purchase-orders');
        Route::post('/purchase-orders', [PharmacyController::class, 'storePurchaseOrder'])->name('purchase-orders.store');
        Route::get('/walk-in-sales', [PharmacyController::class, 'walkInSales'])->name('walk-in-sales');
        Route::post('/walk-in-sales', [PharmacyController::class, 'storeWalkInSale'])->name('walk-in-sales.store');
        Route::get('/reports', [PharmacyController::class, 'reports'])->name('reports');
    });

    Route::prefix('laboratory')->name('admin.laboratory.')->middleware('permission:laboratory.access')->group(function () {
        Route::get('/', [LaboratoryController::class, 'index'])->name('index');
        Route::get('/orders/{order}', [LaboratoryController::class, 'show'])->name('show');
        Route::post('/orders/{order}/result', [LaboratoryController::class, 'storeResult'])->name('result');
        Route::get('/reports', [LaboratoryController::class, 'reports'])->name('reports');
    });

    Route::prefix('radiology')->name('admin.radiology.')->middleware('permission:radiology.access')->group(function () {
        Route::get('/', [RadiologyController::class, 'index'])->name('index');
        Route::get('/orders/{order}', [RadiologyController::class, 'show'])->name('show');
        Route::post('/orders/{order}/report', [RadiologyController::class, 'storeReport'])->name('report');
        Route::get('/reports', [RadiologyController::class, 'reports'])->name('reports');
    });

    Route::prefix('hmo')->controller(HmoController::class)->name('admin.settings.hmo.')->middleware('permission:hmo.access')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/store', 'store')->name('store');
        Route::post('/update/{id}', 'update')->name('update');
        Route::get('/delete/{id}', 'destroy')->name('delete');
    });

    Route::prefix('departments')->name('admin.departments.')->middleware('permission:departments.manage')->group(function () {
        Route::get('/', [DepartmentController::class, 'index'])->name('index');
        Route::get('/create', [DepartmentController::class, 'create'])->name('create');
        Route::post('/store', [DepartmentController::class, 'store'])->name('store');
        Route::get('/show/{id}', [DepartmentController::class, 'show'])->name('show');
        Route::get('/edit/{id}', [DepartmentController::class, 'edit'])->name('edit');
        Route::post('/update/{id}', [DepartmentController::class, 'update'])->name('update');
        Route::get('/delete/{id}', [DepartmentController::class, 'destroy'])->name('delete');
        Route::patch('/toggle-status/{id}', [DepartmentController::class, 'toggleStatus'])->name('toggle-status');
        Route::get('/get-staff/{id}', [DepartmentController::class, 'getStaff'])->name('get-staff');
    });

    Route::prefix('groupname')->controller(GroupNameController::class)->middleware('permission:rbac.manage')->group(function () {
        Route::get('/view', 'groupnameView')->name('groupname.view');
        Route::post('/store', 'groupnameStore')->name('groupname.store');
        Route::post('/update/{id}', 'groupnameUpdate')->name('groupname.update');
        Route::get('/delete/{id}', 'groupnameDelete')->name('groupname.delete');
    });

    Route::prefix('permission')->controller(PermissionController::class)->middleware('permission:rbac.manage')->group(function () {
        Route::get('/view', 'permissionView')->name('permission.view');
        Route::post('/store', 'permissionStore')->name('permission.store');
        Route::post('/update/{id}', 'permissionUpdate')->name('permission.update');
        Route::get('/delete/{id}', 'permissionDelete')->name('permission.delete');
    });

    Route::prefix('roles')->controller(RolesController::class)->middleware('permission:rbac.manage')->group(function () {
        Route::get('/view', 'rolesView')->name('roles.view');
        Route::post('/store', 'rolesStore')->name('roles.store');
        Route::post('/update/{id}', 'rolesUpdate')->name('roles.update');
        Route::get('/delete/{id}', 'rolesDelete')->name('roles.delete');
    });

    Route::prefix('roles-permission')->controller(RoleWithPermissionController::class)->middleware('permission:rbac.manage')->group(function () {
        Route::get('/view', 'rolesWithPermissionView')->name('roleswithpermission.view');
        Route::post('/store', 'rolesWithPermissionStore')->name('roleswithpermission.store');
        Route::post('/update/{id}', 'rolesWithPermissionUpdate')->name('roleswithpermission.update');
        Route::get('/delete/{id}', 'rolesWithPermissionDelete')->name('roleswithpermission.delete');
    });

    Route::prefix('system-settings')->controller(SystemSettingController::class)->middleware('permission:system.settings')->group(function () {
        Route::get('/', 'index')->name('system.settings');
        Route::post('/update', 'update')->name('system.settings.update');
    });

    Route::prefix('smtp-setup')->controller(SmtpController::class)->middleware('permission:system.settings')->group(function () {
        Route::get('/smtp-setting', 'smtpSettings')->name('smtp.setting');
        Route::post('update-smtp', 'smtpUpdate')->name('smtp-settings.update');
    });

    Route::prefix('reports/sessions')->name('admin.session-reports.')->middleware('permission:reports.view')->group(function () {
        Route::get('/', [SessionReportsController::class, 'index'])->name('index');
        Route::get('/{session}', [SessionReportsController::class, 'show'])->name('show');
        Route::get('/{session}/export', [SessionReportsController::class, 'export'])->name('export');
    });

    Route::prefix('flags')->name('admin.flags.')->middleware('permission:reports.view')->group(function () {
        Route::get('/', [FlagReviewController::class, 'index'])->name('index');
        Route::get('/{flag}', [FlagReviewController::class, 'show'])->name('show');
        Route::post('/store', [FlagReviewController::class, 'store'])->name('store');
        Route::put('/{flag}/status', [FlagReviewController::class, 'updateStatus'])->name('update-status');
        Route::post('/bulk-update', [FlagReviewController::class, 'bulkUpdate'])->name('bulk-update');
        Route::delete('/{flag}', [FlagReviewController::class, 'destroy'])->name('destroy');
        Route::get('/export', [FlagReviewController::class, 'export'])->name('export');
        Route::get('/api/stats', [FlagReviewController::class, 'getStats'])->name('stats');
    });

    Route::post('/flag-question', [FlagReviewController::class, 'store'])->name('admin.flag-question')->middleware('permission:reports.view');

    Route::prefix('login-attempts')->name('admin.login-attempts.')->middleware('permission:security.manage')->group(function () {
        Route::get('/', [LoginAttemptController::class, 'index'])->name('index');
        Route::get('/show/{id}', [LoginAttemptController::class, 'show'])->name('show');
        Route::get('/suspicious', [LoginAttemptController::class, 'suspicious'])->name('suspicious');
        Route::get('/analytics', [LoginAttemptController::class, 'analytics'])->name('analytics');
        Route::get('/export', [LoginAttemptController::class, 'export'])->name('export');
        Route::post('/block-ip', [LoginAttemptController::class, 'blockIp'])->name('block-ip');
        Route::post('/clear-old', [LoginAttemptController::class, 'clearOld'])->name('clear-old');
    });

    Route::prefix('user-bans')->controller(AdminManageBanController::class)->name('admin.user-bans.')->middleware('permission:security.manage')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/banned', 'bannedUsers')->name('banned');
        Route::get('/history', 'banHistory')->name('history');
        Route::get('/details/{id}', 'showDetails')->name('details');
        Route::post('/ban/{id}', 'banUser')->name('ban');
        Route::post('/lift/{id}', 'liftBan')->name('lift');
        Route::post('/quick-toggle/{id}', 'quickToggleBan')->name('quick-toggle');
        Route::get('/user-details/{id}', 'getUserDetails')->name('user-details');
        Route::post('/bulk-ban', 'bulkBan')->name('bulk-ban');
        Route::post('/bulk-unban', 'bulkUnban')->name('bulk-unban');
        Route::post('/force-logout/{id}', 'forceLogout')->name('force-logout');
    });

    Route::prefix('two-factor')->name('two-factor.')->group(function () {
        Route::get('/setup', [TwoFactorController::class, 'setup'])->name('setup');
        Route::post('/enable', [TwoFactorController::class, 'enable'])->name('enable');
        Route::post('/disable', [TwoFactorController::class, 'disable'])->name('disable');
        Route::get('/verify', [TwoFactorController::class, 'verify'])->name('verify');
        Route::post('/verify', [TwoFactorController::class, 'verifyCode'])->name('verify.post');
        Route::post('/recovery-codes', [TwoFactorController::class, 'generateRecoveryCodes'])->name('recovery-codes');
    });

    Route::middleware('permission:security.manage')->group(function () {
        Route::get('/ip-management', [IPManagementController::class, 'index'])->name('admin.ip.index');
        Route::post('/ip-management/block', [IPManagementController::class, 'blockIP'])->name('admin.ip.block');
        Route::delete('/ip-management/unblock/{id}', [IPManagementController::class, 'unblockIP'])->name('admin.ip.unblock');
        Route::post('/ip-management/whitelist', [IPManagementController::class, 'whitelistIP'])->name('admin.ip.whitelist');
        Route::delete('/ip-management/remove-whitelist/{id}', [IPManagementController::class, 'removeFromWhitelist'])->name('admin.ip.remove-whitelist');
        Route::post('/ip-management/bulk-block', [IPManagementController::class, 'bulkBlock'])->name('admin.ip.bulk-block');
        Route::post('/ip-management/clear-expired', [IPManagementController::class, 'clearExpiredBlocks'])->name('admin.ip.clear-expired');
        Route::get('/ip-management/access-logs', [IPManagementController::class, 'getAccessLogs'])->name('admin.ip.access-logs');
        Route::get('/ip-management/export-logs', [IPManagementController::class, 'exportAccessLogs'])->name('admin.ip.export-logs');
    });
});

Route::prefix('auths')->controller(AuthController::class)->group(function () {
    Route::get('/register', 'showRegister')->name('auth.register');
    Route::post('/register/store', 'register')->name('auth.register.store');
});

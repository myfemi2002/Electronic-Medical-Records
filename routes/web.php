<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HmoController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SmtpController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\TriageController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\GroupNameController;
use App\Http\Controllers\TwoFactorController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\FlagReviewController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\LayingHandsController;
use App\Http\Controllers\IPManagementController;
use App\Http\Controllers\LoginAttemptController;
use App\Http\Controllers\SystemSettingController;
use App\Http\Controllers\AdminManageBanController;
use App\Http\Controllers\SessionReportsController;
use App\Http\Controllers\RoleWithPermissionController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });


    Route::controller(App\Http\Controllers\IndexController::class)->group(function () {
        Route::get('/', 'index')->name('home');
        Route::get('/properties/{slug}', 'show')->name('properties.show');
    });




    require __DIR__.'/auth.php';


    Route::middleware(['auth','roles:admin'])->group(function () {
    // Dashboard Routes
        Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
        Route::get('/logout', [AdminController::class, 'adminDestroy'])->name('admin.logout');
        Route::get('/profile', [AdminController::class, 'adminProfile'])->name('admin.profile');
        Route::post('/profile/store', [AdminController::class, 'adminProfileStore'])->name('admin.profile.store');
        Route::get('/change/password', [AdminController::class, 'adminChangePassword'])->name('admin.change.password');
        Route::post('/update/password', [AdminController::class, 'adminUpdatePassword'])->name('update.password');   

    });

    // All Admin Routes Middleware Starts Here
    Route::middleware(['auth', 'roles:admin', 'checkBanned'])->group(function () {
        
        // Patient Management Routes (Complete with Receipt Verification Workflow)
        Route::prefix('patients')->controller(PatientController::class)->name('admin.records.patients.')->group(function () {
            
            // Basic CRUD
            Route::get('/', 'index')->name('index');
            Route::get('/all', 'all')->name('all');
            Route::get('/create', 'create')->name('create');
            Route::post('/store', 'store')->name('store');
            Route::get('/show/{id}', 'show')->name('show');
            Route::get('/edit/{id}', 'edit')->name('edit');
            Route::post('/update/{id}', 'update')->name('update');
            Route::get('/delete/{id}', 'destroy')->name('delete');
            
            // File Management Routes
            Route::get('/open-file/{id}', 'openFile')->name('open-file');
            Route::get('/close-file/{id}', 'closeFile')->name('close-file');
            Route::get('/get-open-files', 'getOpenFiles')->name('get-open-files');
            
            // Consultancy Verification Routes (Records Department verifies receipt from Accountant)
            Route::get('/verify-consultancy/{id}', 'verifyConsultancy')->name('verify-consultancy');
            Route::post('/confirm-consultancy-payment/{id}', 'confirmConsultancyPayment')->name('confirm-consultancy-payment');
            Route::get('/consultancy-history/{id}', 'consultancyHistory')->name('consultancy-history');
            
            // Additional Patient Functions
            Route::get('/search', 'search')->name('search');
            Route::get('/open', 'open')->name('open');
            Route::get('/update-demographics', 'updateDemographics')->name('update-demographics');
            Route::get('/print-card/{id}', 'printCard')->name('print-card');
            Route::get('/print-cards', 'printCards')->name('print-cards');
            Route::get('/visit-history', 'visitHistory')->name('visit-history');
            Route::get('/reports', 'reports')->name('reports');
        });

            
        // HMO Management
        Route::prefix('hmo')->controller(HmoController::class)->name('admin.settings.hmo.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/store', 'store')->name('store');
            Route::post('/update/{id}', 'update')->name('update');
            Route::get('/delete/{id}', 'destroy')->name('delete');
        });

        // Complete CRUD for dynamic departments management
        Route::prefix('departments')->name('admin.departments.')->group(function () {
            Route::get('/', [DepartmentController::class, 'index'])->name('index');
            Route::get('/create', [DepartmentController::class, 'create'])->name('create');
            Route::post('/store', [DepartmentController::class, 'store'])->name('store');
            Route::get('/show/{id}', [DepartmentController::class, 'show'])->name('show');
            Route::get('/edit/{id}', [DepartmentController::class, 'edit'])->name('edit');
            Route::post('/update/{id}', [DepartmentController::class, 'update'])->name('update');
            Route::get('/delete/{id}', [DepartmentController::class, 'destroy'])->name('delete');
            Route::patch('/toggle-status/{id}', [DepartmentController::class, 'toggleStatus'])->name('toggle-status');
            
            // AJAX Routes
            Route::get('/get-staff/{id}', [DepartmentController::class, 'getStaff'])->name('get-staff');
        });

        // Complete triage workflow with vital interpretation
        Route::prefix('triage')->name('admin.triage.')->group(function () {
            // Dashboard
            Route::get('/', [TriageController::class, 'index'])->name('index');
            
            // Waiting List
            Route::get('/waiting-list', [TriageController::class, 'waitingList'])->name('waiting-list');
            
            // Vitals Capture
            Route::get('/capture-vitals/{queue}', [TriageController::class, 'captureVitals'])->name('capture-vitals');
            Route::post('/store-vitals/{queue}', [TriageController::class, 'storeVitals'])->name('store-vitals');
            
            // Assessment
            Route::get('/assessment/{queue}', [TriageController::class, 'assessment'])->name('assessment');
            Route::post('/store-assessment/{queue}', [TriageController::class, 'storeAssessment'])->name('store-assessment');
            
            // Reports
            Route::get('/reports', [TriageController::class, 'reports'])->name('reports');
            
            // AJAX Routes
            Route::get('/get-department-staff/{departmentId}', [TriageController::class, 'getDepartmentStaff'])->name('get-department-staff');
        });
    

       
        // GroupName Management
        Route::prefix('groupname')->controller(GroupNameController::class)->group(function () {
            Route::get('/view', 'groupnameView')->name('groupname.view')->middleware('auth', 'permission:groupname.view');
            Route::post('/store', 'groupnameStore')->name('groupname.store');        
            Route::post('/update/{id}', 'groupnameUpdate')->name('groupname.update');
            Route::get('/delete/{id}', 'groupnameDelete')->name('groupname.delete');
        }); 

        // Permission Management
        Route::prefix('permission')->controller(PermissionController::class)->group(function () {
            Route::get('/view', 'permissionView')->name('permission.view')->middleware('auth', 'permission:permission.view');
            Route::post('/store', 'permissionStore')->name('permission.store');
            Route::post('/update/{id}', 'permissionUpdate')->name('permission.update');
            Route::get('/delete/{id}', 'permissionDelete')->name('permission.delete');
        }); 

        // Roles Management
        Route::prefix('roles')->controller(RolesController::class)->group(function () {
            Route::get('/view', 'rolesView')->name('roles.view')->middleware('auth', 'permission:roles.view');
            Route::post('/store', 'rolesStore')->name('roles.store');
            Route::post('/update/{id}', 'rolesUpdate')->name('roles.update');
            Route::get('/delete/{id}', 'rolesDelete')->name('roles.delete');
        });

        // Roles with Permission Management
        Route::prefix('roles-permission')->controller(RoleWithPermissionController::class)->group(function () {
            Route::get('/view', 'rolesWithPermissionView')->name('roleswithpermission.view')->middleware('auth', 'permission:roleswithpermission.view');
            Route::post('/store', 'rolesWithPermissionStore')->name('roleswithpermission.store');
            Route::post('/update/{id}', 'rolesWithPermissionUpdate')->name('roleswithpermission.update');
            Route::get('/delete/{id}', 'rolesWithPermissionDelete')->name('roleswithpermission.delete');
        });

        // System Settings Management
        Route::prefix('system-settings')->controller(SystemSettingController::class)->group(function () {
            Route::get('/', 'index')->name('system.settings');
            Route::post('/update', 'update')->name('system.settings.update');
        });

        // SMTP Setup Management
        Route::prefix('smtp-setup')->controller(SmtpController::class)->group(function () {
            Route::get('/smtp-setting', 'smtpSettings')->name('smtp.setting')->middleware('auth', 'permission:smtp.setting');
            Route::post('update-smtp', 'smtpUpdate')->name('smtp-settings.update');
        });

        // Session Reports Routes
        Route::prefix('reports/sessions')->name('admin.session-reports.')->group(function () {
            Route::get('/', [SessionReportsController::class, 'index'])->name('index');
            Route::get('/{session}', [SessionReportsController::class, 'show'])->name('show');
            Route::get('/{session}/export', [SessionReportsController::class, 'export'])->name('export');
        });
        
        
        // Flag Review Routes
        Route::prefix('flags')->name('admin.flags.')->group(function () {
            Route::get('/', [FlagReviewController::class, 'index'])->name('index');
            Route::get('/{flag}', [FlagReviewController::class, 'show'])->name('show');
            Route::post('/store', [FlagReviewController::class, 'store'])->name('store');
            Route::put('/{flag}/status', [FlagReviewController::class, 'updateStatus'])->name('update-status');
            Route::post('/bulk-update', [FlagReviewController::class, 'bulkUpdate'])->name('bulk-update');
            Route::delete('/{flag}', [FlagReviewController::class, 'destroy'])->name('destroy');
            Route::get('/export', [FlagReviewController::class, 'export'])->name('export');
            Route::get('/api/stats', [FlagReviewController::class, 'getStats'])->name('stats');
        });

        // AJAX route for flagging questions (can be used from analytics pages)
        Route::post('/flag-question', [FlagReviewController::class, 'store'])->name('admin.flag-question');

        
        // Login Attempts Management
        Route::prefix('login-attempts')->name('admin.login-attempts.')->group(function () {
            Route::get('/', [LoginAttemptController::class, 'index'])->name('index');
            Route::get('/show/{id}', [LoginAttemptController::class, 'show'])->name('show');
            Route::get('/suspicious', [LoginAttemptController::class, 'suspicious'])->name('suspicious');
            Route::get('/analytics', [LoginAttemptController::class, 'analytics'])->name('analytics');
            Route::get('/export', [LoginAttemptController::class, 'export'])->name('export');
            
            // Security Actions
            Route::post('/block-ip', [LoginAttemptController::class, 'blockIp'])->name('block-ip');
            Route::post('/clear-old', [LoginAttemptController::class, 'clearOld'])->name('clear-old');
        });
            

        // User Ban Management Routes (matching your view route names)
        Route::prefix('user-bans')->controller(AdminManageBanController::class)->group(function () {
            Route::get('/', 'index')->name('admin.user-bans.index');
            Route::get('/banned', 'bannedUsers')->name('admin.user-bans.banned');
            Route::get('/history', 'banHistory')->name('admin.user-bans.history');
            Route::get('/details/{id}', 'showDetails')->name('admin.user-bans.details');
                   
            // These are the important POST routes:
            Route::post('/ban/{id}', 'banUser')->name('admin.user-bans.ban');
            Route::post('/lift/{id}', 'liftBan')->name('admin.user-bans.lift');
           
            // AJAX endpoints
            Route::post('/quick-toggle/{id}', 'quickToggleBan')->name('admin.user-bans.quick-toggle');
            Route::get('/user-details/{id}', 'getUserDetails')->name('admin.user-bans.user-details');
            
            // Bulk actions
            Route::post('/bulk-ban', 'bulkBan')->name('admin.user-bans.bulk-ban');
            Route::post('/bulk-unban', 'bulkUnban')->name('admin.user-bans.bulk-unban');
             Route::post('/force-logout/{id}', 'forceLogout')->name('admin.user-bans.force-logout');

        });

            
        Route::prefix('two-factor')->name('two-factor.')->group(function () {
            Route::get('/setup', [TwoFactorController::class, 'setup'])->name('setup');
            Route::post('/enable', [TwoFactorController::class, 'enable'])->name('enable');
            Route::post('/disable', [TwoFactorController::class, 'disable'])->name('disable');
            Route::get('/verify', [TwoFactorController::class, 'verify'])->name('verify');
            Route::post('/verify', [TwoFactorController::class, 'verifyCode'])->name('verify.post');
            Route::post('/recovery-codes', [TwoFactorController::class, 'generateRecoveryCodes'])->name('recovery-codes');
        });

        // IP Management
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


    Route::prefix('auths')->controller(AuthController::class)->group(function () {
        Route::get('/register', 'showRegister')->name('auth.register');
        Route::post('/register/store',  'register')->name('auth.register.store');
    });

    

    // =====================================
    // FRONTEND ROUTES
    // =====================================
    Route::get('/', [IndexController::class, 'index'])->name('home');
    Route::get('/about', [IndexController::class, 'about'])->name('about');
    Route::get('/contact', [IndexController::class, 'contact'])->name('contact');
    Route::get('/events', [IndexController::class, 'events'])->name('events');










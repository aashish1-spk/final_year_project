<?php
use App\Http\Controllers\AccountController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\admin\JobApplicationController;
use App\Http\Controllers\admin\JobController;
use App\Http\Controllers\admin\UserController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JobsController;
use App\Models\JobApplication;
use Illuminate\Support\Facades\Route;

// Route to the home page
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/jobs', [JobsController::class, 'index'])->name('jobs');
Route::get('/jobs/detail/{id}', [JobsController::class, 'detail'])->name('jobDetail');
Route::post('/apply-job', [JobsController::class, 'applyJob'])->name('applyJob');
Route::post('/save-job', [JobsController::class, 'saveJob'])->name('saveJob');


Route::get('/forget-password', [AccountController::class, 'forgotPassword'])->name('account.forgetPassword');

Route::post('/process-forget-password', [AccountController::class, 'processForgotPassword'])->name('account.processForgotPassword');


Route::get('/reset-password/{token}', [AccountController::class, 'resetPassword'])->name('account.resetPassword');

Route::post('/process-reset-password', [AccountController::class, 'processResetPassword'])->name('account.processResetPassword');


Route::group(['prefix' => 'admin', 'middleware' => 'checkRole'], function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/users', [UserController::class, 'index'])->name('admin.users');
    Route::get('/users/{id}', [UserController::class, 'edit'])->name('admin.users.edit');
    Route::put('/users/{id}', [UserController::class, 'update'])->name('admin.users.update');

    // Route::delete('/users', [UserController::class, 'destroy'])->name('admin.users.destroy');

    Route::post('/users/deactivate', [UserController::class, 'deactivate'])->name('admin.users.deactivate');
    Route::post('/users/activate', [UserController::class, 'activate'])->name('admin.users.activate');


    Route::get('/jobs', [JobController::class, 'index'])->name('admin.jobs');
     


    Route::get('/jobs/edit/{id}', [JobController::class, 'edit'])->name('admin.jobs.edit');

    Route::put('/jobs/update/{id}', [JobController::class, 'update'])->name('admin.jobs.update');

    // Route::delete('/jobs', [JobController::class, 'destroy'])->name('admin.jobs.destroy');

    Route::delete('/jobs/{id}', [JobController::class, 'destroy'])->name('admin.jobs.destroy');

    Route::get('/job-applications', [JobApplicationController::class, 'index'])->name('admin.jobApplications');

    Route::delete('/job-applications', [JobApplicationController::class, 'destroy'])->name('admin.jobApplications.destroy');






});

    


// Grouping routes under 'account' prefix
Route::group(['prefix' => 'account'], function () {
    // Guest routes
    Route::group(['middleware' => 'guest'], function () {


        Route::get('/register', [AccountController::class, 'registration'])->name('account.registration');
        Route::post('/process-register', [AccountController::class, 'processRegistration'])->name('account.processRegistration');
        Route::get('/login', [AccountController::class, 'login'])->name('account.login');
        Route::post('/authenticate', [AccountController::class, 'authenticate'])->name('account.authenticate');

        
    });

    // Authenticated routes
    Route::group(['middleware' => 'auth'], function () {
        // Common routes
        Route::get('/profile', [AccountController::class, 'profile'])->name('account.profile');
        Route::put('/update-profile', [AccountController::class, 'updateProfile'])->name('account.updateProfile');
        Route::get('/logout', [AccountController::class, 'logout'])->name('account.logout');
        Route::post('/update-profile-pic', [AccountController::class, 'updateProfilePic'])->name('account.updateProfilePic');
        Route::post('/update-password', [AccountController::class, 'updatePassword'])->name('account.updatePassword');

        // Routes for 'company' role
        Route::group(['middleware' => 'role:company'], function () {
            Route::get('/create-job', [AccountController::class, 'createJob'])->name('account.createJob');
            Route::post('/save-job', [AccountController::class, 'saveJob'])->name('account.saveJob');
            Route::get('/my-jobs', [AccountController::class, 'myJobs'])->name('account.myJobs');
            Route::get('/my-jobs/edit/{jobId}', [AccountController::class, 'editJob'])->name('account.editJob');
            Route::post('/update-job/{jobId}', [AccountController::class, 'updateJob'])->name('account.updateJob');
            Route::post('/delete-job', [AccountController::class, 'deleteJob'])->name('account.deleteJob');
            Route::get('/company-profile', [AccountController::class, 'companyProfile'])->name('account.companyProfile');

            
        });

        // Routes for 'user' role
        Route::group(['middleware' => 'role:user'], function () {
            Route::get('/my-job-applications', [AccountController::class, 'myJobApplications'])->name('account.myJobApplications');
            Route::post('/remove-job-application', [AccountController::class, 'removeJobs'])->name('account.removeJobs');
            Route::get('/saved-jobs', [AccountController::class, 'savedJobs'])->name('account.savedJobs');
            Route::post('/remove-saved-job', [AccountController::class, 'removeSavedJob'])->name('account.removeSavedJob');

        });
    });

    // Routes for registration options, company, and jobseeker
    Route::get('/register-options', function () {
        return view('front.account.registration-options');
    })->name('account.registration-options');

    Route::get('/register/company', function () {
        return view('front.account.companyregistration');
    })->name('account.companyregistration');

    Route::get('/register/jobseeker', function () {
        return view('front.account.registration');
    })->name('account.jobseekerregistration');

    // Route to process the company registration form 
    Route::post('/process-company-register', [AccountController::class, 'processCompanyRegistration'])->name('account.processCompanyRegistration');
});

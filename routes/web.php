<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

use App\Http\Controllers\Admin\JobTypeController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\JobApplicationController;
use App\Http\Controllers\Admin\JobController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JobsController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\CvController;

// Home
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/jobs', [JobsController::class, 'index'])->name('jobs');
Route::get('/jobs/detail/{id}', [JobsController::class, 'detail'])->name('jobDetail');
Route::post('/apply-job', [JobsController::class, 'applyJob'])->name('applyJob');
Route::post('/save-job', [JobsController::class, 'saveJob'])->name('saveJob');

// Forgot/Reset Password
Route::get('/forget-password', [AccountController::class, 'forgotPassword'])->name('account.forgetPassword');
Route::post('/process-forget-password', [AccountController::class, 'processForgotPassword'])->name('account.processForgotPassword');
Route::get('/reset-password/{token}', [AccountController::class, 'resetPassword'])->name('account.resetPassword');
Route::post('/process-reset-password', [AccountController::class, 'processResetPassword'])->name('account.processResetPassword');

// Email Verification (UPDATED)
Route::get('/email/verify', function () {
    return auth()->user()->role === 'company'
        ? view('front.account.company-verify-email')
        : view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();

    if ($request->user()->role === 'company') {
        return redirect('/account/company-profile')->with('message', 'Company email verified successfully!');
    } else {
        return redirect('/account/profile')->with('message', 'Email verified successfully!');
    }
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

// Admin Routes
Route::group(['prefix' => 'admin', 'middleware' => 'checkRole'], function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

    Route::get('/users', [UserController::class, 'index'])->name('admin.users');
    Route::get('/users/{id}', [UserController::class, 'edit'])->name('admin.users.edit');
    Route::put('/users/{id}', [UserController::class, 'update'])->name('admin.users.update');
    Route::post('/users/deactivate', [UserController::class, 'deactivate'])->name('admin.users.deactivate');
    Route::post('/users/activate', [UserController::class, 'activate'])->name('admin.users.activate');

    Route::get('/jobs', [JobController::class, 'index'])->name('admin.jobs');
    Route::get('/jobs/edit/{id}', [JobController::class, 'edit'])->name('admin.jobs.edit');
    Route::put('/jobs/update/{id}', [JobController::class, 'update'])->name('admin.jobs.update');
    Route::delete('/jobs/{id}', [JobController::class, 'destroy'])->name('admin.jobs.destroy');

    Route::get('/job-applications', [JobApplicationController::class, 'index'])->name('admin.jobApplications');
    Route::delete('/job-applications', [JobApplicationController::class, 'destroy'])->name('admin.jobApplications.destroy');

    Route::resource('categories', CategoryController::class);
    Route::resource('job_types', JobTypeController::class);
});

// Account Routes
Route::group(['prefix' => 'account'], function () {

    // Guest routes
    Route::group(['middleware' => 'guest'], function () {
        Route::get('/register', [AccountController::class, 'registration'])->name('account.registration');
        Route::post('/process-register', [AccountController::class, 'processRegistration'])->name('account.processRegistration');
        Route::get('/login', [AccountController::class, 'login'])->name('account.login');
        Route::post('/authenticate', [AccountController::class, 'authenticate'])->name('account.authenticate');
    });

    // Authenticated + Email Verified routes
    Route::group(['middleware' => ['auth', 'verified']], function () {

        Route::get('/profile', [AccountController::class, 'profile'])->name('account.profile');
        Route::put('/update-profile', [AccountController::class, 'updateProfile'])->name('account.updateProfile');
        Route::get('/logout', [AccountController::class, 'logout'])->name('account.logout');
        Route::post('/update-profile-pic', [AccountController::class, 'updateProfilePic'])->name('account.updateProfilePic');
        Route::post('/update-password', [AccountController::class, 'updatePassword'])->name('account.updatePassword');

        // CV
        Route::get('/create-cv', [CvController::class, 'create'])->name('account.createCV');
        Route::post('/create-cv', [CvController::class, 'store'])->name('account.storeCV');
        Route::get('/download-cv', [CvController::class, 'downloadCV'])->name('account.downloadCV');
        Route::get('/edit-cv/{id}', [CvController::class, 'edit'])->name('account.editCV');
        Route::put('/update-cv/{id}', [CvController::class, 'update'])->name('account.updateCV');
        Route::get('/cv/{id}/download', [CvController::class, 'downloadCVCompany'])->name('cv.downloadCVCompany');
        Route::delete('/cv/delete/{id}', [CvController::class, 'delete'])->name('cv.delete');
        Route::get('/cvs', [CvController::class, 'listCVs'])->name('account.listCVs');

        // Notifications
        Route::post('/company/notify', [AccountController::class, 'notify'])->name('company.notify');
        Route::delete('/notifications/{id}', [AccountController::class, 'destroy'])->name('notifications.delete');

        // Company-only routes
        Route::group(['middleware' => 'role:company'], function () {
            Route::get('/create-job', [AccountController::class, 'createJob'])->name('account.createJob');
            Route::post('/save-job', [AccountController::class, 'saveJob'])->name('account.saveJob');
            Route::get('/my-jobs', [AccountController::class, 'myJobs'])->name('account.myJobs');
            Route::get('/my-jobs/edit/{jobId}', [AccountController::class, 'editJob'])->name('account.editJob');
            Route::post('/update-job/{jobId}', [AccountController::class, 'updateJob'])->name('account.updateJob');
            Route::post('/delete-job', [AccountController::class, 'deleteJob'])->name('account.deleteJob');
            Route::get('/company-profile', [AccountController::class, 'companyProfile'])->name('account.companyProfile');
        });

        // User-only routes
        Route::group(['middleware' => 'role:user'], function () {
            Route::get('/my-job-applications', [AccountController::class, 'myJobApplications'])->name('account.myJobApplications');
            Route::post('/remove-job-application', [AccountController::class, 'removeJobs'])->name('account.removeJobs');
            Route::get('/saved-jobs', [AccountController::class, 'savedJobs'])->name('account.savedJobs');
            Route::post('/remove-saved-job', [AccountController::class, 'removeSavedJob'])->name('account.removeSavedJob');
        });
    });

    // Registration options
    Route::get('/register-options', function () {
        return view('front.account.registration-options');
    })->name('account.registration-options');

    Route::get('/register/company', function () {
        return view('front.account.companyregistration');
    })->name('account.companyregistration');

    Route::get('/register/jobseeker', function () {
        return view('front.account.registration');
    })->name('account.jobseekerregistration');

    Route::post('/process-company-register', [AccountController::class, 'processCompanyRegistration'])->name('account.processCompanyRegistration');
});

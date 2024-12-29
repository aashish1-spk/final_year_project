<?php
use App\Http\Controllers\AccountController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

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

// Route to the home page
Route::get('/', [HomeController::class, 'index'])->name('home');

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
        Route::get('/profile', [AccountController::class, 'profile'])->name('account.profile');
        Route::put('/update-profile', [AccountController::class, 'updateProfile'])->name('account.updateProfile');
        Route::get('/logout', [AccountController::class, 'logout'])->name('account.logout');
        Route::post('/update-profile-pic', [AccountController::class, 'updateProfilePic'])->name('account.updateProfilePic');
        
//job
    Route::get('/create-job', [AccountController::class, 'createJob'])->name('account.createJob');
    Route::post('/save-job', [AccountController::class, 'saveJob'])->name('account.saveJob');
    Route::get('/my-jobs', [AccountController::class, 'myJobs'])->name('account.myJobs');
    Route::get('/my-jobs/edit/{jobId}', [AccountController::class, 'editJob'])->name('account.editJob');
    Route::post('/update-job/{jobId}', [AccountController::class, 'updateJob'])->name('account.updateJob');
    Route::post('/delete-job', [AccountController::class, 'deleteJob'])->name('account.deleteJob');



    });

// Route to process the company registration form 
    Route::post('/process-company-register', [AccountController::class, 'processCompanyRegistration'])
        ->name('account.processCompanyRegistration');


//routes for registration options, company, and jobseeker
Route::get('/register-options', function () {
    return view('front.account.registration-options');
})->name('account.registration-options');

// Route to show company registration form
Route::get('/register/company', function () {
    return view('front.account.companyregistration');
})->name('account.companyregistration');

// Route to show job seeker registration form
Route::get('/register/jobseeker', function () {
    return view('front.account.registration');
})->name('account.jobseekerregistration'); 

//
Route::get('/company-profile', [AccountController::class, 'companyProfile'])->name('account.companyProfile');


//update password
        Route::post('/update-password', [AccountController::class, 'updatePassword'])->name('account.updatePassword');





});
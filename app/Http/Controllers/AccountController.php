<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\User;
use App\Models\Job;
use App\Models\JobApplication;
use App\Models\savedJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Symfony\Contracts\Service\Attribute\Required;
use App\Models\Category;
use App\Models\JobType;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\ResetPasswordEmail;





class AccountController extends Controller
{

    public function registration()
    {
        return view("front.account.registration");
    }



    public function processRegistration(Request $request)
    {
        // Validate the input
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:5|same:confirm_password',
            'confirm_password' => 'required',
        ]);

        if ($validator->passes()) {

            // Create user
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->save();

            // Log the user in
            Auth::login($user);

            // Send verification email
            $user->sendEmailVerificationNotification();

            return response()->json([
                'status' => true,
                'redirect' => route('verification.notice'), // redirect to email verification notice
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    //this method shows user login page
    public function login()
    {
        return view('front.account.login');
    }

    public function authenticate(Request $request)
    {
        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // If validation passes, proceed with the login attempt
        if ($validator->passes()) {
            // Attempt to authenticate the user with email and password
            if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
                $user = Auth::user(); // Get the authenticated user

                // Check if the user is a 'company' and not approved
                if ($user->role === 'company' && !$user->is_approved) {
                    // Log the user out if they are a company and not approved
                    Auth::logout();
                    return redirect()->route('account.login')->with('error', 'Your account is pending approval by the admin.');
                }

                // Check if the user is active
                if ($user->is_active) {
                    // If the user is active and either not a company or approved, allow access
                    return redirect()->route('account.profile');
                } else {
                    // Log the user out if inactive
                    Auth::logout();
                    // Redirect back to login with error message
                    return redirect()->route('account.login')->with('error', 'Your account has been deactivated.');
                }
            } else {
                // If authentication fails, return with error
                return redirect()->route('account.login')->with('error', 'Either email or password is incorrect');
            }
        } else {
            // If validation fails, return with errors
            return redirect()
                ->route('account.login')
                ->withErrors($validator)
                ->withInput($request->only('email'));
        }
    }



    public function profile()
    {
        $user = Auth::user();

        if ($user->role === 'company' && !$user->is_approved) {
            abort(403, 'Your account is pending approval by the admin.');
        }

        if (in_array($user->role, ['user', 'company', 'admin'])) {
            return view('front.account.profile', [
                'user' => $user
            ]);
        }

        abort(403, 'Unauthorized access.');
    }




   
    public function logout()
    {
        Auth::logout();
        return redirect()->route('account.login');
    }

    //for image

    public function updateProfilePic(Request $request)
    {


        $id = Auth::user()->id;
        $validator = Validator::make($request->all(), [
            'image' => 'required|image'
        ]);



        if ($validator->passes()) {


            $image = $request->image;
            $ext = $image->getClientOriginalExtension();
            $imageName = $id . '-' . time() . '.' . $ext;
            $image->move(public_path('/profile_pic'), $imageName);


            User::where('id', $id)->update(['image' => $imageName]);



            session()->flash('success', 'profile picture updated successfully');

            return response()->json([
                'status' => true,
                'errors' => []
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }


    // public function processCompanyRegistration(Request $request)
    // {
    //     // Validate the input
    //     $validator = Validator::make($request->all(), [
    //         'company_name' => 'required|string|max:255',
    //         'email' => 'required|email|unique:users,email',
    //         'password' => 'required|min:6|same:confirm_password',
    //         'confirm_password' => 'required',
    //         'pan_number' => 'required|unique:users,pan_number',  // Add PAN number validation
    //     ]);

    //     if ($validator->passes()) {

    //         // Creating a new user with the 'company' role
    //         $user = new User();
    //         $user->name = $request->company_name;
    //         $user->email = $request->email;
    //         $user->password = Hash::make($request->password);
    //         $user->role = 'company';
    //         $user->email_verified_at = null;
    //         $user->pan_number = $request->pan_number;  // Save the PAN number in the 'users' table
    //         $user->save();

    //         // Automatically login the user
    //         Auth::login($user);

    //         // Send email verification
    //         $user->sendEmailVerificationNotification();

    //         // Redirect to verification notice page
    //         return response()->json([
    //             'status' => true,
    //             'redirect_url' => route('verification.notice'),
    //         ]);

    //     } else {
    //         return response()->json([
    //             'status' => false,
    //             'errors' => $validator->errors(),
    //         ]);
    //     }
    // }


    public function processCompanyRegistration(Request $request)
    {
        // Validate the input
        $validator = Validator::make($request->all(), [
            'company_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|same:confirm_password',
            'confirm_password' => 'required',
            'pan_number' => 'required|unique:users,pan_number',  // Add PAN number validation
        ]);

        if ($validator->passes()) {
            // Creating a new user with the 'company' role
            $user = new User();
            $user->name = $request->company_name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->role = 'company';
            $user->email_verified_at = null;
            $user->pan_number = $request->pan_number;  // Save the PAN number in the 'users' table
            $user->is_approved = false; // Initially not approved
            $user->save();

            // Send email verification
            $user->sendEmailVerificationNotification();

            // Flash success message to the session
            session()->flash('success', 'Registration successful. Please verify your email and wait for admin approval.');

            // Redirect to the verification notice page
            return response()->json([
                'status' => true,
                'redirect_url' => route('account.login'),
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }
    }






    public function companyprofile()
    {
        $user = auth()->user(); // Get the authenticated user
        return view('front.account.companyprofile', compact('user'));
    }



    public function updateProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . auth()->id(),
            'designation' => 'required|string|max:255',
            'mobile' => 'required|numeric|digits:10',
        ]);
    
        if ($validator->fails()) {
            // Redirect back with errors and old input
            return back()->withErrors($validator)->withInput();
        }
    
        // Update user data
        $user = auth()->user();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->designation = $request->designation;
        $user->mobile = $request->mobile;
        $user->save();
    
        // Redirect back with success message
        return back()->with('success', 'Profile updated successfully');
    }
    
    public function updatePassword(Request $request)
    {
        // Validate the input
        $validator = Validator::make($request->all(), [
            'old_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed', // This ensures 'new_password' and 'new_password_confirmation' match
        ]);
    
        // If validation fails, redirect back with errors
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
    
        $user = auth()->user();
    
        // Check if the old password matches the one in the database
        if (!Hash::check($request->old_password, $user->password)) {
            return back()->withErrors(['old_password' => 'Your old password is incorrect.']);
        }
    
        // Update the password
        $user->password = Hash::make($request->new_password);
        $user->save();
    
        // Redirect back with success message
        return back()->with('success', 'Password updated successfully.');
    }
    



  


    //job create

    public function createJob()
    {
        $categories = Category::orderBy('name', 'ASC')
            ->where('status', 1)
            ->get();

        $jobTypes = JobType::orderBy('name', 'ASC')
            ->where('status', 1)
            ->get();

        return view('front.account.job.create', [
            'categories' => $categories,
            'jobTypes' => $jobTypes,

        ]);
    }

    public function saveJob(Request $request)
    {

        $rules = [
            'title' => 'required|min:5|max:200',
            'category' => 'required',
            'jobType' => 'required',
            'vacancy' => 'required|integer',
            'location' => 'required|max:50',
            'description' => 'required',
            'company_name' => 'required|min:3|max:75',
            'website' => 'nullable|url|max:255',
            'responsibility' => 'nullable|min:5|max:1000',
            'qualifications' => 'nullable|min:5|max:1000',
            'benefits' => 'nullable|min:5|max:1000',
        ];


        $validator = Validator::make($request->all(), $rules);

        // Check if validation passes
        if ($validator->passes()) {
            // Create a new Job entry
            $job = new Job();
            $job->title = $request->title;
            $job->category_id = $request->category;
            $job->job_type_id = $request->jobType;
            $job->user_id = Auth::user()->id;
            $job->vacancy = $request->vacancy;
            $job->salary = $request->salary ?: 'N/A';
            $job->location = $request->location;
            $job->description = $request->description;
            $job->benefits = $request->benefits ?: 'N/A';
            $job->responsibility = $request->responsibility ?: 'N/A';
            $job->qualifications = $request->qualifications ?: 'N/A';
            $job->keywords = $request->keywords;
            $job->experience = $request->experience;
            $job->company_name = $request->company_name;
            $job->company_location = $request->company_location ?: 'N/A';
            $job->company_website = $request->website ?: 'N/A';
            $job->save();

            // Return success response
            session()->flash('success', 'Job added successfully.');

            return response()->json([
                'status' => true,
                'message' => 'Job added successfully.'
            ]);
        } else {
            // Return validation errors
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }



    public function myJobs()
    {

        $jobs = job::where('user_id', Auth::user()->id)->with('jobType')->orderBy('created_at', 'DESC')->paginate(10);

        return view('front.account.job.my-jobs', [
            'jobs' => $jobs
        ]);
    }

    public function editJob(Request $request, $id)
    {


        $categories = Category::orderBy('name', 'ASC')
            ->where('status', 1)
            ->get();

        $jobTypes = JobType::orderBy('name', 'ASC')
            ->where('status', 1)
            ->get();

        $job = Job::where([
            'user_id' => Auth::user()->id,
            'id' => $id
        ])->first();

        if ($job == null) {
            abort(404);
        }

        return view('front.account.job.edit', [
            'categories' => $categories,
            'jobTypes' => $jobTypes,
            'job' => $job,

        ]);
    }

    public function updateJob(Request $request, $id)
    {
        // Validation rules
        $rules = [
            'title' => 'required|min:5|max:200',
            'category' => 'required',
            'jobType' => 'required',
            'vacancy' => 'required|integer',
            'location' => 'required|max:50',
            'description' => 'required',
            'company_name' => 'required|min:3|max:75',
            'website' => 'nullable|url|max:255',
            'responsibility' => 'nullable|min:5|max:1000',
            'qualifications' => 'nullable|min:5|max:1000',
            'benefits' => 'nullable|min:5|max:1000',
        ];

        // Validate the request data
        $validator = Validator::make($request->all(), $rules);

        // Check if validation passes
        if ($validator->passes()) {

            $job = Job::find($id);
            $job->title = $request->title;
            $job->category_id = $request->category;
            $job->job_type_id = $request->jobType;
            $job->user_id = Auth::user()->id;
            $job->vacancy = $request->vacancy;
            $job->salary = $request->salary ?: 'N/A';
            $job->location = $request->location;
            $job->description = $request->description;
            $job->benefits = $request->benefits ?: 'N/A';
            $job->responsibility = $request->responsibility ?: 'N/A';
            $job->qualifications = $request->qualifications ?: 'N/A';
            $job->keywords = $request->keywords;
            $job->experience = $request->experience;
            $job->company_name = $request->company_name;
            $job->company_location = $request->company_location ?: 'N/A';
            $job->company_website = $request->website ?: 'N/A';
            $job->save();

            // Return success response
            session()->flash('success', 'Job updated successfully.');

            return response()->json([
                'status' => true,
                'message' => 'Job updated successfully.'
            ]);
        } else {
            // Return validation errors
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function deleteJob(Request $request)
    {
        $job = Job::where([
            'user_id' => Auth::user()->id,
            'id' => $request->jobId
        ])->first();

        if ($job == null) {
            session()->flash('error', 'Either job deleted or not found.');
            return response()->json([
                'status' => true
            ]);
        }

        Job::where('id', $request->jobId)->delete();
        session()->flash('success', 'Job deleted successfully.');
        return response()->json([
            'status' => true
        ]);
    }

    public function myJobApplications()
    {
        $jobApplications = JobApplication::where('user_id', Auth::user()->id)
            ->with(['job', 'job.jobType', 'job.applications'])
            ->orderBy('created_at', 'DESC')

            ->paginate(10); // Added pagination

        return view('front.account.job.my-job-applications', [
            'jobApplications' => $jobApplications
        ]);
    }


    public function removeJobs(Request $request)
    {
        $jobApplication = JobApplication::where(
            [
                'id' => $request->id,
                'user_id' => Auth::user()->id
            ]
        )->first();

        if ($jobApplication == null) {
            session()->flash('error', 'Job application not found');
            return response()->json([
                'status' => false,
            ]);
        }

        JobApplication::find($request->id)->delete();
        session()->flash('success', 'Job application removed successfully.');

        return response()->json([
            'status' => true,
        ]);
    }

    public function savedJobs()
    {
        // $jobApplications = JobApplication::where('user_id',Auth::user()->id)
        //         ->with(['job','job.jobType','job.applications'])
        //         ->paginate(10);

        $savedJobs = SavedJob::where([
            'user_id' => Auth::user()->id
        ])->with(['job', 'job.jobType', 'job.applications'])
            ->orderBy('created_at', 'DESC')
            ->paginate(10);

        return view('front.account.job.saved-jobs', [
            'savedJobs' => $savedJobs
        ]);
    }

    public function removeSavedJob(Request $request)
    {
        $savedJob = SavedJob::where(
            [
                'id' => $request->id,
                'user_id' => Auth::user()->id
            ]
        )->first();

        if ($savedJob == null) {
            session()->flash('error', 'Job not found');
            return response()->json([
                'status' => false,
            ]);
        }

        SavedJob::find($request->id)->delete();
        session()->flash('success', 'Job removed successfully.');

        return response()->json([
            'status' => true,
        ]);
    }



    public function forgotPassword()
    {
        return view('front.account.forgot-password');
    }

    public function processForgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email'
        ]);

        if ($validator->fails()) {
            return redirect()->route('account.forgetPassword')->withInput()->withErrors($validator);
        }


        $token = Str::random(60);

        \DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        \DB::table('password_reset_tokens')->insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => now()
        ]);

        // Send Email 
        $user = User::where('email', $request->email)->first();
        $mailData =  [
            'token' => $token,
            'user' => $user,
            'subject' => 'You have requested to change your password.'
        ];

        Mail::to($request->email)->send(new ResetPasswordEmail($mailData));

        return redirect()->route('account.forgetPassword')->with('success', 'Reset password email has been sent to your inbox.');
    }

    public function resetPassword($tokenString)
    {
        $token = \DB::table('password_reset_tokens')->where('token', $tokenString)->first();

        if ($token == null) {
            return redirect()->route('account.forgetPassword')->with('error', 'Invalid token.');
        }

        return view('front.account.reset-password', [
            'tokenString' => $tokenString
        ]);
    }

    public function processResetPassword(Request $request)
    {

        $token = \DB::table('password_reset_tokens')->where('token', $request->token)->first();

        if ($token == null) {
            return redirect()->route('account.forgotPassword')->with('error', 'Invalid token.');
        }

        $validator = Validator::make($request->all(), [
            'new_password' => 'required|min:5',
            'confirm_password' => 'required|same:new_password',
        ]);

        if ($validator->fails()) {
            return redirect()->route('account.resetPassword', $request->token)->withErrors($validator);
        }

        User::where('email', $token->email)->update([
            'password' => Hash::make($request->new_password)
        ]);

        return redirect()->route('account.login')->with('success', 'You have successfully changed your password.');
    }




    // public function notify(Request $request)
    // {
    //     // Validate the incoming request
    //     $request->validate([
    //         'applicant_id' => 'required|exists:users,id', // Check if jobseeker exists
    //         'message' => 'required|string', // Notification message
    //     ]);

    //     // Create a new notification for the jobseeker
    //     Notification::create([
    //         'user_id' => $request->applicant_id, // Jobseeker's user ID
    //         'message' => $request->message, // Notification message
    //     ]);

    //     return back()->with('success', 'Notification sent successfully!');
    // }


    public function notify(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'applicant_ids' => 'required|array', // Ensure applicant_ids is an array
            'applicant_ids.*' => 'exists:users,id', // Ensure each ID exists in the users table
            'message' => 'required|string', // Notification message
        ]);
    
        // Loop through each selected applicant and send notifications
        foreach ($request->applicant_ids as $applicant_id) {
            // Find the applicant by ID
            $applicant = User::findOrFail($applicant_id);
    
            // Find the job related to the applicant (Assuming applicant has applied to one job)
            $job = Job::whereHas('applications', function ($query) use ($applicant) {
                $query->where('user_id', $applicant->id);
            })->first(); // Get the first job applied by this applicant
    
            // If no job is found, fallback to default job title and company name
            $jobTitle = $job ? $job->title : '[Job Title]';
            $companyName = $job ? $job->company_name : '[Company Name]';
    
            // Check if the provided message is 'shortlisted' or 'rejected'
            if ($request->message === 'shortlisted') {
                // Professional message for shortlisted applicants
                $messageContent = "Dear {$applicant->name},\n\nWe are pleased to inform you that your application for the position of {$jobTitle} at {$companyName} has been shortlisted. Your profile has caught our attention, and we would like to move forward with your application to the next stage of the selection process. Our HR team will reach out to you soon for further steps.\n\nBest regards, {$companyName}";
            } elseif ($request->message === 'rejected') {
                // Professional message for rejected applicants
                $messageContent = "Dear {$applicant->name},\n\nThank you for your interest in the position of {$jobTitle} at {$companyName}. After careful review of your application, we regret to inform you that we have chosen to proceed with another candidate for this role. We sincerely appreciate your effort and encourage you to apply for future openings that match your qualifications.\n\nBest regards, {$companyName}";
            } else {
                // Handle invalid message type
                return back()->with('error', 'Invalid message type.');
            }
    
            // Create a new notification for the jobseeker
            Notification::create([
                'user_id' => $applicant->id, // Jobseeker's user ID
                'message' => $messageContent, // Dynamic message content
            ]);
        }
    
        return back()->with('success', 'Notifications sent successfully!');
    }

    public function showNotifications()
    {
        $notifications = auth()->user()->notifications;
        return view('front.account.notifications', compact('notifications'));
    }
    
    

    public function destroy($id)
    {
        // Find the notification by ID and delete it
        $notification = Notification::findOrFail($id);

        // Delete the notification
        $notification->delete();

        // Redirect back with a success message
        return back()->with('success', 'Notification deleted successfully!');
    }
}

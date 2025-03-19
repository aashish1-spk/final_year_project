<?php

namespace App\Http\Controllers;

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

            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->save();

            session()->flash('success', 'you have registered successfully.');

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

    //this method shows user login page
    public function login()
    {
        return view('front.account.login');
    }

    public function authenticate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if ($validator->passes()) {
            if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
                return redirect()->route('account.profile');
            } else {
                return redirect()->route('account.login')->with('error', 'Either email or password is incorrect');
            }
        } else {
            return redirect()
                ->route('account.login')
                ->withErrors($validator)
                ->withInput($request->only('email'));
        }
    }

    public function profile()
    {
        $id = Auth::user()->id;
        $user = User::where('id', $id)->first();
    
        // Check for 'user' role
        if (Auth::user()->role == 'user') {
            return view('front.account.profile', [
                'user' => $user
            ]);
        }
        // Check for 'company' role
        elseif (Auth::user()->role == 'company') {
            return view('front.account.profile', [  // Show same profile view for company
                'user' => $user
            ]);
        }
        // Check for 'admin' role and show same profile view as user and company
        elseif (Auth::user()->role == 'admin') {
            return view('front.account.profile', [  // Show same profile view for admin
                'user' => $user
            ]);
        }
    }
    
    

    public function updateProfile(Request $request)
    {
        $id = Auth::user()->id;

        $validator = Validator::make($request->all(), [
            'name' => 'required|min:5|max:20',
            'email' => 'required|email|unique:users,email,' . $id . ',id',
        ]);

        if ($validator->passes()) {
            $user = User::find($id);
            $user->name = $request->name;
            $user->email = $request->email;
            $user->mobile = $request->mobile;
            $user->designation = $request->designation;
            $user->save();

            session()->flash('success', 'profile updated successfully');

            return redirect()->back();
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
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



    // for company

    public function processCompanyRegistration(Request $request)
{
    // Validate the input
    $validator = Validator::make($request->all(), [
        'company_name' => 'required',
        'email' => 'required|email|unique:users,email', 
        'password' => 'required|min:5|same:confirm_password',
        'confirm_password' => 'required',
    ]);

    if ($validator->passes()) {

        // Creating a new user with the 'company' role
        $user = new User();
        $user->name = $request->company_name; // Saving company name as the user name
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->role = 'company'; // Assigning the company role
        $user->save();

        session()->flash('success', 'You have registered your company successfully.');

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

    public function companyprofile()
    {
        return view('front.account.companyprofile');
    }




    // update password
    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'old_password' => 'required',
            'new_password' => 'required|min:8',
            'confirm_password' => 'required|same:new_password',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }

        if (!Hash::check($request->old_password, auth()->user()->password)) {
            return response()->json([
                'status' => false,
                'error' => 'Your old password is incorrect.',
            ]);
        }

        $user = auth()->user();
        $user->password = Hash::make($request->new_password);

        // $user->save();

        return response()->json([
            'status' => true,
            'message' => 'Password updated successfully.',
        ]);
    }



//job create

public function createJob() {
    $categories = Category::orderBy('name', 'ASC')
    ->where('status', 1)
    ->get();

    $jobTypes = JobType::orderBy('name', 'ASC')
    ->where('status', 1)
    ->get();

    return view('front.account.job.create',[
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
        $job->salary = $request->salary?: 'N/A';
        $job->location = $request->location;
        $job->description = $request->description;
        $job->benefits = $request->benefits ?: 'N/A';
        $job->responsibility = $request->responsibility ?: 'N/A';
        $job->qualifications = $request->qualifications ?: 'N/A';
        $job->keywords = $request->keywords;
        $job->experience = $request->experience;
        $job->company_name = $request->company_name;
        $job->company_location = $request->company_location ?: 'N/A';
        $job->company_website = $request->website?: 'N/A'; 
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



public function myJobs() {

    $jobs = job::where('user_id',Auth::user()->id)->with('jobType')->orderBy('created_at','DESC')->paginate(10);
   
    return view('front.account.job.my-jobs',[
        'jobs' => $jobs
    ]);

}

public function editJob(Request $request, $id) {

   
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

    return view('front.account.job.edit',[
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
        $job->salary = $request->salary?: 'N/A';
        $job->location = $request->location;
        $job->description = $request->description;
        $job->benefits = $request->benefits ?: 'N/A';
        $job->responsibility = $request->responsibility ?: 'N/A';
        $job->qualifications = $request->qualifications ?: 'N/A';
        $job->keywords = $request->keywords;
        $job->experience = $request->experience;
        $job->company_name = $request->company_name;
        $job->company_location = $request->company_location ?: 'N/A';
        $job->company_website = $request->website?: 'N/A'; 
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

public function deleteJob(Request $request) {
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

public function myJobApplications() {
    $jobApplications = JobApplication::where('user_id', Auth::user()->id)
        ->with(['job', 'job.jobType','job.applications'])
        ->orderBy('created_at','DESC')

        ->paginate(10); // Added pagination

    return view('front.account.job.my-job-applications', [
        'jobApplications' => $jobApplications
    ]);
}


public function removeJobs(Request $request){
    $jobApplication = JobApplication::where([
                                'id' => $request->id, 
                                'user_id' => Auth::user()->id]
                            )->first();
    
    if ($jobApplication == null) {
        session()->flash('error','Job application not found');
        return response()->json([
            'status' => false,                
        ]);
    }

    JobApplication::find($request->id)->delete();
    session()->flash('success','Job application removed successfully.');

    return response()->json([
        'status' => true,                
    ]);

}

public function savedJobs(){
    // $jobApplications = JobApplication::where('user_id',Auth::user()->id)
    //         ->with(['job','job.jobType','job.applications'])
    //         ->paginate(10);

    $savedJobs = SavedJob::where([
        'user_id' => Auth::user()->id
    ])->with(['job','job.jobType','job.applications'])
    ->orderBy('created_at','DESC')
    ->paginate(10);

    return view('front.account.job.saved-jobs',[
        'savedJobs' => $savedJobs
    ]);
}

public function removeSavedJob(Request $request){
    $savedJob = SavedJob::where([
                                'id' => $request->id, 
                                'user_id' => Auth::user()->id]
                            )->first();
    
    if ($savedJob == null) {
        session()->flash('error','Job not found');
        return response()->json([
            'status' => false,                
        ]);
    }

    SavedJob::find($request->id)->delete();
    session()->flash('success','Job removed successfully.');

    return response()->json([
        'status' => true,                
    ]);

}



}

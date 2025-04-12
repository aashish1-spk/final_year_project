<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\JobType;
use App\Models\SavedJob;
use App\Models\JobApplication;
use App\Models\Job;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\JobNotificationEmail;
use App\Models\Cv;


class JobsController extends Controller
{
    //this method will show jobs page
   public function index(Request $request) {
    $categories = Category::where('status',1)->get();
    $jobTypes = JobType::where('status',1)->get();
    
    $jobs = Job::where('status',1);

    //search using keyword
    if(!empty($request->keyword)) {
        $jobs = $jobs->where(function($querry) use ($request){
        $querry->orWhere('title','like','%'.$request->keyword.'%');
        $querry->orWhere('keywords','like','%'.$request->keyword.'%');

        });
    }

    //search using location
    if(!empty($request->location)) {
        $jobs = $jobs->where('location',$request->location);



    }


    //search using category
    if(!empty($request->category)) {
        $jobs = $jobs->where('category_id',$request->category);



    }

    $jobTypeArray = [];

    // Search using Job Type
    if (!empty($request->jobType)) {
        $jobTypeArray = explode(',', $request->jobType);

        $jobs = $jobs->whereIn('job_type_id', $jobTypeArray);
    }

    // Search using experience
    if (!empty($request->experience)) {
        $jobs = $jobs->where('experience', $request->experience);
    }



    $jobs = $jobs->with(['jobType','category']);


    if ($request->sort == '0') {
        $jobs = $jobs->orderBy('created_at', 'ASC');
    } else {
        $jobs = $jobs->orderBy('created_at', 'DESC');
    }
    

    $jobs = $jobs->paginate(9);
    


    return view('front.jobs', [
        'categories' => $categories,
        'jobTypes' => $jobTypes,
        'jobs' => $jobs,
        'jobTypeArray' => $jobTypeArray
    ]);
    
   

   }
    // This method will show job detail page
    // public function detail($id) {
    //     $job = Job::where([
    //         'id' => $id,
    //         'status' => 1
    //     ])->with(['jobType','category'])->first();
    
    //     if ($job == null) {
    //         abort(404);
    //     }
    
    //     $count = 0;
    //     if (Auth::user()) {
    //         $count = SavedJob::where([
    //             'user_id' => Auth::user()->id,
    //             'job_id' => $id
    //         ])->count();
    //     }
        

    //      // fetch applicants

    //      $applications = JobApplication::where('job_id',$id)->with('user')->get();


    //      return view('front.jobDetail',[ 'job' => $job,
    //                                      'count' => $count,
    //                                      'applications' => $applications
    //                                  ]);
    // }

    public function detail($id) {
        // Fetch the job details
        $job = Job::where([
            'id' => $id,
            'status' => 1
        ])->with(['jobType', 'category'])->first();
    
        if ($job == null) {
            abort(404);
        }
    
        // Check if the user is authenticated and if they have saved this job
        $count = 0;
        if (Auth::user()) {
            $count = SavedJob::where([
                'user_id' => Auth::user()->id,
                'job_id' => $id
            ])->count();
        }
    
        // Fetch job applications
        $applications = JobApplication::where('job_id', $id)->with('user')->get();
    
        // Fetch the CV of the authenticated user
        $cv = null;
        $cvFilePath = null;
        if (Auth::check()) {
            $cv = Cv::where('user_id', Auth::id())->first(); // Fetch CV based on user_id
            if ($cv) {
                $cvFilePath = $cv->cv_file_path;  // If CV exists, fetch the file path
            }
        }
    
        // Return the view with job details, save status, applications, and CV file path
        return view('front.jobDetail', [
            'job' => $job,
            'count' => $count,
            'applications' => $applications,
            'cv' => $cv,  // Pass the entire CV data to the view
            'cvFilePath' => $cvFilePath,  // Pass the CV file path if it exists
        ]);
    }
    
    

    // public function applyJob(Request $request) {
    //     $id = $request->id;
    
    //     $job = Job::where('id', $id)->first();
    
    //     // If job not found in db
    //     if ($job == null) {
    //         session()->flash('error', 'Job does not exist');
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'Job does not exist'
    //         ]);
    //     }
    
    //     // You cannot apply on your own job
    //     $employer_id = $job->user_id;
    //     if ($employer_id == Auth::user()->id) {
    //         session()->flash('error', 'You cannot apply on your own job');
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'You cannot apply on your own job'
    //         ]);
    //     }
    
    //     // Check if the user has already applied for this job
    //     $jobApplicationCount = JobApplication::where('job_id', $id)
    //                                           ->where('user_id', Auth::user()->id)
    //                                           ->count();
    
    //     if ($jobApplicationCount > 0) {
    //         $message = 'You already applied for this job.';
    //         session()->flash('error', $message);
    //         return response()->json([
    //             'status' => false,
    //             'message' => $message
    //         ]);
    //     }
    
    //     // Proceed with job application
    //     $application = new JobApplication();
    //     $application->job_id = $id;
    //     $application->user_id = Auth::user()->id;
    //     $application->employer_id = $employer_id;
    //     $application->applied_date = now();
    //     $application->save();
    
    //      // Send Notification Email to Employer
    //     $employer = User::where('id', $employer_id)->first();
    //     $mailData = [
    //         'employer' => $employer,
    //         'user' => Auth::user(),
    //         'job' => $job,
    // ];

    // Mail::to($employer->email)->send(new JobNotificationEmail($mailData));


    //     $message = 'You have successfully applied.';
    //     session()->flash('success', $message);
    
    //     return response()->json([
    //         'status' => true,
    //         'message' => $message
    //     ]);
    // }


    public function applyJob(Request $request) {
        $id = $request->id;
    
        $job = Job::where('id', $id)->first();
    
        // If job not found in db
        if ($job == null) {
            session()->flash('error', 'Job does not exist');
            return response()->json([
                'status' => false,
                'message' => 'Job does not exist'
            ]);
        }
    
        // You cannot apply on your own job
        $employer_id = $job->user_id;
        if ($employer_id == Auth::user()->id) {
            session()->flash('error', 'You cannot apply on your own job');
            return response()->json([
                'status' => false,
                'message' => 'You cannot apply on your own job'
            ]);
        }
    
        // Check if the user has already applied for this job
        $jobApplicationCount = JobApplication::where('job_id', $id)
                                              ->where('user_id', Auth::user()->id)
                                              ->count();
    
        if ($jobApplicationCount > 0) {
            $message = 'You already applied for this job.';
            session()->flash('error', $message);
            return response()->json([
                'status' => false,
                'message' => $message
            ]);
        }
    
        // Check if the user has uploaded a CV
        $cv = Cv::where('user_id', Auth::user()->id)->first();
        if ($cv == null) {
            session()->flash('error', 'You must upload a CV before applying.');
            return response()->json([
                'status' => false,
                'message' => 'You must upload a CV before applying.'
            ]);
        }
    
        // Proceed with job application
        $application = new JobApplication();
        $application->job_id = $id;
        $application->user_id = Auth::user()->id;
        $application->employer_id = $employer_id;
        $application->applied_date = now();
        $application->cv_file_path = $cv->id; // Attach the CV file path
        $application->save();
    
        // Send Notification Email to Employer
        $employer = User::where('id', $employer_id)->first();
        $mailData = [
            'employer' => $employer,
            'user' => Auth::user(),
            'job' => $job,
            'cv_file_path' => $cv->cv_file_path, // Attach CV file path in the email
        ];
    
        Mail::to($employer->email)->send(new JobNotificationEmail($mailData));
    
        $message = 'You have successfully applied.';
        session()->flash('success', $message);
    
        return response()->json([
            'status' => true,
            'message' => $message
        ]);
    }
    
    
    
    public function saveJob(Request $request) {

        $id = $request->id;

        $job = Job::find($id);

        if ($job == null) {
            session()->flash('error','Job not found');

            return response()->json([
                'status' => false,
            ]);
        }

        // Check if user already saved the job
        $count = SavedJob::where([
            'user_id' => Auth::user()->id,
            'job_id' => $id
        ])->count();

        if ($count > 0) {
            session()->flash('error','You already saved this job.');

            return response()->json([
                'status' => false,
            ]);
        }

        $savedJob = new SavedJob;
        $savedJob->job_id = $id;
        $savedJob->user_id = Auth::user()->id;
        $savedJob->save();

        session()->flash('success','You have successfully saved the job.');

        return response()->json([
            'status' => true,
        ]);

    }






    
       
}
    



<?php

namespace App\Http\Controllers\admin;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Job;
use App\Models\Payment;
use App\Models\JobType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class JobController extends Controller
{
    public function index(Request $request)
{
    $query = Job::with('user', 'applications');

    // Search by title or creator name
    if ($search = $request->get('search')) {
        $query->where('title', 'like', '%' . $search . '%')
              ->orWhereHas('user', function ($q) use ($search) {
                  $q->where('name', 'like', '%' . $search . '%');
              });
    }

    $jobs = $query->orderBy('created_at', 'desc')->paginate(10);

    return view('admin.jobs.list', compact('jobs'));
}


    public function edit($id)
    {
        // Fetch the job and related categories/job types
        $job = Job::findOrFail($id);
        $categories = Category::orderBy('name', 'ASC')->get();
        $jobTypes = JobType::orderBy('name', 'ASC')->get();

        return view('admin.jobs.edit', [
            'job' => $job,
            'categories' => $categories,
            'jobTypes' => $jobTypes,
        ]);
    }


    public function update(Request $request, $id)
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
        ];

        // Validate the request data
        $validator = Validator::make($request->all(), $rules);

        // Check if the validation passes
        if ($validator->passes()) {

            // Find the job
            $job = Job::find($id);

            // Update job attributes
            $job->title = $request->title;
            $job->category_id = $request->category;
            $job->job_type_id = $request->jobType;
            $job->vacancy = $request->vacancy;
            $job->salary = $request->salary;
            $job->location = $request->location;
            $job->description = $request->description;
            $job->benefits = $request->benefits;
            $job->responsibility = $request->responsibility;
            $job->qualifications = $request->qualifications;
            $job->keywords = $request->keywords;
            $job->experience = $request->experience;
            $job->company_name = $request->company_name;
            $job->company_location = $request->company_location;
            $job->company_website = $request->website;

          
            $job->status = $request->status;  // 1 for active, 0 for blocked
            $job->isFeatured = ($request->has('isFeatured')) ? 1 : 0;  // Featured checkbox

            // Save the job
            $job->save();

            // Flash success message
            session()->flash('success', 'Job updated successfully.');

   
            return back();
        } else {

            session()->flash('error', 'There were some errors with your submission.');

           
            return back()->withErrors($validator->errors());
        }
    }


    public function destroy($id)
    {
        $job = Job::findOrFail($id);
        $job->delete();

        return redirect()->route('admin.jobs')->with('success', 'Job deleted successfully!');
    }


    //req feature job
    public function requestFeatured($id)
    {
        $job = Job::findOrFail($id);

        // Check if user owns this job
        if ($job->user_id !== auth()->id()) {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }

        // Check if the job has already been requested for featuring
        if ($job->featured_request) {
            return redirect()->back()->with('error', 'You have already requested this job to be featured.');
        }

        // Logic to mark job as 'pending featured'
        $job->featured_request = true; 
        $job->save();


        return redirect()->back()->with('success', 'Request to feature job has been sent to admin.');
    }


    public function featuredRequests()
    {
        // Fetch jobs where users have requested to be featured
        $featuredRequests = Job::where('featured_request', true)
            ->where('isFeatured', false)
            ->latest()
            ->get();

        return view(
            'admin.jobs.featured_requests',
            compact('featuredRequests')
        );
    }


    public function approveFeatured($id)
    {
        $job = Job::findOrFail($id);

        $job->isFeatured = true;
        $job->featured_request = false;
        $job->featured_until = now()->addMonth(); // Set expiry to 1 month from now
        $job->save();

        return redirect()->back()->with('success', 'Job marked as featured for one month.');
    }



    public function rejectFeatured($id)
    {
        $job = Job::findOrFail($id);
        $job->featured_request = false;
        $job->save();

        return redirect()->back()->with('info', 'Job featured request rejected.');
    }


    public function featuredList()
    {
        $featuredJobs = Job::where('isFeatured', 1)
                            ->whereNotNull('featured_until')
                            ->orderBy('featured_until', 'desc')
                            ->get();
    
        return view('admin.jobs.featured_list', compact('featuredJobs'));
    }
    


    //by admin

    public function viewPaymentDetails($jobId)
    {
        // Fetch job details
        $job = Job::findOrFail($jobId);

        // Fetch the associated payment details
        $payment = Payment::where('job_id', $jobId)->latest()->first(); 

        // If no payment is found, return back with an error
        if (!$payment) {
            return back()->with('error', 'Payment not found for this job.');
        }

        // Return the view with job and payment data
        return view('admin.jobs.payment-details', compact('job', 'payment'));
    }


    //by company
    public function viewPayment(Job $job)
    {
        // Check if the authenticated user owns the job
        if ($job->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access to payment details.');
        }
    
        // Fetch the payment related to this job by the logged-in user
        $payment = Payment::where('job_id', $job->id)
                          ->where('user_id', auth()->id())
                          ->where('status', 'completed')
                          ->first();
    
        // If no completed payment found, return back with error
        if (!$payment) {
            return redirect()->back()->with('error', 'No completed payment found for this job.');
        }
    
        // Show the payment view
        return view('front.account.job.payment', compact('job', 'payment'));

    }


//by admin
    public function downloadInvoice($jobId)
    {
        // Fetch job details
        $job = Job::findOrFail($jobId);

        // Fetch the latest associated payment details
        $payment = Payment::where('job_id', $jobId)->latest()->first();

        // If no payment is found, return back with an error
        if (!$payment) {
            return back()->with('error', 'Payment not found for this job.');
        }

        // Load the invoice view with job and payment data and generate PDF
        $pdf = Pdf::loadView('admin.jobs.invoice', compact('job', 'payment'));

        // Download the generated invoice PDF
        return $pdf->download('invoice_' . $job->id . '.pdf');
    }

    //by company
    public function downloadCompanyInvoice(Job $job)
{
    if ($job->user_id !== auth()->id()) {
        abort(403, 'Unauthorized access to this invoice.');
    }

    $payment = Payment::where('job_id', $job->id)
                      ->where('user_id', auth()->id())
                      ->where('status', 'completed')
                      ->first();

    if (!$payment) {
        return redirect()->back()->with('error', 'No completed payment found for this job.');
    }

    $pdf = Pdf::loadView('front.account.job.invoice', compact('job', 'payment'));

    return $pdf->download('Invoice-Job-' . $job->id . '.pdf');
}

}

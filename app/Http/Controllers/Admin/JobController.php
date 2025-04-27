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
    public function index()
    {
        $jobs = Job::orderBy('created_at', 'DESC')->with('user', 'applications')->paginate(10);
        return view('admin.jobs.list', [
            'jobs' => $jobs
        ]);
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



    // public function update(Request $request, $id)
    // {
    //     // Validate input data
    //     $request->validate([
    //         'title' => 'required|string|max:255',
    //         'category' => 'required|exists:categories,id',
    //         'jobType' => 'required|exists:job_types,id',
    //         'vacancy' => 'required|integer|min:1',
    //         'salary' => 'nullable|numeric|min:0',
    //         'location' => 'required|string|max:255',
    //         'description' => 'required|string',
    //         'benefits' => 'nullable|string',
    //         'responsibility' => 'nullable|string',
    //         'qualifications' => 'nullable|string',
    //         'experience' => 'required|string',
    //         'keywords' => 'nullable|string',
    //         'company_name' => 'required|string|max:255',
    //         'company_location' => 'nullable|string|max:255',
    //         'website' => 'nullable|url',
    //     ]);

    //     // Find job
    //     $job = Job::findOrFail($id);

    //     // Update job details
    //     $job->update([
    //         'title' => $request->title,
    //         'category_id' => $request->category,
    //         'job_type_id' => $request->jobType,
    //         'vacancy' => $request->vacancy,
    //         'salary' => $request->salary,
    //         'location' => $request->location,
    //         'description' => $request->description,
    //         'benefits' => $request->benefits,
    //         'responsibility' => $request->responsibility,
    //         'qualifications' => $request->qualifications,
    //         'experience' => $request->experience,
    //         'keywords' => $request->keywords,
    //         'company_name' => $request->company_name,
    //         'company_location' => $request->company_location,
    //         'company_website' => $request->website,
    //         'is_featured' => $request->has('idFeatured') ? 1 : 0,
    //         'status' => $request->status,
    //     ]);


    //     // Redirect back with success message
    //     return redirect()->route('admin.jobs')->with('success', 'Job updated successfully.');
    // }













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

            // Update status and featured job
            $job->status = $request->status;  // 1 for active, 0 for blocked
            $job->isFeatured = ($request->has('isFeatured')) ? 1 : 0;  // Featured checkbox

            // Save the job
            $job->save();

            // Flash success message
            session()->flash('success', 'Job updated successfully.');

            // Redirect back to the same page to show the success message
            return back();
        } else {
            // Flash validation error message
            session()->flash('error', 'There were some errors with your submission.');

            // Redirect back to the same page to show the error message
            return back()->withErrors($validator->errors());
        }
    }


    // public function destroy(Request $request)
    // {
    //     // Find the job by ID
    //     $job = Job::find($request->id);

    //     // Check if the job exists
    //     if ($job == null) {
    //         session()->flash('error', 'Job not found or already deleted.');
    //         return response()->json([
    //             'status' => false
    //         ]);
    //     }

    //     // Delete the job
    //     $job->delete();

    //     // Flash success message
    //     session()->flash('success', 'Job deleted successfully.');

    //     return response()->json([
    //         'status' => true
    //     ]);
    // }


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
        $job->featured_request = true; // Assuming you have this column
        $job->save();

        // Optionally, notify the admin (you can use events/notifications here)
        // Notification::route('mail', 'admin@example.com')->notify(new FeaturedJobRequest($job));

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


    // public function approveFeatured($id)
    // {
    //     $job = Job::findOrFail($id);

    //     // Ensure you're using the correct column names
    //     $job->isFeatured = true; // This should match the column name in your database
    //     $job->featured_request = false;
    //     $job->save();

    //     return redirect()->back()->with('success', 'Job marked as featured.');
    // }


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

    // public function viewPaymentDetails($jobId)
    // {
    //     // Fetch job details
    //     $job = Job::findOrFail($jobId);

    //     // Fetch the associated payment details with ID 106
    //     $payment = Payment::find(106);  // Fetch payment with ID 106

    //     // Dump the payment details to see its contents
    //     // Log::info('Fetched Payment', ['payment' => $payment]);

    //     // If no payment is found, return back with an error
    //     if (!$payment) {
    //         return back()->with('error', 'Payment not found.');
    //     }

    //     // Return the view with job and payment data
    //     return view('admin.jobs.payment-details', compact('job', 'payment'));
    // }


    //by admin

    public function viewPaymentDetails($jobId)
    {
        // Fetch job details
        $job = Job::findOrFail($jobId);

        // Fetch the associated payment details
        $payment = Payment::where('job_id', $jobId)->latest()->first(); // Use latest payment if there are multiple

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

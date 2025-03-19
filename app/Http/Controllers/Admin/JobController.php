<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Job;
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

    public function update(Request $request, $id)
    {
        // Validate the form data
        $validated = $request->validate([
            'title' => 'required|max:255',
            'category' => 'required|exists:categories,id',
            'jobType' => 'required|exists:job_types,id',
            'vacancy' => 'required|integer|min:1',
            'location' => 'required|string|max:255',
            'description' => 'required|string',
            'company_name' => 'required|string|max:255',
            'experience' => 'required|integer',
            'keywords' => 'nullable|string',
        ]);

        // Find the job by ID
        $job = Job::findOrFail($id);

        // Update the job with validated data
        $job->update([
            'title' => $validated['title'],
            'category_id' => $validated['category'],
            'job_type_id' => $validated['jobType'],
            'vacancy' => $validated['vacancy'],
            'salary' => $request->salary, // Optional
            'location' => $validated['location'],
            'description' => $validated['description'],
            'benefits' => $request->benefits, // Optional
            'responsibility' => $request->responsibility, // Optional
            'qualifications' => $request->qualifications, // Optional
            'experience' => $validated['experience'],
            'keywords' => $request->keywords, // Optional
            'isFeatured' => $request->has('isFeatured') ? 1 : 0,
            'status' => $request->status,
            'company_name' => $validated['company_name'],
            'company_location' => $request->company_location,
            'company_website' => $request->website,
        ]);

        return response()->json(['status' => true]);
    }
}

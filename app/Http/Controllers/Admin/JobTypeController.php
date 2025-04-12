<?php



namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JobType;
use Illuminate\Http\Request;

class JobTypeController extends Controller
{
    public function index()
    {
        $jobTypes = JobType::all();
        return view('admin.job_types.index', compact('jobTypes'));
    }

    public function create()
    {
        return view('admin.job_types.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:job_types,name',
        ]);

        JobType::create([
            'name' => $request->name,
            'status' => $request->status ?? 1
        ]);

        return redirect()->route('job_types.index')->with('success', 'Job Type created successfully.');
    }

    public function edit(JobType $jobType)
    {
        return view('admin.job_types.edit', compact('jobType'));
    }

    public function update(Request $request, JobType $jobType)
    {
        $request->validate([
            'name' => 'required|unique:job_types,name,' . $jobType->id,
        ]);

        $jobType->update([
            'name' => $request->name,
            'status' => $request->status ?? 1
        ]);

        return redirect()->route('job_types.index')->with('success', 'Job Type updated successfully.');
    }

    public function destroy(JobType $jobType)
    {
        $jobType->delete();
        return redirect()->route('job_types.index')->with('success', 'Job Type deleted successfully.');
    }
}

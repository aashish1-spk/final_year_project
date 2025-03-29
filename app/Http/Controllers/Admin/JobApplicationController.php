<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\JobApplication;
use Illuminate\Http\Request;

class JobApplicationController extends Controller
{
    public function index() {
    $applications = JobApplication::orderBy('created_at','DESC')
    
                ->with('job','user','employer')
                ->paginate(10);

    // dd($JobApplication);
    return view('Admin.job-applications.list',[
        'applications' => $applications
    ]);
    }

    public function destroy(Request $request) {
        $id = $request->id;
    
        $jobApplication = JobApplication::find($id);
    
        if (!$jobApplication) {
            return redirect()->back()->with('error', 'Job application not found or already deleted.');
        }
    
        $jobApplication->delete();
    
        return redirect()->back()->with('success', 'Job application deleted successfully.');
    }
    
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Job;
use App\Models\Category;
use App\Models\JobType;

class DashboardController extends Controller
{
    public function index()
    {
        $totalJobSeekers = User::where('role', 'user')->count();      // Job seekers
        $totalCompanies = User::where('role', 'company')->count();    // Companies
        $totalJobs = Job::count();
        $totalCategories = Category::count();
        $totalJobTypes = JobType::count();
    
        return view('admin.dashboard', compact(
            'totalJobSeekers',
            'totalCompanies',
            'totalJobs',
            'totalCategories',
            'totalJobTypes'
        ));
    }
    
}

@extends('front.layouts.app')

@section('main')
<section class="section-5 bg-light">
    <div class="container py-5">
        <div class="row">
            <div class="col">
                <nav aria-label="breadcrumb" class="bg-white shadow-sm rounded-3 p-3 mb-4">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-3 mb-4">
                @include('admin.sidebar')
            </div>
            <div class="col-lg-9">
                @include('front.message')

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body dashboard text-center">
                        <h2 class="mb-3">👋 Welcome Administrator!</h2>
                        <p class="text-muted mb-4">Here is a quick summary of your platform stats.</p>
                        <a href="{{ route('categories.create') }}" class="btn btn-outline-primary mb-2 me-2">➕ Add New Category</a>
                        <a href="{{ route('job_types.create') }}" class="btn btn-outline-success mb-2">➕ Add New Job Type</a>
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="card stat-card shadow-sm p-4 text-center h-100">
                            <i class="bi bi-person-circle display-5 text-primary mb-2"></i>
                            <h5>Job Seekers</h5>
                            <h2>{{ $totalJobSeekers }}</h2>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card stat-card shadow-sm p-4 text-center h-100">
                            <i class="bi bi-building display-5 text-success mb-2"></i>
                            <h5>Companies</h5>
                            <h2>{{ $totalCompanies }}</h2>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card stat-card shadow-sm p-4 text-center h-100">
                            <i class="bi bi-briefcase-fill display-5 text-warning mb-2"></i>
                            <h5>Total Jobs</h5>
                            <h2>{{ $totalJobs }}</h2>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card stat-card shadow-sm p-4 text-center h-100">
                            <i class="bi bi-tags-fill display-5 text-info mb-2"></i>
                            <h5>Categories</h5>
                            <h2>{{ $totalCategories }}</h2>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card stat-card shadow-sm p-4 text-center h-100">
                            <i class="bi bi-layout-text-window-reverse display-5 text-danger mb-2"></i>
                            <h5>Job Types</h5>
                            <h2>{{ $totalJobTypes }}</h2>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>
@endsection

@section('customJs')
<!-- Optionally include Bootstrap Icons if not already loaded -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
@endsection

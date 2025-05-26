@extends('front.layouts.app')

@section('main')
    <section class="section-5 bg-light">
        <div class="container py-5">

            {{-- Breadcrumb --}}
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
                {{-- Sidebar --}}
                <div class="col-lg-3 mb-4">
                    @include('admin.sidebar')
                </div>

                {{-- Main Content --}}
                <div class="col-lg-9">
                    @include('front.message')

                    {{-- Welcome Card --}}
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body dashboard text-center">
                            <h2 class="mb-3">👋 Welcome Administrator!</h2>
                            <p class="text-muted mb-4">Here is a quick summary of your platform stats.</p>
                            <a href="{{ route('categories.create') }}" class="btn btn-outline-primary mb-2 me-2">➕ Add New
                                Category</a>
                            <a href="{{ route('job_types.create') }}" class="btn btn-outline-success mb-2">➕ Add New Job
                                Type</a>
                        </div>
                    </div>

                    {{-- Stat Cards --}}
                    <div class="row g-3 mb-4">
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

                    
                    {{-- <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body">
                            <h4 class="mb-4"> Daily Registrations</h4>
                            <canvas id="dailyChart"></canvas>
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body">
                            <h4 class="mb-4"> Monthly Growth</h4>
                            <canvas id="monthlyChart"></canvas>
                        </div>
                    </div> --}}
                </div>
            </div>
        </div>
    </section>
@endsection

@section('customJs')
    {{-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dailyCtx = document.getElementById('dailyChart').getContext('2d');
            const dailyChart = new Chart(dailyCtx, {
                type: 'bar',
                data: {
                    labels: ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'],
                    datasets: [{
                        label: 'Job Seekers',
                        data: [12, 19, 3, 5, 2, 3, 9],
                        backgroundColor: 'rgba(54, 162, 235, 0.7)'
                    }, {
                        label: 'Companies',
                        data: [2, 3, 20, 5, 1, 4, 7],
                        backgroundColor: 'rgba(255, 99, 132, 0.7)'
                    }]
                },
            });

            const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
            const monthlyChart = new Chart(monthlyCtx, {
                type: 'line',
                data: {
                    labels: ['January', 'February', 'March', 'April', 'May', 'June'],
                    datasets: [{
                        label: 'Job Seekers',
                        data: [30, 50, 40, 60, 70, 90],
                        borderColor: 'rgba(54, 162, 235, 1)',
                        tension: 0.4
                    }, {
                        label: 'Companies',
                        data: [15, 25, 20, 30, 40, 60],
                        borderColor: 'rgba(255, 99, 132, 1)',
                        tension: 0.4
                    }]
                },
            });
        });
    </script> --}}
@endsection

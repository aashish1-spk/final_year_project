@extends('front.layouts.app')

@section('main')
    <section class="section-5 bg-2">
        <div class="container py-5">
            <div class="row">
                <div class="col">
                    <nav aria-label="breadcrumb" class="rounded-3 p-3 mb-4">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item active">Featured Job Requests</li>
                        </ol>
                    </nav>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-3">
                    @include('admin.sidebar')
                </div>
                <div class="col-lg-9">
                    @include('front.message')

                    <div class="card border-0 shadow mb-4">
                        <div class="card-body p-4">
                            <h3 class="fs-4 mb-4">Featured Job Requests</h3>

                            @if($featuredRequests->isEmpty())
                                <p>No requests for featured jobs at the moment.</p>
                            @else
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Job Title</th>
                                            <th>Company</th>
                                            <th>Requested On</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <a href="{{ route('admin.jobs.featured.list') }}" class="btn btn-primary">
                                            View Featured Job List
                                        </a>
                                    </div>
                                    
                                    <tbody>
                                        @foreach ($featuredRequests as $job)
                                            <tr>
                                                <td>{{ $job->title }}</td>
                                                <td>{{ $job->company_name }}</td>
                                                <td>{{ $job->created_at->format('d M, Y') }}</td>
                                                <td>
                                                    <div class="action-dots float-end">
                                                        <button class="btn" data-bs-toggle="dropdown" aria-expanded="false">
                                                            <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
                                                        </button>
                                                        <ul class="dropdown-menu dropdown-menu-end">
                                                            <!-- Approve Action -->
                                                            <li>
                                                                <form action="{{ route('admin.jobs.featured.approve', $job->id) }}" method="POST" class="d-inline">
                                                                    @csrf
                                                                    @method('PUT')
                                                                    <button class="dropdown-item" type="submit">
                                                                        <i class="fa fa-check" aria-hidden="true"></i> Approve
                                                                    </button>
                                                                </form>
                                                            </li>
                                                            <!-- Reject Action -->
                                                            <li>
                                                                <form action="{{ route('admin.jobs.featured.reject', $job->id) }}" method="POST" class="d-inline">
                                                                    @csrf
                                                                    @method('PUT')
                                                                    <button class="dropdown-item" type="submit">
                                                                        <i class="fa fa-times" aria-hidden="true"></i> Reject
                                                                    </button>
                                                                </form>
                                                            </li>
                                                            <!-- View Action -->
                                                            <li>
                                                                <a class="dropdown-item" href="{{ route('jobDetail', $job->id) }}">
                                                                    <i class="fa fa-eye" aria-hidden="true"></i> View
                                                                </a>
                                                            </li>
                                                            <!-- View Payment Details -->
                                                            <li>
                                                                <a class="dropdown-item" href="{{ route('admin.jobs.payment.details', $job->id) }}">
                                                                    <i class="fa fa-credit-card" aria-hidden="true"></i> View Payment
                                                                </a>
                                                            </li>
                                                        </ul>
                                                        
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

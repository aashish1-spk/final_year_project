@extends('front.layouts.app')

@section('main')
<section class="section-5 bg-2">
    <div class="container py-5">
        <div class="row">
            <div class="col">
                <nav aria-label="breadcrumb" class="rounded-3 p-3 mb-4">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Job Types</li>
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

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h3 class="fs-4">All Job Types</h3>
                    <a href="{{ route('job_types.create') }}" class="btn btn-primary">➕ Add New Job Type</a>
                </div>

                <div class="card border-0 shadow">
                    <div class="card-body">
                        @if(count($jobTypes))
                        <table class="table table-bordered mb-0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Job Type Name</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($jobTypes as $index => $jobType)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $jobType->name }}</td>
                                        <td>
                                            <span class="badge bg-{{ $jobType->status ? 'success' : 'secondary' }}">
                                                {{ $jobType->status ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="dropdown text-end">
                                                <button class="btn btn-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <!-- Edit Option -->
                                                    <li>
                                                        <a class="dropdown-item" href="{{ route('job_types.edit', $jobType->id) }}">
                                                            <i class="fa fa-pencil-alt me-2"></i> Edit
                                                        </a>
                                                    </li>

                                                    <!-- Delete Option -->
                                                    <li>
                                                        <form action="{{ route('job_types.destroy', $jobType->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this job type?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="dropdown-item">
                                                                <i class="fa fa-trash me-2"></i> Delete
                                                            </button>
                                                        </form>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @else
                            <p class="text-muted">No job types found.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

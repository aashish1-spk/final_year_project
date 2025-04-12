@extends('front.layouts.app')

@section('main')
<section class="section-5 bg-2">
    <div class="container py-5">
        <div class="row">
            <div class="col">
                <nav aria-label="breadcrumb" class="rounded-3 p-3 mb-4">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('job_types.index') }}">Job Types</a></li>
                        <li class="breadcrumb-item active">Edit</li>
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
                    <div class="card-body card-form">
                        <form action="{{ route('job_types.update', $jobType->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="card-body p-4">
                                <h3 class="fs-4 mb-1">Edit Job Type</h3>

                                <div class="mb-4">
                                    <label for="name" class="mb-2">Job Type Name*</label>
                                    <input type="text" name="name" id="name" placeholder="Enter job type name"
                                        class="form-control @error('name') is-invalid @enderror" value="{{ $jobType->name }}" required>
                                    @error('name')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label for="status" class="mb-2">Status</label>
                                    <select name="status" id="status" class="form-control">
                                        <option value="1" {{ $jobType->status == 1 ? 'selected' : '' }}>Active</option>
                                        <option value="0" {{ $jobType->status == 0 ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                </div>
                            </div>

                            <div class="card-footer p-4">
                                <button type="submit" class="btn btn-success">Update</button>
                                <a href="{{ route('job_types.index') }}" class="btn btn-secondary">Back</a>
                            </div>
                        </form>
                    </div>
                </div>                          
            </div>
        </div>
    </div>
</section>
@endsection

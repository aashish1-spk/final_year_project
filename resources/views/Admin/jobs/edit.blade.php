@extends('front.layouts.app')

@section('main')
<section class="section-5 bg-2">
    <div class="container py-5">
        <div class="row">
            <div class="col">
                <nav aria-label="breadcrumb" class="rounded-3 p-3 mb-4">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Edit Job</li>
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

                <form action="{{ route('admin.jobs.update', $job->id) }}" method="post">
                    @csrf
                    @method('PUT')

                    <div class="card border-0 shadow mb-4">
                        <div class="card-body card-form p-4">
                            <h3 class="fs-4 mb-1">Edit Job Details</h3>

                            <!-- Job Title -->
                            <div class="mb-4">
                                <label class="mb-2">Title<span class="req">*</span></label>
                                <input value="{{ old('title', $job->title) }}" type="text" placeholder="Job Title" name="title" class="form-control @error('title') is-invalid @enderror">
                                @error('title')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Category -->
                            <div class="mb-4">
                                <label class="mb-2">Category<span class="req">*</span></label>
                                <select name="category" class="form-control @error('category') is-invalid @enderror">
                                    <option value="">Select a Category</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category', $job->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                @error('category')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Job Type -->
                            <div class="mb-4">
                                <label class="mb-2">Job Type<span class="req">*</span></label>
                                <select name="jobType" class="form-control @error('jobType') is-invalid @enderror">
                                    <option value="">Select Job Type</option>
                                    @foreach ($jobTypes as $jobType)
                                        <option value="{{ $jobType->id }}" {{ old('jobType', $job->job_type_id) == $jobType->id ? 'selected' : '' }}>{{ $jobType->name }}</option>
                                    @endforeach
                                </select>
                                @error('jobType')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Vacancy -->
                            <div class="mb-4">
                                <label class="mb-2">Vacancy<span class="req">*</span></label>
                                <input value="{{ old('vacancy', $job->vacancy) }}" type="number" min="1" name="vacancy" class="form-control @error('vacancy') is-invalid @enderror">
                                @error('vacancy')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Salary -->
                            <div class="mb-4">
                                <label class="mb-2">Salary</label>
                                <input value="{{ old('salary', $job->salary) }}" type="number" name="salary" class="form-control">
                            </div>

                            <!-- Location -->
                            <div class="mb-4">
                                <label class="mb-2">Location<span class="req">*</span></label>
                                <input value="{{ old('location', $job->location) }}" type="text" name="location" class="form-control @error('location') is-invalid @enderror">
                                @error('location')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                        






                            {{-- <div class="mb-3">
                                <label class="form-label">Featured Job</label>
                                <input class="form-check-input" type="checkbox" id="idFeatured" name="idFeatured" value="1" {{ old('idFeatured', $job->is_featured) ? 'checked' : '' }}>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Status</label>
                                <div>
                                    <input class="form-check-input" type="radio" id="status-active" name="status" value="1" {{ old('status', $job->status) == 1 ? 'checked' : '' }}>
                                    <label for="status-active">Active</label>
                                    <input class="form-check-input" type="radio" id="status-block" name="status" value="0" {{ old('status', $job->status) == 0 ? 'checked' : '' }}>
                                    <label for="status-block">Block</label>
                                </div>
                            </div> --}}






                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="isFeatured" name="isFeatured" value="1" 
                                    {{ old('isFeatured', $job->isFeatured) == 1 ? 'checked' : '' }}>
                                <label class="form-check-label" for="isFeatured">
                                    Featured
                                </label>
                            </div>
                        </div>
                    
                        <div class="mb-4 col-md-6">
                            <!-- Status Radio Buttons -->
                            <div class="form-check-inline">
                                <input class="form-check-input" type="radio" id="status-active" name="status" value="1" 
                                    {{ old('status', $job->status) == 1 ? 'checked' : '' }}>
                                <label class="form-check-label" for="status-active">
                                    Active
                                </label>
                            </div>
                            
                            <div class="form-check-inline">
                                <input class="form-check-input" type="radio" id="status-block" name="status" value="0" 
                                    {{ old('status', $job->status) == 0 ? 'checked' : '' }}>
                                <label class="form-check-label" for="status-block">
                                    Block
                                </label>
                            </div>
                        </div>
                    







                            <!-- Experience -->
                            <div class="mb-4">
                                <label class="mb-2">Experience<span class="req">*</span></label>
                                <select name="experience" class="form-control @error('experience') is-invalid @enderror">
                                    <option value="">Select Experience</option>
                                    @foreach (range(1, 10) as $year)
                                        <option value="{{ $year }}" {{ old('experience', $job->experience) == $year ? 'selected' : '' }}>{{ $year }} years</option>
                                    @endforeach
                                    <option value="10_plus" {{ old('experience', $job->experience) == '10_plus' ? 'selected' : '' }}>10+ years</option>
                                </select>
                                @error('experience')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Description -->
                            <div class="mb-4">
                                <label class="mb-2">Description<span class="req">*</span></label>
                                <textarea name="description" class="form-control @error('description') is-invalid @enderror">{{ old('description', $job->description) }}</textarea>
                                @error('description')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Company Name -->
                            <h3 class="fs-4 mb-1 mt-5 border-top pt-5">Company Details</h3>
                            <div class="mb-4">
                                <label class="mb-2">Company Name<span class="req">*</span></label>
                                <input value="{{ old('company_name', $job->company_name) }}" type="text" name="company_name" class="form-control @error('company_name') is-invalid @enderror">
                                @error('company_name')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Company Location -->
                            <div class="mb-4">
                                <label class="mb-2">Company Location</label>
                                <input value="{{ old('company_location', $job->company_location) }}" type="text" name="company_location" class="form-control">
                            </div>

                            

                            <!-- Submit Button -->
                            <div class="card-footer p-4">
                                <button type="submit" class="btn btn-primary">Update Job</button>
                            </div>
                        </div>
                    </div>
                </form>
                
            </div>
        </div>
    </div>
</section>
@endsection

@extends('front.layouts.app')

@section('main')
    <section class="section-5 bg-2">
        <div class="container py-5">
            <div class="row">
                <div class="col">
                    <nav aria-label="breadcrumb" class="rounded-3 p-3 mb-4">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item active">Edit CV</li>
                        </ol>
                    </nav>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-3">
                    @include('front.account.sidebar')
                </div>

                <div class="col-lg-9">
                    @include('front.message')

                    <div class="card border-0 shadow mb-4">
                        <div class="card-body p-4">
                            <h3 class="fs-4 mb-4">Edit CV</h3>

                            <form action="{{ route('account.updateCV', $cv->id) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="mb-3">
                                    <label>Full Name:</label>
                                    <input type="text" name="name" value="{{ old('name', $cv->name) }}" class="form-control" required>
                                </div>

                                <div class="mb-3">
                                    <label>Email:</label>
                                    <input type="email" name="email" value="{{ old('email', $cv->email) }}" class="form-control" required>
                                </div>

                                <div class="mb-3">
                                    <label>Phone:</label>
                                    <input type="text" name="phone" value="{{ old('phone', $cv->phone) }}" class="form-control" required>
                                </div>

                                <div class="mb-3">
                                    <label>Career Objective:</label>
                                    <textarea name="objective" class="form-control">{{ old('objective', $cv->objective) }}</textarea>
                                </div>

                                <div class="mb-3">
                                    <label>Skills:</label>
                                    <textarea name="skills" class="form-control" required>{{ old('skills', $cv->skills) }}</textarea>
                                </div>

                                <div class="mb-3">
                                    <label>Experience:</label>
                                    <textarea name="experience" class="form-control" required>{{ old('experience', $cv->experience) }}</textarea>
                                </div>

                                <div class="mb-3">
                                    <label>Education:</label>
                                    <textarea name="education" class="form-control" required>{{ old('education', $cv->education) }}</textarea>
                                </div>

                                <div class="mb-3">
                                    <label>Certifications:</label>
                                    <textarea name="certifications" class="form-control">{{ old('certifications', $cv->certifications) }}</textarea>
                                </div>

                                <div class="mb-3">
                                    <label>Languages Known:</label>
                                    <textarea name="languages" class="form-control">{{ old('languages', $cv->languages) }}</textarea>
                                </div>

                                <div class="mb-3">
                                    <label>References:</label>
                                    <textarea name="references" class="form-control">{{ old('references', $cv->references) }}</textarea>
                                </div>

                                <div class="mb-3">
                                    <label>GitHub Link:</label>
                                    <input type="url" name="github" value="{{ old('github', $cv->github_link) }}" class="form-control">
                                </div>
                                
                                <div class="mb-3">
                                    <label>LinkedIn Link:</label>
                                    <input type="url" name="linkedin" value="{{ old('linkedin', $cv->linkedin_link) }}" class="form-control">
                                </div>
                                

                                <button type="submit" class="btn btn-primary">Update CV</button>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
@endsection

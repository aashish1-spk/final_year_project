@extends('front.layouts.app')

@section('main')
    <!-- FontAwesome for star icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <style>
        .star-rating {
            direction: rtl;
            display: inline-flex;
            gap: 0.3rem;
        }

        .star-rating input[type="radio"] {
            display: none;
        }

        .star-rating label {
            color: #ccc;
            cursor: pointer;
            font-size: 1.8rem;
        }

        .star-rating input[type="radio"]:checked~label,
        .star-rating label:hover,
        .star-rating label:hover~label {
            color: #ffc107;
        }

        .card-form {
            background-color: #ffffff;
            border-radius: 0.75rem;
        }

        .section-5.bg-2 {
            background-color: #f8f9fa;
        }

        textarea.form-control {
            resize: none;
        }
    </style>

    <section class="section-5 bg-2">
        <div class="container py-5">
            <div class="row">
                <div class="col">
                    <nav aria-label="breadcrumb" class="rounded-3 p-3 mb-4 bg-light shadow-sm">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('account.myJobApplications') }}">Jobs Applied</a>
                            </li>
                            <li class="breadcrumb-item active">Review and Ask Question</li>
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
                    <div class="card border-0 shadow-sm mb-4 p-3">
                        <div class="card-body card-form">
                            <h3 class="fs-4 mb-3 text-primary text-center">Review and Ask Question</h3>

                            <form action="{{ route('reviews.store', ['company' => $job->user_id]) }}" method="POST">
                                @csrf
                                <input type="hidden" name="job_id" value="{{ $job->id }}">

                                <!-- Star Rating -->
                                <div class="mb-3 text-center">
                                    <label class="form-label d-block mb-2">Rate your experience:</label>
                                    <div class="star-rating justify-content-center">
                                        @for ($i = 5; $i >= 1; $i--)
                                            <input type="radio" name="rating" id="star{{ $i }}"
                                                value="{{ $i }}" {{ old('rating') == $i ? 'checked' : '' }}>
                                            <label for="star{{ $i }}"><i class="fa fa-star"></i></label>
                                        @endfor
                                    </div>
                                    @error('rating')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Instruction -->
                                <h5 class="mt-4 text-center text-muted">
                                    Thank you for applying! Please leave a comment if you have any inquiries about your job
                                    application. We are happy to reach out to you!
                                </h5>

                                <!-- Comment Box -->
                                <div class="mb-3 mt-3">
                                    <label for="comment" class="form-label">Your Comment</label>
                                    <textarea name="comment" rows="3" class="form-control" placeholder="Write a comment..." required>{{ old('comment') }}</textarea>
                                    @error('comment')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Submit Button -->
                                <div class="text-end">
                                    <button type="submit" class="btn btn-primary px-4 py-2 shadow-sm">
                                        <i class="fas fa-paper-plane me-1"></i> Submit Review
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

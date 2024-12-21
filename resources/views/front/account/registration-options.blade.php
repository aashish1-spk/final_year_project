@extends('front.layouts.app')

@section('main')
<section class="section-5 py-5 d-flex align-items-center" style="height: 100vh;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 text-center">
                <h1 class="h3 mb-4">Register</h1>
                <p class="mb-4">Choose an option below to register:</p>
                <div class="d-flex justify-content-around">
                    <a href="{{ route('account.companyregistration') }}" class="btn btn-primary">Register as employee</a>
                    <a href="{{ route('account.jobseekerregistration') }}" class="btn btn-secondary">Register as Job Seeker</a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

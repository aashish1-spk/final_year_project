@extends('front.layouts.app')

@section('main')
<section class="section-5 py-5 d-flex align-items-center" style="height: 100vh;">
    <style>
        /* Base styles for buttons */
        .custom-btn {
            background-color: #333333;
            color: white; 
            padding: 6px 15px; 
            font-size: 0.875rem; 
            text-transform: uppercase;
            border-radius: 5px;
            text-decoration: none;
            border: none;
            cursor: pointer;
            display: inline-block;
            transition: background-color 0.3s ease, color 0.3s ease;
            margin: 0 8px; 
        }

        /* Hover effect for buttons */
        .custom-btn:hover {
            background-color: #28a745; 
            color: white; /
        }

       
        .d-flex {
            display: flex;
            justify-content: center; 
        }
    </style>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 text-center">
                <h1 class="h3 mb-4">Register</h1>
                <p class="mb-4">Choose an option below to register:</p>
                <div class="d-flex">
                    <a href="{{ route('account.companyregistration') }}" class="custom-btn">Register as Company</a>
                    <a href="{{ route('account.registration') }}" class="custom-btn">Register as Job Seeker</a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@extends('front.layouts.app')

@section('main')

<section class="section-5 bg-2">
    <div class="container py-5">
        <div class="row">
            <div class="col">
                <nav aria-label="breadcrumb" class="rounded-3 p-3 mb-4">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li> 
                        <li class="breadcrumb-item active">Account Settings</li>
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
                    {{-- Placeholder for alerts or other content --}}
                </div>

                <div class="card border-0 shadow mb-4">
                    {{-- Placeholder for additional settings --}}
                </div>                
            </div>
        </div>
    </div>
</section>

@endsection

@section('costumjs')

@endsection

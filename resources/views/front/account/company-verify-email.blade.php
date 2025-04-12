@extends('front.layouts.app')

@section('main')

<section class="section-5 bg-light py-5">
    <div class="container">
        <div class="row mb-4">
            <div class="col">
                <nav aria-label="breadcrumb" class="rounded-3 p-3 mb-4 bg-white shadow-sm">
                    <ol class="breadcrumb mb-0">
                        {{-- Optional breadcrumb --}}
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10">
                <div class="card border-0 shadow-lg">
                    <div class="card-body p-5 text-center">
                        <h2 class="mb-3 text-primary">Verify Your Company Email</h2>
                        <p class="lead mb-4 text-muted">
                            Before proceeding, please check your email for a verification link. If you didn’t receive it, you can request another.
                        </p>

                        @if (session('message'))
                            <div class="alert alert-success mt-3" role="alert">
                                {{ session('message') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('verification.send') }}">
                            @csrf
                            <button type="submit" class="btn btn-primary btn-lg px-4 py-2">
                                Resend Verification Email
                            </button>
                        </form>

                        <hr class="my-4">

                        {{-- <p class="mb-0 text-muted">Having trouble? <a href="{{ route('account.contact') }}" class="text-primary">Contact Support</a></p> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

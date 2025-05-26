@extends('front.layouts.app')

@section('main')
    <section class="section-5 bg-2">
        <div class="container py-5">
            <div class="row">
                <div class="col">
                    <nav aria-label="breadcrumb" class="rounded-3 p-3 mb-4">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item active">Notifications</li>
                        </ol>
                    </nav>
                </div>
            </div>

            <div class="row">
                <!-- Sidebar Section -->
                <div class="col-lg-3">
                    @include('front.account.sidebar')
                </div>

                <!-- Main Content Section -->
                <div class="col-lg-9">
                    @include('front.message') 

                    <!-- Notifications List -->
                    <div class="card border-0 shadow mb-4">
                        <div class="card-body p-4">
                            <h3 class="fs-4 mb-4">Your Notifications</h3>

                            @if(auth()->user()->notifications->count() > 0)
                                @foreach(auth()->user()->notifications as $notification)
                                    <div class="alert alert-info">
                                        <p>{{ $notification->message }}</p>
                                        <small>{{ $notification->created_at->diffForHumans() }}</small>

                                        <!-- Delete Notification Form -->
                                        <form action="{{ route('notifications.delete', $notification->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                        </form>
                                    </div>
                                @endforeach
                            @else
                                <div class="alert alert-warning">
                                    You have no notifications at the moment.
                                </div>
                            @endif
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
@endsection

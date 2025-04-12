@extends('front.layouts.app')

@section('main')
    <section class="section-5 bg-2">
        <div class="container py-5">
            <div class="row">
                <div class="col">
                    <nav aria-label="breadcrumb" class="rounded-3 p-3 mb-4">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">My cv</li>
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
                            <h3 class="fs-4 mb-3">My cv</h3>

                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($cvs as $cv)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $cv->name }}</td>
                                            <td>{{ $cv->email }}</td>
                                            <td>{{ $cv->phone }}</td>
                                            <td>
                                                <a href="{{ route('account.downloadCV', $cv->id) }}"
                                                    class="btn btn-sm btn-success">Download PDF</a>
                                                    <form action="{{ route('cv.delete', $cv->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this CV?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                                    </form>
                                                    
                                                <!-- Implement delete later -->
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            @if ($cvs->isEmpty())
                                <p class="text-muted">No CVs available.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

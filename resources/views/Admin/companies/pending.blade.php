@extends('front.layouts.app')

@section('main')
    <section class="section-5 bg-2">
        <div class="container py-5">
            <div class="row">
                <div class="col">
                    <nav aria-label="breadcrumb" class="rounded-3 p-3 mb-4">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item active">Pending Company Approvals</li>
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
                        <div class="card-body p-4">
                            <h3 class="fs-4 mb-3">Pending Company Registrations</h3>

                            @if ($companies->count())
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Company Name</th>
                                            <th>Email</th>
                                            <th>PAN Number</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($companies as $company)
                                            @if ($company->role == 'company' && !$company->is_approved)
                                                <tr>
                                                    <td>{{ $company->name }}</td>
                                                    <td>{{ $company->email }}</td>
                                                    <td>{{ $company->pan_number }}</td>
                                                    <td>
                                                       
                                                        <div class="action-dots float-end">
                                                            <button class="btn" data-bs-toggle="dropdown" aria-expanded="false">
                                                                <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
                                                            </button>
                                                            <ul class="dropdown-menu dropdown-menu-end">
                                                               
                                                                <li>
                                                                    <form action="{{ route('admin.companies.approve', $company->id) }}" method="POST">
                                                                        @csrf
                                                                        <button class="dropdown-item" type="submit">
                                                                            <i class="fa fa-check" aria-hidden="true"></i> Approve
                                                                        </button>
                                                                    </form>
                                                                </li>

                                                               
                                                                <li>
                                                                    <form action="{{ route('admin.companies.reject', $company->id) }}" method="POST">
                                                                        @csrf
                                                                        <button class="dropdown-item" type="submit">
                                                                            <i class="fa fa-times" aria-hidden="true"></i> Reject
                                                                        </button>
                                                                    </form>
                                                                </li>

                                                               
                                                                <li>
                                                                    <a class="dropdown-item" href="https://ird.gov.np/pan-search" target="_blank">
                                                                        <i class="fa fa-search" aria-hidden="true"></i> Check PAN
                                                                    </a>
                                                                </li>

                                                            </ul>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <p>No pending companies.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

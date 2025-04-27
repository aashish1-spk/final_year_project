@extends('front.layouts.app')

@section('main')
    <section class="section-5 bg-2">
        <div class="container py-5">
            <div class="row">
                <div class="col">
                    <nav aria-label="breadcrumb" class="rounded-3 p-3 mb-4">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
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
                        <form action="{{ route('account.updateProfile') }}" method="post" id="userForm" name="userForm">
                            @csrf
                            @method('put')
                            <div class="card-body  p-4">
                                <h3 class="fs-4 mb-1">My Profile</h3>

                                <div class="mb-4">
                                    <label for="name" class="mb-2"> Name*</label>
                                    <input type="text" name="name" id="name" placeholder="Enter Company Name"
                                        class="form-control" value="{{ old('name', $user->name) }}">
                                    @error('name')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="mb-4">
                                    <label for="email" class="mb-2">Email*</label>
                                    <input type="text" name="email" id="email" placeholder="Enter Email"
                                        class="form-control" value="{{ old('email', $user->email) }}">
                                    @error('email')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label for="designation" class="mb-2">
                                        @if ($user->role === 'admin')
                                            Role*
                                        @elseif($user->role === 'company')
                                            Employee Role*
                                        @else
                                            Designation*
                                        @endif
                                    </label>

                                    <input type="text" name="designation" id="designation" placeholder="Designation"
                                        class="form-control"
                                        value="{{ old('designation', $user->role === 'admin' ? 'Admin' : $user->designation) }}"
                                        @if ($user->role === 'admin') readonly @endif>

                                    @error('designation')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>


                                <div class="mb-4">
                                    <label for="mobile" class="mb-2">Mobile*</label>
                                    <input type="text" name="mobile" id="mobile" placeholder="Mobile"
                                        class="form-control" value="{{ old('mobile', $user->mobile) }}">
                                    @error('mobile')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="card-footer  p-4">
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </form>
                    </div>

                    <div class="card border-0 shadow mb-4">
                        <form action="{{ route('account.updatePassword') }}" method="post" id="changePasswordForm"
                            name="changePasswordForm">
                            @csrf
                            <div class="card-body p-4">
                                <h3 class="fs-4 mb-1">Change Password</h3>

                                <!-- Old Password -->
                                <div class="mb-4">
                                    <label for="old_password" class="mb-2">Old Password*</label>
                                    <input type="password" name="old_password" id="old_password" placeholder="Old Password"
                                        class="form-control" value="{{ old('old_password') }}">
                                    @error('old_password')
                                        <p class="text-danger small">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- New Password -->
                                <div class="mb-4">
                                    <label for="new_password" class="mb-2">New Password*</label>
                                    <input type="password" name="new_password" id="new_password" placeholder="New Password"
                                        class="form-control">
                                    @error('new_password')
                                        <p class="text-danger small">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Confirm Password -->
                                <div class="mb-4">
                                    <label for="new_password_confirmation" class="mb-2">Confirm Password*</label>
                                    <input type="password" name="new_password_confirmation" id="new_password_confirmation"
                                        placeholder="Confirm Password" class="form-control">
                                    @error('new_password_confirmation')
                                        <p class="text-danger small">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="card-footer p-4">
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </section>
@endsection

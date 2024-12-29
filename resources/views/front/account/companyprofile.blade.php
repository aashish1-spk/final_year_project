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
                    {{-- <div id="alertContainer"></div> --}}

                    <form action="{{ route('account.updateProfile') }}" method="post" id="userForm" name="userForm">
                        @csrf
                        @method('put')
                        <div class="card-body  p-4">
                            <h3 class="fs-4 mb-1">My Profile</h3>
                            {{-- <div class="mb-4">
                                <label for="" class="mb-2">Employee Name*</label>
                                <input type="text" name="name" id="name" placeholder="Enter Company Name" class="form-control" value="{{ $user->name }}">
                                <p></p>
                            </div>
                            <div class="mb-4">
                                <label for="" class="mb-2">Email*</label>
                                <input type="text" name="email" id="email" placeholder="Enter Email" class="form-control" value="{{ $user->email }}">
                                <p></p>
                            </div> --}}

                            <div class="mb-4">
                                <label for="name" class="mb-2">Company Name*</label>
                                <input type="text" name="name" id="name" placeholder="Enter Company Name" class="form-control" value="{{ $user->name }}">
                                <p class="text-danger error-message"></p> <!-- Error message will go here -->
                            </div>
                            <div class="mb-4">
                                <label for="email" class="mb-2">Email*</label>
                                <input type="text" name="email" id="email" placeholder="Enter Email" class="form-control" value="{{ $user->email }}">
                                <p class="text-danger error-message"></p> <!-- Error message will go here -->
                            </div>

                            <div class="mb-4">
                                <label for="" class="mb-2">Designation*</label>
                                <input type="text" name="designation" id="designation" placeholder="Designation" class="form-control" value="{{ $user->designation }}">
                                <p></p>
                            </div>
                            <div class="mb-4">
                                <label for="" class="mb-2">Mobile*</label>
                                <input type="text" name="mobile" id="mobile" placeholder="Mobile" class="form-control" value="{{ $user->mobile }}">
                                <p></p>
                            </div>                        
                        </div>
                        <div class="card-footer  p-4">
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </form>
                </div>

                <div class="card border-0 shadow mb-4">
                    <form action="{{ route('account.updatePassword') }}" method="post" id="changePasswordForm" name="changePasswordForm">
                        @csrf <!-- Laravel CSRF token -->
                        <div class="card-body p-4">
                            <h3 class="fs-4 mb-1">Change Password</h3>
                            <div class="mb-4">
                                <label for="" class="mb-2">Old Password*</label>
                                <input type="password" name="old_password" id="old_password" placeholder="Old Password" class="form-control">
                                <p id="old_password_error" class="text-danger small d-none">Old password is required.</p>
                            </div>
                            <div class="mb-4">
                                <label for="" class="mb-2">New Password*</label>
                                <input type="password" name="new_password" id="new_password" placeholder="New Password" class="form-control">
                                <p id="new_password_error" class="text-danger small d-none">New password is required.</p>
                            </div>
                            <div class="mb-4">
                                <label for="" class="mb-2">Confirm Password*</label>
                                <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm Password" class="form-control">
                                <p id="confirm_password_error" class="text-danger small d-none">Passwords do not match.</p>
                            </div>
                        </div>
                        <div class="card-footer p-4">
                            <button type="submit" id="updatePasswordBtn" class="btn btn-primary">Update</button>
                        </div>
                    </form>
                </div>                
            </div>
        </div>
    </div>
</section>

<script>
    document.getElementById('userForm').addEventListener('submit', function (event) {
        event.preventDefault(); // Prevent default form submission
        
        // Clear previous error messages
        document.querySelectorAll('.error-message').forEach(msg => msg.textContent = '');

        let isValid = true;

        // Get form fields
        const nameField = document.getElementById('name');
        const emailField = document.getElementById('email');
        const designationField = document.getElementById('designation');
        const mobileField = document.getElementById('mobile');

        // Regex for validating name and designation (letters and spaces only)
        const stringRegex = /^[a-zA-Z\s]+$/;

        // Validate Employee Name
        if (nameField.value.trim() === '') {
            isValid = false;
            nameField.nextElementSibling.textContent = 'Employee Name is required.';
        } else if (!stringRegex.test(nameField.value.trim())) {
            isValid = false;
            nameField.nextElementSibling.textContent = 'Employee Name must contain only letters and spaces.';
        }

        // Validate Email
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(emailField.value.trim())) {
            isValid = false;
            emailField.nextElementSibling.textContent = 'Enter a valid email address.';
        }

        // Validate Designation
        if (designationField.value.trim() === '') {
            isValid = false;
            designationField.nextElementSibling.textContent = 'Designation is required.';
        } else if (!stringRegex.test(designationField.value.trim())) {
            isValid = false;
            designationField.nextElementSibling.textContent = 'Designation must contain only letters and spaces.';
        }

        // Validate Mobile
        const mobileRegex = /^[0-9]{10}$/; // Assuming mobile number should be 10 digits
        if (!mobileRegex.test(mobileField.value.trim())) {
            isValid = false;
            mobileField.nextElementSibling.textContent = 'Enter a valid mobile number (10 digits).';
        }

        // If the form is valid, submit the form
        if (isValid) {
            this.submit(); // Submit the form normally
        } else {
            // Optionally, you could display a general message for errors
            alert('Please fix the errors before submitting the form.');
        }
    });


    //change password
    document.getElementById("changePasswordForm").addEventListener("submit", function (e) {
        // Get form inputs
        const oldPassword = document.getElementById("old_password").value.trim();
        const newPassword = document.getElementById("new_password").value.trim();
        const confirmPassword = document.getElementById("confirm_password").value.trim();

        // Error elements
        const oldPasswordError = document.getElementById("old_password_error");
        const newPasswordError = document.getElementById("new_password_error");
        const confirmPasswordError = document.getElementById("confirm_password_error");

        // Clear previous errors
        oldPasswordError.classList.add("d-none");
        newPasswordError.classList.add("d-none");
        confirmPasswordError.classList.add("d-none");

        // Validation
        let isValid = true;

        if (!oldPassword) {
            oldPasswordError.classList.remove("d-none");
            isValid = false;
        }
        if (!newPassword) {
            newPasswordError.classList.remove("d-none");
            isValid = false;
        }
        if (newPassword !== confirmPassword) {
            confirmPasswordError.classList.remove("d-none");
            isValid = false;
        }

        // Prevent form submission if validation fails
        if (!isValid) {
            e.preventDefault();
        }
    });

    @if ($errors->has('old_password'))
        // Toast error for incorrect old password
        Toastify({
            text: "{{ $errors->first('old_password') }}",
            backgroundColor: "linear-gradient(to right, #FF5F6D, #FFC371)",
            className: "error",
            duration: 3000
        }).showToast();
    @endif
</script>

@endsection

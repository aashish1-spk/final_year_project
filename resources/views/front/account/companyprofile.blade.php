@extends('front.layouts.app')

@section('main')

<section class="section-5 bg-2">
    <div class="container py-5">
        <div class="row">
            <div class="col">
                <nav aria-label="breadcrumb" class=" rounded-3 p-3 mb-4">
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
                                <label for="name" class="mb-2">Employee Name*</label>
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
                    <form action="" method="post" id="changePasswordForm" name="changePasswordForm">
                        <div class="card-body p-4">
                            <h3 class="fs-4 mb-1">Change Password</h3>
                            <div class="mb-4">
                                <label for="" class="mb-2">Old Password*</label>
                                <input type="password" name="old_password" id="old_password" placeholder="Old Password" class="form-control">
                            </div>
                            <div class="mb-4">
                                <label for="" class="mb-2">New Password*</label>
                                <input type="password" name="new_password" id="new_password" placeholder="New Password" class="form-control">
                            </div>
                            <div class="mb-4">
                                <label for="" class="mb-2">Confirm Password*</label>
                                <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm Password" class="form-control">
                            </div>
                        </div>
                        <div class="card-footer  p-4">
                            <button type="button" class="btn btn-primary">Update</button>
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

        // Validate Employee Name
        if (nameField.value.trim() === '') {
            isValid = false;
            nameField.nextElementSibling.textContent = 'Employee Name is required.';
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





</script>









@endsection
{{-- @section('customJs')
<script type="text/javascript">
    $("#userForm").submit(function(e){
        e.preventDefault();

        $.ajax({
            url: '{{ route("account.updateProfile") }}',
            type: 'put',
            dataType: 'json',
            data: $("#userForm").serializeArray(),
            success: function(response) {
                if(response.status == true) {
                    
                } else {
                    var errors = response.errors;


                    if (errors.name) {
                    $("#name").addClass('is-invalid') 
                        .siblings('p')               
                        .addClass('invalid-feedback') 
                        .html(errors.name);          
                } else {
                    $("#name").removeClass('is-invalid')
                        .siblings('p')
                        .removeClass('invalid-feedback')
                        .html(''); 
                }

               
                if (errors.email) {
                    $("#email").addClass('is-invalid')
                        .siblings('p')
                        .addClass('invalid-feedback')
                        .html(errors.email);
                } else {
                    $("#email").removeClass('is-invalid')
                        .siblings('p')
                        .removeClass('invalid-feedback')
                        .html('');
                }

                }
            }
        });
    });
</script>
@endsection --}}

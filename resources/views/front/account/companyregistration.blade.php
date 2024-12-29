@extends('front.layouts.app')

@section('main')
<style>
    p{
        color: red;
    }
</style>

<section class="section-5">
    <div class="container my-5">
        
      </div>
        <div class="py-lg-2">&nbsp;</div>
        <div class="row d-flex justify-content-center">
            <div class="col-md-5">
                <div class="card shadow border-0 p-5">
                    <h1 class="h3 mb-4">Company Registration</h1>
                    <form action="{{ route('account.processCompanyRegistration') }}" method="POST" id="companyRegistrationForm">
                        @csrf
                        <div class="mb-3">
                            <label for="company_name" class="mb-2">Company Name*</label>
                            <input type="text" name="company_name" id="company_name" class="form-control" placeholder="Enter Company Name" required >
                            <p></p> 
                        </div>

                        <div class="mb-3">
                            <label for="company_email" class="mb-2"> Email*</label>
                            <input type="email" name="company_email" id="company_email" class="form-control" placeholder="Enter Company Email" required>
                            <p></p> 
                        </div>

                        <div class="mb-3">
                            <label for="company_password" class="mb-2">Password*</label>
                            <input type="password" name="company_password" id="company_password" class="form-control" placeholder="Enter Password" required>
                            <p></p> 
                        </div>
                        
                        <div class="mb-3">
                            <label for="company_password_confirmation" class="mb-2">Confirm Password*</label>
                            <input type="password" name="company_password_confirmation" id="company_password_confirmation" class="form-control" placeholder="Confirm Password" required>
                            <p></p> 
                        </div>
                        

                        <button type="submit" class="btn btn-primary mt-2">Register Company</button>
                        
                    </form>
                    
                    <div class="position-fixed  text-white top-0 end-0 p-3" style="z-index: 11">
                        <div id="liveToast" class="toast hide bg-danger " role="alert" aria-live="assertive" aria-atomic="true">
                            <div class="toast-header">
                                <strong class="me-auto">Validation Errors</strong>
                                <small>Just now</small>
                                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                            </div>
                            <div class="toast-body">
                                <ul>
                                    @if (session('error'))
                                        @foreach (session('error') as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Call this script after bootstrap.bundle.min.js CDN -->
                    <script>
                        document.addEventListener('DOMContentLoaded', function () {
                            @if (session('error'))
                                var toastDOMElement = document.getElementById('liveToast');
                                var myToast = new bootstrap.Toast(toastDOMElement);
                                myToast.show();
                            @endif
                        });
                    </script>
                    
                </div>

                <div class="mt-4 text-center">
                    <p>Already have an account? <a href="{{ route('account.login') }}">Login</a></p>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
    document.getElementById('companyRegistrationForm').addEventListener('submit', function(event) {
        // Prevent form submission
        event.preventDefault();

        // Clear previous error messages
        const errorMessages = document.querySelectorAll('form p');
        errorMessages.forEach(msg => msg.textContent = '');

        let isValid = true;

        // Get form inputs
        const companyName = document.getElementById('company_name');
        const companyEmail = document.getElementById('company_email');
        const companyPassword = document.getElementById('company_password');
        const companyPasswordConfirmation = document.getElementById('company_password_confirmation');

        // Validation rules
        if (companyName.value.trim() === '') {
            companyName.nextElementSibling.textContent = 'Company Name is required.';
            isValid = false;
        }

        if (companyEmail.value.trim() === '') {
            companyEmail.nextElementSibling.textContent = 'Company Email is required.';
            isValid = false;
        } else if (!/^\S+@\S+\.\S+$/.test(companyEmail.value)) {
            companyEmail.nextElementSibling.textContent = 'Please enter a valid email address.';
            isValid = false;
        }

        if (companyPassword.value.trim() === '') {
            companyPassword.nextElementSibling.textContent = 'Password is required.';
            isValid = false;
        } else if (companyPassword.value.length < 6) {
            companyPassword.nextElementSibling.textContent = 'Password must be at least 6 characters long.';
            isValid = false;
        }

        if (companyPasswordConfirmation.value.trim() === '') {
            companyPasswordConfirmation.nextElementSibling.textContent = 'Confirm Password is required.';
            isValid = false;
        } else if (companyPassword.value !== companyPasswordConfirmation.value) {
            companyPasswordConfirmation.nextElementSibling.textContent = 'Passwords do not match.';
            isValid = false;
        }

        // Submit form if valid
        if (isValid) {
            this.submit();
        }
    });
</script>

@endsection

{{-- @section('customjs')
    <script>
        $("#companyRegistrationForm").submit(function(e) {
            e.preventDefault();

            $.ajax({
                url: '{{ route('account.processCompanyRegistration') }}',
                type: 'POST',
                data: $("#companyRegistrationForm").serialize(),
                dataType: 'json',
                success: function(response) {
                    if (response.status == false) {
                        if (response.errors.company_name) {
                            $("#company_name").addClass('is-invalid')
                                .siblings('p')
                                .addClass('invalid-feedback')
                                .html(response.errors.company_name);
                        } else {
                            $("#company_name").removeClass('is-invalid')
                                .siblings('p')
                                .removeClass('invalid-feedback')
                                .html('');
                        }

                        if (response.errors.company_email) {
                            $("#company_email").addClass('is-invalid')
                                .siblings('p')
                                .addClass('invalid-feedback')
                                .html(response.errors.company_email);
                        } else {
                            $("#company_email").removeClass('is-invalid')
                                .siblings('p')
                                .removeClass('invalid-feedback')
                                .html('');
                        }

                        if (response.errors.company_password) {
                            $("#company_password").addClass('is-invalid')
                                .siblings('p')
                                .addClass('invalid-feedback')
                                .html(response.errors.company_password);
                        } else {
                            $("#company_password").removeClass('is-invalid')
                                .siblings('p')
                                .removeClass('invalid-feedback')
                                .html('');
                        }

                        if (response.errors.confirm_password) {
                            $("#company_password_confirmation").addClass('is-invalid')
                                .siblings('p')
                                .addClass('invalid-feedback')
                                .html(response.errors.confirm_password);
                        } else {
                            $("#company_password_confirmation").removeClass('is-invalid')
                                .siblings('p')
                                .removeClass('invalid-feedback')
                                .html('');
                        }
                    } else {
                        
                        $("#company_name").removeClass('is-invalid')
                            .siblings('p')
                            .removeClass('invalid-feedback')
                            .html('');
                        $("#company_email").removeClass('is-invalid')
                            .siblings('p')
                            .removeClass('invalid-feedback')
                            .html('');
                        $("#company_password").removeClass('is-invalid')
                            .siblings('p')
                            .removeClass('invalid-feedback')
                            .html('');
                        $("#company_password_confirmation").removeClass('is-invalid')
                            .siblings('p')
                            .removeClass('invalid-feedback')
                            .html('');

                        // Display success message and redirect
                        alert(response.message);
                        window.location.href = '{{ route('account.login') }}';
                    }
                },
                error: function() {
                    alert('An error occurred while processing the registration.');
                }
            });
        });

    </script>
@endsection --}}
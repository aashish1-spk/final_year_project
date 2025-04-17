@extends('front.layouts.app')

@section('main')
    <section class="section-5">
        <div class="container my-5">
            <div class="py-lg-2">&nbsp;</div>
            <div class="row d-flex justify-content-center">
                <div class="col-md-5">
                    <div class="card shadow border-0 p-5">
                        <h1 class="h3">Company Registration</h1>
                        <form action="" name="companyRegistrationForm" id="companyRegistrationForm">
                            <div class="mb-3">
                                <label for="" class="mb-2">Company Name*</label>
                                <input type="text" name="company_name" id="company_name" class="form-control"
                                    placeholder="Enter Company Name">
                                <p></p>
                            </div>
                            <div class="mb-3">
                                <label for="" class="mb-2">Email*</label>
                                <input type="text" name="email" id="email" class="form-control"
                                    placeholder="Enter Email">
                                <p></p>
                            </div>
                            <div class="mb-3">
                                <label for="pan_number" class="mb-2">PAN Number*</label>
                                <input type="text" name="pan_number" id="pan_number" class="form-control"
                                    placeholder="Enter PAN Number">
                                <p></p>
                            </div>

                            <div class="mb-3">
                                <label for="" class="mb-2">Password*</label>
                                <input type="password" name="password" id="password" class="form-control"
                                    placeholder="Enter Password">
                                <p></p>
                            </div>
                            <div class="mb-3">
                                <label for="" class="mb-2">Confirm Password*</label>
                                <input type="password" name="confirm_password" id="confirm_password" class="form-control"
                                    placeholder="Please confirm Password">
                                <p></p>
                            </div>
                            <button class="btn btn-primary mt-2">Register</button>
                        </form>
                    </div>
                    <div class="mt-4 text-center">
                        <p>Already have an account? <a href="{{ route('account.login') }}">Login</a></p>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('costumjs')
    <script>
        $("#companyRegistrationForm").submit(function(e) {
            e.preventDefault();

            $.ajax({
                url: '{{ route('account.processCompanyRegistration') }}',
                type: 'post',
                data: $("#companyRegistrationForm").serializeArray(),
                dataType: 'json',
                success: function(response) {
                    if (response.status == false) {
                        var errors = response.errors;

                        // Handle 'company_name' field errors
                        if (errors.company_name) {
                            $("#company_name").addClass('is-invalid')
                                .siblings('p')
                                .addClass('invalid-feedback')
                                .html(errors.company_name);
                        } else {
                            $("#company_name").removeClass('is-invalid')
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

                        if (errors.password) {
                            $("#password").addClass('is-invalid')
                                .siblings('p')
                                .addClass('invalid-feedback')
                                .html(errors.password);
                        } else {
                            $("#password").removeClass('is-invalid')
                                .siblings('p')
                                .removeClass('invalid-feedback')
                                .html('');
                        }

                        if (errors.confirm_password) {
                            $("#confirm_password").addClass('is-invalid')
                                .siblings('p')
                                .addClass('invalid-feedback')
                                .html(errors.confirm_password);
                        } else {
                            $("#confirm_password").removeClass('is-invalid')
                                .siblings('p')
                                .removeClass('invalid-feedback')
                                .html('');
                        }

                        // PAN Number error handling
                        if (errors.pan_number) {
                            $("#pan_number").addClass('is-invalid')
                                .siblings('p')
                                .addClass('invalid-feedback')
                                .html(errors.pan_number);
                        } else {
                            $("#pan_number").removeClass('is-invalid')
                                .siblings('p')
                                .removeClass('invalid-feedback')
                                .html('');
                        }

                    } else {
                        $("#company_name").removeClass('is-invalid')
                            .siblings('p')
                            .removeClass('invalid-feedback')
                            .html('');

                        $("#email").removeClass('is-invalid')
                            .siblings('p')
                            .removeClass('invalid-feedback')
                            .html('');

                        $("#password").removeClass('is-invalid')
                            .siblings('p')
                            .removeClass('invalid-feedback')
                            .html('');

                        $("#confirm_password").removeClass('is-invalid')
                            .siblings('p')
                            .removeClass('invalid-feedback')
                            .html('');

                        $("#pan_number").removeClass('is-invalid')
                            .siblings('p')
                            .removeClass('invalid-feedback')
                            .html('');

                        window.location.href = '{{ route('account.login') }}'
                    }
                }
            });
        });
    </script>
@endsection

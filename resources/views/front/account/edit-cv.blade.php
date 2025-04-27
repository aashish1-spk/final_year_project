@extends('front.layouts.app')

@section('main')
    <section class="section-5 bg-2">
        <div class="container py-5">
            <div class="row">
                <div class="col">
                    <nav aria-label="breadcrumb" class="rounded-3 p-3 mb-4">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Edit CV</li>
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
                        <form action="{{ route('account.updateCV', $cv->id) }}" method="POST" id="cvForm" name="cvForm">
                            @csrf
                            @method('PUT')
                            <div class="card-body p-4">
                                <h3 class="fs-4 mb-1">Edit Your CV</h3>

                                <div class="mb-4">
                                    <label for="name" class="mb-2">Full Name*</label>
                                    <input type="text" name="name" id="name" value="{{ old('name', $cv->name) }}" placeholder="Enter your full name" class="form-control">
                                    <p class="text-danger error-message"></p>
                                </div>

                                <div class="mb-4">
                                    <label for="email" class="mb-2">Email*</label>
                                    <input type="email" name="email" id="email" value="{{ old('email', $cv->email) }}" placeholder="Enter your email" class="form-control">
                                    <p class="text-danger error-message"></p>
                                </div>

                                <div class="mb-4">
                                    <label for="phone" class="mb-2">Phone*</label>
                                    <input type="text" name="phone" id="phone" value="{{ old('phone', $cv->phone) }}" placeholder="Enter your phone number" class="form-control">
                                    <p class="text-danger error-message"></p>
                                </div>

                                <div class="mb-4">
                                    <label for="objective" class="mb-2">Career Objective</label>
                                    <textarea name="objective" id="objective" class="form-control" rows="3" placeholder="Describe your career objective...">{{ old('objective', $cv->objective) }}</textarea>
                                    <p class="text-danger error-message"></p>
                                </div>

                                <div class="mb-4">
                                    <label for="skills" class="mb-2">Skills*</label>
                                    <textarea name="skills" id="skills" class="form-control" rows="4" placeholder="List your skills (e.g., HTML, CSS, JS, Laravel)">{{ old('skills', $cv->skills) }}</textarea>
                                    <p class="text-danger error-message"></p>
                                </div>
 
                                <div class="mb-4">
                                    <label for="experience" class="mb-2">Experience*</label>
                                    <textarea name="experience" id="experience" class="form-control" rows="4" placeholder="Describe your work experience (e.g., Worked at XYZ Company as an intern)">{{ old('experience', $cv->experience) }}</textarea>
                                    <p class="text-danger error-message"></p>
                                </div>

                                <div class="mb-4">
                                    <label for="education" class="mb-2">Education*</label>
                                    <textarea name="education" id="education" class="form-control" rows="4" placeholder="List your education (e.g., BSc. Hons Computing, London Metropolitan University)">{{ old('education', $cv->education) }}</textarea>
                                    <p class="text-danger error-message"></p>
                                </div>

                                <div class="mb-4">
                                    <label for="certifications" class="mb-2">Certifications (if any)</label>
                                    <textarea name="certifications" id="certifications" class="form-control" rows="3" placeholder="List any certifications you have (e.g., Laravel Certification – Udemy)">{{ old('certifications', $cv->certifications) }}</textarea>
                                    <p class="text-danger error-message"></p>
                                </div>

                                <div class="mb-4">
                                    <label for="languages" class="mb-2">Languages Known</label>
                                    <textarea name="languages" id="languages" class="form-control" rows="2" placeholder="List languages you know (e.g., English, Nepali, Hindi)">{{ old('languages', $cv->languages) }}</textarea>
                                    <p class="text-danger error-message"></p>
                                </div>

                                <div class="mb-4">
                                    <label for="references" class="mb-2">References</label>
                                    <textarea name="references" id="references" class="form-control" rows="3" placeholder="Provide references (e.g., Mr. XYZ, Manager, ABC Company – xyz@company.com)">{{ old('references', $cv->references) }}</textarea>
                                    <p class="text-danger error-message"></p>
                                </div>

                                <!-- GitHub Link -->
                                <div class="mb-4">
                                    <label for="github_link" class="mb-2">GitHub Profile Link</label>
                                    <input type="url" name="github_link" id="github_link" value="{{ old('github_link', $cv->github_link) }}" placeholder="Enter your GitHub link" class="form-control">
                                    <p class="text-danger error-message"></p>
                                </div>

                                <!-- LinkedIn Link -->
                                <div class="mb-4">
                                    <label for="linkedin_link" class="mb-2">LinkedIn Profile Link</label>
                                    <input type="url" name="linkedin_link" id="linkedin_link" value="{{ old('linkedin_link', $cv->linkedin_link) }}" placeholder="Enter your LinkedIn link" class="form-control">
                                    <p class="text-danger error-message"></p>
                                </div>

                            </div>

                            <div class="card-footer p-4">
                                <button type="submit" class="btn btn-primary">Update CV</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            document.getElementById("cvForm").addEventListener("submit", function (event) {
                let isValid = true;
                const requiredFields = ["name", "email", "phone", "skills", "experience", "education"];
                const optionalFields = ["certifications", "languages", "references", "github_link", "linkedin_link"];

                // Required fields validation
                requiredFields.forEach(field => {
                    let input = document.getElementById(field);
                    let errorMessage = input.nextElementSibling;

                    if (input.value.trim() === "") {
                        errorMessage.textContent = field.charAt(0).toUpperCase() + field.slice(1) + " is required.";
                        input.classList.add("is-invalid");
                        isValid = false;
                    } else {
                        errorMessage.textContent = "";
                        input.classList.remove("is-invalid");
                    }
                });

                // Optional fields validation (no error if left empty)
                optionalFields.forEach(field => {
                    let input = document.getElementById(field);
                    let errorMessage = input.nextElementSibling;

                    if (input.value.trim() !== "" && input.value.length < 5) {
                        errorMessage.textContent = field.charAt(0).toUpperCase() + field.slice(1) + " must be at least 5 characters long.";
                        input.classList.add("is-invalid");
                        isValid = false;
                    } else {
                        errorMessage.textContent = "";
                        input.classList.remove("is-invalid");
                    }
                });

                // Email validation
                let emailInput = document.getElementById("email");
                let emailError = emailInput.nextElementSibling;
                const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

                if (!emailPattern.test(emailInput.value.trim())) {
                    emailError.textContent = "Enter a valid email address.";
                    emailInput.classList.add("is-invalid");
                    isValid = false;
                }

                // Phone validation
                let phoneInput = document.getElementById("phone");
                let phoneError = phoneInput.nextElementSibling;
                const phonePattern = /^[0-9]+$/;

                if (!phonePattern.test(phoneInput.value.trim())) {
                    phoneError.textContent = "Enter a valid phone number (digits only).";
                    phoneInput.classList.add("is-invalid");
                    isValid = false;
                }

                if (!isValid) {
                    event.preventDefault();
                    alert('Please fix the errors before submitting.');
                }
            });
        });
    </script>
@endsection

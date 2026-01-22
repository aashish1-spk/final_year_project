<!DOCTYPE html>
<html class="no-js" lang="en_AU" />

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>PahiloJob | Find Best Jobs</title>
    <meta name="description" content="" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1, shrink-to-fit=no, maximum-scale=1, user-scalable=no" />
    <meta name="HandheldFriendly" content="True" />
    <meta name="pinterest" content="nopin" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/style.css') }}" />
    <!-- Fav Icon -->
    <link rel="shortcut icon" type="image/x-icon" href="#" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" rel="stylesheet">

</head>

<body data-instant-intensity="mousedown">
    <header>
        <nav class="navbar navbar-expand-lg navbar-light bg-white shadow py-3">
            <div class="container">
                <a class="navbar-brand fw-bold" href={{ route('home') }} style="font-size: 1.5rem; color: #10b981 !important;">
                    PahiloJob
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ms-0 ms-sm-0 me-auto mb-2 mb-lg-0 ms-lg-4">
                        <li class="nav-item">
                            <a class="nav-link fw-500" aria-current="page" href="{{ route('home') }}" style="color: #10b981 !important; transition: all 0.3s ease;">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link fw-500" aria-current="page" href="{{ route('jobs') }}" style="color: #10b981 !important; transition: all 0.3s ease;">Find Jobs</a>
                        </li>
                    </ul>
                    @if (!Auth::check())
                        <a class="btn me-2 fw-bold" href="{{ route('account.login') }}" style="background: white; color: #10b981; border: 2px solid #10b981; border-radius: 8px; padding: 0.6rem 1.2rem; transition: all 0.3s ease;">
                            Login
                        </a>
                    @else
                    @if (Auth::user()->role == 'admin')
                        <a class="btn me-2 fw-bold" href="{{ route('admin.dashboard') }}" style="background: white; color: #10b981; border: 2px solid #10b981; border-radius: 8px; padding: 0.6rem 1.2rem; transition: all 0.3s ease;">
                            Admin
                        </a>
                        @endif

                        <a class="btn me-2 fw-bold" href="{{ route('account.profile') }}" style="background: white; color: #10b981; border: 2px solid #10b981; border-radius: 8px; padding: 0.6rem 1.2rem; transition: all 0.3s ease;">
                            Account
                        </a>
                    @endif

                    <a class="btn fw-bold" href="{{ route('account.createJob') }}" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; border-radius: 8px; padding: 0.6rem 1.5rem; box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3); transition: all 0.3s ease;">
                        <i class="fa fa-plus me-2"></i>Post a Job
                    </a>
                </div>
            </div>
        </nav>
    </header>


    @yield('main')
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title pb-0" id="exampleModalLabel">Change Profile Picture</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="profilePicForm" name="profilePicForm" action="" method="post">
                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">Profile Image</label>
                            <input type="file" class="form-control" id="image" name="image">
                            <p class="text-danger" id="image-error"></p>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary mx-3">Update</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

    <footer class="bg-dark text-white text-center text-lg-start py-3 mt-5">
        <div class="container d-flex flex-column flex-md-row justify-content-between align-items-center small">
          <div class="mb-2 mb-md-0">
            <strong>Pahilo Job</strong><br>
            <span>Providing job info, career tips, and opportunities.</span><br>
            <small>&copy; 2025 PahiloJob. All rights reserved.</small>
          </div>
      
          <div>
            <a href="https://facebook.com" class="text-white me-2" target="_blank" aria-label="Facebook">
              <i class="fab fa-facebook fa-lg"></i>
            </a>
            <a href="https://instagram.com" class="text-white me-2" target="_blank" aria-label="Instagram">
              <i class="fab fa-instagram fa-lg"></i>
            </a>
            <a href="https://twitter.com" class="text-white" target="_blank" aria-label="Twitter">
              <i class="fab fa-twitter fa-lg"></i>
            </a>
          </div>
        </div>
      </footer>
      
      <!-- Font Awesome CDN (needed for icons) -->
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
      
      
      <!-- Font Awesome CDN (needed for icons) -->
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
      
    
    
    <script src="{{ asset('assets/js/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.bundle.5.1.3.min.js') }}"></script>
    <script src="{{ asset('assets/js/instantpages.5.1.0.min.js') }}assets/js/instantpages.5.1.0.min.js"></script>
    <script src="{{ asset('assets/js/lazyload.17.6.0.min.js') }}"></script>
    <script src="{{ asset('assets/js/custom.js') }}"></script>
    

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });


        $("#profilePicForm").submit(function(e) {
            e.preventDefault();

            var formData = new FormData(this);

            $.ajax({
                url: '{{ route('account.updateProfilePic') }}',
                type: 'post',
                data: formData,
                contentType: false,
                processData: false,
                dataType: 'json',
                success: function(response) {

                    if (response.status == false) {
                        var errors = response.errors;
                        if (errors.image) {

                            $("#image-error").html(errors.image)

                        }
                    } else {
                        window.location.href = '{{ url()->current() }}'
                    }
                }
            });
        });
    </script>
    @yield('costumjs')
</body>

</html>
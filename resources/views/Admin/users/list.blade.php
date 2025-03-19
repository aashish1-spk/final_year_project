@extends('front.layouts.app')

@section('main')
<section class="section-5 bg-2">
    <div class="container py-5">
        <div class="row">
            <div class="col">
                <nav aria-label="breadcrumb" class=" rounded-3 p-3 mb-4">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
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
                    <div class="d-flex justify-content-between">
                        <div>
                            <h3 class="fs-4 mb-1">Users</h3>
                        </div>
                        <div style="margin-top: -10px;">
                        </div>
                        
                    </div>
                    <div class="table-responsive">
                        <table class="table ">
                            <thead class="bg-light">
                                <tr>
                                    <th scope="col">Id</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Role</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody class="border-0">
                                @if ($users->isNotEmpty())
                                    @foreach ($users as $user)
                                    <tr class="active">
                                        <td>
                                            {{ $user->id }}
                                        </td>
                                        <td>
                                            <div class="job-name fw-500">{{ $user->name }}</div>
                                        </td>
                                        <td>{{ $user->email }} </td>
                                        <td>
                                            @if ($user->role == 'user')
                                                Job Seeker
                                            @elseif ($user->role == 'company')
                                                Company
                                            @elseif ($user->role == 'admin')
                                                Admin
                                            @else
                                                Unknown Role
                                            @endif
                                        </td>
                           
                                        <td>
                                            <div class="action-dots ">
                                                <button href="#" class="btn" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li><a class="dropdown-item" href="{{ route("admin.users.edit",$user->id) }}"><i class="fa fa-edit" aria-hidden="true"></i> Edit</a></li>
                                                    <li>
                                                        @if($user->is_active)
                                                            <a class="dropdown-item" href="#" onclick="deactivateUser({{ $user->id }})">
                                                                <i class="fa fa-ban" aria-hidden="true"></i> Deactivate
                                                            </a>
                                                        @else
                                                            <a class="dropdown-item" href="#" onclick="activateUser({{ $user->id }})">
                                                                <i class="fa fa-check" aria-hidden="true"></i> Activate
                                                            </a>
                                                        @endif
                                                    </li>
                                                                                       </ul>
                                            </div>
                                        </td>
                                    </tr>         
                                    @endforeach
                                @endif

                              
                               
                            </tbody>
                            
                        </table>
                    </div>

                    <div>
                        {{ $users->links() }}  
                    </div>
                </div>
                </div>                          
            </div>
        </div>
    </div>
</section>
@endsection

@section('customJs')
@section('costumjs')
<script>
    function deactivateUser(id) {
        if (confirm("Are you sure you want to deactivate this user?")) {
            $.ajax({
                url: "{{ route('admin.users.deactivate') }}",
                type: "POST",
                data: {
                    id: id,
                    _token: "{{ csrf_token() }}"
                },
                dataType: "json",
                success: function(response) {
                    if (response.status) {
                        alert("User deactivated successfully");
                        location.reload();
                    } else {
                        alert("Failed to deactivate user.");
                    }
                },
                error: function(xhr) {
                    alert("An error occurred. Please check the server logs.");
                }
            });
        }
    }
    
    function activateUser(id) {
        if (confirm("Are you sure you want to activate this user?")) {
            $.ajax({
                url: "{{ route('admin.users.activate') }}",
                type: "POST",
                data: {
                    id: id,
                    _token: "{{ csrf_token() }}"
                },
                dataType: "json",
                success: function(response) {
                    if (response.status) {
                        alert("User activated successfully");
                        location.reload();
                    } else {
                        alert("Failed to activate user.");
                    }
                },
                error: function(xhr) {
                    alert("An error occurred. Please check the server logs.");
                }
            });
        }
    }
    </script>
@endsection
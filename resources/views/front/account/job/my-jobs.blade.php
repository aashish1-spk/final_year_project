@extends('front.layouts.app')

@section('main')

    <section class="section-5 bg-2">
        <div class="container py-5">
            <div class="row">
                <div class="col">
                    <nav aria-label="breadcrumb" class="rounded-3 p-3 mb-4">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                            <li class="breadcrumb-item active">My Jobs</li>
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
                    <div class="card border-0 shadow mb-4 p-3">
                        <div class="card-body card-form">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h3 class="fs-4 mb-1">My Jobs</h3>
                                </div>
                                <div style="margin-top: -10px;">
                                    <a href="{{ route('account.createJob') }}" class="btn btn-primary">Post a Job</a>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table">
                                    <thead class="bg-light">
                                        <tr>
                                            <th scope="col">Title</th>
                                            <th scope="col">Job Created</th>
                                            <th scope="col">Applicants</th>
                                            <th scope="col">Status</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="border-0">
                                        @if ($jobs->isNotEmpty())
                                            @foreach ($jobs as $job)
                                                <tr class="active">
                                                    <td>
                                                        <div class="job-name fw-500">{{ $job->title }}</div>
                                                        <div class="info1">{{ $job->jobType->name }} . {{ $job->location }}
                                                        </div>
                                                    </td>
                                                    <td>{{ \Carbon\Carbon::parse($job->created_at)->format('d M, Y') }}</td>
                                                    <td>{{ $job->applications()->count() }} Applicants</td>
                                                    <td>
                                                        @if ($job->status == 1)
                                                            <div class="job-status text-capitalize text-success">Active
                                                            </div>
                                                        @else
                                                            <div class="job-status text-capitalize text-danger">Blocked
                                                            </div>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <div class="action-dots float-end">
                                                            <button class="btn" data-bs-toggle="dropdown"
                                                                aria-expanded="false">
                                                                <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
                                                            </button>
                                                            <ul class="dropdown-menu dropdown-menu-end">
                                                                <li><a class="dropdown-item"
                                                                        href="{{ route('jobDetail', $job->id) }}"><i
                                                                            class="fa fa-eye"></i> View</a></li>
                                                                <li><a class="dropdown-item"
                                                                        href="{{ route('account.editJob', $job->id) }}"><i
                                                                            class="fa fa-edit"></i> Edit</a></li>
                                                                <li><a class="dropdown-item" href="#"
                                                                        onclick="deleteJob({{ $job->id }})"><i
                                                                            class="fa fa-trash"></i> Delete</a></li>

                                                                {{-- Featured Request --}}
                                                                <li>
                                                                    @if ($job->isFeatured)
                                                                        <button class="dropdown-item" disabled><i
                                                                                class="fa fa-star"></i> Already
                                                                            Featured</button>
                                                                    @elseif ($job->featured_request)
                                                                        <button class="dropdown-item" disabled><i
                                                                                class="fa fa-star"></i> Request
                                                                            Pending</button>
                                                                    @else
                                                                        <button class="dropdown-item request-featured-btn"
                                                                            data-job-id="{{ $job->id }}">
                                                                            <i class="fa fa-star"></i> Request Featured
                                                                        </button>
                                                                    @endif
                                                                </li>

                                                                {{-- View Payment --}}
                                                                @php
                                                                    $payment = \App\Models\Payment::where(
                                                                        'job_id',
                                                                        $job->id,
                                                                    )
                                                                        ->where('user_id', auth()->id())
                                                                        ->where('status', 'completed')
                                                                        ->first();
                                                                @endphp
                                                                @if ($payment)
                                                                    <li>
                                                                        <a class="dropdown-item"
                                                                            href="{{ route('company.jobs.payment', $job->id) }}">
                                                                            <i class="fa fa-credit-card"></i> View Payment
                                                                        </a>
                                                                    </li>
                                                                @endif
                                                            </ul>
                                                        </div>


                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="5" class="text-center">No jobs found</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>

                            <div>
                                {{ $jobs->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Modal for requesting featured job -->
    <div class="modal fade" id="featuredJobModal" tabindex="-1" aria-labelledby="featuredJobModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="featuredJobModalLabel">Request Featured Job</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="featuredModalBody">
                    <!-- Dynamic content will be injected here -->
                </div>
                <div class="modal-footer" id="featuredModalFooter">
                    <!-- Dynamic footer buttons will be injected here -->
                </div>
            </div>
        </div>
    </div>

@endsection

@section('costumjs')
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            const buttons = document.querySelectorAll('.request-featured-btn');
            buttons.forEach(button => {
                button.addEventListener('click', function() {
                    const jobId = this.dataset.jobId;
                    const name =
                        @json(auth()->user()->name); // Ensure this value is being passed correctly
                    const email =
                        @json(auth()->user()->email); // Ensure this value is being passed correctly
                    const mobile = @json(auth()->user()->mobile); // Correct field for mobile number

                    const modalBody = document.getElementById("featuredModalBody");
                    const modalFooter = document.getElementById("featuredModalFooter");

                    // Check if mobile number is valid and not empty
                    if (!mobile || mobile.trim() === "") {
                        modalBody.innerHTML =
                            "<p>Please update your mobile number to request a featured job.</p>";
                        modalFooter.innerHTML = `
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <a href="{{ route('account.profile') }}" class="btn btn-primary">Update Profile</a>
                        `;
                    } else {
                        modalBody.innerHTML =
                            "<p>You must pay <strong>Rs. 500</strong> via <strong>Khalti</strong> to request this job as a featured listing. This payment will make your job featured for <strong>1 month</strong>. Please click <strong>Pay</strong> to proceed.</p>";
                        modalFooter.innerHTML = `
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <form action="{{ route('khalti.initiate', ['name' => '__name__', 'email' => '__email__', 'phone' => '__mobile__', 'amount' => 500, 'jobId' => '__jobId__']) }}" method="GET" id="payForm">
                                @csrf
                                <button type="submit" class="btn btn-success" id="payNowBtn">Pay</button>
                            </form>
                        `;

                        // Dynamically replace the placeholders with actual data
                        setTimeout(() => {
                            const payForm = document.getElementById('payForm');
                            payForm.action = payForm.action.replace('__name__',
                                    encodeURIComponent(name))
                                .replace('__email__', encodeURIComponent(email))
                                .replace('__mobile__', encodeURIComponent(mobile))
                                .replace('__jobId__', encodeURIComponent(jobId));
                        }, 100);
                    }

                    new bootstrap.Modal(document.getElementById('featuredJobModal')).show();
                });
            });
        });
    </script>


    <script type="text/javascript">
        function deleteJob(jobId) {
            if (confirm('Are you sure you want to delete this job?')) {
                fetch("{{ route('account.deleteJob') }}", {
                        method: "POST",
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            jobId: jobId
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status) {
                            alert('Job deleted successfully.');
                            window.location.reload();
                        } else {
                            alert('Failed to delete job.');
                        }
                    })
                    .catch(error => {
                        console.error('Error deleting job:', error);
                        alert('Something went wrong.');
                    });
            }
        }
    </script>
@endsection

@extends('front.layouts.app')

@section('main')
    <section class="section-4 bg-2">
        <div class="container pt-5">
            <div class="row">
                <div class="col">
                    <nav aria-label="breadcrumb" class="rounded-3 p-3">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('jobs') }}"><i class="fa fa-arrow-left"
                                        aria-hidden="true"></i> &nbsp;Back to Jobs</a></li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <div class="container job_details_area">
            <div class="row pb-5">
                <div class="col-md-8">
                    @include('front.message')
                    <div class="card shadow border-0">
                        <div class="job_details_header">
                            <div class="single_jobs white-bg d-flex justify-content-between">
                                <div class="jobs_left d-flex align-items-center">
                                    <div class="jobs_conetent">
                                        <a href="#">
                                            <h4>{{ $job->title }}</h4>
                                        </a>
                                        <div class="links_locat d-flex align-items-center">
                                            <div class="location">
                                                <p><i class="fa fa-map-marker"></i> {{ $job->location }}</p>
                                            </div>
                                            <div class="location">
                                                <p><i class="fa fa-clock-o"></i> {{ $job->jobType->name }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="jobs_right">
                                    <div class="apply_now  {{ $count == 1 ? 'saved-job' : '' }}">
                                        <a class="heart_mark" href="javascript:void(0);"
                                            onclick="saveJob({{ $job->id }})"> <i class="fa fa-heart-o"
                                                aria-hidden="true"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="descript_wrap white-bg">
                            @if (!empty($job->description))
                                <div class="single_wrap">
                                    <h4>Job description</h4>
                                    {!! nl2br(e($job->description)) !!}
                                </div>
                            @endif

                            @if (!empty($job->responsibility))
                                <div class="single_wrap">
                                    <h4>Responsibility</h4>
                                    {!! nl2br(e($job->responsibility)) !!}
                                </div>
                            @endif

                            @if (!empty($job->qualifications))
                                <div class="single_wrap">
                                    <h4>Qualifications</h4>
                                    {!! nl2br(e($job->qualifications)) !!}
                                </div>
                            @endif

                            @if (!empty($job->benefits))
                                <div class="single_wrap">
                                    <h4>Benefits</h4>
                                    {!! nl2br(e($job->benefits)) !!}
                                </div>
                            @endif

                            <div class="border-bottom"></div>
                            <div class="pt-3 text-end">
                                @auth
                                    @if (Auth::user()->role === 'user')
                                        <a href="#" onclick="saveJob({{ $job->id }});"
                                            class="btn btn-secondary">Save</a>
                                        <a href="#" onclick="applyJob({{ $job->id }})"
                                            class="btn btn-primary">Apply</a>
                                    @else
                                        <a href="javascript:void(0)" class="btn btn-primary disabled">Only Jobseekers can
                                            apply</a>
                                    @endif
                                @else
                                    <a href="javascript:void(0)" class="btn btn-primary disabled">Login to Apply</a>
                                @endauth
                            </div>
                        </div>
                    </div>


                    <div class="reviews-section mt-5 p-4 bg-light rounded shadow">
                        <h3 class="mb-4">Comment and Job Reviews</h3>

                        @if ($reviews->isEmpty())
                            <p class="text-muted">No reviews for this job yet.</p>
                        @else
                            @foreach ($reviews as $review)
                                <div class="review-box border-bottom pb-3 mb-4">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <strong>{{ $review->user?->name ?? 'Deleted User' }}</strong>
                                        <small class="text-muted">{{ $review->created_at->diffForHumans() }}</small>
                                    </div>

                                    <div class="rating mb-1">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <i class="fa{{ $i <= $review->rating ? 's' : 'r' }} fa-star text-warning"></i>
                                        @endfor
                                    </div>

                                    <p class="mt-2">{{ $review->comment }}</p>

                                    @auth
                                        {{-- Reviewer can delete --}}
                                        @if ($review->reviewer_id === auth()->id())
                                            <form action="{{ route('reviews.destroy', $review->id) }}" method="POST"
                                                class="mt-2 d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                            </form>
                                        @endif

                                        {{-- Company reply section --}}
                                        @if ($review->reply)
                                            <div class="bg-white border-start border-primary p-3 mt-3">
                                                <strong>Company Reply:</strong>
                                                <p class="mb-0">{{ $review->reply }}</p>
                                            </div>
                                        @elseif(auth()->user()->role === 'company' && $review->job && $review->job->user_id === auth()->id())
                                            <form action="{{ route('reviews.reply', $review->id) }}" method="POST"
                                                class="mt-3">
                                                @csrf
                                                <div class="mb-2">
                                                    <textarea name="reply" class="form-control" rows="2" placeholder="Write a reply..." required></textarea>
                                                </div>
                                                <button type="submit" class="btn btn-sm btn-outline-primary">Reply</button>
                                            </form>
                                        @endif

                                        {{-- Conversation thread --}}
                                        @php
                                            $user = auth()->user();
                                            $canViewConversation =
                                                $user->id === $review->reviewer_id ||
                                                $user->id === $review->job->user_id;
                                        @endphp

                                        @if ($canViewConversation)
                                            <div class="review-chat mt-4 p-3 bg-white rounded shadow-sm">
                                                <h6 class="mb-3">Conversation</h6>
                                                @forelse($review->replies as $reply)
                                                    <div class="mb-2">
                                                        <strong>{{ $reply->user->name }}</strong>
                                                        <span
                                                            class="text-muted small">{{ $reply->created_at->diffForHumans() }}</span>
                                                        <p class="mb-0">{{ $reply->message }}</p>
                                                    </div>
                                                @empty
                                                    <p class="text-muted">No conversation yet.</p>
                                                @endforelse

                                                <form action="{{ route('reviews.replies.post', $review->id) }}" method="POST"
                                                    class="mt-3">
                                                    @csrf
                                                    <div class="mb-2">
                                                        <textarea name="message" rows="2" class="form-control" placeholder="Write a message..." required></textarea>
                                                    </div>
                                                    <button type="submit" class="btn btn-sm btn-primary">Send</button>
                                                </form>
                                            </div>
                                        @elseif($user->id === $review->reviewer_id)
                                            <div class="mt-3">
                                                <form action="{{ route('reviews.replies.post', $review->id) }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="message" value=" ">
                                                    <button type="submit" class="btn btn-outline-secondary btn-sm">
                                                        Have further queries? You can communicate with us!
                                                    </button>
                                                </form>
                                            </div>
                                        @endif
                                    @endauth
                                </div>
                            @endforeach

                            <div class="mt-4">
                                {{ $reviews->links() }}
                            </div>
                        @endif
                    </div>

                    @auth
                        @if (auth()->user()->id == $job->user_id)
                            <div class="card shadow border-0 mt-4">
                                <div class="job_details_header">
                                    <div class="single_jobs white-bg d-flex justify-content-between">
                                        <div class="jobs_left d-flex align-items-center">
                                            <div class="jobs_conetent">
                                                <h4>Applicants</h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="descript_wrap white-bg">
                                    <table class="table table-striped">
                                        <tr>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Mobile</th>
                                            <th>Applied Date</th>
                                            <th>CV File</th>
                                        </tr>
                                        @forelse ($applications as $application)
                                            <tr>
                                                <td>{{ $application->user->name }}</td>
                                                <td>{{ $application->user->email }}</td>
                                                <td>{{ $application->user->mobile }}</td>
                                                <td>{{ \Carbon\Carbon::parse($application->applied_date)->format('d M, Y') }}
                                                </td>
                                                <td>
                                                    @if ($application->cv_file_path)
                                                        <a href="{{ route('cv.downloadCVCompany', ['id' => $application->cv_file_path]) }}"
                                                            class="btn btn-sm btn-success" target="_blank">generate PDF</a>
                                                    @else
                                                        <span>No CV uploaded</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5">Applicants not found</td>
                                            </tr>
                                        @endforelse
                                    </table>
                                </div>
                            </div>
                        @endif
                    @endauth

                </div>

                <div class="col-md-4">
                    {{-- Job Summary --}}
                    <div class="card shadow border-0">
                        <div class="job_sumary">
                            <div class="summery_header pb-1 pt-4">
                                <h3>Job Summary</h3>
                            </div>
                            <div class="job_content pt-3">
                                <ul>
                                    <li>Published on:
                                        <span>{{ \Carbon\Carbon::parse($job->job_created_at)->format('d M, Y') }}</span>
                                    </li>
                                    <li>Vacancy: <span>{{ $job->vacancy }}</span></li>
                                    @if (!empty($job->salary))
                                        <li>Salary: <span>Rs. {{ number_format((float) $job->salary) }}</span></li>
                                    @endif

                                    <li>Location: <span>{{ $job->location }}</span></li>
                                    <li>Job Nature: <span>{{ $job->jobType->name }}</span></li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    {{-- Company Details --}}
                    <div class="card shadow border-0 my-4">
                        <div class="job_sumary">
                            <div class="summery_header pb-1 pt-4">
                                <h3>Company Details</h3>
                            </div>
                            <div class="job_content pt-3">
                                <ul>
                                    <li>Name: <span>{{ $job->company_name }}</span></li>
                                    @if (!empty($job->location))
                                        <li>Location: <span>{{ $job->location }}</span></li>
                                    @endif
                                    @if (!empty($job->company_website))
                                        <li>Website: <span><a
                                                    href="{{ $job->company_website }}">{{ $job->company_website }}</a></span>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Notification Form for Company --}}
                @auth
                    @if (auth()->user()->role === 'company')
                        <div class="col-md-8 mt-4">
                            <div class="card shadow-lg border-0 rounded-3">
                                <div class="card-header text-white border-0 rounded-top"
                                    style="background: linear-gradient(90deg, #007bff 0%, #0056b3 100%);">
                                    <h5 class="mb-0 fw-bold text-uppercase"> Send Notification to Applicants</h5>
                                </div>

                                @php
                                    $isOwner =
                                        auth()->check() &&
                                        auth()->user()->role === 'company' &&
                                        $job->user_id === auth()->id();
                                @endphp

                                @if ($isOwner)
                                    <div class="card-body">
                                        <form action="{{ route('company.notify') }}" method="POST">
                                            @csrf
                                            <div class="mb-4">
                                                <label for="applicant_ids" class="form-label">Select Applicants</label>
                                                <div class="dropdown">
                                                    <button class="btn btn-outline-primary dropdown-toggle w-100 text-start"
                                                        type="button" id="dropdownMenuButton" data-bs-toggle="dropdown"
                                                        aria-expanded="false">
                                                        -- Select Applicants --
                                                    </button>
                                                    <ul class="dropdown-menu w-100" aria-labelledby="dropdownMenuButton">
                                                        @foreach ($applications as $application)
                                                            <li class="dropdown-item">
                                                                <input type="checkbox" name="applicant_ids[]"
                                                                    value="{{ $application->user->id }}"
                                                                    class="form-check-input"
                                                                    id="applicant_{{ $application->user->id }}">
                                                                <label class="form-check-label ms-2"
                                                                    for="applicant_{{ $application->user->id }}">
                                                                    {{ $application->user->name }} (ID:
                                                                    {{ $application->user->id }})
                                                                </label>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            </div>

                                            <div class="mb-4">
                                                <label for="message" class="form-label">Notification Type</label>
                                                <select name="message" class="form-select form-select-lg" required>
                                                    <option value="">-- Choose Notification Type --</option>
                                                    <option value="shortlisted">Shortlisted</option>
                                                    <option value="rejected">Rejected</option>
                                                </select>
                                            </div>

                                            <div class="d-flex justify-content-end">
                                                <button type="submit"
                                                    class="btn btn-success px-5 py-2 rounded-pill shadow-sm">
                                                    <i class="fa fa-paper-plane me-2"></i> Send Notification
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                @endif

                            </div>
                        </div>
                    @endif
                @endauth




            </div>
        </div>
    </section>
@endsection

@section('costumjs')
    <script type="text/javascript">
        function applyJob(id) {
            if (confirm("Are you sure you want to apply on this job?")) {
                $.ajax({
                    url: '{{ route('applyJob') }}',
                    type: 'post',
                    data: {
                        id: id
                    },
                    dataType: 'json',
                    success: function(response) {
                        window.location.reload();
                    }
                });
            }
        }

        function saveJob(id) {
            $.ajax({
                url: '{{ route('saveJob') }}',
                type: 'post',
                data: {
                    id: id
                },
                dataType: 'json',
                success: function(response) {
                    window.location.reload();
                }
            });
        }
    </script>
@endsection

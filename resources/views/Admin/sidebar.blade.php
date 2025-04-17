<div class="card account-nav border-0 shadow mb-4 mb-lg-0">
    <div class="card-body p-0">
        <ul class="list-group list-group-flush">
            <li class="list-group-item d-flex justify-content-between p-3">
                <a href="{{ route('admin.users') }}">Users</a>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <a href="{{ route('admin.jobs') }}">Jobs</a>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <a href="{{ route('admin.jobApplications') }}">Job Applications</a>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <a href="{{ route('categories.index') }}">Job Categories</a>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <a href="{{ route('job_types.index') }}">Job Types</a>
            </li>

            <!-- Company Registration Approval link -->
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <a href="{{ route('admin.companies.pending') }}">Company Reg Approval</a>
            </li>

            <!-- NEW: Featured Job Requests -->
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <a href="{{ route('admin.featured.requests') }}">Featured Job Requests</a>
            </li>

            <li class="list-group-item d-flex justify-content-between align-items-center">
                <a href="{{ route('account.logout') }}">Logout</a>
            </li>
        </ul>
    </div>
</div>

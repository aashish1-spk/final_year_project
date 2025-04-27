@extends('front.layouts.app')

@section('main')
<section class="section-5 bg-2">
    <div class="container py-5">
        <div class="row">
            <div class="col-lg-3">
                @include('front.account.sidebar')
            </div>
            <div class="col-lg-9">
                @include('front.message')

                <div class="card border-0 shadow mb-4">
                    <div class="card-body p-4">
                        <h3 class="fs-4 mb-4">Payment Details for Job: {{ $job->title }}</h3>

                        <table class="table table-bordered">
                            <tr><th>Name</th><td>{{ $payment->name }}</td></tr>
                            <tr><th>Email</th><td>{{ $payment->email }}</td></tr>
                            <tr><th>Phone</th><td>{{ $payment->phone }}</td></tr>
                            <tr><th>Amount</th><td>Rs. {{ number_format($payment->amount) }}</td></tr>
                            <tr><th>Status</th><td>{{ ucfirst($payment->status) }}</td></tr>
                            <tr><th>Order ID</th><td>{{ $payment->order_id }}</td></tr>
                            <tr><th>Transaction ID</th><td>{{ $payment->transaction_id ?? '-' }}</td></tr>
                            <tr><th>Created At</th><td>{{ $payment->created_at->format('d M, Y h:i A') }}</td></tr>
                        </table>

                        <a href="{{ route('account.job.payment.invoice', $job->id) }}" class="btn btn-success mt-3">
                            <i class="fa fa-file-pdf"></i> Download Invoice
                        </a>
                        
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

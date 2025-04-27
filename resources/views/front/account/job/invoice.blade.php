<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice</title>
    <style>
        body { font-family: sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 8px; border: 1px solid #ccc; text-align: left; }
        h2 { text-align: center; }
    </style>
</head>
<body>
    <h2>Payment Invoice</h2>
    <p><strong>Job Title:</strong> {{ $job->title }}</p>

    <table>
        <tr><th>Name</th><td>{{ $payment->name }}</td></tr>
        <tr><th>Email</th><td>{{ $payment->email }}</td></tr>
        <tr><th>Phone</th><td>{{ $payment->phone }}</td></tr>
        <tr><th>Amount</th><td>Rs. {{ number_format($payment->amount) }}</td></tr>
        <tr><th>Status</th><td>{{ ucfirst($payment->status) }}</td></tr>
        <tr><th>Order ID</th><td>{{ $payment->order_id }}</td></tr>
        <tr><th>Transaction ID</th><td>{{ $payment->transaction_id ?? '-' }}</td></tr>
        <tr><th>Date</th><td>{{ $payment->created_at->format('d M, Y h:i A') }}</td></tr>
    </table>
</body>
</html>

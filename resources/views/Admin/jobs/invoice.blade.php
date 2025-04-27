<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Invoice - {{ $job->title }}</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 14px;
        }

        .invoice-box {
            max-width: 800px;
            margin: auto;
            padding: 30px;
            border: 1px solid #eee;
        }

        h2,
        h4 {
            margin: 0 0 10px;
        }

        table {
            width: 100%;
            line-height: inherit;
            border-collapse: collapse;
        }

        table td,
        table th {
            padding: 8px;
            border: 1px solid #ddd;
        }

        .text-right {
            text-align: right;
        }

        .mt-3 {
            margin-top: 1rem;
        }
    </style>
</head>

<body>
    <div class="invoice-box">
        <h2>Payment Invoice</h2>

        <p><strong>Invoice Date:</strong> {{ $payment->created_at->format('d M, Y') }}</p>

        <h4>Job Details</h4>
        <p><strong>Title:</strong> {{ $job->title }}</p>
        <p><strong>Company:</strong> {{ $job->company_name }}</p>

        <h4>Payment Details</h4>
        <table>
            <tr>
                <th>Name</th>
                <td>{{ $payment->name }}</td>
            </tr>
            <tr>
                <th>Email</th>
                <td>{{ $payment->email ?? '-' }}</td>
            </tr>
            <tr>
                <th>Phone</th>
                <td>{{ $payment->phone ?? '-' }}</td>
            </tr>
            <tr>
                <th>Order ID</th>
                <td>{{ $payment->order_id }}</td>
            </tr>
            <tr>
                <th>Transaction ID</th>
                <td>{{ $payment->transaction_id ?? '-' }}</td>
            </tr>
            <tr>
                <th>Status</th>
                <td>{{ ucfirst($payment->status) }}</td>
            </tr>
            <tr>
                <th>Amount</th>
                <td>Rs. {{ number_format($payment->amount) }}</td>
            </tr>
        </table>

        <p class="mt-3 text-right"><strong>Total: Rs. {{ number_format($payment->amount) }}</strong></p>
    </div>
</body>

</html>

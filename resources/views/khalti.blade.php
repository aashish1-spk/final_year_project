<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Khalti Payment</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Khalti Payment</h2>

        @if ($status == 'success')
            <div class="alert alert-success">
                <strong>Success!</strong> {{ $message }}
            </div>
            <p>Please proceed with the payment using the link below:</p>
            <a href="{{ $paymentUrl }}" class="btn btn-primary">Pay Now</a>
        @else
            <div class="alert alert-danger">
                <strong>Error!</strong> {{ $message }}
            </div>
        @endif

        <div class="mt-4">
            <h4>Order Details:</h4>
            <p><strong>Order ID:</strong> {{ $orderId }}</p>
            <p><strong>Order Name:</strong> {{ $orderName }}</p>
            <p><strong>Amount:</strong> NRP {{ $amount }}</p>
            <p><strong>Job ID:</strong> {{ $jobId }}</p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

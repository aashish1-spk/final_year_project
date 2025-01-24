<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Job Notification Email</title>
    <!-- Include Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body style="background-color: #f8f9fa; padding: 20px;">
    <div class="container">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white text-center">
                <h1 class="h4">Job Notification</h1>
            </div>
            <div class="card-body">
                <h2 class="h5">Hello, {{ $mailData['employer']->name }}</h2>
                <p class="fw-bold">Job Title:</p>
                <p>{{ $mailData['job']->title }}</p>

                <p class="fw-bold">Employee Details:</p>
                <ul class="list-group">
                    <li class="list-group-item">
                        <strong>Name:</strong> {{ $mailData['user']->name }}
                    </li>
                    <li class="list-group-item">
                        <strong>Email:</strong> {{ $mailData['user']->email }}
                    </li>
                    <li class="list-group-item">
                        <strong>Mobile No:</strong> {{ $mailData['user']->mobile }}
                    </li>
                </ul>
            </div>
            
        </div>
    </div>

    <!-- Include Bootstrap JS (optional for interactive elements) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

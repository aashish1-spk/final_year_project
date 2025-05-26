<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Job Notification Email</title>
</head>
<body style="background-color: #f8f9fa; padding: 20px; font-family: Arial, sans-serif;">

    <div style="max-width: 600px; margin: 0 auto; background-color: white; border: 1px solid #ddd; border-radius: 5px;">
        <div style="background-color: #007bff; color: white; padding: 15px; text-align: center; border-top-left-radius: 5px; border-top-right-radius: 5px;">
            <h1 style="margin: 0; font-size: 20px;">Job Notification</h1>
        </div>

        <div style="padding: 20px;">
            <h2 style="font-size: 18px;">Hello, {{ $mailData['employer']->name }}</h2>

            <p style="font-weight: bold;">Job Title:</p>
            <p>{{ $mailData['job']->title }}</p>

            <p style="font-weight: bold;">Employee Details:</p>
            <ul style="list-style: none; padding: 0;">
                <li style="padding: 5px 0;"><strong>Name:</strong> {{ $mailData['user']->name }}</li>
                <li style="padding: 5px 0;"><strong>Email:</strong> {{ $mailData['user']->email }}</li>
                <li style="padding: 5px 0;"><strong>Mobile No:</strong> {{ $mailData['user']->mobile }}</li>
            </ul>
        </div>
    </div>

</body>
</html>

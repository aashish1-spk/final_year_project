<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $cvData->name }} - CV</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            background-color: #f4f4f9;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 80%;
            max-width: 900px;
            margin: 50px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .header h1 {
            font-size: 2.8rem;
            margin: 0;
            color: #333;
            font-weight: 700;
        }

        .header p {
            font-size: 1.1rem;
            color: #666;
        }

        .section-title {
            font-size: 1.5rem;
            color: #333;
            margin-bottom: 15px;
            border-bottom: 2px solid #333;
            padding-bottom: 5px;
        }

        .section-content {
            font-size: 1.1rem;
            margin-bottom: 20px;
            color: #555;
        }

        .section-content ul {
            list-style-type: none;
            padding-left: 0;
        }

        .section-content ul li {
            margin-bottom: 8px;
        }

        .section-content a {
            color: #0073e6;
            text-decoration: none;
        }

        .row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .column {
            flex: 48%;
        }

        .column h4 {
            font-size: 1.2rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
        }

        .column p {
            font-size: 1rem;
            color: #555;
            margin: 0;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 0.9rem;
            color: #777;
        }

        .footer p {
            margin: 5px;
        }

        .social-links {
            font-size: 1.2rem;
            margin-top: 20px;
        }

        .social-links a {
            margin: 0 10px;
            color: #0073e6;
        }

        .section-content p {
            line-height: 1.6;
        }

        .skills {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
        }

        .skills div {
            padding: 10px;
            background-color: #f4f4f9;
            border-radius: 5px;
            border: 1px solid #ddd;
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="header">
            <h1>{{ $cvData->name }}</h1>
            <p>{{ $cvData->email }} | {{ $cvData->phone }}</p>
        </div>

        <!-- Career Objective Section -->
        <div class="section">
            <h3 class="section-title">Career Objective</h3>
            <p class="section-content">{{ $cvData->objective }}</p>
        </div>

        <!-- Skills Section -->
        <div class="section">
            <h3 class="section-title">Skills</h3>
            <div class="skills">
                @foreach (explode(',', $cvData->skills) as $skill)
                    <div>{{ trim($skill) }}</div>
                @endforeach
            </div>
        </div>

        <!-- Experience Section -->
        <div class="section">
            <h3 class="section-title">Experience</h3>
            <p class="section-content">{{ $cvData->experience }}</p>
        </div>

        <!-- Education Section -->
        <div class="section">
            <h3 class="section-title">Education</h3>
            <p class="section-content">{{ $cvData->education }}</p>
        </div>

        <!-- Certifications & Languages Section -->
        <div class="row">
            <div class="column">
                <h4>Certifications</h4>
                <p class="section-content">{{ $cvData->certifications ?: 'N/A' }}</p>
            </div>
            <div class="column">
                <h4>Languages Known</h4>
                <p class="section-content">{{ $cvData->languages ?: 'N/A' }}</p>
            </div>
        </div>

        <!-- References Section -->
        <div class="section">
            <h3 class="section-title">References</h3>
            <p class="section-content">{{ $cvData->references ?: 'N/A' }}</p>
        </div>

        <!-- Social Media Links -->
        <div class="row">
            <div class="column">
                <h4>GitHub</h4>
                <p class="section-content"><a href="{{ $cvData->github_link }}"
                        target="_blank">{{ $cvData->github_link ?: 'N/A' }}</a></p>
            </div>
            <div class="column">
                <h4>LinkedIn</h4>
                <p class="section-content"><a href="{{ $cvData->linkedin_link }}"
                        target="_blank">{{ $cvData->linkedin_link ?: 'N/A' }}</a></p>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>CV created on {{ date('Y-m-d') }}</p>
        </div>
    </div>

</body>

</html>

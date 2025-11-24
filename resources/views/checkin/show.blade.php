<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Week {{ $checkIn->week_number }} – Verdict</title>
    <style>
        body {
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            background: #f5f5f5;
            margin: 0;
            padding: 2rem;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            background: #ffffff;
            padding: 1.75rem 2rem;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.06);
        }

        h1 {
            margin-top: 0;
        }

        .score {
            font-size: 2rem;
            font-weight: 700;
            margin: 0.25rem 0;
        }

        .badge {
            display: inline-block;
            padding: 0.2rem 0.75rem;
            border-radius: 999px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .badge-on {
            background: #dcfce7;
            color: #166534;
        }

        .badge-drifting {
            background: #fef3c7;
            color: #92400e;
        }

        .badge-copium {
            background: #fee2e2;
            color: #b91c1c;
        }

        h2 {
            margin-top: 1.5rem;
            font-size: 1.15rem;
        }

        ul {
            padding-left: 1.2rem;
        }

        a {
            color: #4f46e5;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        .section {
            margin-top: 1rem;
        }

        .label {
            font-weight: 600;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Week {{ $checkIn->week_number }} – Verdict</h1>

    <div class="section">
        <div class="score">{{ $checkIn->score }} / 100</div>
        <div>
            @if ($checkIn->verdict === 'on_track')
                <span class="badge badge-on">On Track</span>
            @elseif ($checkIn->verdict === 'drifting')
                <span class="badge badge-drifting">Drifting</span>
            @else
                <span class="badge badge-copium">Copium Week 😂</span>
            @endif
        </div>
    </div>

    <div class="section">
        <h2>Breakdown</h2>
        <ul>
            <li>Hours on DSA: {{ $checkIn->hours_dsa }}</li>
            <li>Hours on Projects: {{ $checkIn->hours_projects }}</li>
            <li>Hours on Career: {{ $checkIn->hours_career }}</li>
            <li>Total hours: {{ $checkIn->hours_dsa + $checkIn->hours_projects + $checkIn->hours_career }}</li>
            <li>Problems solved: {{ $checkIn->problems_solved }}</li>
            <li>Commits: {{ $checkIn->commits }}</li>
            <li>People reached out: {{ $checkIn->outreach_count }}</li>
        </ul>
    </div>

    <div class="section">
        <h2>Recommendations</h2>
        <ul>
            @foreach ($checkIn->recommendations ?? [] as $rec)
                <li>{{ $rec }}</li>
            @endforeach
        </ul>
    </div>

    <div class="section">
        <h2>Your reflections</h2>
        <p><span class="label">Biggest win:</span> {{ $checkIn->biggest_win }}</p>
        <p><span class="label">Biggest excuse:</span> {{ $checkIn->biggest_excuse }}</p>
        <p><span class="label">Next week fix:</span> {{ $checkIn->next_week_fix }}</p>
    </div>

    <div class="section">
        <a href="{{ route('season.current') }}">← Back to dashboard</a>
    </div>
</div>
</body>
</html>

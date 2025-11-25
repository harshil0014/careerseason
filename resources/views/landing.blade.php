<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CareerSeason – 6-Week Execution OS</title>
    <style>
        body {
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            margin: 0;
            padding: 0;
            background: #f3f4f6;
            color: #111827;
        }
        .wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }
        .card {
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.08);
            max-width: 720px;
            width: 100%;
            padding: 32px 40px;
        }
        h1 {
            font-size: 32px;
            margin-bottom: 8px;
        }
        .tagline {
            font-size: 16px;
            color: #6b7280;
            margin-bottom: 24px;
        }
        .pill {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 999px;
            background: #eef2ff;
            color: #4f46e5;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            margin-bottom: 16px;
        }
        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 16px;
            margin-top: 16px;
            margin-bottom: 24px;
        }
        .grid-item {
            padding: 12px 14px;
            border-radius: 12px;
            background: #f9fafb;
            font-size: 14px;
        }
        .grid-item strong {
            display: block;
            margin-bottom: 4px;
        }
        .cta-row {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            align-items: center;
            margin-top: 8px;
        }
        .btn-primary {
            display: inline-block;
            padding: 10px 18px;
            border-radius: 999px;
            border: none;
            background: #4f46e5;
            color: #ffffff;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
        }
        .btn-secondary {
            font-size: 13px;
            color: #6b7280;
        }
        .footer-note {
            margin-top: 20px;
            font-size: 12px;
            color: #9ca3af;
        }
    </style>
</head>
<body>
    <form method="POST" action="{{ route('logout') }}"
          style="position:absolute; top:20px; right:20px;">
        @csrf
        <button type="submit"
                style="background:#f3f4f6; border-radius:999px; padding:6px 14px;
                       border:1px solid #ddd; cursor:pointer;">
            Log out
        </button>
    </form>

<div class="wrapper">
    <div class="card">
        <div class="pill">For non-IIT CS/IT students</div>
        <h1>CareerSeason</h1>
        <p class="tagline">
            A 6-week execution OS for SDE internships. No more "I'll start from Monday" copium –
            just hours, problems, commits, outreach and a Season report at the end.
        </p>

        <div class="grid">
            <div class="grid-item">
                <strong>Weekly check-ins</strong>
                Log DSA, projects, outreach and reflections in one ritual per week.
            </div>
            <div class="grid-item">
                <strong>Score & verdict</strong>
                Each week gets a 0–100 score and a verdict: on_track, drifting or copium.
            </div>
            <div class="grid-item">
                <strong>Season report</strong>
                See 6 weeks of execution in one report you can show to mentors or seniors.
            </div>
        </div>

        <div class="cta-row">
            <a href="{{ url('/season/start') }}" class="btn-primary">
                Start your 6-week Season
            </a>
            <span class="btn-secondary">
                1 Season • 6 weeks • No content spam. Just execution.
            </span>
        </div>

        <div class="footer-note">
            Built by Harshil as an "industry-grade" startup v0.1 on Laravel 12.
        </div>
    </div>
</div>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Career Season – Public Report</title>
    <style>
        body {
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            margin: 0;
            padding: 24px;
            background: #f3f4f6;
            color: #111827;
        }
        .container {
            max-width: 900px;
            margin: 0 auto;
        }
        .card {
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.08);
            padding: 28px 32px;
        }
        h1 {
            font-size: 28px;
            margin-bottom: 4px;
        }
        .subtitle {
            color: #6b7280;
            font-size: 14px;
            margin-bottom: 20px;
        }
        .pill {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 999px;
            background: #eef2ff;
            color: #4f46e5;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            margin-bottom: 12px;
        }
        .meta-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
            gap: 12px;
            margin-bottom: 20px;
            font-size: 14px;
        }
        .meta-label {
            color: #6b7280;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            margin-bottom: 2px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 12px;
            font-size: 14px;
        }
        th, td {
            border: 1px solid #e5e7eb;
            padding: 8px 10px;
            text-align: left;
        }
        th {
            background: #f9fafb;
            font-weight: 600;
        }
        .status-on_track {
            color: #16a34a;
            font-weight: 500;
        }
        .status-drifting {
            color: #f97316;
            font-weight: 500;
        }
        .status-copium {
            color: #dc2626;
            font-weight: 500;
        }
        .footer-note {
            margin-top: 18px;
            font-size: 12px;
            color: #9ca3af;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="card">
        <div class="pill">CareerSeason – Public Season Report</div>
        <h1>
            @if($season->name)
                {{ $season->name }}
                <span style="font-size:0.85rem; font-weight:400; color:#6b7280;">
                    · 6-week execution report
                </span>
            @else
                Career Season – SDE Internship Track
            @endif
        </h1>
        <p class="subtitle">
            Read-only report for an SDE internship preparation Season. Share this link with mentors or friends.
        </p>

        <div class="meta-grid">
            <div>
                <div class="meta-label">Track</div>
                <div>SDE Internship</div>
            </div>
            <div>
                <div class="meta-label">Weeks in Season</div>
                <div>{{ $season->weeks }}</div>
            </div>
            <div>
                <div class="meta-label">Target hours / week</div>
                <div>{{ $season->target_hours_per_week }}</div>
            </div>
            <div>
                <div class="meta-label">Priorities</div>
                <div>
                    @foreach($season->priority_tracks ?? [] as $track)
                        <span style="display:inline-block;font-size:11px;padding:2px 8px;border-radius:999px;background:#f3f4ff;margin-right:6px;margin-bottom:4px;">
                            {{ $track }}
                        </span>
                    @endforeach
                </div>
            </div>
        </div>

        <h2 style="font-size:18px;margin-top:20px;margin-bottom:6px;">Totals so far</h2>
        <div class="meta-grid">
            <div>
                <div class="meta-label">Total hours (all weeks)</div>
                <div>{{ $totals['hours'] }}</div>
            </div>
            <div>
                <div class="meta-label">Problems solved</div>
                <div>{{ $totals['problems_solved'] }}</div>
            </div>
            <div>
                <div class="meta-label">Commits</div>
                <div>{{ $totals['commits'] }}</div>
            </div>
            <div>
                <div class="meta-label">Outreach</div>
                <div>{{ $totals['outreach_count'] }}</div>
            </div>
        </div>

        <h2 style="font-size:18px;margin-top:20px;margin-bottom:6px;">Week-by-week</h2>
        <table>
            <thead>
            <tr>
                <th>Week</th>
                <th>Score</th>
                <th>Verdict</th>
            </tr>
            </thead>
            <tbody>
            @foreach($checkIns as $checkIn)
                <tr>
                    <td>Week {{ $checkIn->week_number }}</td>
                    <td>{{ $checkIn->score }}</td>
                    <td>
                        <span class="status-{{ $checkIn->verdict }}">
                            {{ ucfirst(str_replace('_', ' ', $checkIn->verdict)) }}
                        </span>
                    </td>
                </tr>
            @endforeach

            @if($checkIns->isEmpty())
                <tr>
                    <td colspan="3">No weeks logged yet for this Season.</td>
                </tr>
            @endif
            </tbody>
        </table>

        <div class="footer-note">
            This is a read-only view. Generated by CareerSeason to show 6-week execution towards an SDE internship.
        </div>
    </div>
</div>
</body>
</html>

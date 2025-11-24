<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Career Season – Internal Report</title>
    <style>
        body {
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            margin: 0;
            padding: 24px;
            background: #f3f4f6;
            color: #111827;
        }
        .container {
            max-width: 960px;
            margin: 0 auto;
        }
        .card {
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.08);
            padding: 28px 32px;
        }
        h1 {
            font-size: 26px;
            margin-bottom: 4px;
        }
        .subtitle {
            color: #6b7280;
            font-size: 14px;
            margin-bottom: 18px;
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
        .footer {
            margin-top: 18px;
            font-size: 13px;
            color: #6b7280;
        }
        .link-row {
            margin-top: 10px;
            font-size: 13px;
        }
        .link-row a {
            color: #4f46e5;
            text-decoration: none;
        }
        .share-url {
            font-size: 12px;
            color: #4b5563;
            word-break: break-all;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="card">
        <div class="pill">Internal Season Report</div>
        <h1>Career Season Report – SDE Internship Track</h1>
        <p class="subtitle">
            Internal view with totals, highlights and week-by-week scores. Use this when you’re reviewing your own execution.
        </p>

        <div class="meta-grid">
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

        <h2 style="font-size:18px;margin-top:16px;margin-bottom:6px;">Totals so far</h2>
        <div class="meta-grid">
            <div>
                <div class="meta-label">Total hours</div>
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

        @if($bestWeek)
            <h2 style="font-size:18px;margin-top:16px;margin-bottom:6px;">Highlights</h2>
            <ul style="margin-top:4px;padding-left:18px;font-size:14px;">
                <li>
                    <strong>Best week so far:</strong>
                    Week {{ $bestWeek->week_number }} – {{ $bestWeek->score }}/100
                    ({{ ucfirst(str_replace('_', ' ', $bestWeek->verdict)) }})
                </li>

                @if($worstWeek && $worstWeek->id !== $bestWeek->id)
                    <li>
                        <strong>Toughest week:</strong>
                        Week {{ $worstWeek->week_number }} – {{ $worstWeek->score }}/100
                        ({{ ucfirst(str_replace('_', ' ', $worstWeek->verdict)) }})
                    </li>
                @endif
            </ul>
        @endif

        <h2 style="font-size:18px;margin-top:18px;margin-bottom:6px;">Weeks</h2>

        @if ($checkIns->isEmpty())
            <p>No weeks logged yet.</p>
        @else
            <table>
                <thead>
                <tr>
                    <th>Week</th>
                    <th>Score</th>
                    <th>Verdict</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($checkIns as $ci)
                    <tr>
                        <td>{{ $ci->week_number }}</td>
                        <td>{{ $ci->score }}</td>
                        <td>
                            <span class="status-{{ $ci->verdict }}">
                                {{ ucfirst(str_replace('_', ' ', $ci->verdict)) }}
                            </span>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif

        <div class="link-row">
            <a href="{{ route('season.current') }}">← Back to dashboard</a>
        </div>

        @if(!empty($season->public_token))
            <div class="link-row">
                Public Season URL:<br>
                <span class="share-url">
                    {{ route('season.public', $season->public_token) }}
                </span>
            </div>
        @endif

        <div class="footer">
            This view is for you as the founder/student to review execution. The public link above is safe to share.
        </div>
    </div>
</div>
</body>
</html>

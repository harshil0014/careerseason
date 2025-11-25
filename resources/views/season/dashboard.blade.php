<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Career Season – Dashboard</title>
    <style>
        body {
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            background: #f5f5f5;
            margin: 0;
            padding: 2rem;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
            background: #ffffff;
            padding: 1.75rem 2rem;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.06);
        }

        h1 {
            margin-top: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 0.75rem;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 0.5rem 0.75rem;
            text-align: left;
            font-size: 0.9rem;
        }

        th {
            background: #fafafa;
        }

        .status-on {
            color: #15803d;
            font-weight: 600;
        }

        .status-drifting {
            color: #b45309;
            font-weight: 600;
        }

        .status-copium {
            color: #b91c1c;
            font-weight: 600;
        }

        .status-current {
            color: #1d4ed8;
        }

        .pill {
            display: inline-block;
            padding: 0.1rem 0.5rem;
            border-radius: 999px;
            background: #eef2ff;
            font-size: 0.8rem;
            margin-right: 0.25rem;
        }

        a {
            color: #4f46e5;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        hr {
            margin: 1.2rem 0;
            border: none;
            border-top: 1px solid #e5e7eb;
        }

        .quick-links ul {
            padding-left: 1.2rem;
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

<div class="container">
    <h1>
        @if($season->name)
            {{ $season->name }}
            <span style="font-size:0.8rem; font-weight:400; color:#6b7280;">
                &middot; Career Season
            </span>
        @else
            Career Season – Dashboard
        @endif
    </h1>

    <p>
        Track: <strong>SDE Internship</strong><br>
        Weeks in Season: {{ $season->weeks }}<br>
        Target hours per week: <strong>{{ $season->target_hours_per_week }}</strong><br>
        Current week: <strong>{{ $season->current_week }}</strong>
    </p>

    @if(!empty($season->public_token))
        @php
            $shareUrl = route('season.public', $season->public_token);
        @endphp

        <div style="margin: 8px 0 16px 0;">
            <strong>Shareable Season link:</strong><br>

            <div style="display:flex;flex-wrap:wrap;gap:8px;align-items:center;margin-top:4px;">
                <input
                    type="text"
                    id="share-season-url"
                    value="{{ $shareUrl }}"
                    readonly
                    style="flex:1;min-width:260px;padding:6px 8px;border-radius:8px;border:1px solid #d1d5db;font-size:13px;"
                >
                <button
                    type="button"
                    onclick="copySeasonUrl()"
                    style="padding:6px 12px;border-radius:999px;border:none;background:#4f46e5;color:white;font-size:13px;font-weight:600;cursor:pointer;"
                >
                    Copy link
                </button>
                <a href="{{ $shareUrl }}" target="_blank" style="font-size:12px;color:#4f46e5;text-decoration:none;">
                    Open
                </a>
            </div>

            <div id="share-season-feedback" style="font-size:11px;color:#6b7280;margin-top:4px;"></div>
        </div>
    @endif

    <p>
        Priorities:
        @foreach ($season->priority_tracks as $track)
            <span class="pill">{{ $track }}</span>
        @endforeach
    </p>

    @if($nudge)
        <div style="
            background: #fff8e1;
            border-left: 6px solid #ffca28;
            padding: 12px 16px;
            margin: 20px 0;
            border-radius: 4px;
        ">
            <strong>Heads up:</strong><br>
            Week {{ $nudge['week'] }} was a light week —
            you logged {{ $nudge['hours'] }}h out of {{ $nudge['target'] }}h
            ({{ $nudge['ratioPercent'] }}%).
            <br>
            Consider either lowering your weekly promise OR planning 2 small sessions this week.
        </div>
    @endif

    <hr>

    <h2>Season timeline</h2>

    <table>
        <tr>
            <th>Week</th>
            <th>Status</th>
            <th>Score</th>
            <th>Action</th>
        </tr>
        @for ($w = 1; $w <= $season->weeks; $w++)
            @php
                $ci = $checkIns->firstWhere('week_number', $w);
                $isCurrent = ($w === $season->current_week);
            @endphp
            <tr>
                <td>Week {{ $w }}</td>
                <td>
                    @if ($ci)
                        @if ($ci->verdict === 'on_track')
                            <span class="status-on">On Track</span>
                        @elseif ($ci->verdict === 'drifting')
                            <span class="status-drifting">Drifting</span>
                        @else
                            <span class="status-copium">Copium 😂</span>
                        @endif
                    @else
                        @if ($isCurrent)
                            <span class="status-current">Current (no check-in yet)</span>
                        @else
                            <span>Pending</span>
                        @endif
                    @endif
                </td>
                <td>
                    @if ($ci)
                        {{ $ci->score }}
                    @else
                        –
                    @endif
                </td>
                <td>
                    @if ($ci)
                        <a href="{{ route('checkin.show', $w) }}">View week</a>
                    @elseif ($isCurrent)
                        <a href="{{ route('checkin.create') }}">Do this week's check-in →</a>
                    @else
                        <span>—</span>
                    @endif
                </td>
            </tr>
        @endfor
    </table>

    <hr>

    <div class="quick-links">
        <h2>Quick links</h2>
        <ul>
            <li><a href="{{ route('checkin.create') }}">Go to current week check-in</a></li>
            <li><a href="{{ route('season.report') }}">View Season report (so far)</a></li>
            <li><a href="{{ route('seasons.index') }}">View all Seasons</a></li>
        </ul>
    </div>
</div>

<script>
    function copySeasonUrl() {
        var input = document.getElementById('share-season-url');
        if (!input) return;

        input.select();
        input.setSelectionRange(0, 99999); // mobile friendly

        var feedback = document.getElementById('share-season-feedback');

        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(input.value).then(function () {
                if (feedback) feedback.textContent = 'Link copied to clipboard.';
            }).catch(function () {
                document.execCommand('copy');
                if (feedback) feedback.textContent = 'Link copied (fallback).';
            });
        } else {
            document.execCommand('copy');
            if (feedback) feedback.textContent = 'Link copied to clipboard.';
        }
    }
</script>
</body>
</html>

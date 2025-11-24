<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Career Season Report</title>
</head>
<body>
    <h1>Career Season Report – SDE Internship Track</h1>

    <p>
        Weeks in Season: {{ $season->weeks }}<br>
        Target hours per week: {{ $season->target_hours_per_week }}
    </p>

    <h2>Totals so far</h2>
    <ul>
        <li>Total hours: {{ $totals['hours'] }}</li>
        <li>Total problems solved: {{ $totals['problems_solved'] }}</li>
        <li>Total commits: {{ $totals['commits'] }}</li>
        <li>Total outreach: {{ $totals['outreach_count'] }}</li>
    </ul>

    @if($bestWeek)
        <h3 style="margin-top:16px;">Highlights</h3>
        <ul>
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

    <h2>Weeks</h2>
    @if ($checkIns->isEmpty())
        <p>No weeks logged yet.</p>
    @else
        <table border="1" cellpadding="6" cellspacing="0">
            <tr>
                <th>Week</th>
                <th>Score</th>
                <th>Verdict</th>
            </tr>
            @foreach ($checkIns as $ci)
                <tr>
                    <td>{{ $ci->week_number }}</td>
                    <td>{{ $ci->score }}</td>
                    <td>{{ $ci->verdict }}</td>
                </tr>
            @endforeach
        </table>
    @endif

    <p style="margin-top:1rem;">
        <a href="{{ route('season.current') }}">← Back to dashboard</a>
    </p>
</body>
</html>

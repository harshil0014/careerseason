<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Seasons</title>
</head>
<body>
    <h1>Your Seasons</h1>

    <p>
        <a href="{{ route('season.create') }}">Start a new Season</a>
        &middot;
        <a href="{{ route('dashboard') }}">Back to dashboard</a>
    </p>

    <ul>
        @forelse ($seasons as $season)
            <li>
                {{ $season->name ?? 'Untitled Season' }}
                (started {{ $season->created_at }})
                –
                <a href="{{ route('seasons.show', $season) }}">Open</a>
                |
                <a href="{{ route('seasons.report', $season) }}">View report</a>
            </li>
        @empty
            <li>No Seasons yet.</li>
        @endforelse
    </ul>
</body>
</html>

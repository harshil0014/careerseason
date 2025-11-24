<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Week {{ $week }} Check-in</title>
</head>
<body>
    <h1>Week {{ $week }} Check-in</h1>

    @if ($errors->any())
        <div style="color:red;">
            <strong>Fix these:</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('checkin.store') }}">
        @csrf

        <h2>Work Summary</h2>

        <div>
            <label>Hours on DSA / questions:
                <input type="number" name="hours_dsa"
                       value="{{ old('hours_dsa', 0) }}" min="0" max="80">
            </label>
        </div>

        <div>
            <label>Hours on Projects / code:
                <input type="number" name="hours_projects"
                       value="{{ old('hours_projects', 0) }}" min="0" max="80">
            </label>
        </div>

        <div>
            <label>Hours on Career (resume, outreach…):
                <input type="number" name="hours_career"
                       value="{{ old('hours_career', 0) }}" min="0" max="80">
            </label>
        </div>

        <div>
            <label># of DSA problems solved:
                <input type="number" name="problems_solved"
                       value="{{ old('problems_solved', 0) }}" min="0" max="500">
            </label>
        </div>

        <div>
            <label># of meaningful commits:
                <input type="number" name="commits"
                       value="{{ old('commits', 0) }}" min="0" max="500">
            </label>
        </div>

        <div>
            <label># of people you reached out to:
                <input type="number" name="outreach_count"
                       value="{{ old('outreach_count', 0) }}" min="0" max="500">
            </label>
        </div>

        <h2>Proof (optional)</h2>
        <div>
            <label>GitHub repos you worked on:
                <textarea name="github_links" rows="2" cols="60">{{ old('github_links') }}</textarea>
            </label>
        </div>

        <div>
            <label>Other proof links (profiles, docs, demos):
                <textarea name="other_links" rows="2" cols="60">{{ old('other_links') }}</textarea>
            </label>
        </div>

        <h2>Reflection</h2>

        <div>
            <label>Biggest win this week?
                <textarea name="biggest_win" rows="2" cols="60">{{ old('biggest_win') }}</textarea>
            </label>
        </div>

        <div>
            <label>Biggest excuse / distraction?
                <textarea name="biggest_excuse" rows="2" cols="60">{{ old('biggest_excuse') }}</textarea>
            </label>
        </div>

        <div>
            <label>What will you fix next week?
                <input type="text" name="next_week_fix"
                       value="{{ old('next_week_fix') }}"
                       placeholder="e.g. message 3 seniors, do 5 DSA daily">
            </label>
        </div>

        <div style="margin-top: 1rem;">
            <button type="submit">Generate my weekly verdict</button>
        </div>
    </form>

    <p style="margin-top:1rem;">
        <a href="{{ route('season.current') }}">← Back to dashboard</a>
    </p>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Start your Career Season</title>
</head>
<body>
    <h1>Start your Career Season</h1>

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

    <form method="POST" action="{{ route('season.store') }}">
        @csrf

        <div style="margin-bottom: 1rem;">
            <label for="name" style="display:block; font-weight:600; margin-bottom:4px;">
                Season name (optional)
            </label>
            <input
                type="text"
                id="name"
                name="name"
                value="{{ old('name') }}"
                placeholder="e.g. Winter SDE Season, Jan–Feb 2026"
                style="width:100%; max-width:320px; padding:6px 8px; border-radius:6px; border:1px solid #d1d5db;"
            >
            @error('name')
                <div style="color:#b91c1c; font-size:0.8rem; margin-top:4px;">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div>
            <label>
                Target hours per week:
                <input type="number" name="target_hours_per_week"
                       value="{{ old('target_hours_per_week', 10) }}"
                       min="1" max="80">
            </label>
        </div>

        <div style="margin-top: 1rem;">
            <p>What matters most right now? (pick up to 3)</p>

            <label>
                <input type="checkbox" name="priority_tracks[]" value="dsa"
                    {{ in_array('dsa', old('priority_tracks', [])) ? 'checked' : '' }}>
                DSA / Problem Solving
            </label><br>

            <label>
                <input type="checkbox" name="priority_tracks[]" value="projects"
                    {{ in_array('projects', old('priority_tracks', [])) ? 'checked' : '' }}>
                Projects / Real Code
            </label><br>

            <label>
                <input type="checkbox" name="priority_tracks[]" value="github_portfolio"
                    {{ in_array('github_portfolio', old('priority_tracks', [])) ? 'checked' : '' }}>
                GitHub &amp; Portfolio
            </label><br>

            <label>
                <input type="checkbox" name="priority_tracks[]" value="core_cs"
                    {{ in_array('core_cs', old('priority_tracks', [])) ? 'checked' : '' }}>
                Core CS (OS / DBMS / CN)
            </label><br>

            <label>
                <input type="checkbox" name="priority_tracks[]" value="networking"
                    {{ in_array('networking', old('priority_tracks', [])) ? 'checked' : '' }}>
                Networking &amp; Referrals
            </label><br>

            <label>
                <input type="checkbox" name="priority_tracks[]" value="freelance"
                    {{ in_array('freelance', old('priority_tracks', [])) ? 'checked' : '' }}>
                Freelance / Side-income
            </label>
        </div>

        <div style="margin-top: 1rem;">
            <button type="submit">Start my Season</button>
        </div>
    </form>
</body>
</html>

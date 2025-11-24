<?php

namespace App\Http\Controllers;

use App\Models\Season;
use App\Models\CheckIn;
use Illuminate\Http\Request;

class CheckInController extends Controller
{
    // Show form for current week's check-in
    public function create()
    {
        $season = Season::latest()->firstOrFail();

        // If already checked in for this week, show verdict instead
        $existing = $season->checkIns()
            ->where('week_number', $season->current_week)
            ->first();

        if ($existing) {
            return redirect()->route('checkin.show', ['week' => $season->current_week]);
        }

        return view('checkin.create', [
            'season' => $season,
            'week'   => $season->current_week,
        ]);
    }

    // Handle submission of weekly check-in
    public function store(Request $request)
    {
        $season = Season::latest()->firstOrFail();
        $week   = $season->current_week;

        $data = $request->validate([
            'hours_dsa'       => 'required|integer|min:0|max:80',
            'hours_projects'  => 'required|integer|min:0|max:80',
            'hours_career'    => 'required|integer|min:0|max:80',
            'problems_solved' => 'required|integer|min:0|max:500',
            'commits'         => 'required|integer|min:0|max:500',
            'outreach_count'  => 'required|integer|min:0|max:500',
            'github_links'    => 'nullable|string|max:2000',
            'other_links'     => 'nullable|string|max:2000',
            'biggest_win'     => 'nullable|string|max:2000',
            'biggest_excuse'  => 'nullable|string|max:2000',
            'next_week_fix'   => 'nullable|string|max:255',
        ]);

        // Compute score & verdict
        [$score, $verdict, $recommendations] = $this->scoreWeek($season, $data);

        $checkIn = CheckIn::create(array_merge($data, [
            'season_id'       => $season->id,
            'week_number'     => $week,
            'score'           => $score,
            'verdict'         => $verdict,
            'recommendations' => $recommendations,
        ]));

        // Advance season week (unless last week)
        if ($season->current_week < $season->weeks) {
            $season->current_week += 1;
            $season->save();
        }

        return redirect()->route('checkin.show', ['week' => $week]);
    }

    // Show verdict for a given week
    public function show(int $week)
    {
        $season = Season::latest()->firstOrFail();

        $checkIn = $season->checkIns()
            ->where('week_number', $week)
            ->firstOrFail();

        return view('checkin.show', compact('season', 'checkIn'));
    }

    /**
     * Scoring logic for a single week.
     */
    protected function scoreWeek(Season $season, array $data): array
    {
        $target = max($season->target_hours_per_week, 1);
        $totalHours = $data['hours_dsa'] + $data['hours_projects'] + $data['hours_career'];

        // 1) Base score from hours vs target (0–70)
        $ratio = $totalHours / $target;
        if ($ratio <= 0) {
            $baseScore = 0;
        } elseif ($ratio >= 1) {
            $baseScore = 70;
        } else {
            $baseScore = (int) round($ratio * 70);
        }

        // 2) Balance bonus for priorities (0–20)
        $priorities = $season->priority_tracks ?? [];
        $balanceScore = 0;

        if (in_array('dsa', $priorities, true) && $data['hours_dsa'] > 0) {
            $balanceScore += 7;
        }
        if (in_array('projects', $priorities, true) && $data['hours_projects'] > 0) {
            $balanceScore += 7;
        }
        if (in_array('networking', $priorities, true) && $data['outreach_count'] > 0) {
            $balanceScore += 6;
        }

        // 3) Grind bonus (0–10)
        $grind = 0;
        if ($data['problems_solved'] >= 5) {
            $grind += 4;
        }
        if ($data['commits'] >= 3) {
            $grind += 3;
        }
        if ($data['outreach_count'] >= 2) {
            $grind += 3;
        }

        $score = max(0, min(100, $baseScore + $balanceScore + $grind));

        // Verdict buckets
        if ($score >= 75) {
            $verdict = 'on_track';
        } elseif ($score >= 40) {
            $verdict = 'drifting';
        } else {
            $verdict = 'copium';
        }

        // Recommendations (simple text list)
        $recs = [];

        if ($totalHours < $target * 0.5) {
            $recs[] = 'You did less than half of your promised hours. Next week, commit to a smaller but real number and hit it.';
        }

        if (in_array('networking', $priorities, true) && $data['outreach_count'] === 0) {
            $recs[] = 'You said networking matters, but did 0 outreach. Next week, message at least 3 seniors/recruiters.';
        }

        if (in_array('github_portfolio', $priorities, true) && empty($data['github_links'])) {
            $recs[] = 'You marked GitHub/portfolio as a priority; make at least one visible repo update next week.';
        }

        if (empty($recs)) {
            $recs[] = 'Solid week. Try to repeat this or push slightly harder on your weakest track.';
        }

        return [$score, $verdict, $recs];
    }
}

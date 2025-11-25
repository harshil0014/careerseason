<?php

namespace App\Http\Controllers;

use App\Models\Season;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SeasonController extends Controller
{
    // Home: if no season, show landing page; else to dashboard
    public function index()
    {
        $season = Season::where('user_id', auth()->id())
            ->latest()
            ->first();

        if (! $season) {
            // No season yet for this user → show landing page
            return view('landing');
        }

        // Season exists for this user → go to dashboard
        return redirect()->route('season.current');
    }

    // Show "Start Season" form
    public function create()
    {
        return view('season.create');
    }

    // Handle Season creation
    public function store(Request $request)
    {
        $data = $request->validate([
            'target_hours_per_week' => 'required|integer|min:1|max:80',
            'priority_tracks'       => 'required|array|min:1|max:3',
            'priority_tracks.*'     => 'string|in:dsa,projects,github_portfolio,core_cs,networking,freelance',
        ]);

        // For v1 we always create a new Season; later we can add "archived" etc.
        Season::create([
            'user_id'               => auth()->id(),   // 👈 added line
            'track'                 => 'sde_internship',
            'weeks'                 => 6,
            'current_week'          => 1,
            'target_hours_per_week' => $data['target_hours_per_week'],
            'priority_tracks'       => $data['priority_tracks'],
            'public_token'          => (string) Str::uuid(),
        ]);

        return redirect()->route('season.current');
    }

    // Main dashboard for the latest Season
    public function showCurrent()
    {
        $season = Season::where('user_id', auth()->id())
            ->with('checkIns')
            ->latest()
            ->firstOrFail();

        $checkIns = $season->checkIns()->orderBy('week_number')->get();

        // Smart nudge based on last logged week
        $nudge = null;

        if ($checkIns->isNotEmpty() && $season->target_hours_per_week > 0) {
            $last = $checkIns->last();

            $lastHours = ($last->hours_dsa ?? 0)
                + ($last->hours_projects ?? 0)
                + ($last->hours_career ?? 0);

            $ratio = $lastHours / $season->target_hours_per_week;

            if ($ratio < 0.5) {
                $nudge = [
                    'week'         => $last->week_number,
                    'hours'        => $lastHours,
                    'target'       => $season->target_hours_per_week,
                    'ratioPercent' => round($ratio * 100),
                ];
            }
        }

        return view('season.dashboard', [
            'season'   => $season,
            'checkIns' => $checkIns,
            'nudge'    => $nudge,
        ]);
    }

    // Simple Season report
    public function report()
    {
        $season = Season::where('user_id', auth()->id())
            ->latest()
            ->with('checkIns')
            ->firstOrFail();

        $checkIns = $season->checkIns()->orderBy('week_number')->get();

        $totals = [
            'hours' => $checkIns->sum(function ($c) {
                return $c->hours_dsa + $c->hours_projects + $c->hours_career;
            }),
            'problems_solved' => $checkIns->sum('problems_solved'),
            'commits'         => $checkIns->sum('commits'),
            'outreach_count'  => $checkIns->sum('outreach_count'),
        ];

        // Best and worst weeks by score (only if you have check-ins)
        $bestWeek = $checkIns->filter(fn ($c) => $c->score !== null)->sortByDesc('score')->first();
        $worstWeek = $checkIns->filter(fn ($c) => $c->score !== null)->sortBy('score')->first();

        return view('season.report', compact('season', 'checkIns', 'totals', 'bestWeek', 'worstWeek'));
    }

    // Public report accessible via token
    public function publicReport(string $token)
    {
        // Find Season by public token + load check-ins
        $season = Season::where('public_token', $token)
            ->with('checkIns')
            ->firstOrFail();

        $checkIns = $season->checkIns()->orderBy('week_number')->get();

        $totals = [
            'hours' => $checkIns->sum(function ($c) {
                return $c->hours_dsa + $c->hours_projects + $c->hours_career;
            }),
            'problems_solved' => $checkIns->sum('problems_solved'),
            'commits'         => $checkIns->sum('commits'),
            'outreach_count'  => $checkIns->sum('outreach_count'),
        ];

        // Best and worst weeks by score (only if you have check-ins)
        $bestWeek = $checkIns->filter(fn ($c) => $c->score !== null)->sortByDesc('score')->first();
        $worstWeek = $checkIns->filter(fn ($c) => $c->score !== null)->sortBy('score')->first();

        return view('season.public', compact('season', 'checkIns', 'totals', 'bestWeek', 'worstWeek'));
    }
}

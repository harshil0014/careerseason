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
        $season = Season::latest()->first();

        if (! $season) {
            // No season yet → show landing page
            return view('landing');
        }

        // Season exists → go to dashboard
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
        $season = Season::latest()->with('checkIns')->firstOrFail();

        return view('season.dashboard', [
            'season'   => $season,
            'checkIns' => $season->checkIns()->orderBy('week_number')->get(),
        ]);
    }

    // Simple Season report
    public function report()
    {
        $season = Season::latest()->with('checkIns')->firstOrFail();
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

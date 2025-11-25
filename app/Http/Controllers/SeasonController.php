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
            'name'                  => 'nullable|string|max:100',
            'target_hours_per_week' => 'required|integer|min:1|max:80',
            'priority_tracks'       => 'required|array|min:1|max:3',
            'priority_tracks.*'     => 'string|in:dsa,projects,github_portfolio,core_cs,networking,freelance',
        ]);

        // For v1 we always create a new Season; later we can add "archived" etc.
        Season::create([
            'user_id'               => auth()->id(),
            'name'                  => $data['name'] ?? null,
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

        // Coach v1: copium streak (2+ bad weeks in a row)
        $copiumBanner = null;

        if ($checkIns->isNotEmpty()) {
            $streak = 0;
            $lastWeekWithCheckIn = null;

            foreach ($checkIns as $ci) {
                if ($ci->verdict === 'copium') {
                    $streak++;
                    $lastWeekWithCheckIn = $ci->week_number;
                } else {
                    $streak = 0;
                }
            }

            if ($streak >= 2 && $lastWeekWithCheckIn !== null) {
                $copiumBanner = [
                    'streak'     => $streak,
                    'startWeek'  => $lastWeekWithCheckIn - $streak + 1,
                    'endWeek'    => $lastWeekWithCheckIn,
                ];
            }
        }

        return view('season.dashboard', [
            'season'       => $season,
            'checkIns'     => $checkIns,
            'nudge'        => $nudge,
            'copiumBanner' => $copiumBanner, // 👈 pass to view
        ]);
    }

    // Dashboard for a specific Season (opened from /seasons list)
    public function showSeason(Season $season)
    {
        // Security: only owner can view
        if ($season->user_id !== auth()->id()) {
            abort(403);
        }

        $season->load('checkIns');

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

        // Coach v1: copium streak (2+ bad weeks in a row)
        $copiumBanner = null;

        if ($checkIns->isNotEmpty()) {
            $streak = 0;
            $lastWeekWithCheckIn = null;

            foreach ($checkIns as $ci) {
                if ($ci->verdict === 'copium') {
                    $streak++;
                    $lastWeekWithCheckIn = $ci->week_number;
                } else {
                    $streak = 0;
                }
            }

            if ($streak >= 2 && $lastWeekWithCheckIn !== null) {
                $copiumBanner = [
                    'streak'     => $streak,
                    'startWeek'  => $lastWeekWithCheckIn - $streak + 1,
                    'endWeek'    => $lastWeekWithCheckIn,
                ];
            }
        }

        return view('season.dashboard', [
            'season'       => $season,
            'checkIns'     => $checkIns,
            'nudge'        => $nudge,
            'copiumBanner' => $copiumBanner,
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

    // Season report for a specific Season (from /seasons list)
    public function reportForSeason(Season $season)
    {
        // Security: make sure this Season belongs to the logged-in user
        if ($season->user_id !== auth()->id()) {
            abort(403);
        }

        // Eager load check-ins
        $season->load('checkIns');

        $checkIns = $season->checkIns()->orderBy('week_number')->get();

        $totals = [
            'hours' => $checkIns->sum(function ($c) {
                return $c->hours_dsa + $c->hours_projects + $c->hours_career;
            }),
            'problems_solved' => $checkIns->sum('problems_solved'),
            'commits'         => $checkIns->sum('commits'),
            'outreach_count'  => $checkIns->sum('outreach_count'),
        ];

        $bestWeek = $checkIns->filter(fn ($c) => $c->score !== null)
            ->sortByDesc('score')
            ->first();

        $worstWeek = $checkIns->filter(fn ($c) => $c->score !== null)
            ->sortBy('score')
            ->first();

        return view('season.report', compact('season', 'checkIns', 'totals', 'bestWeek', 'worstWeek'));
    }

    // List all Seasons for the current user
    public function indexAll()
    {
        $seasons = Season::where('user_id', auth()->id())
            ->orderByDesc('created_at')
            ->get();

        return view('season.index', [
            'seasons' => $seasons,
        ]);
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

# CareerSeason

**CareerSeason** is a 6-week execution OS for CS/IT students aiming for SDE internships.

Instead of giving you more content and roadmaps, CareerSeason forces 6 weeks of focused execution:
- Track your weekly hours (DSA / projects / career)
- Log problems solved, commits pushed, and outreach done
- Get a weekly score (0–100) and a verdict (on_track / drifting / copium)
- Finish with a Season Report you can show to mentors and recruiters

---

## Features (v0.1)

- Start a 6-week Season with:
  - Target hours per week
  - 1–3 priority tracks (DSA, projects, networking, etc.)
- Log a weekly check-in:
  - Hours (DSA / projects / career)
  - Problems solved
  - Commits
  - Outreach count
  - Proof links (GitHub / others)
  - Reflections (biggest win, biggest excuse, next-week fix)
- Automatic scoring per week:
  - Base score from hours vs target
  - Bonuses for hitting your priorities
  - Verdict: `on_track`, `drifting`, or `copium`
  - Concrete recommendations for the next week
- Season dashboard:
  - All 6 weeks at a glance
  - Status and score per week
  - One-click to current week check-in or week verdict
- Season report:
  - Totals (hours, problems, commits, outreach)
  - Week-by-week scores and verdicts

Built with **Laravel 12** and **PHP 8.2**.

---

## Getting Started

### Prerequisites

- PHP 8.2+
- Composer
- SQLite (or another DB you configure in `.env`)

### Setup

```bash
git clone <your-repo-url>
cd careerseason

composer install

cp .env.example .env
php artisan key:generate

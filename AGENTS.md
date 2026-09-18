# Project agent memory

This file is the project's committed home for project-intrinsic agent knowledge: build, test, release, architecture, and sharp-edge notes that should travel with the code.

- Add durable project-specific notes here as they are discovered through real work.

## Craft 5 verification lab

- The throwaway Craft 5.11 lab lives in `lab/` (gitignored), served by the DDEV project `calendarize-lab` (config in `.ddev/`, docroot `lab/web`). The plugin is required from the lab via a composer `path` repository pointing at `..`, so live edits apply without reinstalling.
- Lab content (section, `schedule` field, recurring entry) is provisioned by content migrations in `lab/migrations/`; Twig proof templates live in `lab/templates/` (e.g. `/caltest` returns occurrence JSON).
- The lab is pinned to UTC in `lab/config/general.php` so date round-trips are exact; post dates in the system's timezone (as the CP does) if that is ever removed.

## Maintaining this file

Keep this file for knowledge useful to almost every future agent session in this project.
Do not repeat what the codebase already shows; point to the authoritative file or command instead.
Prefer rewriting or pruning existing entries over appending new ones.
When updating this file, preserve this bar for all agents and keep entries concise.

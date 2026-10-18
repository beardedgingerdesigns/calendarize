# Craft 5 verification lab

The existing local lab was checked against `.ddev/config.yaml`,
`lab/composer.json`, `lab/composer.lock`, `lab/config/general.php`,
`lab/migrations/`, and `lab/templates/`. These paths are ignored by Git,
not a reproducible environment delivered by a fresh checkout.

## Setup and isolation

The throwaway Craft 5.11 lab (locally locked to 5.11.2) lives in `lab/`.
DDEV project `calendarize-lab` serves `lab/web`, using PHP 8.4 and MariaDB
10.11 in the inspected `.ddev/config.yaml`. In an already provisioned,
independent lab, run `ddev start` from the repository root.

A new worker copy needs its own provisioned Craft application, database,
and DDEV configuration before that command is useful. Ask the maintainer
for lab provisioning; no tracked bootstrap script exists. Use a unique
DDEV project name for concurrent copies, and do not point at another
worker's database. Stop only a lab you started, with `ddev stop`.

The lab's Composer `path` repository points at `..` with symlinking enabled,
so edits to this plugin apply without reinstalling. Preserve that relative
relationship when provisioning a worker copy (`lab/composer.json`).

## Manual checks

Content migrations in `lab/migrations/` provision the `events` section,
`schedule` field, and recurring entry. In a provisioned lab, apply pending
content migrations with `ddev exec php lab/craft migrate/all`.
Twig proof templates live in `lab/templates/`; `/caltest` returns occurrence
JSON. For the original project name, inspect it with
`curl --fail https://calendarize-lab.ddev.site/caltest`.
The fixture tests a weekly recurrence and exclusion of September 21, 2026.
Its fixed dates mean `next` and `upcoming` results depend on the current date;
inspect the fixed-window occurrence results as well (`caltest.twig`).

The lab is pinned to UTC in `lab/config/general.php` so date round-trips are
exact. If that pin is removed, post dates in the system's timezone, as the
Craft control panel does.

## Credentials

Local Craft configuration lives in ignored `lab/.env`; variable names are
documented by `lab/.env.example.dev`, `lab/.env.example.staging`, and
`lab/.env.example.production`. They include `CRAFT_APP_ID`,
`CRAFT_ENVIRONMENT`, `CRAFT_SECURITY_KEY`, `CRAFT_DB_DRIVER`,
`CRAFT_DB_SERVER`, `CRAFT_DB_PORT`, `CRAFT_DB_DATABASE`, `CRAFT_DB_USER`,
`CRAFT_DB_PASSWORD`, `CRAFT_DB_SCHEMA`, and `CRAFT_DB_TABLE_PREFIX`.
These example files are local lab material, not evidence of hosted environments.
No test-login credentials file or login environment variable is documented
in the tracked repository; obtain a local control-panel account from the
lab maintainer. Never copy credential values into project documentation.

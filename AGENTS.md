# Calendarize
Craft CMS 5 recurring-date field plugin for site developers and editors; see [README.md](README.md) and [composer.json](composer.json).

## Run it
- PHP 8.2+ and Craft 5 are required by `composer.json`; install plugin dependencies with `composer install`.
- Frontend: `yarn install --frozen-lockfile`, then `npm run dev` or `npm run watch` (`package.json`, `yarn.lock`).
- Provisioned lab only: `ddev start`; `.ddev/config.yaml` names `calendarize-lab`, with docroot `lab/web`.
- Worker copies do not receive ignored `lab/` or `.ddev/`; see [lab setup and isolation](docs/wiki/verification-lab.md) before starting DDEV.

## Check it
- PHP syntax: `find src -name '*.php' -exec php -l {} \;`; dependency metadata: `composer validate --no-check-publish` (`composer.json`).
- Asset build: `npm run production` (`package.json`, `webpack.prod.config.js`); output goes to `src/assetbundles/resources/dist/`.
- No test or lint scripts are defined in `package.json` or `composer.json`; manual recurrence checks and login guidance are in [the lab notes](docs/wiki/verification-lab.md).

## Ship it
- Target `master` for this repository's work; inspect refs with `git branch -r` and release history in `CHANGELOG.md`.
- No tracked `.github/workflows/` or deploy scripts define dev → staging → production; no staging branch or URL is documented (see [release boundaries](docs/wiki/decisions/README.md)).
- Releases and consuming-site deployments require manual maintainer coordination; `npm run production` only builds plugin assets (`webpack.prod.config.js`).

## Access
- Local lab uses DDEV/Docker and Composer; Craft environment variable names and credential ownership are in [the lab notes](docs/wiki/verification-lab.md).
- No server `user@host` inventory or deployment credential contract is recorded; ask the owner for release/hosting access ([release boundaries](docs/wiki/decisions/README.md)).

## Never
- Do not commit `.env`, `vendor/`, `node_modules/`, `lab/`, or `.ddev/`; `.gitignore` defines the local-only paths.
- Do not reuse another worker's DDEV project identity or database; follow [lab isolation](docs/wiki/verification-lab.md).
- Preserve recurrence data/API compatibility documented in `CHANGELOG.md`; implementation lives in `src/models/CalendarizeModel.php` and `src/migrations/`.

## Decisions
- Keep decisions and history in [docs/wiki/decisions/](docs/wiki/decisions/README.md); keep this command map concise using [documentation guidance](docs/wiki/decisions/README.md#documentation-guidance).

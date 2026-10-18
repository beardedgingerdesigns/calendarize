# Decisions and operational boundaries

## Existing compatibility decision

[CHANGELOG.md](../../../CHANGELOG.md) records the Craft 5 / PHP 8.2 minimum,
major-version rationale, unchanged recurrence table shape, and public API
compatibility. Keep that existing history authoritative rather than duplicating it.

## Release boundaries

The inspected remote refs expose `origin/master`; no tracked workflow,
deployment script, server inventory, staging URL, or staging/production branch
contract establishes a deployment path. `package.json` and
`webpack.prod.config.js` build plugin assets, not a hosted site. Coordinate
release publication and consuming-site deployments manually with the owner;
record a verified release procedure here when one is established.

## Documentation guidance

[AGENTS.md](../../../AGENTS.md) is the short, committed operating map.
Keep durable project-specific knowledge supported by real work in linked docs;
keep decisions and history in this directory. Prefer references to authoritative
code and commands over duplicating them. Rewrite or prune stale guidance instead
of accumulating notes, and preserve this concise-documentation standard when editing.

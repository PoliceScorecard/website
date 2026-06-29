# Police Scorecard Website AI Instructions

## Project Posture

- Treat this repository as production software with active traffic and public links.
- Prefer small, reviewable patches that preserve existing behavior unless the user explicitly asks for a larger change.
- Do not make broad refactors, dependency upgrades, route or URL changes, auth/admin changes, deploy-script changes, or generated asset churn without explicit approval.
- Check the current git status before editing. Do not overwrite or revert user changes.

## Scope

- In scope: Laravel web routes, local `/api` routes, `ApiController`, Blade rendering, helpers, View Components, admin UI, frontend assets, tests, and project docs.
- Out of scope unless explicitly requested: the separate external Scorecard API project, even if it exists next to this repo on disk.
- The website consumes the external Scorecard API through `POLICE_SCORECARD_API_BASE`, commonly `http://localhost:5001` in local development.
- Treat external Scorecard API responses as data contracts to inspect and adapt to, not code to edit from this repo.

## Safety Rules

- Never inline real secrets, API keys, Mapbox tokens, passwords, or signing keys. Use `.env` and the existing Laravel config pattern.
- Keep public URLs stable. Routes in `routes/web.php` and `routes/api.php` are user-facing contracts.
- Avoid changing cache behavior unless the task is specifically about caching.
- Do not commit generated outputs under `public/`, `reports/`, `vendor/`, `node_modules/`, `bootstrap/cache`, or `storage`.

## Annual Data Work

- Annual data changes usually mean the external API payload has new or changed fields. Trace the field through this website before editing.
- Use existing hard-coded year patterns conservatively. Do not replace them with a new abstraction unless the user explicitly approves a refactor.
- For new year support, update affected helper chart data, Blade visibility guards, script guards, visible year ranges, and no-data behavior together.
- All direct `$scorecard[...]` usage must remain guarded when fields may be absent.

## Validation

- Prefer the repo's existing commands:
  - `npm run test-lint`
  - `npm run test-unit`
  - `npm run dev`
- If local PHP is broken or unavailable, use the documented Docker workflow or report the blocker clearly.
- For rendering changes, verify representative pages such as `/us`, `/ca`, `/ca/police-department/los-angeles`, and `/ca/sheriff/los-angeles-county` when a local server is available.

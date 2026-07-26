---
name: legacy-verification
description: Verification checklist for conservative changes to this legacy Laravel website.
argument-hint: "[change-summary]"
---

# Legacy Verification

Use this before handing off code changes in this website repo.

## Commands

Run what is relevant and available:

- `git diff --check`
- `npm run test-lint`
- `npm run test-unit`
- `npm run dev` when frontend assets or Blade-rendered scripts changed

If local PHP is unavailable or broken, use the documented Docker workflow or report the blocker.

## Manual Checks

When a local server is available, check affected routes plus representative pages:

- `/us`
- `/ca`
- `/ca/police-department/los-angeles`
- `/ca/sheriff/los-angeles-county`
- `/api/search` when local API search changed
- `/api/map/us/sheriff` when map API behavior changed

## Review Focus

- public URLs are unchanged
- optional `$scorecard` fields are guarded
- charts still receive the same data shape
- visible year ranges match data fields
- no secrets or generated assets were added
- no unrelated user changes were touched

---
name: annual-data-update
description: Checklist for adding annual Scorecard payload fields to this Laravel website. Use when adding a new data year, new CSV-derived field, or new chart/rendering support.
argument-hint: "[field-or-year]"
---

# Annual Data Update

Use this for website-side annual data work only. Do not edit the separate external Scorecard API project.

## Workflow

1. Identify the data contract:
   - field name or year
   - payload section, such as `arrests`, `police_accountability`, `police_violence`, `police_funding`, `jail`, `homicide`, or `report`
   - affected agency types: police department, sheriff, state, national

2. Trace existing website usage:
   - `rg -n "<field>|<prefix>_20|2013|2023" app/Helpers.php resources/views app/Http routes`
   - Run a broad year-key audit, not only a supplied CSV-column audit:
     `rg -o "[A-Za-z][A-Za-z0-9_]*_20[0-9]{2}" app resources/views routes config --glob '!public/**' --glob '!resources/assets/images/**' | sed 's/^.*://' | sort | uniq -c | sort -k2`
   - Find helper chart builders, Blade guards, script guards, visible copy, and methodology text.
   - Include derived annual families and aggregates for the same new years, such as `arrests_YYYY`, `low_level_arrests_YYYY`, `total_arrests_YYYY`, chart labels, and national summary math, even when those fields are not explicitly listed in the CSV-column request.

3. Inspect payloads through this website's configured external API contract:
   - Use `POLICE_SCORECARD_API_BASE` from local configuration when available.
   - Do not print or commit secret values.
   - If credentials are unavailable, document the missing payload evidence and continue only where existing patterns are unambiguous.

4. Implement conservatively:
   - Add the new year or field to the existing pattern.
   - Add every year in the supported range across all related field families, not just the exact fields named by the user.
   - Keep fallback behavior when the field is absent.
   - Keep chart data shapes unchanged.
   - Do not introduce a broad year-range abstraction unless the user explicitly approves that refactor.

5. Verify:
   - `npm run test-lint`
   - `npm run test-unit`
   - `npm run dev` or Docker equivalent when asset output matters
   - Manual pages when relevant: `/us`, `/ca`, `/ca/police-department/los-angeles`, `/ca/sheriff/los-angeles-county`, `/api/search`, `/api/map/us/sheriff`

## Required Handoff

End with:

- fields added or changed
- files touched
- guards/fallbacks preserved
- validation commands run
- pages or endpoints checked
- any external API payload assumptions that remain unverified

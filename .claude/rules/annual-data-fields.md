---
paths:
  - "app/Helpers.php"
  - "resources/views/**/*.blade.php"
  - "app/Http/Controllers/ApiController.php"
  - "routes/api.php"
---

# Annual Data Field Rules

- Start annual data work by identifying the exact external payload fields and the section they belong to, such as `arrests`, `police_accountability`, `police_violence`, `police_funding`, `jail`, `homicide`, or `report`.
- Inspect this website for matching older-year patterns with `rg` before editing.
- Do not edit the separate external API project from this repo.
- Add the new year to each affected existing pattern instead of redesigning the pattern.
- Keep backwards compatibility when a new field is absent from an older payload.
- Update all affected layers together: helper chart data, Blade `@if` guards, script guards, visible labels, tooltip text, and about/methodology copy when applicable.
- Check both department and sheriff/state pages when fields apply to more than one agency type.
- Add focused characterization tests for helper output when practical; otherwise document manual page checks.

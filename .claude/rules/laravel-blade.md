---
paths:
  - "resources/views/**/*.blade.php"
  - "app/Helpers.php"
  - "app/View/**/*.php"
---

# Laravel And Blade Rules

- Follow the existing Laravel 7 style and helper-heavy Blade patterns.
- Keep Blade changes local to the affected component or section.
- Guard optional scorecard fields before reading them. Prefer existing `isset`, `output`, `num`, `nFormatter`, and `<x-partial.no-data-found />` patterns.
- Do not introduce a new frontend framework, chart library, or component pattern.
- Keep chart rendering guards in sync with the helper that builds the chart data.
- If a helper emits JSON for a chart, preserve the existing Chart.js or Highcharts data shape.
- Treat direct `$scorecard[...]` reads as risky unless the field is known to be always present.
- Update visible year-range copy when annual data fields change.

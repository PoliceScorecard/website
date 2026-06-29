---
paths:
  - "routes/api.php"
  - "app/Http/Controllers/ApiController.php"
  - "config/api.php"
  - "resources/views/layouts/admin.blade.php"
---

# Local API Proxy Rules

- This repo has its own Laravel `/api` routes. They are in scope.
- Do not confuse those routes with the separate external Scorecard API service configured by `POLICE_SCORECARD_API_BASE`.
- `ApiController` is the website client for the external API and also serves local endpoints such as `/api/search` and `/api/map/us/sheriff`.
- Preserve local API response shapes unless the task explicitly changes an API contract.
- Preserve external API error handling unless the task is specifically about errors.
- Be careful with cache keys. Current requests often include query strings in the endpoint string itself.
- Do not expose configured API keys, admin tokens, or Mapbox tokens in logs, docs, or examples.
- Do not call admin import/update endpoints as part of routine validation.

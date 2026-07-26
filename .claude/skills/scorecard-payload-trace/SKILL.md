---
name: scorecard-payload-trace
description: Trace external Scorecard API payload fields into this Laravel website without editing the external API project.
argument-hint: "[field-name-or-endpoint]"
allowed-tools: Read, Grep, Glob, Bash
---

# Scorecard Payload Trace

Use this to understand how a field from the external Scorecard API is consumed by this website.

## Rules

- Inspect only this website repo unless the user explicitly expands scope.
- Do not edit the separate external API project.
- Do not reveal API keys, admin tokens, Mapbox tokens, or secrets.
- Treat the external payload as a contract that may vary by location and agency type.

## Trace Steps

1. Locate the website API client path:
   - `config/api.php`
   - `app/Http/Controllers/ApiController.php`
   - `routes/api.php`

2. Locate website consumption:
   - `rg -n "<field-name>" app resources routes config`
   - For annual fields, also search nearby year and prefix patterns.

3. Inspect representative payloads if a local or configured external API is available:
   - Prefer local configuration and avoid printing credentials.
   - Check at least one police department and one sheriff/state payload when the field is not agency-type-specific.

4. Return a concise map:
   - external endpoint
   - payload path
   - Laravel helper or controller usage
   - Blade or script usage
   - guard/fallback behavior
   - unknowns or payload variants

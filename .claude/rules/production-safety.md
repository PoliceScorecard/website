# Production Safety

- This is not a greenfield project. Preserve public behavior unless the task explicitly requests a change.
- Keep patches narrow. Do not combine unrelated cleanup with feature or data updates.
- Before editing, re-read the files being changed and check `git status --short`.
- Do not touch user-modified files unless the requested work requires it.
- Do not run destructive git commands, delete data, rewrite history, or remove files without explicit approval.
- Do not alter deployment scripts, auth, admin protections, route patterns, dependency versions, or build tooling as incidental cleanup.
- Do not edit generated assets or cache files unless the user asks for generated output.
- If a validation command fails because local tooling is broken, report the exact blocker and the command that failed.

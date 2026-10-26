#!/usr/bin/env bash
set -euo pipefail

# Always lint with the active PHP runtime; cached results can hide upgrade errors.
find app config routes resources tests -type f -name '*.php' -print0 |
    xargs -0 -n 1 php -l

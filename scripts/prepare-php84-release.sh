#!/usr/bin/env bash
set -euo pipefail
umask 077

# Prepare and test a fresh release. Never updates a live root or Nginx.
case "${1:-}" in
    development) site=/var/www/dev.policescorecard.org; build=dev ;;
    production) site=/var/www/www.policescorecard.org; build=prod ;;
    *) echo "Usage: bash scripts/prepare-php84-release.sh development|production" >&2; exit 2 ;;
esac

php84=/opt/policescorecard/php84/bin/php
composer84=/opt/policescorecard/php84/bin/composer.phar
export PATH="/opt/policescorecard/php84/bin:$PATH"
source_dir=$(git rev-parse --show-toplevel)
cd "$source_dir"

if [[ $(id -u) == 0 ]]; then
    echo "Run as the deploy account, not root." >&2
    exit 1
fi
if [[ "$source_dir" == "$site/html" ]]; then
    echo "Use a separate source checkout, not the live website directory." >&2
    exit 1
fi
if [[ -n $(git status --porcelain --untracked-files=normal) ]]; then
    echo "Source checkout must contain only the committed release, with no local changes." >&2
    exit 1
fi
"$php84" -r 'exit(PHP_MAJOR_VERSION === 8 && PHP_MINOR_VERSION === 4 && version_compare(PHP_VERSION, "8.4.27", ">=") ? 0 : 1);'
"$php84" -r '$c=json_decode(file_get_contents("composer.json"), true, 512, JSON_THROW_ON_ERROR); exit(($c["require"]["laravel/framework"] ?? "") === "^13.0" ? 0 : 1);'
if [[ $(node --version) != v12.12.0 || $(npm --version) != 6.11.3 ]]; then
    echo "This migration uses the verified legacy build toolchain: Node 12.12.0 / npm 6.11.3." >&2
    exit 1
fi
[[ -r "$site/html/.env" ]] || { echo "Cannot read the environment file for $site" >&2; exit 1; }

commit=$(git rev-parse HEAD)
mkdir -p "$site/releases"
release=$(mktemp -d "$site/releases/php84-${commit:0:12}-XXXXXX")
trap 'echo "Preparation failed; candidate retained at $release. Live website unchanged." >&2' ERR
git archive "$commit" | tar -x -C "$release"
install -m 0600 "$site/html/.env" "$release/.env"
cd "$release"
mkdir -p bootstrap/cache storage/framework/cache/data storage/framework/sessions storage/framework/views storage/logs

"$php84" "$composer84" install --prefer-dist --no-interaction --no-ansi --no-progress
"$php84" "$composer84" check-platform-reqs
npm ci --no-optional
npm run -s "$build"
"$php84" vendor/bin/phpunit --display-all-issues
"$php84" artisan config:cache
"$php84" artisan route:cache
"$php84" artisan view:cache
printf '%s\n' "$commit" > .release-commit
trap - ERR
printf '\nCandidate prepared: %s\nCommit: %s\n' "$release" "$commit"
echo "Not promoted. Set deploy/FPM group permissions, test through the private pool, and measure performance before changing Nginx."
echo "Dev dependencies are included for tests; remove them from a production candidate before its final validation."

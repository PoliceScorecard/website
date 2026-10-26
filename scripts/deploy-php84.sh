#!/usr/bin/env bash
set -Eeuo pipefail
umask 027

case "${1:-}" in
    development) site=/var/www/dev.policescorecard.org/html; branch=develop; build=dev ;;
    production) site=/var/www/www.policescorecard.org/html; branch=master; build=prod ;;
    *) echo 'Usage: deploy-php84 development|production [--check]' >&2; exit 2 ;;
esac
case "${2:-}" in ''|--check) ;; *) echo 'Unknown option' >&2; exit 2 ;; esac
[[ $# -le 2 ]] || exit 2
[[ $(id -un) == psadmin ]] || { echo 'Run as psadmin, without sudo.' >&2; exit 1; }
export PATH="/opt/policescorecard/php84/bin:$PATH"
php84=/opt/policescorecard/php84/bin/php
composer84=/opt/policescorecard/php84/bin/composer.phar
cd "$site"
# Both sites share server resources. Serialize deployment builds across them.
exec 9>/var/www/dev.policescorecard.org/html/.git/php84-deploy.lock
flock -n 9 || { echo 'Another deployment is running.' >&2; exit 1; }
[[ -r .env && -w .env && -w storage && -w bootstrap/cache ]]
[[ $(git rev-parse --abbrev-ref HEAD) == "$branch" ]] || { echo "Expected branch $branch." >&2; exit 1; }
[[ -z $(git status --porcelain --untracked-files=normal) ]] || { echo 'Working tree is not clean; inspect changes first.' >&2; exit 1; }
"$php84" -r 'exit(PHP_MAJOR_VERSION === 8 && PHP_MINOR_VERSION === 4 && version_compare(PHP_VERSION, "8.4.27", ">=") ? 0 : 1);'
"$php84" "$composer84" --version
[[ $(node --version) == v12.12.0 && $(npm --version) == 6.11.3 ]]
[[ $("$php84" -r 'echo ini_get("opcache.validate_timestamps");') == 1 ]] || { echo 'Timestamp validation must be enabled for in-place deploys.' >&2; exit 1; }
if [[ ${2:-} == --check ]]; then
    echo "Checks passed for $branch. No fetch, build, or maintenance change performed."
    exit 0
fi
git fetch origin "$branch"
target=$(git rev-parse "origin/$branch")
git merge-base --is-ancestor HEAD "$target" || { echo 'Deployment requires a fast-forward.' >&2; exit 1; }
git show "$target:composer.json" | "$php84" -r '$c=json_decode(stream_get_contents(STDIN), true, 512, JSON_THROW_ON_ERROR); exit(($c["require"]["laravel/framework"] ?? "") === "^13.0" ? 0 : 1);'
backup_dir="$HOME/.policescorecard-deploy-backups"
mkdir -p "$backup_dir"
chmod 700 "$backup_dir"
backup=$(mktemp "$backup_dir/$branch.env.XXXXXX")
install -m 600 .env "$backup"
env_hash=$(sha256sum .env | cut -d ' ' -f 1)
trap 'echo "Deployment failed. Maintenance is retained if entered; inspect the error before reopening. Environment backup: $backup" >&2' ERR
"$php84" artisan down --retry=60
git merge --ff-only "$target"
mkdir -p bootstrap/cache storage/framework/cache/data storage/framework/sessions storage/framework/views storage/logs
"$php84" artisan config:clear
composer_options=(--prefer-dist --no-interaction --no-ansi --no-progress --optimize-autoloader)
if [[ $branch == master ]]; then composer_options+=(--no-dev); fi
"$php84" "$composer84" install "${composer_options[@]}"
"$php84" "$composer84" check-platform-reqs
npm ci --no-optional --production=false
npm run -s "$build"
if [[ $branch == develop ]]; then "$php84" vendor/bin/phpunit --display-all-issues; fi
chgrp -R www-data storage bootstrap/cache .env
chmod -R ug+rwX,o-rwx storage bootstrap/cache
chmod 640 .env
"$php84" artisan cache:clear
"$php84" artisan config:cache
"$php84" artisan route:cache
"$php84" artisan view:cache
[[ $(sha256sum .env | cut -d ' ' -f 1) == "$env_hash" ]]
"$php84" artisan up
trap - ERR
echo "Deployed $target to $branch. Verify the website in your browser."

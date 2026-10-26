PHP upgrade testing
===================

This branch targets PHP 8.4 and Laravel 13. Production must use PHP 8.4.27
or a newer patched 8.4 release before deploying this branch. Keep CLI PHP,
PHP-FPM, and the self-hosted CI runner on the same PHP minor version.

Start Docker Desktop, then run from the repository root:

```bash
docker compose build --pull app
docker compose run --rm app composer install
docker compose run --rm app php artisan optimize:clear
docker compose run --rm app php artisan --version
docker compose run --rm app php -v
docker compose run --rm app composer check-platform-reqs
docker compose run --rm app ./tests/commands/phplint.sh
docker compose run --rm app ./vendor/bin/phpunit --display-all-issues
docker compose up -d
```

Open http://localhost:8000. The port binds only to the local computer.
If port 8000 is in use, run `WEBSITE_PORT=8001 docker compose up -d` and
open http://localhost:8001 instead.
Use your existing local `.env`; do not regenerate APP_KEY for an existing
installation. A new checkout needs `.env.example` copied to `.env` and
`docker compose run --rm app php artisan key:generate`.
Docker uses `POLICE_SCORECARD_API_BASE` from `.env`. Set `DOCKER_API_BASE`
when starting Compose to override it. If neither is set, Docker uses
`http://host.docker.internal:5001`.
The app needs its API endpoint/key and existing compiled assets for manual
browsing. API requests in automated HTTP tests are faked.

Dependencies and behavior
-------------------------

Laravel, Guzzle, Tinker, Faker, Collision, and PHPUnit have been upgraded.
Built-in Laravel middleware replaces Fideloper proxy and Fruitcake CORS.
The old application structure and controller route namespaces are retained.
Livewire 1 and Spatie export were removed: no application references were
found, but external server scripts must be checked for `artisan export`.
Page-speed HTML transformations were removed; verify page layouts and asset
loading. Built-in PHP OPcache remains enabled, but the old package's
`artisan opcache:*` and npm wrappers are no longer available. Restart PHP-FPM
when deploying. Legacy package config files are inert.
Xdebug is installed but disabled by default; use XDEBUG_MODE=debug or
XDEBUG_MODE=coverage when needed. Session upload progress is disabled.

Before production
-----------------

Verify the home/national pages, state lists, department and sheriff reports,
incomplete records, search, charts, maps, and admin authentication. Test API
failures and large-response caching. Only run Mapbox publishing operations
against explicitly designated test resources. Check deployment scripts for
removed package commands, and take a server snapshot before cutover.

Composer's platform setting resolves dependencies for PHP 8.4.27; it does
not patch the interpreter. Verify the actual FPM runtime separately. This
website upgrade does not update the separate API application.

Verified in this branch
-----------------------

Built the Docker image with PHP 8.4.27 and installed Laravel 13.38.0 from
the new lockfile. All 12 tests passed (36 assertions), including mocked
page rendering, API search, admin authentication, rankings, and API caching.
Platform requirements, PHP lint, Blade compilation, route caching, and
Composer audit passed. No advisories were reported by Composer.

The local site was started on port 8001 because port 8000 was occupied.
Real page browsing currently requires starting the local API on host port
5001 or supplying a reachable DOCKER_API_BASE. A connection failure to that
API currently produces an HTTP 500; real report/chart behavior has not yet
been verified on this runtime.

External API follow-up: Compose now honors POLICE_SCORECARD_API_BASE from
`.env`, with DOCKER_API_BASE as an optional override. The configured external
API host is reachable, but an authenticated request to `/v1/scorecard/states`
timed out after 15 seconds without receiving a response. Real page testing
remains pending that endpoint responding. The 12 regression tests still pass.

Large API responses
-------------------

Local Docker PHP allows 1024 MB per request, with max_execution_time and
max_input_time set to 900 seconds. PHP-FPM terminates requests at 960 seconds.
Nginx FastCGI read/send timeouts are 900 seconds (proxy_* settings do not
apply to PHP-FPM). The local .env API timeout is set to 600 seconds; set
POLICE_SCORECARD_API_TIMEOUT=600 in other test environments as needed.
Restart the containers after changing PHP or FPM settings. These are local
testing settings; size production limits against available RAM and worker count.

Verified native Ubuntu 16.04 build and release preparation
=========================================================

Operator checks confirmed the private PHP 8.4.27 CLI and FPM installation at
`/opt/policescorecard/php84`, Composer 2.10.3 at `bin/composer.phar`, and the
systemd service `policescorecard-php84`. The private socket is
`/opt/policescorecard/php84/var/run/php84-fpm.sock`. A localhost-only Nginx check
on 127.0.0.1:8084 confirmed FPM 8.4.27, loaded OPcache, a 1024M memory limit,
OpenSSL 3.5.9, and cURL 8.23.0. This does not switch any website upstream.
The service was started but has not yet been enabled at boot.

Private dependency prefixes under `/opt/policescorecard/deps` are: libxml2
2.15.4, zlib 1.3.2, openssl 3.5.9, libpsl 0.23.3, curl 8.23.0, oniguruma
6.9.10, libzip 1.12.1, and sqlite 3.54.1. System libraries were not replaced.
libpsl uses the system ICU development library. libzip and SQLite received
smoke checks; their complete upstream regression suites were not run.

GCC 5.4 failed CPU-feature checks; Clang 6.0 needed C99 mode for PHP's alternate
atomic implementation. The final configure settings include
`CC=clang-6.0 CXX=clang++-6.0 CFLAGS="-g -O0 -std=gnu99"`,
`--disable-opcache-jit`, and `ax_cv_have_func_attribute_ifunc=no`, along with
explicit private-library pkg-config paths and runtime search paths. The
optimized Clang build crashed and produced incorrect error messages, so it
must not be installed. The installed build is UNOPTIMIZED: application and
performance testing are still required before production use. Keep the exact
source trees, config.nice files, build logs, and dependency versions for rebuilds.
The PHP configuration path is `etc/php.ini` plus `etc/conf.d/99-scorecard.ini`;
OpenSSL/cURL CA paths explicitly use `/etc/ssl/certs/ca-certificates.crt`.

The unoptimized suite reported 17,015 passes, nine expected failures, one
warning, and one TLS test failure. That remaining TLS test passed when an
identified orphaned test server on port 14430 was stopped and the test rerun
with the private OpenSSL first in PATH. These results span separate runs;
there was not a single all-passing full-suite run. The earlier UTF crash and
typed-property/serialization failures passed with optimization disabled.

Both live .env files were backed up and compared successfully under
`/root/policescorecard-env-backups/{dev,production}.env`. An unauthenticated
upload script was discovered in the development storage tree. Preserve the
investigation evidence; clean application files alone do not establish host
integrity or whether credentials were exposed. Do not copy the old vendor,
node_modules, caches, or storage PHP files into a fresh release.

Node 12.12.0 and npm 6.11.3 match the repository's legacy .nvmrc/build setup.
The release helper retains these versions for this migration; modernizing the
frontend build toolchain is separate work. The old deploy scripts use bare
php/composer, obsolete artisan options and OPcache package commands, and do
not stop on errors. Keep them unused during migration. Pushing develop/master
still triggers those scripts through the existing workflows; first publish the
reviewed upgrade commit on a separate branch, without opening a PR that would
run the legacy self-hosted workflow. Existing automation has NOT been updated.

`scripts/prepare-php84-release.sh` prepares a candidate on this same server
without updating a live checkout or Nginx. Run it with bash as the deploy
account from a separate clean Git checkout of the committed upgrade branch.
Its environment argument is development or production. The deploy account
must be able to create the site's releases directory and read its live .env.
The helper archives only committed source, copies .env with mode 0600,
installs dependencies from lockfiles, runs PHPUnit and the asset build, and
creates Laravel caches. It uses the private PHP/Composer and stops on error.
A failed candidate is retained for inspection. It deliberately does not run
artisan down/up, reset/clean the live checkout, modify system PHP, or restart
services. It does not fetch or pull code, so verify the selected commit first.

Example AFTER the upgrade commit is published and checked out separately:

```bash
bash scripts/prepare-php84-release.sh development
```

The resulting directory is private to the deploy account until its permissions
are deliberately set for FPM/Nginx. Set the group to www-data, grant group read
and directory traversal, and grant group write only for storage/bootstrap/cache.
Keep .env inaccessible to other users. Test the candidate through the private
pool with a loopback Nginx server; do not point existing public Laravel 7
checkouts at PHP 8.4. Production candidates initially include dev dependencies
for validation; remove them with Composer --no-dev and repeat final validation
before promotion. Measure the unoptimized build under representative load
before changing a production root/upstream. Final automation replacement,
public cutover, and removal of the old runtimes remain pending.

Production PHP upgrade on the existing DigitalOcean Droplet
===========================================================

Scope: keep the existing Droplet and Ubuntu installation; install PHP 8.4
alongside the existing runtimes, test a separate application release, then
switch only this website's Nginx upstream and deployment commands. No new
server, DNS change, or OS upgrade is assumed. These are planned operator
steps; no production changes have been performed from this repository.

Recorded server inventory
-------------------------

The operator supplied the following production inventory:

| Item | Recorded value |
| --- | --- |
| OS | Ubuntu 16.04.7 LTS, Xenial |
| CLI PHP | 7.3.21-1+ubuntu16.04.1+deb.sury.org+1, NTS, built Aug 7 2020 |
| CLI OPcache | 7.3.21, enabled |
| PHP executable | /usr/bin/php |
| Composer executable | /usr/local/bin/composer |
| Composer version | 1.10.10, released Aug 3 2020 |
| CLI php.ini | /etc/php/7.3/cli/php.ini |
| Additional CLI configuration | /etc/php/7.3/cli/conf.d/*.ini |
| Running web server | nginx.service |
| Running PHP services | php7.0-fpm.service AND php7.3-fpm.service |
| Public TCP listeners reported | Nginx on 80/443 and SSH on 22, IPv4 and IPv6 |

The module list includes curl, mbstring, GD, Imagick, intl, JSON, PDO with
MySQL/PostgreSQL, XML/DOM/XSL, zip, sodium, pcntl, and OPcache. It also includes
legacy recode, wddx, and xmlrpc extensions. This is the CLI inventory, not proof
of which extensions an HTTP request loads. Do not copy PHP 7 extension .so
files or conf.d symlinks into PHP 8.4. Select PHP 8.4 extensions based on actual
application requirements and inventory other sites before retiring anything.

No public FPM TCP listener appears in the supplied output. FPM may use Unix
sockets; confirm its listeners. Running two FPM versions does not establish
which one serves this website or whether both are needed by other sites.

Confirmed Nginx site mapping
----------------------------

The operator's nginx -T output confirms all three application sites include
`snippets/basic.conf`, whose PHP handler uses
`fastcgi_pass unix:/run/php/php7.3-fpm.sock;`:

| Environment | Nginx site file under /etc/nginx/sites-enabled/ | Document root |
| --- | --- | --- |
| Production | policescorecard.org | /var/www/www.policescorecard.org/html/public |
| Development | dev.policescorecard.org | /var/www/dev.policescorecard.org/html/public |
| Staging | staging.policescorecard.org | /var/www/staging.policescorecard.org/html/public |

Production serves both policescorecard.org and www.policescorecard.org. The
shared file is `/etc/nginx/snippets/basic.conf`; its PHP location includes
`snippets/fastcgi-php.conf` and also contains shared asset/security rules.
**Do not replace the PHP socket in basic.conf globally:** that would move
production, development, and staging together, even if their code is still
Laravel 7. To test one environment, make a separate copy such as
`/etc/nginx/snippets/basic-php84.conf`, retain its existing rules, and update
only the copy's socket and FastCGI timeouts. Change only the selected server
block's include to that new snippet, together with its prepared release root.
Check the other environments still include basic.conf and retain PHP 7.3.
Resolve sites-enabled symlinks before editing so the intended sites-available
source is updated. Use nginx -t before reload and preserve backups for rollback.

The output also contains an ssl_stapling warning because a certificate lacks
an OCSP responder URL. It is separate from PHP version routing; inspect the
complete certificate/TLS configuration before changing shared SSL snippets.
The shared ssl-params.conf enables stapling. This inventory does not establish
that the warning is a failed Nginx configuration test or a PHP error.

Composer upgrade requirement
----------------------------

Composer 1.10.10 cannot install this branch's Laravel dependency set: the
locked framework requires composer-runtime-api ^2.2. Install current stable
Composer 2 separately using the checksum verification procedure on the
[Composer download page](https://getcomposer.org/download/). Keep the existing
/usr/local/bin/composer intact until its consumers and rollback are accounted
for. Use an explicitly named Composer 2 PHAR with the verified PHP 8.4 executable
for this branch, and test that pair with --version and check-platform-reqs.
Composer 2 can be staged before the PHP switch, but it does not make this
branch compatible with PHP 7.3. Do not run a global composer self-update as
an unreviewed side effect of upgrading one site.

1. Establish an installable PHP 8.4 runtime for Xenial
----------------------------------------------------

The normal Ondrej PHP PPA supports current Ubuntu LTS releases and explicitly
excludes Expanded Security Maintenance releases. It does not provide a verified
current PHP 8.4 installation route for this Xenial host. Do not use the previous
Ubuntu 24.04 apt commands here, change apt distribution names to noble/jammy,
or install packages built for another Ubuntu release.

Next terminal step: read-only PHP build/package preflight
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

The agreed scope is this existing Droplet with Ubuntu 16.04 unchanged. Run
this block on it and share the output. It installs nothing, changes no service,
and does not print .env or credentials. Missing commands/packages are useful
results; the block continues past them. apt-cache uses existing local package
metadata, so an absent candidate does not prove no vendor package exists.

```bash
uname -m
getconf GNU_LIBC_VERSION
free -h
df -h / /tmp
nproc
command -v gcc g++ make pkg-config curl tar xz
(gcc --version 2>/dev/null || true) | head -n 1
(g++ --version 2>/dev/null || true) | head -n 1
openssl version
apt-cache policy php8.4-cli php8.4-fpm build-essential pkg-config
for library in openssl libcurl libxml-2.0 sqlite3 oniguruma libzip icu-uc; do
    printf '%s: ' "$library"
    pkg-config --modversion "$library" 2>/dev/null || true
done
dpkg-query -W -f='${Package} ${Version}\n' gcc g++ make pkg-config libssl-dev libcurl4-openssl-dev libxml2-dev libsqlite3-dev libonig-dev libzip-dev libicu-dev 2>/dev/null || true
```

Use these results to prepare a side-by-side PHP 8.4 CLI/FPM installation,
preferably under an isolated prefix such as /opt/policescorecard/php84, with
private dependencies where necessary. That path is a proposed destination,
not an existing executable. Keep /usr/bin/php, both existing FPM services,
and shared Nginx basic.conf untouched during installation and testing.
Do not execute generic PHP 8.4 apt installation commands on Xenial yet.
The actual build/install commands remain pending this compatibility check;
this preflight block is not an installation procedure.

Before an executable install procedure can be written, obtain one of:

- A provider-maintained PHP 8.4 CLI/FPM build explicitly compatible with Ubuntu
  16.04, including required extensions, security updates, and service integration.
- A separately maintained custom build, with a tested compiler/library toolchain,
  isolated installation prefix, CLI/FPM configuration, systemd service, required
  extensions, and an owner/process for future security rebuilds.

Ask the hosting provider whether it can supply the first option. Availability
has not been confirmed. A source build is possible in principle, but the exact
procedure depends on Xenial's libraries and compiler; this runbook does not claim
that a stock PHP source configure/make command will work. Do not replace system
OpenSSL or other shared libraries to force a build. Install any custom runtime
and dependencies in isolated paths and test them before production use.

Docker is not an assumed shortcut: current Docker Engine's supported Ubuntu
list excludes 16.04. Local Docker testing does not verify this host's ability
to run a maintained container runtime. Updating PHP also does not update the
host OS or other sites still using PHP 7.0/7.3.

2. Complete read-only site and runtime mapping
---------------------------------------------

On this same server, inspect the following locally. Nginx/deployment output
may contain private configuration; redact credentials before sharing it.

```bash
sudo nginx -T
sudo ss -lxnp
sudo systemctl cat php7.0-fpm php7.3-fpm
sudo grep -R -E '^[[:space:]]*(listen|user|group|pm.max_children)[[:space:]]*=' /etc/php/7.0/fpm/pool.d /etc/php/7.3/fpm/pool.d
/usr/local/bin/composer --version
free -h
```

Record the production server_name, root, fastcgi_pass, TLS configuration,
FPM pool user/group, and memory budget. Map dev/staging and any API or other
hosted domains to their own roots and FPM pools. Inspect the three server-side
`/usr/local/bin/deploy-*` scripts privately to record release paths, PHP and
Composer invocation, asset builds, restart commands, and branch selection.
Inventory cron, workers, databases, persistent files, and the self-hosted runner.
Do not stop either existing FPM service until its consumers are established.

3. Back up and prepare a separate release on this server
--------------------------------------------------------

Freeze automatic deployments before changing production. The repository's
production workflow runs on pushes to master using a self-hosted Linux X64
runner. Preserve the current release and vendor directory intact. Back up
Nginx/FPM configuration, deployment scripts, .env/APP_KEY, persistent files,
and any local database. Create a DigitalOcean snapshot with application/data
consistency appropriate to hosted services; separately back up external data.
A snapshot rollback restores the whole Droplet and can discard subsequent writes.

Install the verified PHP 8.4 runtime alongside 7.0/7.3, with a DISTINCT FPM
service and socket. Require patched PHP 8.4.27 or newer in the 8.4 series.
Do not change /usr/bin/php or update-alternatives globally: other sites, cron,
and deployment jobs may still depend on PHP 7. Use explicit executable paths.
Runtime paths, configuration directories, socket, and service name must come
from the selected package/build, not assumptions about Ubuntu package layout.

Prepare the reviewed application commit in a separate release directory. Keep
its vendor directory and generated bootstrap caches separate from the PHP 7
release. Preserve production APP_KEY, use APP_ENV=production and APP_DEBUG=false,
and configure the production API URL/key and API timeout. Do not run key:generate.
Do not copy compiled Blade/cache files from the old release. Transfer persistent
files and grant the FPM user write access to storage and bootstrap/cache without
world-writable permissions. Build/copy public assets with the tested frontend
build process. Install Composer 2 compatible with the new runtime separately; the recorded
Composer 1.10.10 cannot support this branch. Do not break old deployment jobs.

After substituting the VERIFIED paths, run as the deploy account from the
new release directory (these are placeholders, not known server paths):

```bash
PHP84=/verified/path/to/php84
COMPOSER2=/verified/path/to/composer2.phar
"$PHP84" -v
"$PHP84" --ini
"$PHP84" "$COMPOSER2" install --no-dev --prefer-dist --no-interaction --optimize-autoloader
"$PHP84" "$COMPOSER2" check-platform-reqs --no-dev
"$PHP84" artisan --version
"$PHP84" artisan config:cache
"$PHP84" artisan route:cache
"$PHP84" artisan view:cache
```

Use the reviewed lockfile; do not run composer update or ignore platform
requirements. Test with dev dependencies in a separate test checkout. Do not
run database migrations without an identified application requirement.

4. Configure the isolated FPM service and test locally
-----------------------------------------------------

Apply the required settings to the NEW PHP 8.4 runtime's FPM configuration.
Its CLI configuration may be separate. Do not edit /etc/php/7.3/cli/php.ini
expecting it to affect the new HTTP runtime.

```ini
memory_limit=1024M
max_execution_time=900
max_input_time=900
default_socket_timeout=600
session.upload_progress.enabled=0
opcache.enable=1
opcache.validate_timestamps=1
opcache.revalidate_freq=0
```

Set request_terminate_timeout=960s in its FPM pool and
POLICE_SCORECARD_API_TIMEOUT=600 in the new release's .env. Size pm.max_children
against available RAM and measured peak request memory, including memory used
by BOTH existing FPM services during testing. These local-test limits have not
been load-tested for production. Enable no production Xdebug.

Create a temporary Nginx server listening only on 127.0.0.1 on an unused port,
with the prepared release's public directory as root and its own PHP 8.4 socket
as fastcgi_pass. Adapt the existing Laravel routing/FastCGI configuration,
including SCRIPT_FILENAME and hidden-file protections. Set fastcgi_read_timeout
and fastcgi_send_timeout to 900s. proxy_* settings do not apply to FPM.
Validate Nginx and the new FPM configuration before reloading. Do not alter the
existing site's upstream yet or expose a public phpinfo page.

Run the suite with the explicit PHP 8.4 executable. Test actual GET responses,
assets, cold/warm large API requests, charts, reports, search, admin access,
and memory under concurrency. Avoid publishing Mapbox operations during testing.
Verify FPM version/settings separately from CLI. Inspect CDN/proxy timeout
limits too. Successful local Docker tests do not replace these native-runtime tests.

5. Switch this website and its deployment jobs
---------------------------------------------

After successful tests and a rollback rehearsal, change only this website's
production Nginx root to the prepared release and its fastcgi_pass to the
verified PHP 8.4 socket. Update its FastCGI timeouts. Retain all required domain,
TLS, redirect, and access rules. Run sudo nginx -t before reloading Nginx.
No DNS/IP change is needed. Leave other sites and their PHP pools unchanged.

Update this website's deployment scripts, cron/worker commands, and CI jobs to
use explicit PHP 8.4 and Composer 2 paths. A PHP-based composer launcher must
run under PHP 8.4, even if /usr/bin/php remains 7.3. Tests need PHP 8.4 too.
The self-hosted workflow currently invokes bare php/composer and npm test scripts
that invoke php: give those jobs a dedicated PHP 8.4 PATH or explicit commands,
without changing unrelated services. Remove obsolete artisan export/opcache:*
calls; reload/restart ONLY the new FPM service to reset its OPcache on deployment.
Review the workflow's hardcoded runner cleanup path and existing deploy scripts.

Verify external HTTPS pages, logs, peak RAM, FPM queues, API/cache behavior,
and 5xx/timeouts. Resume automatic deployment only when its runtime and release
target are verified. This change does not patch other PHP sites on the server.

6. Roll back as a matching code/runtime pair
-------------------------------------------

Keep the previous website release, vendor files, Nginx root/upstream configuration,
and original FPM service available. If cutover fails, restore BOTH its original
release path and its original PHP socket, validate/reload Nginx, and restore its
matching deployment commands. Do not run Laravel 7 on PHP 8.4 or the new branch
on PHP 7. Reconcile any persistent writes before reverting releases.

The original PHP versions remain vulnerable unless verified backports exist.
A static maintenance response that bypasses PHP is an alternative if rolling
back to the unpatched runtime is unacceptable. Laravel maintenance mode alone
does not block PHP-engine attack paths. Do not uninstall old runtimes until
all their consumers are identified and migrated.

References
----------

- [Ondrej PHP PPA support scope](https://launchpad.net/~ondrej/+archive/ubuntu/php)
- [Docker Engine supported Ubuntu versions](https://docs.docker.com/engine/install/ubuntu/)
- [Laravel production configuration](https://laravel.com/docs/13.x/deployment)
- [Composer installation](https://getcomposer.org/download/)
- [DigitalOcean snapshots](https://docs.digitalocean.com/products/snapshots/how-to/snapshot-droplets/)
- [PHP security advisory](https://www.php.net/archive/2026.php#2026-10-21-5)

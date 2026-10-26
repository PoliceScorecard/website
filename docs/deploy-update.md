**[↤ Developer Overview](../README.md)**

Deploy Update
===

> Here are the instructions on how to deploy updates to this website.

**IMPORTANT:** Before updating the website, you should run any updates to the API first.

Connect to SSH on Digital Ocean
---

1. Loging to [Digital Ocean](https://digitalocean.com)
2. Switch to the `Police Scorecard` Team
3. Select the `police-scorecard` Droplet
4. Click the `Console` link to open Digital Oceans Web Console
5. Run one of the following scripts based on what you want to update

Run Scripts
---

The PHP 8.4 deployment replacement is `scripts/deploy-php84.sh`. Install a
reviewed copy as `/usr/local/bin/deploy-php84`, with wrappers passing
`development` or `production`. Run as `psadmin`, without sudo. Before deploying,
run `/usr/local/bin/deploy-php84 production --check` (or `development`). This
check does not fetch, build, or enter maintenance.

The replacement uses PHP 8.4 and Composer 2 explicitly, requires a clean checkout
on develop/master, and accepts only fast-forward updates. It backs up `.env`
privately, preserves it, uses lockfile installs, builds assets, and refreshes
Laravel caches. Development also runs PHPUnit after building assets; production
installs without development dependencies, so test the release before promotion
to master. Failures stop deployment and retain maintenance when entered. Inspect
the failure before manually running `artisan up`. Deployments take the site
offline during the build. No database migrations run automatically.

The old server scripts must be replaced before using the commands below.
Staging remains outside the PHP 8.4 migration; do not run its legacy deployment
script as part of this procedure. Automatic deployment remains paused.

```bash
# To update https://dev.policescorecard.org using `develop` branch
/usr/local/bin/deploy-development

# To update https://policescorecard.org using `master` branch
/usr/local/bin/deploy-production
```

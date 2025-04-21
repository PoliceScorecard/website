**[↤ Developer Overview](../README.md)**

Deploy Update
===

> Here are the instructions on how to deploy updates to this website.

**IMPORTANT:** Before updating the website, you should run any updates to the API first.

```bash
# SSH into Police Scorecard
ssh staywoke@policescorecard.org

# To update https://dev.policescorecard.org using `develop` branch
/usr/local/bin/deploy-development

# To update https://staging.policescorecard.org using `staging` branch
/usr/local/bin/deploy-staging

# To update https://policescorecard.org using `master` branch
/usr/local/bin/deploy-production
```

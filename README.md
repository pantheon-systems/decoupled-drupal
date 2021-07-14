# Decoupled Bridge Demo Site

This is a Drupal 9 site created with Pantheon Build tools. It uses CircleCI for continuous integration and includes sane defaults for an enterprise Drupal 9 site. This includes default settings, utility modules with default configuration, environment-specific configuration via Config Split, Quicksilver automation scripts, local development tooling, and basic tests.

## Key Project Information

[![CircleCI](https://circleci.com/gh/pantheon-systems/dbridge-drupal-backend-demo.svg?style=shield&circle-token=5f2515763a969ddbcb1eaf64bb025986f5d904c0)](https://circleci.com/gh/pantheon-systems/dbridge-drupal-backend-demo)
[![Pantheon Dashboard demo-decoupled-bridge](https://img.shields.io/badge/dashboard-demo_decoupled_bridge-yellow.svg)](https://dashboard.pantheon.io/sites/012f039b-c885-4391-a277-1968da264cae#dev/code)
[![Pantheon Dev Site demo-decoupled-bridge](https://img.shields.io/badge/site-demo_decoupled_bridge-blue.svg)](http://dev-demo-decoupled-bridge.pantheonsite.io/)


## Quickstart
1. Clone this repo: 
```
git clone git@github.com:pantheon-systems/dbridge-drupal-backend-demo.git
```
2. Install composer dependencies
```
cd <your repo> && composer install
```
3. Copy local settings file from template: 
```
cp web/sites/default/default.settings.local.php web/sites/default/settings.local.php
```
43. Start local developmen environment
```
lando start
```


## Local Development
This repo includes a .lando.yml file which is pre-configured. You simply just need to run `lando start` in the directory where the .lando.yml file resides and this will spin up a local Pantheon environment which closely mirrors the architecture of Pantheon hosting. The first time you run `lando start` you will be prompted to enter a Pantheon token if you don't have one set up already on your machine. Once lando successfully starts your project, you should see output in your terminal similar to the below:

```
 NAME                  demo-decoupled-bridge
 LOCATION              /Users/lindseycatlett/sites/demo-decoupled-bridge
 SERVICES              appserver_nginx, appserver, database, cache, edge_ssl, edge, index
 APPSERVER_NGINX URLS  https://localhost:51725
                       http://localhost:51726
 EDGE_SSL URLS         https://localhost:51730
 EDGE URLS             http://localhost:51729
                       http://demo-decoupled-bridge.lndo.site/
                       https://demo-decoupled-bridge.lndo.site/
```


## Helpful Local Development commands
Sync dev environment database to local environment:
```
lando pull --database=dev --files=none --code=none
```


Sync dev environment database and files to local environment:
```
lando pull --database=dev --files=dev --code=none
```





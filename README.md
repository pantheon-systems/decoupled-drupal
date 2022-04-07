# Composer-enabled Decoupled Drupal Project

This is Pantheon's recommended starting point for creating new decoupled Drupal 9 backend sites. It builds on Pantheon's default Drupal 9 upstream to provide best practices, default configuration, and accellerator tools for using drupal 9 as a backend in decoupled archictures. 

Unlike with earlier Pantheon upstreams, files such as Drupal Core that you are
unlikely to adjust while building sites are not in the main branch of the 
repository. Instead, they are referenced as dependencies that are installed by
Composer.

## Manage secret for preview sites

Install [terminus-secrets-plugin](https://github.com/pantheon-systems/terminus-secrets-plugin):

```
terminus self:plugin:install pantheon-systems/terminus-secrets-plugin
terminus self:plugin:reload
```
After that use the following terminus commands to set a secret value in your pantheon site.

To set a secret:
```
terminus secrets:set siteName.env preview.secret value
```
For example:
```
terminus secrets:set decoupled-drupal.dev preview.secret mySecret
```

The key name for the secret should be `preview.secret` as it overwrite the value for the preview site config entity in [decoupled.settings.php](web/sites/default/decoupled.settings.php)

Get complete list of available commands for terminus-secrets-plugin:
```
terminus list secret
```

## Contributing

Contributions are welcome in the form of GitHub pull requests. However, the
`pantheon-upstreams/decoupled-drupal-project` repository is a mirror that does not
directly accept pull requests.

Instead, to propose a change, please fork [pantheon-systems/decoupled-drupal-project](https://github.com/pantheon-systems/decoupled-drupal-project)
and submit a PR to that repository.

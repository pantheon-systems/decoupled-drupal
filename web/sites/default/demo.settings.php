<?php

  /**
   * @file
   * Settings customizations for decoupled demo site.
   */
use Symfony\Component\Console\Input\ArgvInput;

/**
 * Useful variables
 */

$repo_root = dirname(DRUPAL_ROOT);

/**
 * Pantheon envs.
 */
$is_pantheon_env = isset($_ENV['PANTHEON_ENVIRONMENT']);
$pantheon_env = $is_pantheon_env ? $_ENV['PANTHEON_ENVIRONMENT'] : NULL;
$is_pantheon_dev_env = $pantheon_env == 'dev';
$is_pantheon_stage_env = $pantheon_env == 'test';
$is_pantheon_prod_env = $pantheon_env == 'live';

/**
 * Local env.
 */
$is_local_env = !$is_pantheon_env && !$is_ci_env;


/**
 * Environment Indicator settings.
 */

/**
 * Config split settings.
 */

// Configuration directories.
$settings['config_sync_directory'] = $repo_root . "/config/default";

$config_directories['sync'] = $repo_root . "/config/default";

$split_filename_prefix = 'config_split.config_split';
$split_filepath_prefix = $config_directories['sync'] . '/' . $split_filename_prefix;



/**
 * Set environment splits.
 */
$split_envs = [
  'local',
  'dev',
  'test',
  'live',
  'ci'
];

// Disable all split by default.
foreach ($split_envs as $split_env) {
  $config["$split_filename_prefix.$split_env"]['status'] = FALSE;
}

// Enable env splits.
// Do not set $split unless it is unset. This allows prior scripts to set it.
if (!isset($split)) {
  $split = 'none';

  // Local envs.
  if ($is_local_env) {
    $split = 'local';
  }

  // Acquia only envs.

  if ($is_pantheon_env) {
    if ($pantheon_env == 'live') {
      $split = 'live';
    }
    elseif ($pantheon_env == 'test') {
      $split = 'test';
    }
    elseif ($pantheon_env == 'dev') {
      $split = 'dev';
    }
  }
}

// Enable the environment split only if it exists.
if ($split != 'none') {
  $config["$split_filename_prefix.$split"]['status'] = TRUE;
}

 /**
 * Redis settings.
 */





/**
 * Load universal local development override configuration, if available.
 */
  
  if ($is_local_env) {
    //Add local settings here
  
  }



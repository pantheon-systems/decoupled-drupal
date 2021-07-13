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
$is_local_env = $pantheon_env == 'lando';

/**
 * CI envs.
 */
$is_circle_env = isset($_ENV['CIRCLECI']);
$is_ci_env = $is_circle_env || isset($_ENV['CI']);;


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
 * Environment Indicator settings.
 */
$config['environment_indicator_overwrite'] = TRUE;
$config['environment_indicator.indicator']['fg_color'] = '#ffffff';


if ($is_local_env) {
  $config['environment_indicator.indicator']['name'] = 'Local';
  $config['environment_indicator.indicator']['bg_color'] = '#3363aa';
}

if ($is_pantheon_dev_env){
  $config['environment_indicator.indicator']['bg_color'] = '#33aa3c';
}

if($is_pantheon_stage_env) {
  $config['environment_indicator.indicator']['bg_color'] = '#ffBB00';
}

if ($is_pantheon_prod_env) {
  $config['environment_indicator.indicator']['bg_color'] = '#aa3333';
}





/**
 * Load universal local development override configuration, if available.
 */
  
  if ($is_local_env) {
    //Add local settings here
  
  }



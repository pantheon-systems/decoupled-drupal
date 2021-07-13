<?php

  /**
   * @file
   * Settings customizations for decoupled demo site.
   */

/**
 * Pantheon envs.
 */
$is_pantheon_env = isset($_ENV['PANTHEON_ENVIRONMENT']);
$pantheon_env = $is_pantheon_env ? $_ENV['PANTHEON_ENVIRONMENT'] : NULL;
$is_pantheon_dev_env = $pantheon_env == 'dev';
$is_pantheon_stage_env = $pantheon_env == 'test';
$is_pantheon_prod_env = $pantheon_env == 'live';

/**
 * Environment Indicator settings.
 */

/**
 * Config split settings.
 */


 /**
 * Redis settings.
 */



/**
 * Local envs.
 */
$is_local_env = !$is_pantheon_env && !$is_ci_env;

/**
 * Load universal local development override configuration, if available.
 */
  
  if ($is_local_env) {
    //Add local settings here
  
  }



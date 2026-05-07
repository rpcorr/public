<?php
/**
 * Admin Notices for Required Plugins
 *
 * - Displays an error notice if the required ACF plugin is not active.
 * - Only visible to users with plugin activation capabilities.
 *
 * @package NLSA_Theme
 * @since 1.0.0
 */

function nlsa_require_plugins_notice() {

  // Only show in WP admin dashboard and to users who can activate plugins
  if (!current_user_can('activate_plugins')) {
    return;
  }

  // Check for ACF plugin
  if (!defined('ACF_VERSION')) {
    echo '<div class="notice notice-error"><p>';
    echo '<strong>NLSA Theme:</strong> This theme requires the ';
    echo '<strong>Advanced Custom Fields (ACF)</strong> plugin. ';
    echo 'Please install and activate it.';
    echo '</p></div>';
  }
}

add_action('admin_notices', 'nlsa_require_plugins_notice');

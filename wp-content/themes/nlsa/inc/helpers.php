<?php

/**
 * Theme Helpers
 *
 * - Contains utility functions for the theme, such as safe ACF field retrieval.
 * - Centralizes common logic to keep templates clean and maintainable.
 *
 * @package NLSA_Theme
 * @since 1.0.0
 */

function nlsa_get_field($field, $post_id = false) {

  if (!function_exists('get_field')) {
    return null;
  }

  return get_field($field, $post_id);
}
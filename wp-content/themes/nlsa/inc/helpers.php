<?php

/**
 * Theme Helpers
 *
 * - Contains utility functions for the theme, including an optimized ACF field retrieval helper.
 * - nlsa_get_field() uses cached get_fields() when possible for performance.
 * - Falls back to get_field() for compatibility with all ACF contexts (repeaters, dynamic queries, etc.).
 * - Centralizes common logic to keep templates clean and maintainable.
 *
 * @package NLSA_Theme
 * @since 1.0.0
 */

function nlsa_get_field($field, $post_id = false) {

  if (!function_exists('get_field')) {
    return null;
  }

  /**
   * If no specific post ID is passed,
   * try using a cached full field set for the current page
   */
  static $cache = null;
  static $cache_post_id = null;

  $current_id = $post_id ?: get_the_ID();

  if ($post_id === false) {

    if ($cache === null || $cache_post_id !== $current_id) {
      $cache = function_exists('get_fields')
        ? (get_fields($current_id) ?: [])
        : [];

      $cache_post_id = $current_id;
    }

    if (isset($cache[$field])) {
      return $cache[$field];
    }
  }

  /**
   * Fallback to normal ACF lookup (for non-cached or repeater contexts)
   */
  return get_field($field, $post_id);
}

function nlsa_get_image_alt($image, $fallback = 'Image') {
  if (is_array($image)) {
    $alt = $image['alt'] ?? '';
  } elseif (is_numeric($image)) {
    $alt = get_post_meta($image, '_wp_attachment_image_alt', true);
  } else {
    $alt = '';
  }

  return $alt !== '' ? $alt : $fallback;
}
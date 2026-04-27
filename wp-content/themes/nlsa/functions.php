<?php

function nlsa_enqueue_assets() {

  /* ================== STYLES ================== */

  // Google Fonts
  wp_enqueue_style(
    'nlsa-google-fonts',
    'https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap',
    [],
    null
  );

  // Font Awesome
  wp_enqueue_style(
    'nlsa-font-awesome',
    'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css',
    [],
    '6.4.2'
  );

  // Theme CSS (ordered from low → high specificity)
  wp_enqueue_style(
    'nlsa-reset',
    get_theme_file_uri('/assets/css/reset.css'),
    [],
    '1.0'
  );

  wp_enqueue_style(
    'nlsa-variables',
    get_theme_file_uri('/assets/css/variables.css'),
    ['nlsa-reset'],
    '1.0'
  );

  wp_enqueue_style(
    'nlsa-base',
    get_theme_file_uri('/assets/css/base.css'),
    ['nlsa-variables'],
    '1.0'
  );

  wp_enqueue_style(
    'nlsa-layouts',
    get_theme_file_uri('/assets/css/layouts.css'),
    ['nlsa-base'],
    '1.0'
  );

  wp_enqueue_style(
    'nlsa-components',
    get_theme_file_uri('/assets/css/components.css'),
    ['nlsa-layouts'],
    '1.0'
  );

  wp_enqueue_style(
    'nlsa-navigation',
    get_theme_file_uri('/assets/css/navigation.css'),
    ['nlsa-components'],
    '1.0'
  );

  wp_enqueue_style(
    'nlsa-utilities',
    get_theme_file_uri('/assets/css/utilities.css'),
    ['nlsa-navigation'],
    '1.0'
  );

  /* ================== SCRIPTS ================== */

  wp_enqueue_script(
    'nlsa-scripts',
    get_theme_file_uri('/assets/js/scripts.js'),
    [],
    '1.0',
    true // load in footer
  );

  // WordPress threaded comments support (only when needed)
  if (is_singular() && comments_open() && get_option('thread_comments')) {
    wp_enqueue_script('comment-reply');
  }
}

add_action('wp_enqueue_scripts', 'nlsa_enqueue_assets');

add_theme_support('title-tag');
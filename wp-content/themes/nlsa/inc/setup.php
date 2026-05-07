<?php

/**
 * Theme Setup
 *
 * - Initializes theme features and supports.
 * - Registers navigation menus.
 * - Loads text domain for translations.
 *
 * @package NLSA_Theme
 * @since 1.0.0
 */

if ( !function_exists( 'nlsa_theme_setup' ) ) {

  /* ================== THEME SETUP ================== */
  function nlsa_theme_setup() {

    // Load translations
    load_theme_textdomain( 'nlsa', get_template_directory() . '/languages' );

    // Enable dynamic <title> tag support
    add_theme_support('title-tag');

    // Enable featured images
    add_theme_support('post-thumbnails');

    // Enable HTML5 markup support
    add_theme_support( 'html5', array('search-form', 'comment-form', 'comment-list', 'gallery', 'caption') );

    // Enable selective refresh in Customizer
    add_theme_support( 'customize-selective-refresh-widgets' );

    // Enable responsive embeds (videos, iframes)
    add_theme_support( 'responsive-embeds' );

    // Register navigation menus
    register_nav_menus(
      array(
        'primary' => esc_html__('Primary Menu', 'nlsa'),
      )
    );
  }
}

// Hook theme setup
add_action('after_setup_theme', 'nlsa_theme_setup');

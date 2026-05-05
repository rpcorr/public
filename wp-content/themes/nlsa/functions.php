<?php
/**
 * Theme Functions File
 *
 * This file contains core setup and functionality for the NLSA theme.
 *
 * Responsibilities:
 * - Theme setup (supports, menus, translations)
 * - Asset registration and enqueueing (CSS/JS)
 * - Custom navigation walker for primary menu
 * - Reusable helper functions (e.g. SVG icon system)
 *
 * Notes:
 * - Keep this file focused on theme-level functionality only.
 * - Business logic or complex features should be moved into /inc/ or modules if needed.
 * - All assets are loaded via wp_enqueue_scripts for proper dependency handling.
 *
 * @package NLSA_Theme
 * @since 1.0.0
 */

if ( !function_exists( 'nlsa_theme_setup' ) ) {
  /* ================== THEME SETUP ================== */
  function nlsa_theme_setup() {


    load_theme_textdomain( 'nlsa', get_template_directory() . '/languages' );

    // Enable support for dynamic document titles
    add_theme_support('title-tag');

    // Enable support for featured images (post thumbnails)
    add_theme_support('post-thumbnails');

    add_theme_support( 'html5', array('search-form', 'comment-form', 'comment-list', 'gallery', 'caption') );

    add_theme_support( 'customize-selective-refresh-widgets' );

    add_theme_support( 'responsive-embeds' );

    // Register a primary navigation menu
    register_nav_menus(
      array(
        'primary' => esc_html__('Primary Menu', 'nlsa'),
      )
    );
  }

}

add_action('after_setup_theme', 'nlsa_theme_setup');

if (!function_exists('nlsa_assets')) {
  function nlsa_assets() {

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
}

add_action('wp_enqueue_scripts', 'nlsa_assets');

class NLSA_Walker_Nav_Menu extends Walker_Nav_Menu {

  private $submenu_count = 0;
  private $current_item = null;

  // OPEN <ul>
  function start_lvl(&$output, $depth = 0, $args = null) {

    $this->submenu_count++;

    $classes = ($depth === 0)
      ? 'submenu'
      : 'submenu submenu--nested';

    // Fallback label
    $label = 'Submenu ' . $this->submenu_count;

    // Use parent title if available
    if ($this->current_item) {
      $label = esc_attr($this->current_item->title . ' submenu');
    }

    // ID only for first level
    $submenu_id = '';

    if ($depth === 0 && $this->current_item) {
      $submenu_id = ' id="submenu-' . esc_attr($this->current_item->ID) . '"';
    }

    $output .= '<ul' . $submenu_id . ' class="' . esc_attr($classes) . '" aria-label="' . $label . '">';
  }

  function end_lvl(&$output, $depth = 0, $args = null) {
    $output .= '</ul>';
  }

  // START ITEM
  function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {

    $this->current_item = $item;

    $classes = empty($item->classes) ? [] : (array) $item->classes;
    $has_children = in_array('menu-item-has-children', $classes, true);

    // ---------- <li> ----------
    $li_classes = [];

    if ($depth === 0) {
      $li_classes[] = 'site-nav__item';
    }

    if ($has_children) {
      $li_classes[] = 'has-submenu';
    }

    $li_attr = !empty($li_classes)
      ? ' class="' . esc_attr(implode(' ', $li_classes)) . '"'
      : '';

    $output .= '<li' . $li_attr . '>';

    // ---------- LINK CLASSES ----------

    // Detect user-defined classes (ignore WP defaults)
    $user_classes = array_filter($classes, function($class) {
      return !empty($class)
        && !str_starts_with($class, 'menu-item')
        && !str_starts_with($class, 'current')
        && !str_starts_with($class, 'page');
    });

    // If user defined classes → use ONLY those
    if (!empty($user_classes)) {
      $link_classes = $user_classes;
    } else {
      // Default classes
      $link_classes = ($depth === 0)
        ? ['site-nav__link']
        : ['submenu__link'];
    }

    // Add submenu toggle ONLY if no custom classes
    if ($has_children && empty($user_classes)) {
      $link_classes[] = 'submenu-toggle';
    }

    $link_classes = implode(' ', array_unique($link_classes));

    // ---------- ARIA ----------
    $aria = '';

    if ($has_children) {
      $aria = ' aria-haspopup="true" aria-expanded="false"';

      if ($depth === 0) {
        $aria .= ' aria-controls="submenu-' . esc_attr($item->ID) . '"';
      }
    }

    // role="button" only for top-level toggles without custom classes
    $role = ($has_children && $depth === 0 && empty($user_classes))
      ? ' role="button"'
      : '';

    // ---------- TARGET ----------
    $target = '';
    $rel = '';

    if (!empty($item->target) && $item->target === '_blank') {
      $target = ' target="_blank"';
      $rel = ' rel="noopener noreferrer"';
    }

    // ---------- ARIA CURRENT ----------
    $aria_current = '';

    if (
      in_array('current-menu-item', $classes) ||
      in_array('current_page_item', $classes)
    ) {
      $aria_current = ' aria-current="page"';
    }

    // ---------- OUTPUT ----------
    $output .= '<a href="' . esc_url($item->url) . '"'
            . $role
            . ' class="' . esc_attr($link_classes) . '"'
            . $aria
            . $aria_current
            . $target
            . $rel
            . '>';

    $output .= '<span class="link-text">' . esc_html($item->title) . '</span>';
    $output .= '</a>';
  }

  function end_el(&$output, $item, $depth = 0, $args = null) {
    $output .= '</li>';
  }
}


if (!function_exists('nlsa_get_icon')) {
  function nlsa_get_icon($name) {

    $icons = [
      'user_group' => '<svg aria-hidden="true" focusable="false" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>',

      'megaphone' => '<svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 11l18-8v18L3 13z"></path></svg>',

      'heart' => '<svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.6l-1-1a5.5 5.5 0 0 0-7.8 7.8l1 1L12 21l7.8-7.6 1-1a5.5 5.5 0 0 0 0-7.8z"></path></svg>',

      'calendar' => '<svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line></svg>',

      'info' => '<svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>',
    ];

    $svg = $icons[$name] ?? '';

    if (!$svg) return '';

    $svg = str_replace(
      '<svg',
      '<svg class="icon icon-' . esc_attr($name) . '"',
      $svg
    );

    return $svg;
  }
}


/* ================== ADMIN NOTICES ================== */

/**
 * Warn admin if required plugins are missing
 */
function nlsa_require_plugins_notice() {

  // Only show in WP admin dashboard
  if (!current_user_can('activate_plugins')) {
    return;
  }

  // Check for ACF
  if (!defined('ACF_VERSION')) {

    echo '<div class="notice notice-error"><p>';
    echo '<strong>NLSA Theme:</strong> This theme requires the ';
    echo '<strong>Advanced Custom Fields (ACF)</strong> plugin. ';
    echo 'Please install and activate it.';
    echo '</p></div>';
  }
}

add_action('admin_notices', 'nlsa_require_plugins_notice');


/**
 * Safe ACF field getter
 * Prevents fatal errors if ACF is not active
 */
function nlsa_get_field($field, $post_id = false) {

  if (!function_exists('get_field')) {
    return null;
  }

  return get_field($field, $post_id);
}


function nlsa_customize_footer($wp_customize) {

  // ================== FOOTER SECTION ==================
  $wp_customize->add_section('nlsa_footer_section', [
    'title'    => __('Footer Settings', 'nlsa'),
    'priority' => 120,
  ]);

  // Newsletter title
  $wp_customize->add_setting('nlsa_footer_newsletter_title', [
    'default' => 'Subscribe to Our Newsletter',
    'sanitize_callback' => 'sanitize_text_field',
  ]);

  $wp_customize->add_control('nlsa_footer_newsletter_title', [
    'label'   => __('Newsletter Title', 'nlsa'),
    'section' => 'nlsa_footer_section',
    'type'    => 'text',
  ]);

  // Get in touch title
  $wp_customize->add_setting('nlsa_footer_get_in_touch_title', [
  'default'           => 'Get in Touch',
  'sanitize_callback' => 'sanitize_text_field',
  ]);

  $wp_customize->add_control('nlsa_footer_get_in_touch_title', [
  'label'   => __('Get In Touch Title', 'nlsa'),
  'section' => 'nlsa_footer_section',
  'type'    => 'text',
  ]);

  // Facebook URL
  $wp_customize->add_setting('nlsa_facebook_url', [
    'default' => 'https://www.facebook.com/groups/535643736920153/',
    'sanitize_callback' => 'esc_url_raw',
  ]);

  $wp_customize->add_control('nlsa_facebook_url', [
    'label'   => __('Facebook URL', 'nlsa'),
    'section' => 'nlsa_footer_section',
    'type'    => 'url',
  ]);

  // Instagram URL
  $wp_customize->add_setting('nlsa_instagram_url', [
    'default' => 'https://www.instagram.com/nlstuttering/',
    'sanitize_callback' => 'esc_url_raw',
  ]);

  $wp_customize->add_control('nlsa_instagram_url', [
    'label'   => __('Instagram URL', 'nlsa'),
    'section' => 'nlsa_footer_section',
    'type'    => 'url',
  ]);

  // Copyright text
  $wp_customize->add_setting('nlsa_footer_copyright_text', [
  'default'           => 'All rights reserved.',
  'sanitize_callback' => 'sanitize_text_field',
  ]);

  $wp_customize->add_control('nlsa_footer_copyright_text', [
  'label'   => __('Copyright Text', 'nlsa'),
  'section' => 'nlsa_footer_section',
  'type'    => 'text',
  ]);
}

add_action('customize_register', 'nlsa_customize_footer');

// Custom login error message to prevent username enumeration
function nlsa_login_error_message() {
    return 'We couldn\'t log you in. Please check your details and try again.';
}
add_filter('login_errors', 'nlsa_login_error_message');
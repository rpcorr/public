<?php

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
    $has_children = in_array('menu-item-has-children', $classes);

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
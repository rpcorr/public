<?php
/**
 * Theme Functions File
 *
 * Core setup and functionality for the NLSA theme.
 *
 * Responsibilities:
 * - Theme setup (supports, menus, translations)
 * - Asset registration and enqueueing (CSS/JS, including login screen styles)
 * - Custom navigation walker for primary menu (accessible, multi-level)
 * - Customizer settings (footer content and theme options)
 * - Reusable helper functions (e.g. SVG icon system, safe ACF access)
 * - Admin notices for required plugins
 * - Security enhancements (e.g. generic login error messages)
 * - Login page customization:
 *     - Branding (logo styling and appearance)
 *     - Login logo URL override (redirects to site/blog instead of wordpress.org)
 *     - Accessibility text for login branding
 *
 * Notes:
 * - Keep this file focused on theme-level functionality only.
 * - Business logic or complex features should be moved into /inc/ or modules if needed.
 * - All assets are loaded via wp_enqueue_scripts or appropriate hooks.
 * - Avoid direct output where possible; prefer hooks and filters.
 * - Ensure accessibility (ARIA, semantics) is preserved when customizing UI.
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

if (!function_exists('nlsa_assets')) {

   /* ================== ASSETS ================== */
  /**
   * Enqueue theme styles and scripts
   *
   * Ensures proper dependency order and avoids hardcoding in templates
   */
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
    wp_enqueue_style('nlsa-reset', get_theme_file_uri('/assets/css/reset.css'), [], '1.0');
    wp_enqueue_style('nlsa-variables', get_theme_file_uri('/assets/css/variables.css'), ['nlsa-reset'], '1.0');
    wp_enqueue_style('nlsa-base', get_theme_file_uri('/assets/css/base.css'), ['nlsa-variables'], '1.0');
    wp_enqueue_style('nlsa-layouts', get_theme_file_uri('/assets/css/layouts.css'), ['nlsa-base'], '1.0');
    wp_enqueue_style('nlsa-components', get_theme_file_uri('/assets/css/components.css'), ['nlsa-layouts'], '1.0');
    wp_enqueue_style('nlsa-navigation', get_theme_file_uri('/assets/css/navigation.css'), ['nlsa-components'], '1.0');
    wp_enqueue_style('nlsa-utilities', get_theme_file_uri('/assets/css/utilities.css'), ['nlsa-navigation'], '1.0');

    /* ================== SCRIPTS ================== */

    wp_enqueue_script(
      'nlsa-scripts',
      get_theme_file_uri('/assets/js/scripts.js'),
      [],
      '1.0',
      true // load in footer
    );

    // Load comment reply script only when needed
    if (is_singular() && comments_open() && get_option('thread_comments')) {
      wp_enqueue_script('comment-reply');
    }
  }
}

// Hook assets
add_action('wp_enqueue_scripts', 'nlsa_assets');


/**
 * Custom navigation walker
 *
 * Handles:
 * - Accessible submenu structure (ARIA attributes)
 * - Conditional class handling
 * - Multi-level navigation rendering
 */
class NLSA_Walker_Nav_Menu extends Walker_Nav_Menu {

  private $submenu_count = 0;
  private $current_item = null;

  // OPEN <ul>
  function start_lvl(&$output, $depth = 0, $args = null) {

    $this->submenu_count++;

    $classes = ($depth === 0)
      ? 'submenu'
      : 'submenu submenu--nested';

    // Default fallback label
    $label = 'Submenu ' . $this->submenu_count;

    // Use parent title for accessibility
    if ($this->current_item) {
      $label = esc_attr($this->current_item->title . ' submenu');
    }

    // Only first-level submenu gets ID
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
    // Strip default WP classes; keep user-defined ones only
    $user_classes = array_filter($classes, function($class) {
      return !empty($class)
        && !str_starts_with($class, 'menu-item')
        && !str_starts_with($class, 'current')
        && !str_starts_with($class, 'page');
    });

    if (!empty($user_classes)) {
      $link_classes = $user_classes;
    } else {
      $link_classes = ($depth === 0)
        ? ['site-nav__link']
        : ['submenu__link'];
    }

    // Add toggle class if submenu exists
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

/**
 * Returns inline SVG icons by name
 */
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

/**
 * Display admin notice if required plugins are missing
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


/**
 * Safe wrapper for ACF get_field()
 */
function nlsa_get_field($field, $post_id = false) {

  if (!function_exists('get_field')) {
    return null;
  }

  return get_field($field, $post_id);
}

/**
 * ACF Local JSON - Save field groups to theme
 */
add_filter('acf/settings/save_json', function () {
    return get_stylesheet_directory() . '/acf-json';
});

/**
 * ACF Local JSON - Load field groups from theme
 */
add_filter('acf/settings/load_json', function ($paths) {

    // remove default path (optional but cleaner)
    unset($paths[0]);

    // add your theme path
    $paths[] = get_stylesheet_directory() . '/acf-json';

    return $paths;
});

/**
 * Register Customizer settings for footer content
 */
function nlsa_customize_footer($wp_customize) {

  // Footer section
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

/* ================== LOGIN SCREEN CUSTOMIZATION ================== */

/**
 * Override login error messages to prevent username enumeration
 */
function nlsa_login_error_message() {
    return 'We couldn\'t log you in. Please check your details and try again.';
}
add_filter('login_errors', 'nlsa_login_error_message');

// Custom login page styling
function nlsa_login_logo() {
    wp_enqueue_style(
        'nlsa-login',
        get_theme_file_uri('/assets/css/login.css'),
        [],
        '1.0'
    );
}
add_action('login_enqueue_scripts', 'nlsa_login_logo');

// Login logo link -> your blog
add_filter('login_headerurl', function () {
    return home_url('/');
});

// Login logo tooltip text
add_filter('login_headertext', function () {
    return 'Go to our Website';
});


// Allow editors to manage menus
function nlsa_allow_editors_manage_menus() {
    $role = get_role('editor');

    if ($role && !$role->has_cap('edit_theme_options')) {
        $role->add_cap('edit_theme_options');
    }
}
add_action('init', 'nlsa_allow_editors_manage_menus');


// Lock editors to only edit the Primary Menu (ID = 3)
function nlsa_lock_editor_to_primary_menu() {
    if (!current_user_can('editor') || current_user_can('administrator')) {
        return;
    }

    $allowed_menu_id = 3;
    ?>
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const menuSelect = document.querySelector('#menu');

            

            if (menuSelect) {
                menuSelect.value = "<?php echo $allowed_menu_id; ?>";
                menuSelect.style.pointerEvents = 'none';
                menuSelect.style.backgroundColor = '#eee';
            }

            const form = document.querySelector('#update-nav-menu');

            if (form) {
                form.addEventListener('submit', function () {
                    let menuInput = document.querySelector('input[name="menu"]');

                    if (!menuInput) {
                        menuInput = document.createElement('input');
                        menuInput.type = 'hidden';
                        menuInput.name = 'menu';
                        form.appendChild(menuInput);
                    }

                    menuInput.value = "<?php echo $allowed_menu_id; ?>";
                });
            }
        });
    </script>
    <?php
}
add_action('admin_footer-nav-menus.php', 'nlsa_lock_editor_to_primary_menu');


// Prevent editors from deleting menus
function nlsa_prevent_menu_deletion($menu_id) {
    if (current_user_can('editor') && !current_user_can('administrator')) {
        wp_die('You are not allowed to delete menus.');
    }
}
add_action('wp_delete_nav_menu', 'nlsa_prevent_menu_deletion');

// Clean up the menu editor UI for editors (hide delete button, switching menus, and settings)
function nlsa_clean_menu_ui_for_editors() {
    if (!current_user_can('editor') || current_user_can('administrator')) {
        return;
    }

    echo '<style>

        /* ===============================
          CUSTOM MESSAGE (NO FLICKER)
        =============================== */

        .manage-menus .add-edit-menu-action {
            display: none !important;
        }

        .manage-menus {
            border-left: 4px solid #0073aa;
            padding-left: 12px;
            margin-bottom: 10px;
        }

        .manage-menus::before {
            content: "Edit your menu below and do not forget to save your changes!";
            display: block;
            font-weight: 500;
        }

        /* ===============================
          HIDE MENU SETTINGS
        =============================== */
        .menu-settings {
            display: none !important;
        }

        /* ===============================
          HIDE MANAGE LOCATIONS
        =============================== */
        #nav-menu-theme-locations,
        .nav-menu-locations,
        .menu-locations {
            display: none !important;
        }

        /* ===============================
          HIDE DELETE MENU (ONLY MENU, NOT ITEMS)
        =============================== */
        #delete-action,
        #delete-menu-action,
        .delete-action {
            display: none !important;
        }

        /* ===============================
          HIDE MENU SWITCHING TABS
        =============================== */
        .nav-tab-wrapper a:not(.nav-tab-active) {
            display: none !important;
        }

        /* ===============================
          DISABLE MENU NAME EDITING
        =============================== */
        #menu-name {
            pointer-events: none !important;
            background: #f1f1f1 !important;
            color: #666 !important;
        }

        label[for="menu-name"] {
            opacity: 0.5;
        }

    </style>';
}
add_action('admin_head-nav-menus.php', 'nlsa_clean_menu_ui_for_editors');

// Force editors to always edit the Primary Menu (ID = 3) on the backend
function nlsa_force_primary_menu_backend($menu_id) {
    if (current_user_can('editor') && !current_user_can('administrator')) {
        return 3; // always force Primary Menu
    }
    return $menu_id;
}

add_filter('wp_edit_nav_menu_walker', function($walker, $menu_id) {
    if (current_user_can('editor') && !current_user_can('administrator')) {
        return $walker;
    }
    return $walker;
}, 10, 2);

// Hide "Add Menu" button for editors to prevent creating new menus
function nlsa_hide_add_menu_button() {
    if (current_user_can('editor') && !current_user_can('administrator')) {
        echo '<style>
            .page-title-action { display: none !important; }
        </style>';
    }
}
add_action('admin_head-nav-menus.php', 'nlsa_hide_add_menu_button');


function nlsa_disable_menu_name_for_editors( $args ) {
    if ( current_user_can('editor') && ! current_user_can('administrator') ) {

        // Remove menu name field entirely
        add_filter('wp_nav_menu_manage_columns', '__return_empty_array');

        // Disable menu name input rendering
        add_action('admin_footer-nav-menus.php', function () {
            echo '<style>
                #menu-name {
                    pointer-events: none;
                    background: #f1f1f1;
                }

                label[for="menu-name"] {
                    opacity: 0.6;
                }
            </style>';
        });
    }

    return $args;
}

// Block saving menu location changes for editors
function nlsa_lock_menu_locations_save($value) {

    if (current_user_can('editor') && !current_user_can('administrator')) {
        return get_theme_mod('nav_menu_locations'); // keep existing
    }

    return $value;
}
add_filter('pre_set_theme_mod_nav_menu_locations', 'nlsa_lock_menu_locations_save');

// Hide menu location settings from editors in the Customizer
function nlsa_disable_menu_locations_customizer($wp_customize) {
    if (current_user_can('editor') && !current_user_can('administrator')) {

        // Remove the whole section
        $wp_customize->remove_section('menu_locations');
    }
}
add_action('customize_register', 'nlsa_disable_menu_locations_customizer', 100);

// Additional CSS fallback to hide menu location settings in case the section is still rendered
function nlsa_hide_menu_locations_customizer_ui() {
    if (current_user_can('editor') && !current_user_can('administrator')) {
        echo '<style>
            #accordion-section-menu_locations {
                display: none !important;
            }
        </style>';
    }
}
add_action('customize_controls_print_styles', 'nlsa_hide_menu_locations_customizer_ui');

// Hide "Add Menu" button in Customizer for editors
function nlsa_hide_create_menu_button_customizer() {
    if (current_user_can('editor') && !current_user_can('administrator')) {
        echo '<style>
            /* Hide "Create New Menu" button */
            .customize-control-create-nav-menu {
                display: none !important;
            }

            /* Hide "Create New Menu" inside panel (fallback) */
            .customize-pane-child .customize-control-create-nav-menu {
                display: none !important;
            }

            /* Extra fallback for newer WP markup */
            button.create-menu,
            .button.create-menu {
                display: none !important;
            }
        </style>';
    }
}
add_action('customize_controls_print_styles', 'nlsa_hide_create_menu_button_customizer');

// Prevent editors from creating new menus via the backend
function nlsa_prevent_editor_menu_creation($term, $taxonomy) {
    if ($taxonomy === 'nav_menu' && current_user_can('editor') && !current_user_can('administrator')) {
        wp_die('You are not allowed to create menus.');
    }
}
add_action('created_term', 'nlsa_prevent_editor_menu_creation', 10, 2);

// Additional JavaScript fallback to remove "Create New Menu" button in Customizer for editors (handles dynamic rendering)
function nlsa_remove_create_menu_button_customizer_js() {
    if (current_user_can('editor') && !current_user_can('administrator')) {
        ?>
        <script>
        (function() {

            function removeCreateMenu() {
                document.querySelectorAll('button, a').forEach(el => {
                    if (el.textContent.trim() === 'Create New Menu') {
                        el.remove();
                    }
                });
            }

            // Run repeatedly for a short time (handles async rendering)
            let attempts = 0;
            const interval = setInterval(() => {
                removeCreateMenu();
                attempts++;

                if (attempts > 20) {
                    clearInterval(interval);
                }
            }, 300);

            // Also observe future DOM changes
            const observer = new MutationObserver(removeCreateMenu);
            observer.observe(document.body, {
                childList: true,
                subtree: true
            });

        })();
        </script>
        <?php
    }
}
add_action('customize_controls_print_footer_scripts', 'nlsa_remove_create_menu_button_customizer_js');

// Hide menu location settings and "Automatically add new pages" option inside the menu editor for editors in the Customizer
function nlsa_hide_menu_controls_inside_menu_customizer() {
    if (current_user_can('editor') && !current_user_can('administrator')) {
        echo '<style>

            /* Menu location */
            .menu-location-settings,
            .customize-control-nav_menu_locations {
                display: none !important;
            }

            /* Auto-add pages */
            .customize-control-nav_menu_auto_add,
            .nav-menu-auto-add,
            .auto-add-pages {
                display: none !important;
            }

            /* DELETE MENU (REAL FIX) */
            .customize-control-nav_menu_delete,
            .menu-delete,
            .menu-delete-item,
            .delete-menu,
            button[name="remove-menu"],
            button[data-action="remove-menu"],
            .customize-section .menu-delete {
                display: none !important;
            }

        </style>';
    }
}
add_action('customize_controls_print_styles', 'nlsa_hide_menu_controls_inside_menu_customizer');

// Prevent editors from changing the "Automatically add new pages" setting
function nlsa_lock_auto_add_pages($value) {
    if (current_user_can('editor') && !current_user_can('administrator')) {
        return get_theme_mod('nav_menu_options'); // keep existing
    }
    return $value;
}
add_filter('pre_set_theme_mod_nav_menu_options', 'nlsa_lock_auto_add_pages');

function nlsa_add_google_analytics() {

    // 1. Skip admins
    if (current_user_can('manage_options')) {
        return;
    }

    // 2. Only run on production
    if (wp_get_environment_type() !== 'production') {
        return;
    }

    ?>
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-5CGVTZVK02"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', 'G-5CGVTZVK02');
    </script>
    <?php
}
add_action('wp_head', 'nlsa_add_google_analytics');
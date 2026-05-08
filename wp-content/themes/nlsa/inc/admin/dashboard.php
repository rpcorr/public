<?php 

/**
 * Admin Dashboard Customizations
 *
 * Customizes the WordPress admin dashboard experience by:
 * - Adding a Google Analytics monitoring widget for administrators
 * - Removing unnecessary default dashboard widgets
 * - Simplifying the admin toolbar interface
 *
 * Features:
 * - Displays Google Analytics detection status
 * - Highlights recent GA tracking issues
 * - Removes the default WordPress "Events and News" widget
 * - Removes the WordPress logo and dropdown menu from the admin toolbar
 *
 * @package NLSA_Theme
 * @since 1.0.0
 */

/**
 * Register the custom Google Analytics dashboard widget.
 *
 * The widget is only displayed for users with manage_options capability
 * (typically administrators).
 */
add_action('wp_dashboard_setup', function () {

    if (!current_user_can('manage_options')) {
        return;
    }

    wp_add_dashboard_widget(
        'nlsa_ga_dashboard_widget',
        'Google Analytics Status',
        'nlsa_render_ga_dashboard_widget'
    );
});

/**
 * Render the Google Analytics dashboard widget content.
 *
 * Displays the current GA detection status based on frontend monitoring.
 * Shows alerts for recent tracking failures and provides diagnostic details
 * including timestamp and affected page URL when available.
 */
function nlsa_render_ga_dashboard_widget() {

    $data = get_option('nlsa_ga_status');

    $status = $data['status'] ?? 'ok';
    $time   = $data['time'] ?? null;
    $url    = $data['url'] ?? '';

    // Determine whether the issue occurred within the last 24 hours.
    $is_recent_issue = $time && (time() - $time < 86400);

    if ($status === 'missing' && $is_recent_issue) {
        echo '<p style="color:#b32d2e;"><strong>🔴 GA Not Detected</strong></p>';
        echo '<p>Google Analytics was not detected on the site.</p>';
        echo '<p><strong>Last seen:</strong> ' . date('Y-m-d H:i:s', $time) . '</p>';
        echo '<p><strong>Page:</strong> ' . esc_html($url) . '</p>';
    }

    elseif ($status === 'missing') {
        echo '<p style="color:#dba617;"><strong>🟠 Previous GA issue detected (older than 24h)</strong></p>';
        echo '<p>Check if issue is still ongoing.</p>';
    }

    else {
        echo '<p style="color:#00a32a;"><strong>🟢 Google Analytics appears OK</strong></p>';
        echo '<p>No issues detected.</p>';
    }

    echo '<hr>';
    echo '<p><small>This check is based on frontend detection of gtag + dataLayer presence.</small></p>';
}

/**
 * Remove default WordPress dashboard widgets.
 *
 * Removes the built-in "WordPress Events and News" widget
 * to provide a cleaner admin dashboard experience.
 */
function nlsa_remove_dashboard_widgets() {
    remove_meta_box('dashboard_primary', 'dashboard', 'side');
}
add_action('wp_dashboard_setup', 'nlsa_remove_dashboard_widgets');

/**
 * Remove the WordPress logo and dropdown menu
 * from the admin toolbar.
 *
 * This helps simplify the admin interface and reduce
 * unnecessary toolbar items for site administrators.
 *
 * @param WP_Admin_Bar $wp_admin_bar The WordPress admin bar instance.
 */
function nlsa_remove_wp_logo($wp_admin_bar) {
    $wp_admin_bar->remove_node('wp-logo');
}
add_action('admin_bar_menu', 'nlsa_remove_wp_logo', 999);
<?php 

/**
 * Admin Dashboard Customizations
 *
 * - Adds a dashboard widget to monitor Google Analytics status.
 * - Displays recent detection issues with timestamps and URLs.
 * - Only visible to administrators.
 *
 * @package NLSA_Theme
 * @since 1.0.0
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

function nlsa_render_ga_dashboard_widget() {

    $data = get_option('nlsa_ga_status');

    $status = $data['status'] ?? 'ok';
    $time   = $data['time'] ?? null;
    $url    = $data['url'] ?? '';

    $is_recent_issue = $time && (time() - $time < 86400); // last 24h

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
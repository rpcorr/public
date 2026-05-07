<?php

/**
 * Theme Security Enhancements
 *
 * - Customizes the login screen to prevent username enumeration.
 * - Adds custom styling to the login page for branding consistency.
 * - Ensures that error messages do not reveal sensitive information.
 *
 * @package NLSA_Theme
 * @since 1.0.0
 */

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

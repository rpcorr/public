<?php

/**
 * Theme Security Enhancements
 *
 * - Limits failed login attempts to help prevent brute-force attacks.
 * - Temporarily locks login access for 1 hour after repeated failed attempts.
 * - Displays remaining login attempts and lockout duration messages.
 * - Customizes login error messages to prevent username enumeration.
 * - Adds custom styling to the WordPress login screen for branding consistency.
 * - Customizes the login logo URL and tooltip text.
 *
 * @package NLSA_Theme
 * @since 1.0.0
 */

/* ================== LOGIN ATTEMPT LIMITER ================== */

/**
 * Configuration
 */
define('NLSA_LOGIN_MAX_ATTEMPTS', 5);
define('NLSA_LOGIN_LOCKOUT_TIME', HOUR_IN_SECONDS);

/**
 * Get user IP address
 */
function nlsa_get_user_ip() {

    // Cloudflare support
    if (!empty($_SERVER['HTTP_CF_CONNECTING_IP'])) {
        return sanitize_text_field($_SERVER['HTTP_CF_CONNECTING_IP']);
    }

    // Proxy support
    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ips = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        return sanitize_text_field(trim($ips[0]));
    }

    return sanitize_text_field($_SERVER['REMOTE_ADDR'] ?? 'unknown');
}

/**
 * Track failed login attempts
 */
function nlsa_track_failed_login($username) {

    $ip = nlsa_get_user_ip();

    $attempts_key = 'nlsa_login_attempts_' . md5($ip);
    $lockout_key  = 'nlsa_login_lockout_' . md5($ip);

    // If already locked, do nothing
    if (get_transient($lockout_key)) {
        return;
    }

    $attempts = (int) get_transient($attempts_key);
    $attempts++;

    // Lock account after max attempts
    if ($attempts >= NLSA_LOGIN_MAX_ATTEMPTS) {

        set_transient($lockout_key, time(), NLSA_LOGIN_LOCKOUT_TIME);

        delete_transient($attempts_key);

    } else {

        // Store attempts for 1 hour
        set_transient(
            $attempts_key,
            $attempts,
            NLSA_LOGIN_LOCKOUT_TIME
        );
    }
}
add_action('wp_login_failed', 'nlsa_track_failed_login');

/**
 * Prevent login if locked out
 */
function nlsa_check_login_lockout($user, $username, $password) {

    $ip = nlsa_get_user_ip();

    $lockout_key = 'nlsa_login_lockout_' . md5($ip);

    $locked = get_transient($lockout_key);

    if ($locked) {

        $remaining = NLSA_LOGIN_LOCKOUT_TIME - (time() - $locked);

        if ($remaining > 0) {

            $minutes = ceil($remaining / 60);

            return new WP_Error(
                'login_locked',
                sprintf(
                    'Too many failed login attempts. Please try again in %d minute(s).',
                    $minutes
                )
            );
        }

        delete_transient($lockout_key);
    }

    return $user;
}
add_filter('authenticate', 'nlsa_check_login_lockout', 30, 3);

/**
 * Show remaining login attempts
 */
function nlsa_login_error_message_with_attempts($error) {

    $ip = nlsa_get_user_ip();

    $attempts_key = 'nlsa_login_attempts_' . md5($ip);
    $lockout_key  = 'nlsa_login_lockout_' . md5($ip);

    // Locked out
    $locked = get_transient($lockout_key);

    if ($locked) {

        $remaining = NLSA_LOGIN_LOCKOUT_TIME - (time() - $locked);
        $minutes   = ceil($remaining / 60);

        return sprintf(
            'Too many failed login attempts. Please try again in %d minute(s).',
            $minutes
        );
    }

    // Remaining attempts
    $attempts = (int) get_transient($attempts_key);

    $remaining_attempts = NLSA_LOGIN_MAX_ATTEMPTS - $attempts;

    return sprintf(
        'We couldn\'t log you in. Please check your details and try again. %d attempt(s) remaining before temporary lockout.',
        $remaining_attempts
    );
}
add_filter('login_errors', 'nlsa_login_error_message_with_attempts');


/* ================== LOGIN SCREEN CUSTOMIZATION ================== */


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

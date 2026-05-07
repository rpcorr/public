<?php
/**
 * ACF Integration
 *
 * - Configures ACF to save and load field groups from the theme directory (a json file).
 * - Ensures field groups are version-controlled with the theme.
 *
 * @package NLSA_Theme
 * @since 1.0.0
 */

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

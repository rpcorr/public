<?php
/**
 * NLSA Theme Functions
 *
 * This file contains the core functions and setup for the NLSA WordPress theme.
 * It includes theme support, asset enqueuing, navigation, customizer settings,
 * ACF integration, security hardening, analytics consent management, and admin dashboard customizations.
 *
 * @package NLSA_Theme
 * @since 1.0.0
 */

/* ================== CORE ================== */

require_once get_template_directory() . '/inc/setup.php';
require_once get_template_directory() . '/inc/assets.php';
require_once get_template_directory() . '/inc/navigation.php';
require_once get_template_directory() . '/inc/icons.php';
require_once get_template_directory() . '/inc/helpers.php';
require_once get_template_directory() . '/inc/customizer.php';
require_once get_template_directory() . '/inc/acf.php';
require_once get_template_directory() . '/inc/security.php';
require_once get_template_directory() . '/inc/analytics.php';

/* ================== ADMIN ================== */

require_once get_template_directory() . '/inc/admin/plugin-notices.php';
require_once get_template_directory() . '/inc/admin/editor-menus.php';
require_once get_template_directory() . '/inc/admin/dashboard.php';

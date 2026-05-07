<?php
/**
 * Theme Customizer Settings
 *
 * - Adds a custom section for footer settings in the WordPress Customizer.
 * - Allows admins to easily update newsletter title, contact info, social links, and copyright text.
 *
 * @package NLSA_Theme
 * @since 1.0.0
 */

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

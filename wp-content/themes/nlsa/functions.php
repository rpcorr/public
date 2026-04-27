<?php

function nlsa_enqueue_styles() {
  wp_enqueue_style('nlsa-reset', get_template_directory_uri() . '/assets/css/reset.css');
  wp_enqueue_style('nlsa-variables', get_template_directory_uri() . '/assets/css/variables.css');
  wp_enqueue_style('nlsa-base', get_template_directory_uri() . '/assets/css/base.css');
  wp_enqueue_style('nlsa-layouts', get_template_directory_uri() . '/assets/css/layouts.css');
  wp_enqueue_style('nlsa-components', get_template_directory_uri() . '/assets/css/components.css');
  wp_enqueue_style('nlsa-navigation', get_template_directory_uri() . '/assets/css/navigation.css');
  wp_enqueue_style('nlsa-utilities', get_template_directory_uri() . '/assets/css/utilities.css');
}

add_action('wp_enqueue_scripts', 'nlsa_enqueue_styles');
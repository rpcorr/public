<?php
/**
 * Theme Navigation
 *
 * - Contains the custom walker class for rendering navigation menus.
 * - Ensures accessible and semantic markup for multi-level menus.
 *
 * @package NLSA_Theme
 * @since 1.0.0
 */

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

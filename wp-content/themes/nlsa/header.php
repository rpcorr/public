<?php
/**
 * Header Template
 *
 * This file contains the <head> section and the opening markup up to the start
 * of the main content area. It is included on all pages via get_header().
 *
 * Responsibilities:
 * - Defines the document structure (<!doctype>, <html>, <head>, <body>)
 * - Outputs essential meta tags (charset, viewport, theme color)
 * - Calls wp_head() to allow WordPress core, themes, and plugins to inject scripts, styles, and meta data
 * - Renders the site header and primary navigation
 *
 * Notes:
 * - Do NOT hardcode stylesheets or scripts here; use wp_enqueue_scripts in functions.php
 * - Keep SEO/meta minimal if using an SEO plugin (it will manage most tags)
 * - Use WordPress template tags for dynamic content (e.g., bloginfo(), home_url())
 * - Ensure accessibility attributes (aria-*, skip links, etc.) remain intact
 *
 * Typical Structure:
 * <!doctype html>
 * <html <?php language_attributes(); ?>>
 * <head>
 *   <meta charset="<?php bloginfo('charset'); ?>">
 *   <meta name="viewport" content="width=device-width, initial-scale=1">
 *   <?php wp_head(); ?>
 * </head>
 * <body <?php body_class(); ?>>
 *
 * @package NLSA_Theme
 */
?>

<!doctype html>
<html <?php language_attributes(); ?>>

<head>
  <meta charset="<?php bloginfo('charset'); ?>" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <!-- Basic SEO (temporary until SEO plugin) -->
  <meta name="description" content="The Newfoundland and Labrador Stuttering Association (NLSA) supports people who stutter through advocacy, research, community events, and education." />
  <meta name="author" content="Newfoundland and Labrador Stuttering Association" />

  <!-- Open Graph -->
  <meta property="og:title" content="<?php bloginfo('name'); ?>" />
  <meta property="og:description" content="<?php bloginfo('description'); ?>" />
  <meta property="og:type" content="website" />
  <meta property="og:url" content="<?php echo esc_url(home_url('/')); ?>" />

  <!-- Theme color -->
  <meta name="theme-color" content="#0b3072" />

  <!-- Structured data -->
  <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "NonprofitOrganization",
      "name": "Newfoundland and Labrador Stuttering Association",
      "url": "https://nlstuttering.ca/",
      "logo": "https://nlstuttering.ca/assets/imgs/NLSA-logo.png"
    }
  </script>

  <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
  <!-- Skip link improves keyboard accessibility -->
  <a href="#primary" class="skip-link">Skip to content</a>

  <!-- ================== HEADER / NAVIGATION ================== -->
  <header class="site-header">
    <nav class="site-nav" aria-label="Main navigation">
      <div class="site-nav__inner">
         <!-- Show logo on all pages except front page, where the tagline is sufficient -->
        <div class="site-nav__tagline" <?php echo is_front_page() ? 'aria-hidden="true"' : ''; ?>>
            
            <?php if (!is_front_page()) : ?>
              <a href="<?php echo esc_url(home_url('/')); ?>">
                <img 
                  loading="lazy"
                  src="<?php echo esc_url(get_theme_file_uri('/assets/imgs/NLSA-logo.png')); ?>"
                  alt="<?php bloginfo('name'); ?> logo">
              </a>
            <?php endif; ?>

        </div>

        <!-- Mobile hamburger toggle -->
        <button class="hamburger" type="button" aria-label="Toggle main menu" aria-expanded="false" aria-controls="primary-menu">
          <span></span>
          <span></span>
          <span></span>
        </button>

        <!-- Primary navigation (inert by default for accessibility) -->
         <?php
            wp_nav_menu([
            'theme_location' => 'primary',
            'container'      => false,
            'menu_id'        => 'primary-menu',
            'menu_class'     => 'site-nav__list',
            'depth'          => 3,
            'fallback_cb'    => false,
            'walker'         => new NLSA_Walker_Nav_Menu(),
            ]);
          ?>

      </div>
    </nav>
  </header>

  <!-- Backdrop used for mobile menu overlay -->
  <div class="nav-backdrop"></div>
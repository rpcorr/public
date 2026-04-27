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

<body>
  <!-- Skip link improves keyboard accessibility -->
  <a href="#primary" class="skip-link">Skip to content</a>

  <!-- ================== HEADER / NAVIGATION ================== -->
  <header class="site-header">
    <nav class="site-nav" aria-label="Main navigation">
      <div class="site-nav__inner">
        <!-- Decorative tagline (hidden from screen readers) -->
        <div class="site-nav__tagline" aria-hidden="true"></div>

        <!-- Mobile hamburger toggle -->
        <button class="hamburger" type="button" aria-label="Toggle main menu" aria-expanded="false" aria-controls="primary-menu">
          <span></span>
          <span></span>
          <span></span>
        </button>

        <!-- Primary navigation (inert by default for accessibility) -->
        <ul id="primary-menu" class="site-nav__list" inert aria-hidden="true">
          <!-- Top-level link -->
          <li>
            <a href="index.html" class="site-nav__link" aria-current="page">
              <span class="link-text">Home</span>
            </a>
          </li>

          <!-- Dropdown menu -->
          <li class="site-nav__item has-submenu">
            <a href="#" role="button" class="site-nav__link submenu-toggle" aria-haspopup="true" aria-expanded="false" aria-controls="submenu-about">
              <span class="link-text">About</span>
            </a>

            <!-- Submenu -->
            <ul id="submenu-about" class="submenu" aria-label="About submenu">
              <li>
                <a href="about.html" class="submenu__link">
                  <span class="link-text">Overview</span>
                </a>
              </li>
              <li>
                <a href="team.html" class="submenu__link">
                  <span class="link-text">Our Team</span>
                </a>
              </li>

              <!-- Nested submenu -->
              <li class="has-submenu">
                <a href="#" class="submenu__link submenu-toggle" aria-haspopup="true" aria-expanded="false">
                  <span class="link-text">History</span>
                </a>

                <!-- Sub-sub menu -->
                <ul class="submenu submenu--nested" aria-label="History submenu">
                  <li>
                    <a href="early.html" class="submenu__link">
                      <span class="link-text">Early Years</span>
                    </a>
                  </li>
                  <li>
                    <a href="modern.html" class="submenu__link">
                      <span class="link-text">Modern Era</span>
                    </a>
                  </li>
                </ul>
              </li>
            </ul>
          </li>

          <!-- Standard navigation links -->
          <li>
            <a href="research.html" class="site-nav__link">
              <span class="link-text">Research</span>
            </a>
          </li>
          <li>
            <a href="get-involved.html" class="site-nav__link">
              <span class="link-text">Get Involved</span>
            </a>
          </li>
          <li>
            <a href="news.html" class="site-nav__link">
              <span class="link-text">News</span>
            </a>
          </li>

          <!-- External link (opens in new tab) -->
          <li>
            <a href="https://somestutterluh.hcommons.org/" class="site-nav__link" target="_blank" rel="noopener noreferrer">
              <span class="link-text"> Podcast </span>
            </a>
          </li>

          <li>
            <a href="contact.html" class="site-nav__link">
              <span class="link-text">Contact</span>
            </a>
          </li>

          <!-- Call-to-action -->
          <li>
            <a href="https://www.canadahelps.org/en/charities/newfoundland-and-labrador-stuttering-association-inc/" class="btn btn--cta" target="_blank" rel="noopener noreferrer" aria-label="Donate (opens in new tab)">
              Donate
            </a>
          </li>
        </ul>
      </div>
    </nav>
  </header>

  <!-- Backdrop used for mobile menu overlay -->
  <div class="nav-backdrop"></div>
<?php 
/**
 * The template for displaying 404 pages (Not Found)
 *
 * @package NLSA_Theme
 * @since 1.0.0
 */

get_header(); ?>

<main id="primary" class="wrapper">

  <section class="page-content error-404 not-found">

    <div class="error-layout">
      
      <div class="content">
        <h1>Page Not Found</h1>

        <p>This page isn’t here—but that's okay. Take a moment to collect yourself. We'll help you find your way.</p>

        <p>
          <a href="javascript:history.back()"><strong>Go back</strong></a> 
          or return to the 
          <a href="<?php echo esc_url(home_url('/')); ?>"><strong>homepage</strong></a>.
        </p>

        <div class="helpful-links">
          <h2>Helpful Links</h2>
          <ul>
            <?php
                wp_nav_menu([
                    'theme_location' => 'primary',
                    'container'      => false,
                    'items_wrap'     => '%3$s', // removes extra <ul> wrapper
                    'depth'          => 1,
                ]);
            ?>
          </ul>
        </div>
      </div>

      <div class="error-image">
        <img src="<?php echo get_template_directory_uri(); ?>/assets/imgs/puffin.jpg" alt="Puffin standing calmly">
      </div>

    </div>

  </section>

</main>

<?php get_footer(); ?>
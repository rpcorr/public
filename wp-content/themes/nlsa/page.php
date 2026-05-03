<?php 
/**
 *  The template for displaying page
 * 
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-page
 * 
 * @package NLSA_Theme
 * @since 1.0.0
*/


get_header(); ?>

<main id="primary" class="wrapper">

  <?php while (have_posts()) : the_post(); ?>

    <section class="page-content">
      <h1><?php the_title(); ?></h1>

      <div class="content">
        <?php the_content(); ?>
      </div>
    </section>

  <?php endwhile; ?>

</main>

<?php get_footer(); ?>
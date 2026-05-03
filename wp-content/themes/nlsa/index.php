<?php
/**
 * Main Index Template
 *
 * This is the fallback template used by WordPress when no more specific
 * template exists (e.g., blog posts, archives, or generic content pages).
 *
 * It should NOT contain homepage-specific layout.
 *
 * @package NLSA_Theme
 * @since 1.0.0
 */

get_header();
?>

<main id="primary" class="wrapper">

  <?php if (have_posts()) : ?>

    <?php while (have_posts()) : the_post(); ?>

      <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

        <header class="entry-header">
          <?php the_title('<h1 class="entry-title">', '</h1>'); ?>
        </header>

        <div class="entry-content">
          <?php the_content(); ?>
        </div>

      </article>

    <?php endwhile; ?>

    <?php the_posts_navigation(); ?>

  <?php else : ?>

    <section class="no-content">
      <h1>No content found</h1>
      <p>Sorry, there’s nothing here yet.</p>
    </section>

  <?php endif; ?>

</main>

<?php get_footer(); ?>
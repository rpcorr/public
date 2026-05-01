<?php get_header(); ?>

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
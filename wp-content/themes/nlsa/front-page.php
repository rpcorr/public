<?php
/**
 * Front Page Template
 *
 * This file controls the layout and content of the site's homepage
 * when a static front page is assigned in WordPress settings.
 *
 * Responsibilities:
 * - Renders the homepage hero section (headline, text, image, CTA)
 * - Displays announcement banner content
 * - Outputs programs/initiatives grid (dynamic ACF repeater-style fields)
 * - Shows featured event highlight section
 *
 * Data Source:
 * - Advanced Custom Fields (ACF) for all dynamic content
 *
 * Notes:
 * - This template is only used when "A static page" is set as the homepage
 *   in Settings → Reading → "Your homepage displays"
 * - Keep layout logic minimal; heavy logic should stay in functions.php
 * - All images use lazy loading for performance
 *
 * @package NLSA_Theme
 * @since 1.0.0
 */
?>

<?php get_header(); ?>

  <!-- ================== MAIN CONTENT ================== -->
  <main id="primary" class="wrapper">
    <!-- ===== Hero Section ===== -->
    <?php
$hero_heading = nlsa_get_field('hero_heading');
$hero_text    = nlsa_get_field('hero_text');
$hero_image   = nlsa_get_field('hero_image');
$hero_link    = nlsa_get_field('hero_button_link');
$hero_btn_txt = nlsa_get_field('hero_button_text');
?>

<section class="split-panel split-panel--50-50 split-panel--hero" aria-labelledby="hero-heading">

  <!-- Image -->
  <div class="split-panel__media">
      <?php if ($hero_image):
        $img_url = is_array($hero_image) ? $hero_image['url'] : wp_get_attachment_image_url($hero_image, 'full');
        $img_alt = is_array($hero_image) ? $hero_image['alt'] : get_post_meta($hero_image, '_wp_attachment_image_alt', true);
        $img_alt = $img_alt ?: 'Hero image';
      ?>
        <img loading="lazy"
            src="<?php echo esc_url($img_url); ?>"
            alt="<?php echo esc_attr($img_alt); ?>">
      <?php endif; ?>
    </div>

    <!-- Hero Content -->
    <div>

      <?php if ($hero_heading): ?>
        <h1 id="hero-heading">
          <?php echo esc_html($hero_heading); ?>
        </h1>
      <?php endif; ?>

      <?php if ($hero_text): ?>
        <p class="font-weight-medium">
          <?php echo esc_html($hero_text); ?>
        </p>
      <?php endif; ?>

      <?php if ($hero_link): 
        $url = is_array($hero_link) ? $hero_link['url'] : $hero_link;
        $target = is_array($hero_link) ? ($hero_link['target'] ?: '_self') : '_self';
        $link_title = is_array($hero_link) ? $hero_link['title'] : '';
      ?>
        <div class="btn btn--primary u-lift">
          <a href="<?php echo esc_url($url); ?>"
            target="<?php echo esc_attr($target); ?>"
            <?php echo $target === '_blank' ? 'rel="noopener noreferrer"' : ''; ?>>

            <?php echo esc_html($hero_btn_txt ?: $link_title ?: 'Learn more'); ?>

          </a>
        </div>
      <?php endif; ?>

    </div>

  </section>

    <?php
    $ann_heading = nlsa_get_field('announcement_heading');
    $ann_icon    = nlsa_get_field('announcement_icon');
    $ann_text    = nlsa_get_field('announcement_text');
    $ann_link    = nlsa_get_field('announcement_button_link');
    $ann_btn_txt = nlsa_get_field('announcement_button_text');
  ?>

  <section class="announcement announcement--primary" aria-labelledby="announcement-heading">

    <!-- Icon -->
    <div class="announcement__icon">

      <?php if ($ann_icon && $ann_icon !== 'none'): ?>
        <?php echo nlsa_get_icon($ann_icon); ?>
      <?php endif; ?>

    </div>

    <!-- Content -->
    <div class="announcement__content">

      <?php if ($ann_heading): ?>
        <h2 id="announcement-heading">
          <?php echo esc_html($ann_heading); ?>
        </h2>
      <?php endif; ?>

      <?php if ($ann_text): ?>
        <p class="font-weight-medium">
          <?php echo esc_html($ann_text); ?>
        </p>
      <?php endif; ?>

    </div>

    <!-- CTA -->
    <?php if ($ann_link): 
      $url    = is_array($ann_link) ? $ann_link['url'] : $ann_link;
      $target = is_array($ann_link) ? ($ann_link['target'] ?: '_self') : '_self';
      $title  = is_array($ann_link) ? $ann_link['title'] : '';
    ?>

      <div class="announcement__actions">

       <div class="btn btn--secondary u-lift">
          <a href="<?php echo esc_url($url); ?>"
            target="<?php echo esc_attr($target); ?>"
            <?php echo $target === '_blank' ? 'rel="noopener noreferrer"' : ''; ?>>

            <?php echo esc_html($ann_btn_txt ?: $title ?: 'Learn More'); ?>

          </a>
        </div>

      </div>

    <?php endif; ?>

  </section>

    <!-- ===== Programs / Initiatives Grid ===== -->
    <section aria-labelledby="programs-heading">
      <h2 id="programs-heading" class="visually-hidden">
        Programs and Initiatives
      </h2>

      <!-- Grid layout (mixed 60/40 columns) -->
      <div class="layout-grid">
          <?php for ($i = 1; $i <= 4; $i++) :

            $layout = nlsa_get_field("program_{$i}_layout");
            $image  = nlsa_get_field("program_{$i}_image");
            $title  = nlsa_get_field("program_{$i}_title");
            $desc   = nlsa_get_field("program_{$i}_description");
            $link   = nlsa_get_field("program_{$i}_link");
            $text   = nlsa_get_field("program_{$i}_link_text");

            if (!$title && !$image && !$desc && !$link) continue;

            $col_class = $layout === '40' ? 'col-5' : 'col-7';

            $id = 'program-' . $i . '-' . get_the_ID();
          ?>

            <div class="layout-grid__item <?php echo esc_attr($col_class); ?>"
                aria-labelledby="<?php echo esc_attr($id); ?>">

              <?php if ($image):
                $img_url = is_array($image) ? $image['url'] : wp_get_attachment_image_url($image, 'full');
                $img_alt = is_array($image) ? $image['alt'] : get_post_meta($image, '_wp_attachment_image_alt', true);
                $img_alt = $img_alt ?: $title;
              ?>
                <?php if (!empty($img_url)): ?>
                  <img loading="lazy"
                      src="<?php echo esc_url($img_url); ?>"
                      alt="<?php echo esc_attr($img_alt); ?>">
                <?php endif; ?>
              <?php endif; ?>

              <h3 id="<?php echo esc_attr($id); ?>">
                <?php echo esc_html($title); ?>
              </h3>

              <p class="font-weight-medium">
                <?php echo esc_html($desc); ?>
              </p>

              <?php if ($link): 
                $url = $link['url'];
                $link_title = $link['title'];
                $target = $link['target'] ?: '_self';
              ?>

                <div class="btn btn--primary u-lift">
                  <a href="<?php echo esc_url($url); ?>"
                    target="<?php echo esc_attr($target); ?>"
                    <?php echo $target === '_blank' ? 'rel="noopener noreferrer"' : ''; ?>>
                    <?php echo esc_html($text ?: $link_title); ?>
                  </a>
                </div>

              <?php endif; ?>

            </div>

          <?php endfor; ?>
        </div>
    </section>

    <!-- ===== Event Highlight Section ===== -->
    <?php
      $show_event    = nlsa_get_field('show_event');

      if ($show_event):

          $event_heading = nlsa_get_field('event_heading');
          $event_date    = nlsa_get_field('event_date');
          $event_link    = nlsa_get_field('event_link');
          $event_image   = nlsa_get_field('event_image');
      ?>

      <!-- ===== Event Highlight Section ===== -->
      <section class="split-panel split-panel--center split-panel--ratio-67-33 split-panel--stack-image-first"
        <?php echo $event_heading ? 'aria-labelledby="event-heading"' : ''; ?>>

        <!-- Text -->
        <div>

          <?php if ($event_heading): ?>
            <h2 id="event-heading">
              <?php echo esc_html($event_heading); ?>
            </h2>
          <?php endif; ?>

          <?php if ($event_date): ?>
            <p class="font-weight-medium">
              <?php echo esc_html($event_date); ?>
            </p>
          <?php endif; ?>

          <?php if ($event_link):
            $url = is_array($event_link) ? $event_link['url'] : $event_link;
            $target = is_array($event_link) ? ($event_link['target'] ?: '_self') : '_self';
            $link_title = is_array($event_link) ? $event_link['title'] : '';
          ?>

            <div class="btn btn--primary u-lift">
              <a href="<?php echo esc_url($url); ?>"
                target="<?php echo esc_attr($target); ?>"
                <?php echo $target === '_blank' ? 'rel="noopener noreferrer"' : ''; ?>>

                <?php echo esc_html($link_title ?: 'Learn More'); ?>

              </a>
            </div>

          <?php endif; ?>

        </div>

        <!-- Image -->
        <div class="split-panel__media">
          <?php if ($event_image):
            $img_url = is_array($event_image) ? $event_image['url'] : wp_get_attachment_image_url($event_image, 'full');
            $img_alt = is_array($event_image) ? $event_image['alt'] : get_post_meta($event_image, '_wp_attachment_image_alt', true);
            $img_alt = $img_alt ?: 'Event image';
          ?>
            <?php if (!empty($img_url)): ?>
              <img loading="lazy"
                  src="<?php echo esc_url($img_url); ?>"
                  alt="<?php echo esc_attr($img_alt); ?>">
            <?php endif; ?>
          <?php endif; ?>
        </div>

      </section>

      <?php endif; ?>
  </main>

<?php get_footer(); ?>
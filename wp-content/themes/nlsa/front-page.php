<?php get_header(); ?>

  <!-- ================== MAIN CONTENT ================== -->
  <main id="primary" class="wrapper">
    <!-- ===== Hero Section ===== -->
    <?php
$hero_heading = get_field('hero_heading');
$hero_text    = get_field('hero_text');
$hero_image   = get_field('hero_image');
$hero_link    = get_field('hero_button_link');
$hero_btn_txt = get_field('hero_button_text');
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
        <a href="<?php echo esc_url($url); ?>"
          class="btn btn--primary u-lift"
          target="<?php echo esc_attr($target); ?>"
          <?php echo $target === '_blank' ? 'rel="noopener noreferrer"' : ''; ?>>

          <?php echo esc_html($hero_btn_txt ?: $link_title ?: 'Learn more'); ?>

        </a>
      <?php endif; ?>

    </div>

  </section>

    <?php
    $ann_heading = get_field('announcement_heading');
    $ann_text    = get_field('announcement_text');
    $ann_link    = get_field('announcement_button_link');
    $ann_btn_txt = get_field('announcement_button_text');
    ?>

    <section class="announcement announcement--primary" aria-labelledby="announcement-heading">

      <!-- Icon (static) -->
      <div class="announcement__icon">
        <svg
          aria-hidden="true"
          focusable="false"
          xmlns="http://www.w3.org/2000/svg"
          viewBox="0 0 24 24"
          fill="none"
          stroke="currentColor"
          stroke-width="2"
          stroke-linecap="round"
          stroke-linejoin="round"
        >
          <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
          <circle cx="9" cy="7" r="4"></circle>
          <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
          <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
        </svg>
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
        $url = is_array($ann_link) ? $ann_link['url'] : $ann_link;
        $target = is_array($ann_link) ? ($ann_link['target'] ?: '_self') : '_self';
        $link_title = is_array($ann_link) ? $ann_link['title'] : '';
      ?>

        <div class="announcement__actions">
          <a href="<?php echo esc_url($url); ?>"
            class="btn btn--secondary u-lift"
            target="<?php echo esc_attr($target); ?>"
            <?php echo $target === '_blank' ? 'rel="noopener noreferrer"' : ''; ?>>

            <?php echo esc_html($ann_btn_txt ?: $link_title ?: 'Learn More'); ?>

          </a>
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

            $layout = get_field("program_{$i}_layout");
            $image  = get_field("program_{$i}_image");
            $title  = get_field("program_{$i}_title");
            $desc   = get_field("program_{$i}_description");
            $link   = get_field("program_{$i}_link");
            $text   = get_field("program_{$i}_link_text");

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

                <a href="<?php echo esc_url($url); ?>"
                  class="btn btn--primary u-lift"
                  target="<?php echo esc_attr($target); ?>"
                  <?php echo $target === '_blank' ? 'rel="noopener noreferrer"' : ''; ?>>
                  <?php echo esc_html($text ?: $link_title); ?>
                </a>

              <?php endif; ?>

            </div>

          <?php endfor; ?>
        </div>
    </section>

    <!-- ===== Conference Highlight Section ===== -->
    <section class="split-panel split-panel--center split-panel--ratio-67-33 split-panel--stack-image-first" aria-labelledby="conference-heading">
      <!-- Text -->
      <div>
        <h2 id="conference-heading">
          Joint Canadian Stuttering Association & Newfoundland and Labrador
          Stuttering Association Conference.
        </h2>

        <p class="font-weight-medium">August 21–23, 2026</p>

        <a href="https://stutter.ca/events/conference/2026" target="_blank" rel="noopener noreferrer" class="btn btn--primary u-lift" aria-label="Learn more about the 2026 CSA Conference (opens in new tab)">
          Learn more about the 2026 CSA Conference
        </a>
      </div>

      <!-- Image -->
      <div class="split-panel__media">
        <img loading="lazy" src="<?php echo get_theme_file_uri('/assets/imgs/at-home-with-stuttering.jpg'); ?>" alt="Joint Conference with the Canadian Stuttering Association" />
      </div>
    </section>
  </main>

<?php get_footer(); ?>
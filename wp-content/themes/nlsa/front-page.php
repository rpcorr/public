<?php get_header(); ?>

  <!-- ================== MAIN CONTENT ================== -->
  <main id="primary" class="wrapper">
    <!-- ===== Hero Section ===== -->
    <section class="split-panel split-panel--50-50 split-panel--hero" aria-labelledby="hero-heading">
      <!-- Visual/logo -->
      <div class="split-panel__media">
        <img loading="lazy" src="<?php echo get_theme_file_uri('/assets/imgs/NLSA-logo.png'); ?>" alt="Newfoundland and Labrador Stuttering Association logo" />
      </div>

      <!-- Hero content -->
      <div>
        <h1 id="hero-heading">
          Supporting People Who Stutter in Newfoundland and Labrador
        </h1>

        <p class="font-weight-medium">
          The NLSA advocates for people who stutter, fostering inclusion across health care, education, and public life. We promote understanding, support, and equal opportunities, ensuring every voice is heard and valued.
        </p>

        <a href="about.html" class="btn btn--primary u-lift">
          Learn more about
          <abbr title="Newfoundland and Labrador Stuttering Association">
            NLSA
          </abbr>
        </a>
      </div>
    </section>

    <!-- ===== Announcement / CTA Banner ===== -->
    <section class="announcement announcement--primary" aria-labelledby="announcement-heading">
      <!-- Icon (decorative SVG) -->
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

      <!-- Text content -->
      <div class="announcement__content">
        <h2 id="announcement-heading">
          Be Part of Research That Makes a Difference
        </h2>
        <p class="font-weight-medium">Help improve access and support for people who stutter in Newfoundland and Labrador. Your participation matters!</p>
      </div>

      <!-- CTA -->
      <div class="announcement__actions">
        <a href="get-involved.html" class="btn btn--secondary u-lift" aria-label="Get involved today and make a difference">
          Get Involved Today
        </a>
      </div>
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

            $is_external = $link && !str_contains($link, home_url());
          ?>

            <div class="layout-grid__item <?php echo esc_attr($col_class); ?>"
                aria-labelledby="<?php echo esc_attr($id); ?>">

              <?php if ($image):
                $img_url = is_array($image) ? $image['url'] : wp_get_attachment_image_url($image, 'full');
                $img_alt = is_array($image) ? $image['alt'] : get_post_meta($image, '_wp_attachment_image_alt', true);
                $img_alt = $img_alt ?: $title;
              ?>
                <img loading="lazy"
                    src="<?php echo esc_url($img_url); ?>"
                    alt="<?php echo esc_attr($img_alt); ?>">
              <?php endif; ?>

              <h3 id="<?php echo esc_attr($id); ?>">
                <?php echo esc_html($title); ?>
              </h3>

              <p class="font-weight-medium">
                <?php echo esc_html($desc); ?>
              </p>

              <?php if ($link): ?>
                <a href="<?php echo esc_url($link); ?>"
                  class="btn btn--primary u-lift"
                  <?php echo $is_external ? 'target="_blank" rel="noopener noreferrer"' : ''; ?>>
                  <?php echo esc_html($text ?: 'Learn More'); ?>
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
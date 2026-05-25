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

<?php get_header(); 

$fields = function_exists('get_fields')
  ? (get_fields() ?: [])
  : [];

?>

  <!-- ================== MAIN CONTENT ================== -->
  <main id="primary" class="wrapper">
    <!-- ===== Hero Section ===== -->
    <?php
$hero_heading = $fields['hero_heading'] ?? null;
$hero_text    = $fields['hero_text'] ?? null;
$hero_image   = $fields['hero_image'] ?? null;
$hero_link    = $fields['hero_button_link'] ?? null;
$hero_btn_txt = $fields['hero_button_text'] ?? null;
?>

<section class="split-panel split-panel--50-50 split-panel--hero" aria-labelledby="hero-heading">

  <!-- Image -->
  <div class="split-panel__media">
      <?php if ($hero_image):
        $img_url = is_array($hero_image) ? $hero_image['url'] : wp_get_attachment_image_url($hero_image, 'large');
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
        $target = $hero_link['target'] ?? '_self';
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
    $ann_heading = $fields['announcement_heading'] ?? null;
    $ann_icon    = $fields['announcement_icon'] ?? null;
    $ann_text    = $fields['announcement_text'] ?? null;
    $ann_link    = $fields['announcement_button_link'] ?? null;
    $ann_btn_txt = $fields['announcement_button_text'] ?? null;
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
      $target = is_array($ann_link) ? ($ann_link['target'] ?? '_self') : '_self';
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

            $layout = $fields["program_{$i}_layout"] ?? null;
            $image  = $fields["program_{$i}_image"] ?? null;
            $title  = $fields["program_{$i}_title"] ?? null;
            $desc   = $fields["program_{$i}_description"] ?? null;
            $link   = $fields["program_{$i}_link"] ?? null;
            $text   = $fields["program_{$i}_link_text"] ?? null;

            if (!$title && !$image && !$desc && !$link) continue;

            $col_class = ((string)$layout === '40') ? 'col-5' : 'col-7';

            $id = 'program-' . $i . '-' . get_the_ID();
          ?>

            <div class="layout-grid__item <?php echo esc_attr($col_class); ?>"
                aria-labelledby="<?php echo esc_attr($id); ?>">

              <?php
              $image_id = is_array($image) ? $image['ID'] : $image;

              if ($image_id) {

                echo wp_get_attachment_image(
                  $image_id,
                  'large',
                  false,
                  [
                    'loading' => 'lazy',
                    'alt' => nlsa_get_image_alt($image, $title)
                  ]
                );
              }
              ?>

              <h3 id="<?php echo esc_attr($id); ?>">
                <?php echo esc_html($title); ?>
              </h3>

              <p class="font-weight-medium">
                <?php echo esc_html($desc); ?>
              </p>

              <?php if ($link): 
                $url = $link['url'];
                $link_title = $link['title'];
                $target = is_array($link) ? ($link['target'] ?? '_self') : '_self';
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
      $show_event = $fields['show_event'] ?? null;

      if ($show_event):

          $event_heading = $fields['event_heading'] ?? null;
          $event_date    = $fields['event_date'] ?? null;
          $event_link    = $fields['event_link'] ?? null;
          $event_image   = $fields['event_image'] ?? null;
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
            $img_url = is_array($event_image) ? $event_image['url'] : wp_get_attachment_image_url($event_image, 'large');
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
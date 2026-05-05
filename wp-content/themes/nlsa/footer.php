  <?php
/**
 * Footer Template
 *
 * This file closes the main content area and outputs the site footer.
 * It is included on all pages via get_footer().
 *
 * Responsibilities:
 * - Renders the global site footer (newsletter, social links, copyright)
 * - Calls wp_footer() before closing </body> (required for scripts, plugins, analytics)
 * - Closes the HTML document structure
 *
 * Notes:
 * - Do NOT manually include scripts here; use wp_enqueue_scripts in functions.php
 * - wp_footer() is required for many plugins and WordPress core features
 *
 * Typical Structure:
 * <footer>...</footer>
 * <?php wp_footer(); ?>
 * </body>
 * </html>
 *
 * @package NLSA_Theme
 * @since 1.0.0
 */
?>
  
  <!-- ================== FOOTER ================== -->
  <footer>
    <div class="wrapper footer-inner">
      <!-- Newsletter subscription form -->
      <div>
        <h2 class="footer__title">
          <?php echo esc_html(get_theme_mod('nlsa_footer_newsletter_title', 'Subscribe to Our Newsletter')); ?>
        </h2>
        <!-- Uses Formspree for handling submissions -->
        <form class="form-inline" action="https://formspree.io/f/mabcdxyz" method="POST">
          <!-- Visually hidden label for accessibility -->
          <label for="footer-email" class="visually-hidden">
            Email address
          </label>

          <input class="form-input" id="footer-email" name="email" type="email" placeholder="Enter your email" required />

          <button class="btn btn--primary" type="submit" aria-label="Subscribe to our newsletter">
            Subscribe
          </button>
        </form>
      </div>

      <!-- Social links -->
      <div>
          <h3 class="footer__title">
            <?php echo esc_html(get_theme_mod('nlsa_footer_get_in_touch_title', 'Get in Touch')); ?>
          </h3>

          <div class="social-icons">

            <a href="<?php echo esc_url(get_theme_mod('nlsa_facebook_url')); ?>" aria-label="Facebook">
              <i class="fab fa-facebook-f" aria-hidden="true"></i> Facebook
            </a>

            <a href="<?php echo esc_url(get_theme_mod('nlsa_instagram_url')); ?>" aria-label="Instagram">
              <i class="fab fa-instagram" aria-hidden="true"></i> Instagram
            </a>

          </div>
      </div>

      <!-- Footer bottom -->
      <div class="footer__bottom">
        <p class="footer__text">
          &copy; <?php echo date('Y'); ?> <?php echo get_bloginfo('name'); ?>. <?php echo esc_html(get_theme_mod('nlsa_footer_copyright_text', 'All rights reserved.')); ?>
        </p>
      </div>
    </div>
  </footer>

  <!-- ================== SCRIPTS ================== -->
  <!-- Main navigation + interaction logic -->
  <?php wp_footer(); ?>
</body>

</html>
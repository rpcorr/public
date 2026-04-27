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
        <!-- Individual program cards follow same structure -->
        <!-- Image → heading → description → CTA -->

        <div class="layout-grid__item col-7">
          <!--60% -->
          <img loading="lazy" src="<?php echo get_theme_file_uri('/assets/imgs/researching-barriers.jpg'); ?>" alt="NLSA Research Logo" />
          <h3>Researching Communication Barriers</h3>
          <p class="font-weight-medium">
            We're exploring ways to improve access and support for people who
            stutter. We need your voice to help make a difference in our
            community.
          </p>
          <a href="research.html" class="btn btn--primary u-lift" aria-label="Learn more about researching communication barriers">
            Learn More
          </a>
        </div>

        <div class="layout-grid__item col-5">
          <!--40%-->
          <img loading="lazy" src="<?php echo get_theme_file_uri('/assets/imgs/kitchen-party.jpg'); ?>" alt="Kitchen Party Logo" />
          <h3>
            Kitchen Party <br />
            <span>Where Every Voice Belongs</span>
          </h3>

          <p class="font-weight-medium">
            Celebrate connection, storytelling, and community at our NLSA
            Kitchen Party—where every voice is welcome.
          </p>
          <a href="https://nlstuttering.ca/kitchenparty/" class="btn btn--primary u-lift" aria-label="Get details about the kitchen party where everyone is welcomed">Get Details</a>
        </div>

        <div class="layout-grid__item col-7">
          <!--60%-->
          <img loading="lazy" src="<?php echo get_theme_file_uri('/assets/imgs/ssl.jpg'); ?>" alt="Some Stutter Luh! Logo" />
          <h3>
            Some Stutter Luh! &mdash; Newfoundland and Labrador's First
            Podcast About Communicating Differently
          </h3>
          <p class="font-weight-medium">
            Some Stutter Luh! celebrates the many ways people communicate. As
            Newfoundland and Labrador's first podcast focused on speech and
            communication differences, it shines a light on the experiences of
            people who stutter and others with devalued communication styles.
            Through open conversations, the podcast challenges myths, reduces
            stigma, and highlights the beauty of diverse voices. Every story
            reminds us that all forms of communication—fluent, disfluent, or
            in between—deserve to be heard, understood, and respected.
            Together, we're changing how we talk about talking.
          </p>
          <a href="https://somestutterluh.hcommons.org/" target="_blank" rel="noopener noreferrer" class="btn btn--primary u-lift" aria-label="Listen to Some Stutter Luh podcast now (opens in a new tab)">
            Listen Now
          </a>
        </div>

        <div class="layout-grid__item col-5">
          <!--40%-->
          <img loading="lazy" src="<?php echo get_theme_file_uri('/assets/imgs/walk-run-roll.jpg'); ?>" alt="Walk, Run, Roll Logo" />
          <h3>
            Walk, Run, and Roll<br />
            <span>Moving Forward Together</span>
          </h3>
          <p class="font-weight-medium">
            Join us for Walk, Run, and Roll—a fun, inclusive event that brings
            people together to support and celebrate people who stutter in
            Newfoundland and Labrador. Everyone is welcome to move at their
            own pace, connect with others, and help raise awareness about
            stuttering. Each step strengthens our community and celebrates the
            power of coming together.
          </p>
          <a href="https://nlstuttering.ca/walk-run-roll/" class="btn btn--primary u-lift" aria-label="Get details on the annual walk, run, and roll raising awareness event">Get Details</a>
        </div>
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

  <!-- ================== FOOTER ================== -->
  <footer>
    <div class="wrapper footer-inner">
      <!-- Newsletter subscription form -->
      <div>
        <h2 class="footer__title">Subscribe to Our Newsletter</h2>

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
        <h3 class="footer__title">Get in Touch</h3>

        <div class="social-icons">
          <!-- External social links -->
          <a href="https://www.facebook.com/groups/535643736920153/" aria-label="Get in touch with us through Facebook">
            <i class="fab fa-facebook-f" aria-hidden="true"></i> Facebook
          </a>

          <a href="https://www.instagram.com/nlstuttering/" aria-label="Get in touch with us through Instagram">
            <i class="fab fa-instagram" aria-hidden="true"></i> Instagram
          </a>
        </div>
      </div>

      <!-- Footer bottom -->
      <div class="footer__bottom">
        <p class="footer__text">
          &copy; 2026 Newfoundland &amp; Labrador Stuttering Association
        </p>
      </div>
    </div>
  </footer>

  <!-- ================== SCRIPTS ================== -->
  <!-- Main navigation + interaction logic -->
  <?php wp_footer(); ?>
</body>

</html>
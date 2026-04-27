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

<?php get_footer(); ?>
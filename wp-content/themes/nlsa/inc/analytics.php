<?php

/**
 * Analytics Integration
 *
 * - Adds Google Analytics script with user consent handling.
 * - Provides a dashboard widget to monitor GA status based on frontend detection.
 * - Only loads GA for non-admins and in production environment.
 *
 * @package NLSA_Theme
 * @since 1.0.0
 */

function nlsa_add_google_analytics() {

    // 1. Skip admins
    if (current_user_can('manage_options')) {
        return;
    }

    // 2. Only run on production
    if (wp_get_environment_type() !== 'production') {
        return;
    }
    ?>
    <script>
    (function() {
        const consent = localStorage.getItem('nlsa_cookie_consent');

        if (consent === 'accepted') {

            const script = document.createElement('script');
            script.async = true;
            script.src = "https://www.googletagmanager.com/gtag/js?id=G-5CGVTZVK02";
            document.head.appendChild(script);

            window.dataLayer = window.dataLayer || [];

            window.gtag = function() {
                dataLayer.push(arguments);
            };

            gtag('js', new Date());
            gtag('config', 'G-5CGVTZVK02');
        }
    })();
    </script>
    <?php
}
add_action('wp_head', 'nlsa_add_google_analytics');


// add a cookie consent banner for analytics cookies, only for non-admins and in production environment
function nlsa_cookie_consent_banner() {

    if (current_user_can('manage_options')) {
        return;
    }

    if (wp_get_environment_type() !== 'production') {
        return;
    }
    ?>
    <div id="nlsa-cookie-banner">
        <p>
            This site uses cookies for analytics. By clicking "Accept", you agree.
        </p>
        <button id="nlsa-accept">Accept</button>
        <button id="nlsa-decline">Decline</button>
    </div>

    <style>
        #nlsa-cookie-banner {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: #222;
            color: #fff;
            padding: 15px;
            display: none;
            justify-content: space-between;
            align-items: center;
            z-index: 9999;
        }

        #nlsa-cookie-banner .nlsa-buttons {
            display: flex;
            gap: 6px; /* tighten or increase as needed */
        }

        #nlsa-cookie-banner button {
            margin-left: 10px;
            padding: 6px 12px;
            cursor: pointer;
        }
    </style>

    <script>
      (function() {

          function createCookieBanner() {
              const banner = document.createElement('div');
              banner.id = 'nlsa-cookie-banner';

              const message = document.createElement('p');
              message.textContent = 'This site uses cookies for analytics. By clicking "Accept", you agree.';

              const buttonWrapper = document.createElement('div');
              buttonWrapper.className = 'nlsa-buttons';

              const acceptBtn = document.createElement('button');
              acceptBtn.id = 'nlsa-accept';
              acceptBtn.textContent = 'Accept';

              const declineBtn = document.createElement('button');
              declineBtn.id = 'nlsa-decline';
              declineBtn.textContent = 'Decline';

              buttonWrapper.appendChild(acceptBtn);
              buttonWrapper.appendChild(declineBtn);

              banner.appendChild(message);
              banner.appendChild(buttonWrapper);

              document.body.appendChild(banner);

              // show it (important — you're not setting display anymore)
              banner.style.display = 'flex';

              acceptBtn.addEventListener('click', function () {
                  localStorage.setItem('nlsa_cookie_consent', 'accepted');
                  banner.remove();
                  location.reload();
              });

              declineBtn.addEventListener('click', function () {
                  localStorage.setItem('nlsa_cookie_consent', 'declined');
                  banner.remove();
              });
          }

          // Show only if no consent stored
          const consent = localStorage.getItem('nlsa_cookie_consent');

          if (!consent) {
              if (document.body) {
                  createCookieBanner();
              } else {
                  document.addEventListener('DOMContentLoaded', createCookieBanner);
              }
          }

      })();
      </script>
    <?php
}
add_action('wp_footer', 'nlsa_cookie_consent_banner');

// Check for presence of GA script and dataLayer, and send alert if missing (only in production for non-admins)
function nlsa_ga_presence_check() {

    if (wp_get_environment_type() !== 'production') {
        return;
    }
    ?>
    <script>
    (function () {

        const ALERT_KEY = 'nlsa_ga_alert_sent';

        function sendAlert() {

            if (sessionStorage.getItem(ALERT_KEY)) return;
            sessionStorage.setItem(ALERT_KEY, '1');

            fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: new URLSearchParams({
                    action: 'nlsa_ga_missing_alert',
                    url: window.location.href
                })
            });
        }

        function checkGA() {

            const consent = localStorage.getItem('nlsa_cookie_consent');

            // only check when GA SHOULD be active
            if (consent !== 'accepted') return;

            setTimeout(() => {

                const gtagExists = typeof window.gtag === 'function';

                const dataLayerExists = Array.isArray(window.dataLayer);

                if (!gtagExists || !dataLayerExists) {
                    sendAlert();
                }

            }, 5000);
        }

        if (document.readyState === 'complete') {
            checkGA();
        } else {
            window.addEventListener('load', checkGA);
        }

    })();
    </script>
    <?php
}
add_action('wp_footer', 'nlsa_ga_presence_check');

add_action('wp_ajax_nlsa_ga_missing_alert', function () {

    if (wp_get_environment_type() !== 'production') {
        wp_die();
    }

    if (!current_user_can('manage_options')) {
        wp_die();
    }

    $url = esc_url_raw($_POST['url'] ?? '');

    // store failure timestamp + last URL
    update_option('nlsa_ga_status', [
        'status' => 'missing',
        'time'   => time(),
        'url'    => $url
    ]);

    wp_send_json_success();
});
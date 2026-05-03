// ================== MAIN NAV ELEMENTS ==================
const menu = document.querySelector('#primary-menu'); // main nav container
const button = document.querySelector('.hamburger'); // toggle button (open/close)
const backdrop = document.querySelector('.nav-backdrop'); // overlay behind menu

// Fail early if required elements are missing
if (!button || !menu || !backdrop) {
  console.warn('Navigation elements missing');
} else {
  // All elements that can receive focus (used for keyboard trapping)
  const focusableSelectors =
    'a[href], button, input, textarea, select, [tabindex]:not([tabindex="-1"])';

  // Focus management
  let focusables = [];
  let firstFocusable;
  let lastFocusable;

  // Resize debounce timer
  let resizeTimeout;

  // Scroll position (for locking body when menu opens)
  let scrollY = 0;

  // Element that triggered menu open (for restoring focus)
  let lastTrigger = null;

  // Detect mobile breakpoint (matches CSS breakpoint)
  function isMobile() {
    return window.matchMedia('(max-width: 768px)').matches;
  }

  // ================== CLOSE MENU ==================
  function closeMenu() {
    // Step 1: apply closing class for CSS animation
    menu.classList.add('is-closing');

    // Step 2: next frame remove open state (ensures animation runs)
    requestAnimationFrame(() => {
      menu.classList.remove('is-open');
      button.classList.remove('is-open');
      backdrop.classList.remove('is-open');
    });

    // Update accessibility state
    button.setAttribute('aria-expanded', 'false');
    document.body.classList.remove('menu-open');

    updateMenuAccessibility();

    // Fully disable menu for screen readers + interaction
    menu.setAttribute('inert', '');
    menu.setAttribute('aria-hidden', 'true');

    // Disable all submenus
    document.querySelectorAll('.submenu, .submenu--nested').forEach((el) => {
      el.inert = true;
    });

    // Close any open submenu states
    document.querySelectorAll('.has-submenu.is-open').forEach((item) => {
      item.classList.remove('is-open');
      item
        .querySelector('.submenu-toggle')
        ?.setAttribute('aria-expanded', 'false');
    });

    // Restore scroll after animation completes
    setTimeout(() => {
      document.body.style.position = '';
      document.body.style.top = '';
      document.body.style.left = '';
      document.body.style.right = '';
      document.body.style.width = '';

      window.scrollTo(0, scrollY);
    }, 420);

    // Cleanup closing class
    setTimeout(() => {
      menu.classList.remove('is-closing');
    }, 420);

    // Restore focus to trigger element (or fallback to button)
    if (lastTrigger && document.contains(lastTrigger)) {
      lastTrigger.focus();
    } else {
      button.focus();
    }
  }

  // ================== OPEN MENU ==================
  function openMenu() {
    const activeLink = menu.querySelector('a.active');

    // Save element that opened menu (for later focus restore)
    lastTrigger = document.activeElement;

    // Apply open states
    menu.classList.add('is-open');
    button.classList.add('is-open');
    backdrop.classList.add('is-open');
    document.body.classList.add('menu-open');

    // Accessibility
    button.setAttribute('aria-expanded', 'true');
    updateMenuAccessibility();

    // Enable menu interaction
    menu.removeAttribute('inert');
    menu.removeAttribute('aria-hidden');

    // Ensure all submenus start closed
    menu.querySelectorAll('.submenu, .submenu--nested').forEach((el) => {
      el.inert = true;
    });

    // Lock body scroll
    scrollY = window.scrollY;
    document.body.style.position = 'fixed';
    document.body.style.top = `-${scrollY}px`;
    document.body.style.left = '0';
    document.body.style.right = '0';
    document.body.style.width = '100%';

    // Prepare focusable elements
    setFocusableElements();

    // Scroll active link into view
    scrollActiveItemIntoView();

    // Move focus to active link or first focusable item
    if (activeLink && typeof activeLink.focus === 'function') {
      activeLink.focus();
    } else if (firstFocusable && typeof firstFocusable.focus === 'function') {
      firstFocusable.focus();
    }
  }

  // Scroll currently active page link into view inside menu
  function scrollActiveItemIntoView() {
    const activeLink = menu.querySelector('a.active');
    if (!activeLink) return;

    // Wait a frame to ensure layout is stable
    requestAnimationFrame(() => {
      activeLink.scrollIntoView({
        block: 'center',
        inline: 'nearest',
        behavior: 'auto',
      });
    });
  }

  // Collect all focusable elements for focus trapping
  function setFocusableElements() {
    const navFocusables = menu.querySelectorAll(focusableSelectors);

    // Include hamburger so it acts as first/last loop point
    const closeBtn = button;

    focusables = [closeBtn, ...navFocusables];
    firstFocusable = focusables[0];
    lastFocusable = focusables[focusables.length - 1];
  }

  // Toggle menu open/close
  function toggleMenu() {
    const isOpen = menu.classList.contains('is-open');
    isOpen ? closeMenu() : openMenu();
  }

  // Bind global listeners (once)
  function bindGlobalNavEvents() {
    document.addEventListener('keydown', onGlobalKeyDown);
    document.addEventListener('click', onGlobalClick);

    // Clicking backdrop closes menu
    backdrop.addEventListener('click', closeMenu);
  }

  // Handle global key actions
  function onGlobalKeyDown(e) {
    if (e.key === 'Escape') {
      closeMenu();
      closeAllSubmenus();
    }
  }

  // Close all open submenus
  function closeAllSubmenus() {
    document.querySelectorAll('.has-submenu.is-open').forEach(closeSubmenu);
  }

  // Handle clicks outside nav
  function onGlobalClick(e) {
    const clickedInsideNav = e.target.closest('.site-nav');
    const clickedHamburger = e.target.closest('.hamburger');
    const clickedSubmenuToggle = e.target.closest('.submenu-toggle');

    // Click outside everything → close menu
    const isMenuOpen = menu.classList.contains('is-open');

    if (
      isMenuOpen &&
      !clickedInsideNav &&
      !clickedHamburger &&
      !clickedSubmenuToggle
    ) {
      closeMenu();
      return;
    }

    // Otherwise close submenus if needed
    closeAllSubmenusIfOutside(e);
  }

  // Close submenus if click occurred outside them
  function closeAllSubmenusIfOutside(e) {
    document.querySelectorAll('.has-submenu.is-open').forEach((item) => {
      if (!item.contains(e.target)) {
        item.classList.remove('is-open');
        item
          .querySelector('.submenu-toggle')
          ?.setAttribute('aria-expanded', 'false');
      }
    });
  }

  // Close a specific submenu
  function closeSubmenu(item) {
    item.classList.remove('is-open');
    item
      .querySelector('.submenu-toggle')
      ?.setAttribute('aria-expanded', 'false');
  }

  // Manage aria/inert based on state + breakpoint
  function updateMenuAccessibility() {
    if (isMobile()) {
      if (menu.classList.contains('is-open')) {
        menu.removeAttribute('inert');
        menu.removeAttribute('aria-hidden');
      } else {
        menu.setAttribute('inert', '');
        menu.setAttribute('aria-hidden', 'true');
      }
    } else {
      // Desktop: menu always accessible
      menu.removeAttribute('inert');
      menu.removeAttribute('aria-hidden');
    }
  }

  // Toggle button click
  button.addEventListener('click', (e) => {
    e.stopPropagation(); // prevents document click from interfering
    toggleMenu();
  });

  /* ================== KEYBOARD NAVIGATION ================== */
  menu.addEventListener('keydown', (e) => {
    // Only trap keys when menu is open
    if (!document.body.classList.contains('menu-open')) return;

    /* ===== TAB → focus trap ===== */
    if (e.key === 'Tab') {
      if (!firstFocusable || !lastFocusable) return;

      // SHIFT + TAB (wrap backwards)
      if (e.shiftKey && document.activeElement === firstFocusable) {
        e.preventDefault();
        lastFocusable.focus();
      }

      // TAB (wrap forwards)
      if (!e.shiftKey && document.activeElement === lastFocusable) {
        e.preventDefault();
        firstFocusable.focus();
      }

      return; // stop further handling
    }

    /* ===== ARROW KEYS → vertical nav ===== */
    if (!['ArrowDown', 'ArrowUp'].includes(e.key)) return;

    // Only include visible/valid links (skip hidden nested items)
    const items = Array.from(
      menu.querySelectorAll('.site-nav__link, .submenu__link'),
    ).filter((el) => {
      const isNested = el.closest('.submenu--nested');
      const parentOpen = el.closest('.has-submenu.is-open');

      return !isNested || parentOpen;
    });

    const currentIndex = items.indexOf(document.activeElement);
    if (currentIndex === -1) return;

    e.preventDefault();

    let nextIndex =
      e.key === 'ArrowDown'
        ? Math.min(currentIndex + 1, items.length - 1)
        : Math.max(currentIndex - 1, 0);

    items[nextIndex]?.focus();
  });

  /* ================== CLOSE ON LINK CLICK ================== */
  menu.addEventListener('click', (e) => {
    const link = e.target.closest('.site-nav__link');
    const submenuToggle = e.target.closest('.submenu-toggle');

    // Ignore submenu toggle clicks (handled elsewhere)
    if (submenuToggle) return;

    // Close menu on real navigation
    if (link && !submenuToggle) {
      closeMenu();
    }
  });

  /* ================== SWIPE TO CLOSE (mobile UX) ================== */
  let startX = 0;
  let isSwiping = false;

  // Record initial touch position
  menu.addEventListener('touchstart', (e) => {
    startX = e.touches[0].clientX;
    isSwiping = true;
  });

  // Detect swipe gesture
  menu.addEventListener('touchmove', (e) => {
    if (!isSwiping) return;

    const diff = e.touches[0].clientX - startX;

    // Swipe right → close menu
    if (diff > 100) {
      isSwiping = false;
      closeMenu();
    }
  });

  // Reset swipe state
  menu.addEventListener('touchend', () => {
    isSwiping = false;
  });

  menu.addEventListener('touchcancel', () => {
    isSwiping = false;
  });

  // Initial accessibility setup
  updateMenuAccessibility();

  // Bind global events once
  bindGlobalNavEvents();

  // ================== RESPONSIVE RESET ==================
  window.addEventListener('resize', () => {
    clearTimeout(resizeTimeout);

    resizeTimeout = setTimeout(() => {
      if (!isMobile()) {
        // Fully reset menu state when switching to desktop
        menu.classList.remove('is-open');
        button.classList.remove('is-open');
        backdrop.classList.remove('is-open');

        document.body.classList.remove('menu-open');

        // Clear scroll lock styles
        document.body.style.position = '';
        document.body.style.top = '';
        document.body.style.left = '';
        document.body.style.right = '';
        document.body.style.width = '';

        window.scrollTo(0, scrollY);
      }

      updateMenuAccessibility();
    }, 150);
  });
}

// ================== SUBMENU LOGIC ==================
document.querySelectorAll('.has-submenu').forEach((menuItem) => {
  const toggle = menuItem.querySelector(':scope > .submenu-toggle');

  // NEW: get submenu via aria-controls
  const submenuId = toggle?.getAttribute('aria-controls');
  const submenu = submenuId
    ? document.getElementById(submenuId)
    : menuItem.querySelector('.submenu'); // fallback (nested menus)
  const links = submenu?.querySelectorAll('.submenu__link') || [];

  // Centralized submenu state handler
  function setSubmenuState(state) {
    menuItem.classList.toggle('is-open', state);
    toggle?.setAttribute('aria-expanded', String(state));

    if (submenu) {
      submenu.inert = !state;
    }
  }

  function openSubmenu() {
    setSubmenuState(true);

    if (submenu) {
      submenu.inert = false; // ensure it's interactive
    }
  }

  function closeSubmenu() {
    setSubmenuState(false);
  }

  // Detect touch vs hover device
  function isTouchDevice() {
    return window.matchMedia('(hover: none)').matches;
  }

  // Click behavior differs for touch vs desktop
  toggle.addEventListener('click', (e) => {
    e.stopPropagation(); // prevent global click handler

    const isOpen = menuItem.classList.contains('is-open');

    // TOUCH → toggle only (prevent navigation)
    if (isTouchDevice()) {
      if (!isOpen) {
        // First tap → open submenu
        e.preventDefault();

        // Close other open submenus (not ancestors)
        document.querySelectorAll('.has-submenu.is-open').forEach((item) => {
          if (item !== menuItem && !item.contains(menuItem)) {
            item.classList.remove('is-open');
            item
              .querySelector('.submenu-toggle')
              ?.setAttribute('aria-expanded', 'false');
          }
        });

        setSubmenuState(true);
      }
      // Second tap → allow navigation (no preventDefault)
      return;
    }

    // DESKTOP → allow normal link behavior
  });

  // Keyboard support for submenu toggle
  toggle.addEventListener('keydown', (e) => {
    switch (e.key) {
      case 'Enter':
        return; // allow default link behavior

      case ' ':
        e.preventDefault();
        setSubmenuState(!menuItem.classList.contains('is-open'));
        break;

      case 'ArrowDown':
        e.preventDefault();
        openSubmenu();
        links[0]?.focus();
        break;

      case 'ArrowUp':
        e.preventDefault();
        openSubmenu();
        links[links.length - 1]?.focus();
        break;

      case 'Escape':
        closeSubmenu();
        toggle.focus();
        break;
    }
  });

  let isPointerInside = false;

  // Hover interactions (desktop only)
  if (!isTouchDevice()) {
    menuItem.addEventListener('mouseenter', () => {
      isPointerInside = true;
      setSubmenuState(true);
    });

    menuItem.addEventListener('mouseleave', () => {
      isPointerInside = false;

      // Don't close if user is still navigating via keyboard
      if (menuItem.contains(document.activeElement)) return;

      setSubmenuState(false);
    });
  }

  // Close submenu when focus leaves (with pointer awareness)
  menuItem.addEventListener('focusout', (e) => {
    const next = e.relatedTarget;

    // Still inside submenu → ignore
    if (next && menuItem.contains(next)) return;

    // If pointer still inside (desktop), keep open
    if (!isTouchDevice() && isPointerInside) return;

    setSubmenuState(false);
  });

  // Open submenu on focus (desktop keyboard users)
  menuItem.addEventListener('focusin', () => {
    setSubmenuState(true);
  });
});

// ================== ACTIVE LINK HIGHLIGHT ==================
document.addEventListener('DOMContentLoaded', () => {
  const currentPath = window.location.pathname.split('/').pop() || 'index.html';

  document.querySelectorAll('.site-nav__link').forEach((link) => {
    const linkPath = link.getAttribute('href');

    // Skip external links
    if (!linkPath || linkPath.startsWith('http')) return;

    // Match current page
    if (linkPath === currentPath) {
      link.classList.add('active');
      link.setAttribute('aria-current', 'page');
    } else {
      link.classList.remove('active');
      link.removeAttribute('aria-current');
    }
  });
});

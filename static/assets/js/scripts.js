const menu = document.querySelector('#primary-menu');
const button = document.querySelector('.hamburger');
const backdrop = document.querySelector('.nav-backdrop');

if (!button || !menu || !backdrop) {
  console.warn('Navigation elements missing');
} else {
  const focusableSelectors =
    'a[href], button, input, textarea, select, [tabindex]:not([tabindex="-1"])';

  let focusables = [];
  let firstFocusable;
  let lastFocusable;
  let resizeTimeout;

  let scrollY = 0;
  let lastTrigger = null;

  function isMobile() {
    return window.matchMedia('(max-width: 768px)').matches;
  }

  function closeMenu() {
    // 1. Apply closing state FIRST
    menu.classList.add('is-closing');

    // 2. Next frame, remove open
    requestAnimationFrame(() => {
      menu.classList.remove('is-open');
      button.classList.remove('is-open');
      backdrop.classList.remove('is-open');
    });

    button.setAttribute('aria-expanded', 'false');

    document.body.classList.remove('menu-open');

    updateMenuAccessibility();

    setTimeout(() => {
      document.body.style.position = '';
      document.body.style.top = '';
      document.body.style.left = '';
      document.body.style.right = '';
      document.body.style.width = '';

      window.scrollTo(0, scrollY);
    }, 420);

    // cleanup
    setTimeout(() => {
      menu.classList.remove('is-closing');
    }, 420);

    // restore focus
    if (lastTrigger && document.contains(lastTrigger)) {
      lastTrigger.focus();
    } else {
      button.focus();
    }
  }

  function openMenu() {
    const activeLink = menu.querySelector('a.active');

    lastTrigger = document.activeElement;

    menu.classList.add('is-open');
    button.classList.add('is-open');
    backdrop.classList.add('is-open');
    document.body.classList.add('menu-open');

    button.setAttribute('aria-expanded', 'true');

    updateMenuAccessibility();

    scrollY = window.scrollY;

    document.body.style.position = 'fixed';
    document.body.style.top = `-${scrollY}px`;
    document.body.style.left = '0';
    document.body.style.right = '0';
    document.body.style.width = '100%';

    setFocusableElements();

    scrollActiveItemIntoView();

    // move focus to current link else home into menu
    if (activeLink && typeof activeLink.focus === 'function') {
      activeLink.focus();
    } else if (firstFocusable && typeof firstFocusable.focus === 'function') {
      firstFocusable.focus();
    }
  }

  function scrollActiveItemIntoView() {
    const activeLink = menu.querySelector('a.active');

    if (!activeLink) return;

    // Small delay ensures layout + transitions are applied
    requestAnimationFrame(() => {
      activeLink.scrollIntoView({
        block: 'center',
        inline: 'nearest',
        behavior: 'auto',
      });
    });
  }

  function setFocusableElements() {
    focusables = menu.querySelectorAll(focusableSelectors);

    firstFocusable = focusables.length ? focusables[0] : null;
    lastFocusable = focusables.length
      ? focusables[focusables.length - 1]
      : null;
  }

  function toggleMenu() {
    const isOpen = menu.classList.contains('is-open');
    isOpen ? closeMenu() : openMenu();
  }

  function bindGlobalNavEvents() {
    document.addEventListener('keydown', onGlobalKeyDown);
    document.addEventListener('click', onGlobalClick);

    backdrop.addEventListener('click', closeMenu);
  }

  function onGlobalKeyDown(e) {
    if (e.key === 'Escape') {
      closeMenu();
      closeAllSubmenus();
    }
  }

  function onGlobalClick(e) {
    const clickedInsideNav = e.target.closest('.site-nav');
    const clickedHamburger = e.target.closest('.hamburger');

    if (!clickedInsideNav && !clickedHamburger) {
      closeMenu();
    }

    closeAllSubmenusIfOutside(e);
  }

  function closeAllSubmenusIfOutside(e) {
    document.querySelectorAll('.has-submenu.is-open').forEach((item) => {
      if (!item.contains(e.target)) {
        closeSubmenu(item);
      }
    });
  }

  function closeSubmenu(item) {
    item.classList.remove('is-open');

    item
      .querySelector('.submenu-toggle')
      ?.setAttribute('aria-expanded', 'false');
  }

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
      // Desktop: always accessible
      menu.removeAttribute('inert');
      menu.removeAttribute('aria-hidden');
    }
  }

  button.addEventListener('click', toggleMenu);

  /* ================== FOCUS TRAP ================== */
  menu.addEventListener('keydown', (e) => {
    if (!menu.classList.contains('is-open')) return;
    if (e.key !== 'Tab') return;

    const focusables = menu.querySelectorAll(focusableSelectors);

    const first = focusables[0];
    const last = focusables[focusables.length - 1];

    // SHIFT + TAB (going backwards)
    if (e.shiftKey && document.activeElement === first) {
      e.preventDefault();
      last.focus();
    }

    // TAB (going forwards)
    if (!e.shiftKey && document.activeElement === last) {
      e.preventDefault();
      first.focus();
    }
  });

  /* ================== CLOSE ON LINK CLICK ================== */
  menu.addEventListener('click', (e) => {
    const isTopLink = e.target.closest('.site-nav__link');

    const isSubmenuToggle = e.target.classList.contains('submenu-toggle');

    if (isTopLink && !isSubmenuToggle) {
      closeMenu();
    }
  });

  /* ================== SWIPE TO CLOSE (mobile feel) ================== */
  let startX = 0;
  let isSwiping = false;

  menu.addEventListener('touchstart', (e) => {
    startX = e.touches[0].clientX;
    isSwiping = true;
  });

  menu.addEventListener('touchmove', (e) => {
    if (!isSwiping) return;

    const diff = e.touches[0].clientX - startX;

    // swipe right closes menu
    if (diff > 100) {
      isSwiping = false;
      closeMenu();
    }
  });

  menu.addEventListener('touchend', () => {
    isSwiping = false;
  });

  menu.addEventListener('touchcancel', () => {
    isSwiping = false;
  });

  updateMenuAccessibility();

  bindGlobalNavEvents();

  window.addEventListener('resize', () => {
    clearTimeout(resizeTimeout);

    resizeTimeout = setTimeout(() => {
      if (!isMobile()) {
        menu.classList.remove('is-open');
        button.classList.remove('is-open');
        backdrop.classList.remove('is-open');

        document.body.classList.remove('menu-open');

        // FULL reset of scroll lock
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

document.querySelectorAll('.has-submenu').forEach((menuItem) => {
  const toggle = menuItem.querySelector('.submenu-toggle');
  const submenu = menuItem.querySelector('.submenu');
  const links = submenu?.querySelectorAll('.submenu__link') || [];

  const openMenu = () => {
    menuItem.classList.add('is-open');
    toggle.setAttribute('aria-expanded', 'true');
  };

  const closeSubmenu = () => {
    menuItem.classList.remove('is-open');
    toggle.setAttribute('aria-expanded', 'false');
  };

  toggle.addEventListener('keydown', (e) => {
    switch (e.key) {
      case 'Enter':
      case ' ':
        e.preventDefault();
        menuItem.classList.toggle('is-open');
        toggle.setAttribute(
          'aria-expanded',
          menuItem.classList.contains('is-open'),
        );
        break;

      case 'ArrowDown':
        e.preventDefault();
        openMenu();
        links[0]?.focus();
        break;

      case 'ArrowUp':
        e.preventDefault();
        openMenu();
        links[links.length - 1]?.focus();
        break;

      case 'Escape':
        closeSubmenu();
        toggle.focus();
        break;
    }
  });

  links.forEach((link, index) => {
    link.addEventListener('keydown', (e) => {
      switch (e.key) {
        case 'ArrowDown':
          e.preventDefault();
          links[(index + 1) % links.length].focus();
          break;

        case 'ArrowUp':
          e.preventDefault();
          links[(index - 1 + links.length) % links.length].focus();
          break;

        case 'Escape':
          closeSubmenu();
          toggle.focus();
          break;
      }
    });
  });

  toggle.addEventListener('click', () => {
    const isOpen = menuItem.classList.toggle('is-open');
    toggle.setAttribute('aria-expanded', isOpen);
  });
});

document.addEventListener('DOMContentLoaded', () => {
  const currentPath = window.location.pathname.split('/').pop() || 'index.html';

  document.querySelectorAll('.site-nav__link').forEach((link) => {
    const linkPath = link.getAttribute('href');

    // skip external links
    if (!linkPath || linkPath.startsWith('http')) return;

    if (linkPath === currentPath) {
      link.classList.add('active');
      link.setAttribute('aria-current', 'page');
    } else {
      link.classList.remove('active');
      link.removeAttribute('aria-current');
    }
  });
});

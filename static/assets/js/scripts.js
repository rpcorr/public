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

    // disable whole menu properly
    menu.setAttribute('inert', '');
    menu.setAttribute('aria-hidden', 'true');

    document.querySelectorAll('.submenu, .submenu--nested').forEach((el) => {
      el.inert = true;
    });

    document.querySelectorAll('.has-submenu.is-open').forEach((item) => {
      item.classList.remove('is-open');
      item
        .querySelector('.submenu-toggle')
        ?.setAttribute('aria-expanded', 'false');
    });

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

    menu.removeAttribute('inert');
    menu.removeAttribute('aria-hidden');

    menu.querySelectorAll('.submenu, .submenu--nested').forEach((el) => {
      el.inert = true; // default closed state
    });

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
    const navFocusables = menu.querySelectorAll(focusableSelectors);
    const closeBtn = button; // hamburger acts as close button

    focusables = [closeBtn, ...navFocusables];

    firstFocusable = focusables[0];
    lastFocusable = focusables[focusables.length - 1];
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

  function closeAllSubmenus() {
    document.querySelectorAll('.has-submenu.is-open').forEach(closeSubmenu);
  }

  function onGlobalClick(e) {
    const clickedInsideNav = e.target.closest('.site-nav');
    const clickedHamburger = e.target.closest('.hamburger');
    const clickedSubmenuToggle = e.target.closest('.submenu-toggle');

    if (!clickedInsideNav && !clickedHamburger && !clickedSubmenuToggle) {
      closeMenu();
      return;
    }

    closeAllSubmenusIfOutside(e);
  }

  // function closeAllSubmenusIfOutside(e) {
  //   document.querySelectorAll('.has-submenu.is-open').forEach((item) => {
  //     if (!item.contains(e.target)) {
  //       closeSubmenu(item);
  //     }
  //   });
  // }

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

  /* ================== KEYBOARD NAVIGATION ================== */
  menu.addEventListener('keydown', (e) => {
    if (!document.body.classList.contains('menu-open')) return;

    /* ================== TAB (focus trap) ================== */
    if (e.key === 'Tab') {
      if (!firstFocusable || !lastFocusable) return;

      // SHIFT + TAB (backwards)
      if (e.shiftKey && document.activeElement === firstFocusable) {
        e.preventDefault();
        lastFocusable.focus();
      }

      // TAB (forwards)
      if (!e.shiftKey && document.activeElement === lastFocusable) {
        e.preventDefault();
        firstFocusable.focus();
      }

      return; // IMPORTANT: stop here
    }

    /* ================== ARROW NAV ================== */
    if (!['ArrowDown', 'ArrowUp'].includes(e.key)) return;

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

    let nextIndex;

    if (e.key === 'ArrowDown') {
      nextIndex = Math.min(currentIndex + 1, items.length - 1);
    } else {
      nextIndex = Math.max(currentIndex - 1, 0);
    }

    items[nextIndex]?.focus();
  });

  /* ================== CLOSE ON LINK CLICK ================== */
  menu.addEventListener('click', (e) => {
    const link = e.target.closest('.site-nav__link');
    const submenuToggle = e.target.closest('.submenu-toggle');

    // If it's a submenu toggle, DO NOTHING here
    if (submenuToggle) return;

    // If it's a real navigation link (not a parent toggle), close menu
    if (link && !submenuToggle) {
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
  const toggle = menuItem.querySelector(':scope > .submenu-toggle');
  const submenu = menuItem.querySelector('.submenu');
  const links = submenu?.querySelectorAll('.submenu__link') || [];

  function setSubmenuState(state) {
    menuItem.classList.toggle('is-open', state);
    toggle?.setAttribute('aria-expanded', String(state));

    if (submenu) {
      submenu.inert = !state;
    }
  }

  function openSubmenu() {
    setSubmenuState(true);
  }

  function closeSubmenu() {
    setSubmenuState(false);
  }

  toggle.addEventListener('click', (e) => {
    e.preventDefault();
    e.stopPropagation();

    const isOpen = menuItem.classList.contains('is-open');

    document.querySelectorAll('.has-submenu.is-open').forEach((item) => {
      if (item !== menuItem && !item.contains(menuItem)) {
        item.classList.remove('is-open');
        item
          .querySelector('.submenu-toggle')
          ?.setAttribute('aria-expanded', 'false');
      }
    });

    setSubmenuState(!isOpen);
  });

  toggle.addEventListener('keydown', (e) => {
    switch (e.key) {
      case 'Enter':
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

  menuItem.addEventListener('focusout', () => {
    requestAnimationFrame(() => {
      if (!menuItem.contains(document.activeElement)) {
        setSubmenuState(false);
      }
    });
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

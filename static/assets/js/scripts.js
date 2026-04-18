const button = document.querySelector('.hamburger');
const menu = document.querySelector('#primary-menu');
const backdrop = document.querySelector('.nav-backdrop');

if (!button || !menu || !backdrop) return;

const focusableSelectors =
  'a[href], button, input, textarea, select, [tabindex]:not([tabindex="-1"])';

let focusables = [];
let firstFocusable;
let lastFocusable;
let resizeTimeout;

let scrollY = 0;
let lastTrigger = null;

function setFocusableElements() {
  focusables = menu.querySelectorAll(focusableSelectors);

  firstFocusable = focusables.length ? focusables[0] : null;
  lastFocusable = focusables.length ? focusables[focusables.length - 1] : null;
}

function openMenu() {
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

  // move focus into menu
  if (firstFocusable && typeof firstFocusable.focus === 'function') {
    firstFocusable.focus();
  }
}

function closeMenu() {
  menu.classList.remove('is-open');
  button.classList.remove('is-open');
  backdrop.classList.remove('is-open');

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
  }, 300);

  // restore focus
  if (lastTrigger && document.contains(lastTrigger)) {
    lastTrigger.focus();
  } else {
    button.focus();
  }
}

function toggleMenu() {
  const isOpen = menu.classList.contains('is-open');
  isOpen ? closeMenu() : openMenu();
}

function isMobile() {
  return window.matchMedia('(max-width: 768px)').matches;
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
backdrop.addEventListener('click', closeMenu);

/* ================== ESC KEY ================== */
document.addEventListener('keydown', (e) => {
  if (e.key === 'Escape') {
    closeMenu();
    button.focus();
  }
});

/* ================== FOCUS TRAP ================== */
menu.addEventListener('keydown', (e) => {
  if (!menu.classList.contains('is-open')) return;
  if (e.key !== 'Tab') return;

  const focusables = [button, ...menu.querySelectorAll(focusableSelectors)];

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
menu.querySelectorAll('a').forEach((link) => {
  link.addEventListener('click', closeMenu);
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

const button = document.querySelector('.hamburger');
const menu = document.querySelector('#primary-menu');
const backdrop = document.querySelector('.nav-backdrop');

const focusableSelectors =
  'a[href], button, input, textarea, select, [tabindex]:not([tabindex="-1"])';

let focusables = [];
let firstFocusable;
let lastFocusable;

function setFocusableElements() {
  focusables = menu.querySelectorAll(focusableSelectors);

  firstFocusable = focusables[0];
  lastFocusable = focusables[focusables.length - 1];
}

function openMenu() {
  menu.classList.add('is-open');
  button.classList.add('is-open');
  backdrop.classList.add('is-open');

  button.setAttribute('aria-expanded', 'true');
  document.body.style.overflow = 'hidden';

  setFocusableElements();
  button.focus();
}

function closeMenu() {
  menu.classList.remove('is-open');
  button.classList.remove('is-open');
  backdrop.classList.remove('is-open');

  button.setAttribute('aria-expanded', 'false');

  setTimeout(() => {
    document.body.style.overflow = '';
  }, 300);

  button.focus();
}

function toggleMenu() {
  const isOpen = menu.classList.contains('is-open');
  isOpen ? closeMenu() : openMenu();
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

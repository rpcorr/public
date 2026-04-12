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
  menu.style.transform = '';
  menu.style.transition = '';
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

  menu.style.transform = '';
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
let isDragging = false;
let startX = 0;
let currentX = 0;
let menuWidth = 0;

/* hepler */
function setMenuTranslate(x) {
  menu.style.transform = `translateX(${x}px)`;
}

/* start drag */
menu.addEventListener('touchstart', (e) => {
  if (!menu.classList.contains('is-open')) return;

  isDragging = true;
  startX = e.touches[0].clientX;
  menuWidth = menu.offsetWidth;

  menu.style.transition = 'none';
});

/* move drag */
menu.addEventListener('touchmove', (e) => {
  if (!isDragging) return;

  currentX = e.touches[0].clientX;
  const diff = currentX - startX;

  // only allow dragging to the right (closing direction)
  if (diff > 0) {
    setMenuTranslate(diff);
  }
});

/* end drag (snap logic) */
menu.addEventListener('touchend', () => {
  if (!isDragging) return;

  isDragging = false;

  const diff = currentX - startX;

  menu.style.transition = 'transform 0.35s cubic-bezier(0.22, 1, 0.36, 1)';

  // threshold to close
  if (diff > menuWidth * 0.25) {
    setMenuTranslate(menuWidth);
    closeMenu();
  } else {
    setMenuTranslate(0);
  }
});

const button = document.querySelector('.hamburger');
const menu = document.querySelector('#primary-menu');
const backdrop = document.querySelector('.nav-backdrop');

function openMenu() {
  menu.classList.add('is-open');
  button.classList.add('is-open');
  backdrop.classList.add('is-open');

  button.setAttribute('aria-expanded', 'true');
  document.body.style.overflow = 'hidden';
}

function closeMenu() {
  menu.classList.remove('is-open');
  button.classList.remove('is-open');
  backdrop.classList.remove('is-open');

  button.setAttribute('aria-expanded', 'false');
  document.body.style.overflow = '';
}

function toggleMenu() {
  const isOpen = menu.classList.contains('is-open');
  isOpen ? closeMenu() : openMenu();
}

button.addEventListener('click', toggleMenu);
backdrop.addEventListener('click', closeMenu);

// Escape key closes EVERYTHING
document.addEventListener('keydown', (e) => {
  if (e.key === 'Escape') {
    closeMenu();
    button.focus();
  }
});

// Close when clicking links
menu.querySelectorAll('a').forEach((link) => {
  link.addEventListener('click', closeMenu);
});

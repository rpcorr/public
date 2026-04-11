const button = document.querySelector('.hamburger');
const menu = document.querySelector('#primary-menu');

button.addEventListener('click', () => {
  const isOpen = menu.classList.toggle('is-open');
  button.setAttribute('aria-expanded', isOpen);
});

// Close on Escape key
document.addEventListener('keydown', (e) => {
  if (e.key === 'Escape') {
    menu.classList.remove('is-open');
    button.setAttribute('aria-expanded', 'false');
    button.focus();
  }
});

menu.querySelectorAll('a').forEach((link) => {
  link.addEventListener('click', () => {
    menu.classList.remove('is-open');
    button.setAttribute('aria-expanded', 'false');
  });
});

document.addEventListener('DOMContentLoaded', () => {
  const button = document.querySelector('[data-nav-toggle]');
  const mobileNav = document.querySelector('[data-mobile-nav]');
  if (!button || !mobileNav) return;

  button.addEventListener('click', () => {
    mobileNav.classList.toggle('open');
  });
});

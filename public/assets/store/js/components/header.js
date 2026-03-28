document.addEventListener('DOMContentLoaded', () => {
  const button = document.querySelector('[data-nav-toggle]');
  const mobileNav = document.querySelector('[data-mobile-nav]');
  if (!button || !mobileNav) return;

  const closeNav = () => {
    mobileNav.classList.remove('open');
    button.setAttribute('aria-expanded', 'false');
  };

  const openNav = () => {
    mobileNav.classList.add('open');
    button.setAttribute('aria-expanded', 'true');
  };

  button.setAttribute('aria-expanded', 'false');

  button.addEventListener('click', () => {
    if (mobileNav.classList.contains('open')) {
      closeNav();
      return;
    }

    openNav();
  });

  document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape') {
      closeNav();
    }
  });

  document.addEventListener('click', (event) => {
    if (!mobileNav.classList.contains('open')) {
      return;
    }

    if (mobileNav.contains(event.target) || button.contains(event.target)) {
      return;
    }

    closeNav();
  });

  window.addEventListener('resize', () => {
    if (window.innerWidth >= 980) {
      closeNav();
    }
  });
});

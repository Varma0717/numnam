document.addEventListener('DOMContentLoaded', () => {
  const hamburgerBtn = document.getElementById('hamburgerBtn');
  const fullscreenMenu = document.getElementById('nnFullscreenMenu');
  const header = document.getElementById('siteHeader');

  if (!hamburgerBtn || !fullscreenMenu) return;

  const openMenu = () => {
    fullscreenMenu.classList.add('open');
    hamburgerBtn.classList.add('active');
    hamburgerBtn.setAttribute('aria-expanded', 'true');
    fullscreenMenu.setAttribute('aria-hidden', 'false');
    document.body.classList.add('nn-menu-open');
    if (header) header.classList.remove('header-hidden');
  };

  const closeMenu = () => {
    fullscreenMenu.classList.remove('open');
    hamburgerBtn.classList.remove('active');
    hamburgerBtn.setAttribute('aria-expanded', 'false');
    fullscreenMenu.setAttribute('aria-hidden', 'true');
    document.body.classList.remove('nn-menu-open');
  };

  hamburgerBtn.addEventListener('click', () => {
    if (fullscreenMenu.classList.contains('open')) {
      closeMenu();
    } else {
      openMenu();
    }
  });

  // Dedicated close button inside the menu
  const menuCloseBtn = document.getElementById('nnMenuCloseBtn');
  if (menuCloseBtn) {
    menuCloseBtn.addEventListener('click', closeMenu);
  }

  // Close on Escape key
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') closeMenu();
  });

  // Close when clicking the overlay backdrop (not the inner content)
  fullscreenMenu.addEventListener('click', (e) => {
    if (e.target === fullscreenMenu) closeMenu();
  });

  // Close menu on nav link click (SPA navigation)
  fullscreenMenu.querySelectorAll('.nn-menu-link').forEach((link) => {
    link.addEventListener('click', closeMenu);
  });

  // Scroll-based header style
  if (header) {
    const onScroll = () => {
      if (window.scrollY > 20) {
        header.classList.add('scrolled');
      } else {
        header.classList.remove('scrolled');
      }
    };
    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();
  }
});


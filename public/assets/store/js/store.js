document.addEventListener('DOMContentLoaded', () => {

  /* ====== Auto-dismiss alerts ====== */
  const autoDismiss = document.querySelectorAll('[data-auto-dismiss]');
  autoDismiss.forEach((node) => {
    window.setTimeout(() => {
      node.style.opacity = '0';
      node.style.transform = 'translateY(-6px)';
      node.style.transition = 'all .25s ease';
      window.setTimeout(() => node.remove(), 280);
    }, 3200);
  });

  /* ====== Back to Top ====== */
  const backToTop = document.getElementById('backToTop');
  if (backToTop) {
    window.addEventListener('scroll', () => {
      backToTop.classList.toggle('visible', window.scrollY > 400);
    }, { passive: true });
    backToTop.addEventListener('click', () => {
      window.scrollTo({ top: 0, behavior: 'smooth' });
    });
  }

  /* ====== Scroll Reveal (Intersection Observer) ====== */
  const fadeEls = document.querySelectorAll('.fade-in-up');
  if (fadeEls.length && 'IntersectionObserver' in window) {
    const observer = new IntersectionObserver((entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          entry.target.classList.add('is-visible');
          observer.unobserve(entry.target);
        }
      });
    }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });
    fadeEls.forEach((el) => observer.observe(el));
  }

  /* ====== Search Overlay ====== */
  const searchToggle = document.querySelector('[data-search-toggle]');
  const searchOverlay = document.getElementById('searchOverlay');
  const searchClose = document.querySelector('[data-search-close]');
  if (searchToggle && searchOverlay) {
    searchToggle.addEventListener('click', () => {
      searchOverlay.hidden = !searchOverlay.hidden;
      if (!searchOverlay.hidden) {
        searchOverlay.querySelector('input')?.focus();
      }
    });
    if (searchClose) {
      searchClose.addEventListener('click', () => searchOverlay.hidden = true);
    }
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape' && !searchOverlay.hidden) searchOverlay.hidden = true;
    });
  }

  /* ====== Toast Notification System ====== */
  window.numnamToast = function(message, type) {
    type = type || 'info';
    const container = document.getElementById('toast-container');
    if (!container) return;
    const toast = document.createElement('div');
    toast.className = 'toast toast-' + type;
    toast.textContent = message;
    container.appendChild(toast);
    window.setTimeout(() => {
      toast.classList.add('toast-out');
      window.setTimeout(() => toast.remove(), 300);
    }, 4000);
  };

  /* ====== FAQ Accordion ====== */
  document.querySelectorAll('.accordion-trigger').forEach((trigger) => {
    trigger.addEventListener('click', () => {
      const item = trigger.closest('.accordion-item');
      const panel = item.querySelector('.accordion-panel');
      const isOpen = item.classList.contains('open');
      // Close all siblings
      item.closest('.accordion').querySelectorAll('.accordion-item').forEach((sibling) => {
        sibling.classList.remove('open');
        const sp = sibling.querySelector('.accordion-panel');
        if (sp) sp.style.maxHeight = null;
      });
      if (!isOpen) {
        item.classList.add('open');
        panel.style.maxHeight = panel.scrollHeight + 'px';
      }
    });
  });

  /* ====== Product Gallery Lightbox ====== */
  const galleryMain = document.querySelector('.product-gallery-main img');
  const thumbs = document.querySelectorAll('.product-thumb');

  if (thumbs.length) {
    thumbs.forEach((thumb) => {
      thumb.addEventListener('click', () => {
        thumbs.forEach(t => t.classList.remove('active'));
        thumb.classList.add('active');
        if (galleryMain) {
          galleryMain.src = thumb.querySelector('img')?.src || thumb.dataset.src || '';
        }
      });
    });
  }

  // Lightbox open
  if (galleryMain) {
    galleryMain.closest('.product-gallery-main')?.addEventListener('click', () => {
      const lb = document.getElementById('productLightbox');
      if (lb) {
        lb.hidden = false;
        lb.querySelector('img').src = galleryMain.src;
      }
    });
  }
  const lbClose = document.querySelector('.lightbox-close');
  if (lbClose) {
    lbClose.addEventListener('click', () => {
      document.getElementById('productLightbox').hidden = true;
    });
  }

  /* ====== Account Tabs ====== */
  document.querySelectorAll('.account-tab').forEach((tab) => {
    tab.addEventListener('click', () => {
      const target = tab.dataset.tab;
      tab.closest('.account-tabs')?.querySelectorAll('.account-tab').forEach(t => t.classList.remove('active'));
      tab.classList.add('active');
      document.querySelectorAll('.account-panel').forEach(p => p.classList.remove('active'));
      const panel = document.getElementById(target);
      if (panel) panel.classList.add('active');
    });
  });

  /* ====== Hero Slider ====== */
  const slider = document.querySelector('.hero-slider');
  if (!slider) return;

  const track = slider.querySelector('[data-hero-track]');
  const slides = Array.from(slider.querySelectorAll('.hero-slide'));
  const prevBtn = slider.querySelector('[data-hero-prev]');
  const nextBtn = slider.querySelector('[data-hero-next]');
  const dots = Array.from(slider.querySelectorAll('[data-hero-dot]'));

  if (!track || !slides.length) return;

  let currentIndex = 0;
  let autoplayTimer = null;

  const render = () => {
    track.style.transform = `translateX(-${currentIndex * 100}%)`;
    dots.forEach((dot, index) => {
      dot.classList.toggle('is-active', index === currentIndex);
    });
  };

  const next = () => {
    currentIndex = (currentIndex + 1) % slides.length;
    render();
  };

  const prev = () => {
    currentIndex = (currentIndex - 1 + slides.length) % slides.length;
    render();
  };

  const restartAutoplay = () => {
    if (autoplayTimer) {
      window.clearInterval(autoplayTimer);
    }
    autoplayTimer = window.setInterval(next, 5000);
  };

  if (nextBtn) {
    nextBtn.addEventListener('click', () => {
      next();
      restartAutoplay();
    });
  }

  if (prevBtn) {
    prevBtn.addEventListener('click', () => {
      prev();
      restartAutoplay();
    });
  }

  dots.forEach((dot, index) => {
    dot.addEventListener('click', () => {
      currentIndex = index;
      render();
      restartAutoplay();
    });
  });

  render();
  restartAutoplay();
});

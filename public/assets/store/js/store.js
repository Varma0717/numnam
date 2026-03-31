document.addEventListener('DOMContentLoaded', () => {

  const safeStorage = {
    get(key) {
      try {
        return window.localStorage.getItem(key);
      } catch (_) {
        return null;
      }
    },
    set(key, value) {
      try {
        window.localStorage.setItem(key, value);
      } catch (_) {
        // Ignore storage write failures (private mode, disabled storage, etc.)
      }
    }
  };

  /* ====== Top Offer Banner ====== */
  const announcementBar = document.getElementById('announcementBar');
  if (announcementBar) {
    const announcementKey = announcementBar.dataset.announcementKey || 'numnam_announcement_closed_v1';
    const closeAnnouncementBtn = announcementBar.querySelector('[data-announcement-close]');

    if (safeStorage.get(announcementKey) === '1') {
      announcementBar.remove();
    } else if (closeAnnouncementBtn) {
      closeAnnouncementBtn.addEventListener('click', () => {
        safeStorage.set(announcementKey, '1');
        announcementBar.remove();
      });
    }
  }

  /* ====== First-Time Discount Popup ====== */
  const discountPopup = document.getElementById('discountPopup');
  if (discountPopup) {
    const body = document.body;
    const popupDismissKey = 'numnam_discount_popup_dismissed_v2';
    const dismissButtons = discountPopup.querySelectorAll('[data-discount-close]');
    const copyBtn = discountPopup.querySelector('[data-copy-discount]');
    const codeNode = discountPopup.querySelector('#discountCode');

    const closePopup = () => {
      discountPopup.classList.add('hidden');
      if (body) body.classList.remove('discount-popup-open');
      safeStorage.set(popupDismissKey, '1');
    };

    if (safeStorage.get(popupDismissKey) !== '1') {
      window.setTimeout(() => {
        discountPopup.classList.remove('hidden');
        if (body) body.classList.add('discount-popup-open');
      }, 1400);
    }

    dismissButtons.forEach((btn) => {
      btn.addEventListener('click', closePopup);
    });

    if (copyBtn && codeNode) {
      copyBtn.addEventListener('click', async () => {
        const code = codeNode.textContent ? codeNode.textContent.trim() : '';
        if (!code) return;

        try {
          await navigator.clipboard.writeText(code);
          copyBtn.textContent = 'Copied';
          window.setTimeout(() => {
            copyBtn.textContent = 'Copy Code';
          }, 1800);
        } catch (_) {
          copyBtn.textContent = code;
        }
      });
    }
  }

  /* ====== Breadcrumb Position (Below Banner) ====== */
  const mainContent = document.getElementById('main-content');
  if (mainContent) {
    const children = Array.from(mainContent.children || []);
    const breadcrumbNode = children.find((el) => el.classList && el.classList.contains('breadcrumbs'));
    const heroSection = children.find((el) => el.tagName === 'SECTION' && el.classList.contains('hero'));

    if (breadcrumbNode && heroSection) {
      heroSection.insertAdjacentElement('afterend', breadcrumbNode);
      breadcrumbNode.classList.add('breadcrumbs-below-banner');
    }
  }

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
  const animSelectors = '.fade-in-up,.animate-fade-up,.animate-fade-down,.animate-fade-left,.animate-fade-right,.animate-scale-in,.animate-blur-in,.stagger-children';
  const animEls = document.querySelectorAll(animSelectors);
  if (animEls.length && 'IntersectionObserver' in window) {
    const observer = new IntersectionObserver((entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          entry.target.classList.add('is-visible');
          observer.unobserve(entry.target);
        }
      });
    }, { threshold: 0.1, rootMargin: '0px 0px -50px 0px' });
    animEls.forEach((el) => observer.observe(el));
  }

  /* ====== Header Scroll State ====== */
  const siteHeader = document.querySelector('.site-header');
  if (siteHeader) {
    let lastScroll = 0;
    window.addEventListener('scroll', () => {
      const y = window.scrollY;
      siteHeader.classList.toggle('scrolled', y > 60);

      if (y > 140 && y > lastScroll) {
        siteHeader.classList.add('header-hidden');
      } else {
        siteHeader.classList.remove('header-hidden');
      }

      lastScroll = y;
    }, { passive: true });
  }

  /* ====== Count-Up Animation ====== */
  const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  const countEls = document.querySelectorAll('[data-count-to]');
  if (countEls.length && 'IntersectionObserver' in window) {
    const countObserver = new IntersectionObserver((entries) => {
      entries.forEach((entry) => {
        if (!entry.isIntersecting) return;
        const el = entry.target;
        const end = parseInt(el.dataset.countTo, 10) || 0;
        if (prefersReducedMotion) {
          el.textContent = end.toLocaleString();
          countObserver.unobserve(el);
          return;
        }
        const duration = 1600;
        const start = performance.now();
        const tick = (now) => {
          const progress = Math.min((now - start) / duration, 1);
          const eased = 1 - Math.pow(1 - progress, 3);
          el.textContent = Math.floor(eased * end).toLocaleString();
          if (progress < 1) requestAnimationFrame(tick);
          else el.textContent = end.toLocaleString();
        };
        requestAnimationFrame(tick);
        countObserver.unobserve(el);
      });
    }, { threshold: 0.3 });
    countEls.forEach((el) => countObserver.observe(el));
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

  /* ====== Search Autocomplete ====== */
  const searchForms = document.querySelectorAll('[data-search-form]');
  if (searchForms.length) {
    const formatCurrency = new Intl.NumberFormat('en-IN', {
      style: 'currency',
      currency: 'INR',
      maximumFractionDigits: 0,
    });

    const escapeHtml = (value) => String(value || '')
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;')
      .replace(/'/g, '&#039;');

    const debounce = (fn, wait) => {
      let timer = null;
      return (...args) => {
        if (timer) window.clearTimeout(timer);
        timer = window.setTimeout(() => fn(...args), wait);
      };
    };

    searchForms.forEach((form) => {
      const input = form.querySelector('[data-search-input]');
      const resultsBox = form.querySelector('[data-search-results]');
      const suggestUrl = form.dataset.suggestUrl;

      if (!input || !resultsBox || !suggestUrl) return;

      let latestQuery = '';
      let controller = null;

      const hideResults = () => {
        resultsBox.classList.add('hidden');
      };

      const renderResults = (items, query) => {
        if (!items.length) {
          resultsBox.innerHTML = `<p class="search-suggest-empty">No products found for "${escapeHtml(query)}".</p>`;
          resultsBox.classList.remove('hidden');
          return;
        }

        const html = items.map((item) => {
          const price = Number(item.price || 0);
          const rating = escapeHtml(item.rating || '4.8');
          const reviewCount = Number(item.reviewCount || 0).toLocaleString('en-IN');

          return `
            <a href="${escapeHtml(item.url || '#')}" class="search-suggest-item" data-search-suggest-link>
              <span class="truncate font-medium text-slate-800">${escapeHtml(item.name || '')}</span>
              <span class="shrink-0 text-xs text-slate-500">${formatCurrency.format(price)} • ${rating}★ (${reviewCount})</span>
            </a>
          `;
        }).join('');

        resultsBox.innerHTML = html;
        resultsBox.classList.remove('hidden');
      };

      const fetchSuggestions = async (query) => {
        if (controller) controller.abort();
        controller = new AbortController();

        try {
          const url = new URL(suggestUrl, window.location.origin);
          url.searchParams.set('q', query);

          const response = await fetch(url.toString(), {
            method: 'GET',
            headers: { 'Accept': 'application/json' },
            signal: controller.signal,
          });

          if (!response.ok) throw new Error('Suggestion request failed');

          const payload = await response.json();
          if (query !== latestQuery) return;

          const items = Array.isArray(payload.items) ? payload.items : [];
          renderResults(items, query);
        } catch (error) {
          if (error && error.name === 'AbortError') return;
          if (query !== latestQuery) return;
          renderResults([], query);
        }
      };

      const handleInput = debounce(() => {
        const query = input.value.trim();
        latestQuery = query;

        if (query.length < 2) {
          resultsBox.innerHTML = '';
          hideResults();
          return;
        }

        fetchSuggestions(query);
      }, 220);

      input.addEventListener('input', handleInput);

      input.addEventListener('focus', () => {
        if (resultsBox.innerHTML.trim() && input.value.trim().length >= 2) {
          resultsBox.classList.remove('hidden');
        }
      });

      input.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') hideResults();
      });

      form.addEventListener('submit', hideResults);

      form.addEventListener('click', (event) => {
        if (event.target.closest('[data-search-suggest-link]')) hideResults();
      });

      document.addEventListener('click', (event) => {
        if (!form.contains(event.target)) hideResults();
      });
    });
  }

  /* ====== Toast Notification System ====== */
  window.numnamToast = function (message, type) {
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
    }, 3200);
  };

  // Flush server-side flash queue once toast API is ready.
  const flashQueue = Array.isArray(window.__numnamFlashQueue) ? window.__numnamFlashQueue : [];
  if (flashQueue.length) {
    flashQueue.forEach((entry) => {
      if (!entry || !entry.message) return;
      window.numnamToast(entry.message, entry.type || 'info');
    });
    window.__numnamFlashQueue = [];
  }

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
      const tabList = tab.closest('.account-tabs');
      if (tabList) {
        tabList.querySelectorAll('.account-tab').forEach(t => {
          t.classList.remove('active');
          t.setAttribute('aria-selected', 'false');
        });
      }
      tab.classList.add('active');
      tab.setAttribute('aria-selected', 'true');
      document.querySelectorAll('.account-panel').forEach(p => p.classList.remove('active'));
      const panel = document.querySelector('[data-panel="' + target + '"]');
      if (panel) panel.classList.add('active');
    });
  });

  /* ====== Product Carousel ====== */
  const productCarousels = document.querySelectorAll('[data-product-carousel]');
  productCarousels.forEach((carousel) => {
    const track = carousel.querySelector('[data-carousel-track]');
    const prevBtn = carousel.closest('.section')?.querySelector('[data-carousel-prev]');
    const nextBtn = carousel.closest('.section')?.querySelector('[data-carousel-next]');
    const items = Array.from(carousel.querySelectorAll('.product-carousel-item'));
    if (!track || items.length <= 1) return;

    let index = 0;
    let maxIndex = 0;
    let perView = 3;
    let timer = null;

    const setPerView = () => {
      perView = window.matchMedia('(max-width: 979px)').matches ? 1 : 3;
      maxIndex = Math.max(0, items.length - perView);
      if (index > maxIndex) index = maxIndex;
    };

    const render = () => {
      setPerView();
      track.style.transform = `translateX(-${index * (100 / perView)}%)`;
      if (prevBtn) prevBtn.disabled = items.length <= perView;
      if (nextBtn) nextBtn.disabled = items.length <= perView;
    };

    const next = () => {
      if (maxIndex === 0) return;
      index = index >= maxIndex ? 0 : index + 1;
      render();
    };

    const prev = () => {
      if (maxIndex === 0) return;
      index = index <= 0 ? maxIndex : index - 1;
      render();
    };

    const restart = () => {
      if (timer) window.clearInterval(timer);
      if (items.length > perView) timer = window.setInterval(next, 4200);
    };

    if (nextBtn) {
      nextBtn.addEventListener('click', () => {
        next();
        restart();
      });
    }

    if (prevBtn) {
      prevBtn.addEventListener('click', () => {
        prev();
        restart();
      });
    }

    carousel.addEventListener('mouseenter', () => {
      if (timer) window.clearInterval(timer);
    });

    carousel.addEventListener('mouseleave', restart);
    window.addEventListener('resize', render, { passive: true });

    render();
    restart();
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

  /* ====== Product Detail Tabs ====== */
  const tabBtns = document.querySelectorAll('.product-tab[data-tab]');
  if (tabBtns.length) {
    tabBtns.forEach(btn => {
      btn.addEventListener('click', () => {
        const targetId = btn.getAttribute('data-tab');
        document.querySelectorAll('.product-tab').forEach(b => { b.classList.remove('active'); b.setAttribute('aria-selected', 'false'); });
        document.querySelectorAll('.product-tab-panel').forEach(p => p.classList.remove('active'));
        btn.classList.add('active');
        btn.setAttribute('aria-selected', 'true');
        const panel = document.getElementById(targetId);
        if (panel) panel.classList.add('active');
      });
    });
  }
});

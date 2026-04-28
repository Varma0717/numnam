(function() {
        function nnCarousel(type, dir) {
            var slides = document.querySelectorAll('.nn-' + type + '-slide');
            var current = 0;
            for (var i = 0; i < slides.length; i++) {
                if (slides[i].style.display !== 'none') {
                    current = i;
                    break;
                }
            }
            slides[current].style.display = 'none';
            var next = (current + dir + slides.length) % slides.length;
            slides[next].style.display = 'block';
        }
        window.nnCarousel = nnCarousel;
    }());

    // Transform-based full-page scroll controller
    (function() {
        var SECTIONS = document.querySelectorAll('.nn-fp-section');
        var current = 0;
        var isAnimating = false;
        var wrapper = document.getElementById('nn-fp-wrapper');
        var sectionOffsets = [];

        if (!wrapper || !SECTIONS.length) return;

        function sectionHeight() {
            var header = document.querySelector('.site-header');
            var hh = header ? header.offsetHeight : 100;
            document.documentElement.style.setProperty('--nn-header-h', hh + 'px');
            return window.innerHeight - hh;
        }

        function recalcLayout() {
            sectionHeight();
            sectionOffsets = Array.prototype.map.call(SECTIONS, function(section) {
                return section.offsetTop;
            });
        }

        function applyTransform() {
            var targetY = sectionOffsets[current] || 0;
            wrapper.style.transform = 'translate3d(0, ' + (-targetY) + 'px, 0)';
        }

        function goTo(index) {
            if (index < 0 || index >= SECTIONS.length) return;
            if (isAnimating || index === current) return;

            isAnimating = true;
            current = index;
            applyTransform();

            setTimeout(function() {
                isAnimating = false;
            }, 700);
        }

        window.addEventListener('wheel', function(e) {
            // Only intercept scroll when in the slide zone (page hasn't scrolled yet)
            if (window.scrollY > 20) return;
            var isLastSection = current === SECTIONS.length - 1;
            var isFirstSection = current === 0;

            if (e.deltaY > 0) {
                if (isLastSection) {
                    // Exit slide zone — scroll into normal page content
                    e.preventDefault();
                    document.documentElement.classList.remove('nn-slides-active');
                    window.scrollTo({
                        top: window.innerHeight,
                        behavior: 'smooth'
                    });
                    return;
                }
                e.preventDefault();
                goTo(current + 1);
            } else {
                if (isFirstSection) {
                    e.preventDefault();
                    return;
                }
                e.preventDefault();
                goTo(current - 1);
            }
        }, {
            passive: false
        });

        var startY = 0;

        window.addEventListener('touchstart', function(e) {
            startY = e.touches[0].clientY;
        }, {
            passive: true
        });

        window.addEventListener('touchend', function(e) {
            if (window.scrollY > 20) return;

            var endY = e.changedTouches[0].clientY;
            var diff = startY - endY;
            var isLastSection = current === SECTIONS.length - 1;
            var isFirstSection = current === 0;

            if (Math.abs(diff) < 50) return;

            if (diff > 0) {
                if (isLastSection) {
                    document.documentElement.classList.remove('nn-slides-active');
                    window.scrollTo({
                        top: window.innerHeight,
                        behavior: 'smooth'
                    });
                } else {
                    goTo(current + 1);
                }
            } else {
                if (!isFirstSection) goTo(current - 1);
            }
        }, {
            passive: true
        });

        recalcLayout();
        applyTransform();

        function updateSlidesMode() {
            if (window.scrollY <= 20) {
                document.documentElement.classList.add('nn-slides-active');
            } else {
                document.documentElement.classList.remove('nn-slides-active');
            }
        }

        updateSlidesMode();

        window.addEventListener('scroll', function() {
            updateSlidesMode();
        }, {
            passive: true
        });

        window.addEventListener('resize', function() {
            recalcLayout();
            applyTransform();
            updateSlidesMode();
        });

    })();

    (function() {
        var tabs = ['purees', 'puffs'];
        var activeStyle = 'background:#ffffff;color:#1A1A2E;border:2px solid #ffffff;';
        var inactiveStyle = 'background:transparent;color:rgba(255,255,255,0.70);border:2px solid rgba(255,255,255,0.35);';
        window.nnTab = function(active) {
            var allPanel = document.getElementById('tabpanel-all');
            if (allPanel) {
                allPanel.style.display = 'none';
            }
            tabs.forEach(function(t) {
                var btn = document.getElementById('tab-' + t);
                var panel = document.getElementById('tabpanel-' + t);
                if (!btn || !panel) {
                    return;
                }
                if (t === active) {
                    btn.style.cssText = 'font-family:\'Poppins\',sans-serif;font-weight:700;font-size:0.78rem;letter-spacing:0.12em;text-transform:uppercase;padding:10px 28px;border-radius:100px;cursor:pointer;transition:all 0.2s;' + activeStyle;
                    panel.style.display = 'grid';
                } else {
                    btn.style.cssText = 'font-family:\'Poppins\',sans-serif;font-weight:700;font-size:0.78rem;letter-spacing:0.12em;text-transform:uppercase;padding:10px 28px;border-radius:100px;cursor:pointer;transition:all 0.2s;' + inactiveStyle;
                    panel.style.display = 'none';
                }
            });
        };
    }());
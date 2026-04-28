(function () {

    /* =========================================================
       1. CAROUSEL
    ========================================================= */
    function nnCarousel(type, dir) {
        var slides = document.querySelectorAll('.nn-' + type + '-slide');
        if (!slides.length) return;

        var current = 0;

        slides.forEach(function (slide, i) {
            if (slide.style.display !== 'none') current = i;
        });

        slides[current].style.display = 'none';

        var next = (current + dir + slides.length) % slides.length;
        slides[next].style.display = 'block';
    }

    window.nnCarousel = nnCarousel;


    /* =========================================================
       2. FULL PAGE SLIDES (BABYGOURMET STYLE)
    ========================================================= */
    var WRAPPER = document.getElementById('nn-fp-wrapper');
    var FIXED = document.getElementById('nn-fp-fixed');

    // Only count sections that are currently visible (allows mobile-only slides)
    function getSections() {
        return Array.from(document.querySelectorAll('.nn-fp-section')).filter(function (s) {
            return getComputedStyle(s).display !== 'none';
        });
    }

    var SECTIONS = getSections();

    var current = 0;
    var isAnimating = false;
    var slidesActive = true;
    var fixedHideTimer = null;

    if (WRAPPER && SECTIONS.length) {

        function goTo(index) {
            if (index < 0 || index >= SECTIONS.length) return;
            if (isAnimating) return;

            isAnimating = true;
            current = index;

            WRAPPER.style.transform =
                'translate3d(0, -' + SECTIONS[index].offsetTop + 'px, 0)';

            setTimeout(function () { isAnimating = false; }, 650);
        }

        function exitSlides() {
            slidesActive = false;
            document.documentElement.classList.remove('nn-slides-active');
            window.scrollTo({ top: window.innerHeight, behavior: 'smooth' });

            // Keep slide 5 visible while the browser starts the smooth scroll.
            // Hiding the fixed panel too early causes a brief white flash.
            if (fixedHideTimer) window.clearTimeout(fixedHideTimer);
            fixedHideTimer = window.setTimeout(function () {
                if (FIXED && !slidesActive) FIXED.style.visibility = 'hidden';
            }, 380);
        }

        function reEnterSlides() {
            if (isAnimating) return;
            // Show fixed panel, re-enable slide lock, go to last slide
            if (fixedHideTimer) {
                window.clearTimeout(fixedHideTimer);
                fixedHideTimer = null;
            }
            if (FIXED) FIXED.style.visibility = '';
            document.documentElement.classList.add('nn-slides-active');
            window.scrollTo({ top: 0, behavior: 'instant' });
            slidesActive = true;
            goTo(SECTIONS.length - 1);
        }

        /* ===== WHEEL ===== */
        window.addEventListener('wheel', function (e) {

            // Not in slides — check if user wants to scroll back up into slides
            if (!slidesActive) {
                if (e.deltaY < 0 && window.scrollY <= window.innerHeight) {
                    e.preventDefault();
                    reEnterSlides();
                }
                return;
            }

            var isLast = current === SECTIONS.length - 1;

            if (e.deltaY > 0) {
                if (isLast) {
                    exitSlides();
                    return;
                }
                e.preventDefault();
                goTo(current + 1);
            } else {
                if (current === 0) {
                    e.preventDefault();
                    return;
                }
                e.preventDefault();
                goTo(current - 1);
            }

        }, { passive: false });


        /* ===== TOUCH ===== */
        var startY = 0;

        window.addEventListener('touchstart', function (e) {
            startY = e.touches[0].clientY;
        }, { passive: true });

        window.addEventListener('touchend', function (e) {
            var diff = startY - e.changedTouches[0].clientY;

            if (Math.abs(diff) < 50) return;

            // Not in slides — check if swiping down (scroll up) to re-enter
            if (!slidesActive) {
                if (diff < 0 && window.scrollY <= window.innerHeight) {
                    reEnterSlides();
                }
                return;
            }

            var isLast = current === SECTIONS.length - 1;

            if (diff > 0) {
                // Swipe up = scroll down
                if (isLast) {
                    exitSlides();
                } else {
                    goTo(current + 1);
                }
            } else {
                // Swipe down = scroll up
                if (current > 0) goTo(current - 1);
            }
        });

        /* ===== INIT ===== */
        function initSlides() {
            document.documentElement.classList.add('nn-slides-active');
            if (fixedHideTimer) {
                window.clearTimeout(fixedHideTimer);
                fixedHideTimer = null;
            }
            if (FIXED) FIXED.style.visibility = '';
            goTo(0);
        }

        initSlides();

        window.addEventListener('resize', function () {
            SECTIONS = getSections();
            if (current >= SECTIONS.length) current = SECTIONS.length - 1;
            if (slidesActive) goTo(current);
        });
    }


    /* =========================================================
       3. TABS
    ========================================================= */
    (function () {

        var tabs = ['purees', 'puffs'];

        window.nnTab = function (active) {

            var allPanel = document.getElementById('tabpanel-all');
            if (allPanel) allPanel.style.display = 'none';

            tabs.forEach(function (t) {

                var btn = document.getElementById('tab-' + t);
                var panel = document.getElementById('tabpanel-' + t);

                if (!btn || !panel) return;

                if (t === active) {
                    btn.classList.add('active-tab');
                    panel.style.display = 'grid';
                } else {
                    btn.classList.remove('active-tab');
                    panel.style.display = 'none';
                }
            });
        };

    })();

})();
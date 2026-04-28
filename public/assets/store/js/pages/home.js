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
    var SECTIONS = document.querySelectorAll('.nn-fp-section');
    var WRAPPER = document.getElementById('nn-fp-wrapper');

    var current = 0;
    var isAnimating = false;
    var slidesActive = true; // ONLY first time

    if (WRAPPER && SECTIONS.length) {

        function goTo(index) {
            if (index < 0 || index >= SECTIONS.length) return;
            if (isAnimating) return;

            isAnimating = true;
            current = index;

            WRAPPER.style.transform =
                'translate3d(0, -' + SECTIONS[index].offsetTop + 'px, 0)';

            setTimeout(function () {
                isAnimating = false;
            }, 600);
        }

        /* ===== WHEEL ===== */
        window.addEventListener('wheel', function (e) {

            if (!slidesActive) return;

            var isLast = current === SECTIONS.length - 1;

            if (e.deltaY > 0) {

                if (isLast) {
                    // EXIT SLIDES FOREVER
                    slidesActive = false;

                    document.documentElement.classList.remove('nn-slides-active');
                    document.documentElement.classList.add('nn-slides-done');

                    window.scrollTo({
                        top: window.innerHeight,
                        behavior: 'smooth'
                    });

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
        });

        window.addEventListener('touchend', function (e) {

            if (!slidesActive) return;

            var diff = startY - e.changedTouches[0].clientY;

            if (Math.abs(diff) < 50) return;

            var isLast = current === SECTIONS.length - 1;

            if (diff > 0) {

                if (isLast) {
                    slidesActive = false;

                    document.documentElement.classList.remove('nn-slides-active');
                    document.documentElement.classList.add('nn-slides-done');

                    window.scrollTo({
                        top: window.innerHeight,
                        behavior: 'smooth'
                    });

                } else {
                    goTo(current + 1);
                }

            } else {
                if (current > 0) goTo(current - 1);
            }

        });

        /* ===== INIT ===== */
        function initSlides() {
            document.documentElement.classList.add('nn-slides-active');
            goTo(0);
        }

        initSlides();

        window.addEventListener('resize', function () {
            goTo(current);
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
(function () {

    /* =========================
       CONFIG
    ========================== */
    var SECTIONS = document.querySelectorAll('.nn-fp-section');
    var WRAPPER = document.getElementById('nn-fp-wrapper');

    if (!WRAPPER || !SECTIONS.length) return;

    var current = 0;
    var isAnimating = false;
    var isSlidesMode = true;
    var sectionOffsets = [];
    var HEADER_OFFSET = 0;


    /* =========================
       HELPERS
    ========================== */
    function getHeaderHeight() {
        var header = document.querySelector('.site-header');
        return header ? header.offsetHeight : 0;
    }

    function recalcLayout() {
        HEADER_OFFSET = getHeaderHeight();

        sectionOffsets = Array.prototype.map.call(SECTIONS, function (section) {
            return section.offsetTop;
        });

        document.documentElement.style.setProperty('--nn-header-h', HEADER_OFFSET + 'px');
    }

    function applyTransform() {
        var targetY = sectionOffsets[current] || 0;
        WRAPPER.style.transform = 'translate3d(0, ' + (-targetY) + 'px, 0)';
    }

    function enableSlidesMode(index) {
        isSlidesMode = true;
        document.documentElement.classList.add('nn-slides-active');

        if (typeof index === 'number') {
            current = index;
        }

        applyTransform();
    }

    function disableSlidesMode() {
        isSlidesMode = false;
        document.documentElement.classList.remove('nn-slides-active');
    }

    function goTo(index) {
        if (index < 0 || index >= SECTIONS.length) return;
        if (isAnimating) return;

        isAnimating = true;
        current = index;
        applyTransform();

        setTimeout(function () {
            isAnimating = false;
        }, 650);
    }


    /* =========================
       WHEEL CONTROL
    ========================== */
    window.addEventListener('wheel', function (e) {

        if (!isSlidesMode) return;

        var isLast = current === SECTIONS.length - 1;
        var isFirst = current === 0;

        if (e.deltaY > 0) {
            // scroll down
            if (isLast) {
                // EXIT slides
                disableSlidesMode();

                window.scrollTo({
                    top: window.innerHeight + 5,
                    behavior: 'smooth'
                });

                return;
            }

            e.preventDefault();
            goTo(current + 1);

        } else {
            // scroll up
            if (isFirst) {
                e.preventDefault();
                return;
            }

            e.preventDefault();
            goTo(current - 1);
        }

    }, { passive: false });


    /* =========================
       TOUCH (MOBILE)
    ========================== */
    var startY = 0;

    window.addEventListener('touchstart', function (e) {
        startY = e.touches[0].clientY;
    }, { passive: true });

    window.addEventListener('touchend', function (e) {

        if (!isSlidesMode) return;

        var endY = e.changedTouches[0].clientY;
        var diff = startY - endY;

        if (Math.abs(diff) < 50) return;

        var isLast = current === SECTIONS.length - 1;
        var isFirst = current === 0;

        if (diff > 0) {
            // swipe up
            if (isLast) {
                disableSlidesMode();

                window.scrollTo({
                    top: window.innerHeight + 5,
                    behavior: 'smooth'
                });

            } else {
                goTo(current + 1);
            }

        } else {
            // swipe down
            if (!isFirst) {
                goTo(current - 1);
            }
        }

    }, { passive: true });


    /* =========================
       SCROLL (RE-ENTRY FIX 🔥)
    ========================== */
    window.addEventListener('scroll', function () {

        var scrollY = window.scrollY;

        // When user scrolls back up into slide zone
        if (scrollY <= window.innerHeight) {

            if (!isSlidesMode) {
                enableSlidesMode(SECTIONS.length - 1); // jump to last slide (5th)
            }

        } else {
            disableSlidesMode();
        }

    }, { passive: true });


    /* =========================
       INIT
    ========================== */
    function init() {
        recalcLayout();
        enableSlidesMode(0);
    }

    init();

    window.addEventListener('resize', function () {
        recalcLayout();
        applyTransform();
    });

})();
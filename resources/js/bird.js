/**
 * NumNam Bird Mascot
 * Cute animated bird that follows the cursor across the page.
 * Uses spring physics so movement feels organic and alive.
 */
(function () {
    'use strict';

    var _base = (function () {
        var m = document.querySelector('meta[name="asset-base"]');
        return m ? m.getAttribute('content') : '';
    }());
    var VIEW_SRC = {
        sideLeft: _base + '/assets/images/mascot-bird/side_left_view.svg',
        sideRight: _base + '/assets/images/mascot-bird/side_right_view.svg',
        front: _base + '/assets/images/mascot-bird/front_view.svg',
        back: _base + '/assets/images/mascot-bird/back_view.svg',
        threeQuarter: _base + '/assets/images/mascot-bird/3by4th_view.svg'
    };

    function preloadViews() {
        Object.keys(VIEW_SRC).forEach(function (key) {
            var img = new Image();
            img.src = VIEW_SRC[key];
        });
    }

    /* ── Create DOM element ──────────────────────────────────────────── */
    function buildBird() {
        var el = document.createElement('div');
        el.id = 'bird-mascot';
        el.className = 'bird-mascot';
        el.setAttribute('aria-hidden', 'true');

        var img = document.createElement('img');
        img.className = 'bird-svg';
        img.alt = '';
        img.decoding = 'async';
        img.loading = 'eager';
        img.src = VIEW_SRC.sideRight;
        img.setAttribute('data-bird-view', 'sideRight');
        img.style.transformOrigin = 'center center';

        el.appendChild(img);
        document.body.appendChild(el);
        return el;
    }

    function setBirdView(birdImg, view) {
        var currentView = birdImg.getAttribute('data-bird-view');
        var desiredSrc = VIEW_SRC[view] || VIEW_SRC.sideRight;

        if (currentView !== view) {
            birdImg.src = desiredSrc;
            birdImg.setAttribute('data-bird-view', view);
        }
    }

    function shouldDisableBird() {
        if (typeof window === 'undefined' || !window.matchMedia) {
            return false;
        }

        return window.matchMedia('(max-width: 1023px), (hover: none) and (pointer: coarse)').matches;
    }

    /* ── Animation loop ─────────────────────────────────────────────── */
    function init() {
        if (shouldDisableBird()) {
            return;
        }

        preloadViews();
        var bird = buildBird();
        var birdImg = bird.querySelector('.bird-svg');

        /* Current bird position */
        var bx = window.innerWidth * 0.65;
        var by = window.innerHeight * 0.6;

        /* Target (cursor or idle wander) */
        var tx = bx;
        var ty = by;

        /* Velocity */
        var vx = 0;
        var vy = 0;

        /* Idle wander state */
        var idle = true;
        var idleAngle = 0;
        var idleTimer = null;

        /* ── Idle wander ────────────────────────────────────────────── */
        function startIdle() {
            idle = true;
        }

        /* ── Mouse tracking ─────────────────────────────────────────── */
        document.addEventListener('mousemove', function (e) {
            /* Offset: beak tip is the cursor point (bird is 72 px wide) */
            tx = e.clientX - 68;
            ty = e.clientY - 38;
            idle = false;
            clearTimeout(idleTimer);
            idleTimer = setTimeout(startIdle, 5000);
        });

        /* ── Click = excited flutter ─────────────────────────────────── */
        document.addEventListener('click', function () {
            bird.classList.add('bird-excited');
            setTimeout(function () {
                bird.classList.remove('bird-excited');
            }, 800);
        });

        /* ── Spring physics tick ─────────────────────────────────────── */
        function tick() {
            if (idle) {
                idleAngle += 0.007;
                tx = window.innerWidth * 0.5 + Math.sin(idleAngle) * (window.innerWidth * 0.31);
                ty = window.innerHeight * 0.38 + Math.sin(idleAngle * 1.9) * (window.innerHeight * 0.13);
            }

            /* Spring toward target */
            var ax = (tx - bx) * 0.042;
            var ay = (ty - by) * 0.042;
            vx = (vx + ax) * 0.80;
            vy = (vy + ay) * 0.80;
            bx += vx;
            by += vy;

            /* Clamp inside viewport */
            bx = Math.max(0, Math.min(window.innerWidth - 72, bx));
            by = Math.max(0, Math.min(window.innerHeight - 72, by));

            var absX = Math.abs(vx);
            var absY = Math.abs(vy);
            var view = 'front';
            var mirror = false;

            /* Pick one of the 5 supplied mascot views from motion vector */
            if (absX > 1.1 && absY > 1.1) {
                view = 'threeQuarter';
                mirror = vx < 0;
            } else if (absX >= absY) {
                view = vx < 0 ? 'sideLeft' : 'sideRight';
            } else {
                view = vy < 0 ? 'back' : 'front';
            }

            setBirdView(birdImg, view);

            bird.style.left = Math.round(bx) + 'px';
            bird.style.top = Math.round(by) + 'px';
            bird.style.transform = mirror ? 'scaleX(-1)' : 'none';

            requestAnimationFrame(tick);
        }

        /* Kick off idle wander after 1 s then start the loop */
        setTimeout(startIdle, 1000);
        tick();
    }

    /* ── Boot ────────────────────────────────────────────────────────── */
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();

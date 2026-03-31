/**
 * NumNam Bird Mascot
 * Cute animated bird that follows the cursor across the page.
 * Uses spring physics so movement feels organic and alive.
 */
(function () {
    'use strict';

    /* ── SVG markup ──────────────────────────────────────────────────── */
    var BIRD_SVG = [
        '<svg viewBox="0 0 80 80" fill="none" xmlns="http://www.w3.org/2000/svg" class="bird-svg" aria-hidden="true">',

        /* Back wing */
        '<g class="bird-wing-back">',
        '  <ellipse cx="34" cy="54" rx="18" ry="9" fill="#fecd26" transform="rotate(-22 34 54)"/>',
        '</g>',

        /* Body */
        '<ellipse cx="42" cy="57" rx="20" ry="16" fill="#fe7d94"/>',

        /* Belly highlight */
        '<ellipse cx="46" cy="62" rx="11" ry="9" fill="#f1dbc0"/>',

        /* Head */
        '<circle cx="59" cy="42" r="14" fill="#fe7d94"/>',

        /* Beak */
        '<polygon points="70,39 83,44 70,49" fill="#fecd26"/>',

        /* Eye white */
        '<circle cx="62" cy="39" r="5" fill="white"/>',

        /* Pupil */
        '<circle cx="63" cy="39" r="2.4" fill="#1a1a2e"/>',

        /* Eye shine */
        '<circle cx="64" cy="38" r="1.1" fill="white"/>',

        /* Cheek blush */
        '<ellipse cx="67" cy="47" rx="4.5" ry="3" fill="#fc5d4d" opacity="0.35"/>',

        /* Front wing */
        '<g class="bird-wing-front">',
        '  <ellipse cx="30" cy="51" rx="14" ry="7" fill="#fc5d4d" transform="rotate(-18 30 51)"/>',
        '</g>',

        /* Tail feathers */
        '<polygon points="24,62 6,70 11,62 6,74 26,66" fill="#b3b7ec"/>',

        '</svg>',
    ].join('');

    /* ── Create DOM element ──────────────────────────────────────────── */
    function buildBird() {
        var el = document.createElement('div');
        el.id = 'bird-mascot';
        el.className = 'bird-mascot';
        el.setAttribute('aria-hidden', 'true');
        el.innerHTML = BIRD_SVG;
        document.body.appendChild(el);
        return el;
    }

    /* ── Animation loop ─────────────────────────────────────────────── */
    function init() {
        var bird = buildBird();

        /* Current bird position */
        var bx = window.innerWidth * 0.65;
        var by = window.innerHeight * 0.6;

        /* Target (cursor or idle wander) */
        var tx = bx;
        var ty = by;

        /* Velocity */
        var vx = 0;
        var vy = 0;

        /* Flipped (facing left) state */
        var flipped = false;

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

            /* Flip to face direction of travel */
            if (vx < -1.2) { flipped = true; }
            else if (vx > 1.2) { flipped = false; }

            bird.style.left = Math.round(bx) + 'px';
            bird.style.top = Math.round(by) + 'px';
            bird.style.transform = flipped ? 'scaleX(-1)' : 'none';

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

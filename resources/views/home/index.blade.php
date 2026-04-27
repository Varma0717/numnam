<!DOCTYPE html>
<html lang="en" class="hp-enabled">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $homepage?->meta_title ?? 'NumNam' }}</title>
    <meta name="description" content="{{ $homepage?->meta_description ?? 'NumNam homepage' }}">
    <style>
        /* ── Reset & base ───────────────────────────────── */
        html.hp-enabled,
        .hp-enabled body {
            margin: 0;
            padding: 0;
            overflow: hidden;
            -webkit-tap-highlight-color: rgba(0, 0, 0, 0);
        }

        /* ── Scrolling wrapper — slides vertically ───────── */
        .hp-wrapper {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            will-change: transform;
            -webkit-transition: transform 0.75s cubic-bezier(0.77, 0, 0.175, 1);
            transition: transform 0.75s cubic-bezier(0.77, 0, 0.175, 1);
        }

        .hp-wrapper.hp-no-transition {
            -webkit-transition: none !important;
            transition: none !important;
        }

        /* ── Individual section ──────────────────────────── */
        .hp-section {
            position: relative;
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            box-sizing: border-box;
            height: 100vh;
            height: calc(var(--vh, 1vh) * 100);
            width: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            overflow: hidden;
        }

        .hp-section>section {
            /* inner <section> from each blade partial */
            height: 100%;
            box-sizing: border-box;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        /* ── Side navigation dots ─────────────────────────── */
        #hp-nav {
            position: fixed;
            z-index: 100;
            top: 50%;
            right: 17px;
            transform: translateY(-50%);
            -webkit-transform: translate3d(0, -50%, 0);
            pointer-events: none;
        }

        #hp-nav ul {
            margin: 0;
            padding: 0;
            list-style: none;
        }

        #hp-nav ul li {
            display: block;
            width: 14px;
            height: 13px;
            margin: 7px;
            position: relative;
        }

        #hp-nav ul li a {
            display: block;
            position: relative;
            z-index: 1;
            width: 100%;
            height: 100%;
            cursor: pointer;
            text-decoration: none;
            pointer-events: all;
        }

        #hp-nav ul li a span {
            border-radius: 50%;
            position: absolute;
            z-index: 1;
            height: 4px;
            width: 4px;
            border: 0;
            background: #333;
            left: 50%;
            top: 50%;
            margin: -2px 0 0 -2px;
            -webkit-transition: all 0.1s ease-in-out;
            -moz-transition: all 0.1s ease-in-out;
            -o-transition: all 0.1s ease-in-out;
            transition: all 0.1s ease-in-out;
        }

        #hp-nav ul li:hover a span {
            width: 10px;
            height: 10px;
            margin: -5px 0 0 -5px;
        }

        #hp-nav ul li a.hp-active span,
        #hp-nav ul li:hover a.hp-active span {
            height: 12px;
            width: 12px;
            margin: -6px 0 0 -6px;
            border-radius: 100%;
        }

        #hp-nav ul li .hp-tooltip {
            position: absolute;
            top: -2px;
            right: 20px;
            color: #fff;
            font-size: 13px;
            font-family: arial, helvetica, sans-serif;
            white-space: nowrap;
            max-width: 200px;
            overflow: hidden;
            display: block;
            opacity: 0;
            width: 0;
            cursor: pointer;
            pointer-events: none;
            background: rgba(0, 0, 0, 0.55);
            border-radius: 4px;
            padding: 2px 8px;
        }

        #hp-nav ul li:hover .hp-tooltip {
            -webkit-transition: opacity 0.2s ease-in;
            transition: opacity 0.2s ease-in;
            width: auto;
            opacity: 1;
        }

        /* ── Shared layout helpers (used by section partials) */
        body {
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            color: #1f2937;
            background: #f8fafc;
        }

        .wrap {
            width: min(1120px, 92vw);
            margin: 0 auto;
        }

        h1,
        h2,
        h3 {
            margin: 0 0 12px;
        }

        p {
            margin: 0 0 10px;
            line-height: 1.6;
        }

        .btn {
            display: inline-block;
            padding: 11px 16px;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 600;
        }

        .btn-primary {
            background: #0f766e;
            color: #fff;
        }

        .btn-light {
            background: #fff;
            color: #0f766e;
        }

        .grid-2 {
            display: grid;
            gap: 20px;
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .grid-3 {
            display: grid;
            gap: 16px;
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }

        .card {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 16px;
        }

        .muted {
            color: #6b7280;
        }

        @media (max-width: 900px) {

            .grid-2,
            .grid-3 {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
    @php $sectionList = collect($sections); @endphp

    {{-- Full-page wrapper --}}
    <div class="hp-wrapper" id="hp-wrapper">
        @forelse($sectionList as $section)
        <div class="hp-section" data-index="{{ $loop->index }}" data-label="{{ e($section->title ?? $section->section_type) }}">
            @includeIf('home.sections.' . $section->section_type, ['section' => $section])
        </div>
        @empty
        <div class="hp-section">
            <section>
                <div class="wrap card">
                    <h2>Homepage not configured yet</h2>
                    <p class="muted">Create a homepage and add sections from the admin dashboard.</p>
                </div>
            </section>
        </div>
        @endforelse
    </div>

    {{-- Side nav dots (only when >1 section) --}}
    @if($sectionList->count() > 1)
    <nav id="hp-nav" aria-label="Page sections">
        <ul>
            @foreach($sectionList as $section)
            <li>
                <a href="#{{ $loop->index }}" data-index="{{ $loop->index }}" class="{{ $loop->first ? 'hp-active' : '' }}">
                    <span></span>
                </a>
                <span class="hp-tooltip">{{ e($section->title ?? $section->section_type) }}</span>
            </li>
            @endforeach
        </ul>
    </nav>
    @endif

    <script>
        (function() {
            var wrapper = document.getElementById('hp-wrapper');
            var navLinks = document.querySelectorAll('#hp-nav a[data-index]');
            var sections = document.querySelectorAll('.hp-section');
            var total = sections.length;
            var current = 0;
            var animating = false;
            var DURATION = 750; // must match CSS transition ms

            /* ── vh fix for mobile browsers ─────────────── */
            function setVh() {
                document.documentElement.style.setProperty('--vh', (window.innerHeight * 0.01) + 'px');
            }
            setVh();
            window.addEventListener('resize', setVh);

            /* ── Move to a section by index ─────────────── */
            function goTo(index, instant) {
                if (index < 0 || index >= total) return;
                if (index === current && !instant) return;
                if (animating) return;

                animating = true;
                current = index;

                if (instant) {
                    wrapper.classList.add('hp-no-transition');
                } else {
                    wrapper.classList.remove('hp-no-transition');
                }

                wrapper.style.transform = 'translate3d(0, ' + (-index * 100) + 'vh, 0)';

                // update nav dots
                navLinks.forEach(function(a) {
                    a.classList.toggle('hp-active', parseInt(a.dataset.index) === current);
                });

                // unlock after transition
                setTimeout(function() {
                    wrapper.classList.remove('hp-no-transition');
                    animating = false;
                }, instant ? 0 : DURATION);
            }

            /* ── Mouse-wheel ─────────────────────────────── */
            var lastWheel = 0;
            window.addEventListener('wheel', function(e) {
                e.preventDefault();
                var now = Date.now();
                if (now - lastWheel < 900) return; // throttle
                lastWheel = now;
                if (e.deltaY > 0) goTo(current + 1);
                else goTo(current - 1);
            }, {
                passive: false
            });

            /* ── Touch ───────────────────────────────────── */
            var touchStartY = 0;
            window.addEventListener('touchstart', function(e) {
                touchStartY = e.touches[0].clientY;
            }, {
                passive: true
            });
            window.addEventListener('touchend', function(e) {
                var diff = touchStartY - e.changedTouches[0].clientY;
                if (Math.abs(diff) < 50) return;
                if (diff > 0) goTo(current + 1);
                else goTo(current - 1);
            }, {
                passive: true
            });

            /* ── Keyboard ────────────────────────────────── */
            window.addEventListener('keydown', function(e) {
                if (e.key === 'ArrowDown' || e.key === 'PageDown') {
                    e.preventDefault();
                    goTo(current + 1);
                }
                if (e.key === 'ArrowUp' || e.key === 'PageUp') {
                    e.preventDefault();
                    goTo(current - 1);
                }
                if (e.key === 'Home') {
                    e.preventDefault();
                    goTo(0);
                }
                if (e.key === 'End') {
                    e.preventDefault();
                    goTo(total - 1);
                }
            });

            /* ── Nav dot clicks ──────────────────────────── */
            navLinks.forEach(function(a) {
                a.addEventListener('click', function(e) {
                    e.preventDefault();
                    goTo(parseInt(a.dataset.index));
                });
            });

            /* ── Hash on load ────────────────────────────── */
            var hash = parseInt(window.location.hash.replace('#', ''));
            if (!isNaN(hash) && hash > 0 && hash < total) {
                goTo(hash, true);
            }
        }());
    </script>
</body>

</html>
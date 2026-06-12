@extends('store.layouts.app')

@section('title', 'NumNam Weaning Tracker')
@section('meta_description', 'Track your baby\'s weaning journey with recipes, community chat, and poop diagnostics.')

@section('head')
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@600;700;800&display=swap" rel="stylesheet">

<style>
    /* Hide breadcrumbs and alerts for tracker page */
    nav.breadcrumbs,
    .alerts-container,
    .page-shell>nav:first-of-type {
        display: none !important;
    }

    main#main-content {
        padding: 0 !important;
        max-width: 100% !important;
        margin-top: 100px;
    }

    main#main-content>.page {
        display: block !important;
        padding: 0 !important;
        margin: 0 !important;
        width: 100% !important;
    }

    /* Tracker wrapper - match website style */
    .numnam-tracker-wrapper {
        background: #FFFDF8;
        color: #1a1a1a;
        font-family: 'Inter', sans-serif;
        padding: 0;
        margin: 0;
        display: block !important;
        visibility: visible !important;
        opacity: 1 !important;
        width: 100% !important;
        position: relative;
        z-index: 1;
        overflow: visible;
    }

    /* Tracker header - match site header */
    .numnam-header {
        background: white;
        border-bottom: 3px solid #FF6B8A;
        padding: 20px 0;
        display: flex !important;
        align-items: center;
        justify-content: center;
        position: sticky;
        top: 100px;
        z-index: 50;
        width: 100%;
        margin: 0;
        box-sizing: border-box;
    }

    .logo-container {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 30px;
        max-width: 1400px;
        width: 100%;
        padding: 0 20px;
    }

    .tracker-logo {
        font-family: 'Poppins', sans-serif;
        font-size: 24px;
        font-weight: 700;
        color: #1a1a1a;
        letter-spacing: -0.5px;
    }

    .tracker-logo span {
        color: #FF6B8A;
    }

    .baby-age-pill {
        background: #FFF0F5;
        color: #FF6B8A;
        border-radius: 20px;
        padding: 8px 16px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        border: 1.5px solid #FFD6E5;
        transition: all 0.2s;
        white-space: nowrap;
        font-family: 'Inter', sans-serif;
    }

    .baby-age-pill:hover {
        background: #FFE5F0;
        border-color: #FF6B8A;
    }

    /* Tabs - match website style */
    .tabs {
        display: flex !important;
        background: white;
        border-bottom: 1px solid #FFD6E5;
        overflow-x: auto;
        gap: 0;
        width: 100%;
        margin: 0 !important;
        box-sizing: border-box;
        justify-content: center;
        position: relative;
        z-index: 40;
    }

    .tab {
        padding: 16px 24px;
        font-size: 14px;
        font-weight: 600;
        color: #999;
        cursor: pointer;
        border-bottom: 3px solid transparent;
        white-space: nowrap;
        transition: all 0.2s;
        flex-shrink: 0;
        background: none;
        border: none;
        font-family: 'Inter', sans-serif;
    }

    .tab.active {
        color: #FF6B8A;
        border-bottom-color: #FF6B8A;
    }

    .tab:hover:not(.active) {
        color: #1a1a1a;
    }

    /* Tracker page - match main content area */
    .tracker-page {
        display: none !important;
        max-width: 1400px;
        margin: 0 auto;
        width: 100%;
        padding: 40px 20px;
        box-sizing: border-box;
    }

    .tracker-page.active {
        display: block !important;
    }

    /* Cards */
    .card {
        background: white;
        border: 1px solid #FFD6E5;
        border-radius: 12px;
        padding: 24px;
        margin-bottom: 20px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
    }

    .card-title {
        font-family: 'Poppins', sans-serif;
        font-size: 18px;
        font-weight: 700;
        color: #1a1a1a;
        margin-bottom: 8px;
    }

    .card-sub {
        font-size: 13px;
        color: #999;
        margin-bottom: 16px;
    }

    .section-title {
        font-family: 'Poppins', sans-serif;
        font-size: 24px;
        font-weight: 700;
        color: #1a1a1a;
        margin-bottom: 8px;
    }

    .section-sub {
        font-size: 14px;
        color: #999;
        margin-bottom: 24px;
    }

    /* Buttons */
    .btn {
        padding: 12px 20px;
        border-radius: 8px;
        border: none;
        cursor: pointer;
        font-family: 'Inter', sans-serif;
        font-size: 14px;
        font-weight: 600;
        transition: all 0.2s;
    }

    .btn-primary {
        background: #FF6B8A;
        color: white;
    }

    .btn-primary:hover {
        background: #FF5578;
        transform: translateY(-2px);
    }

    .btn-outline {
        background: transparent;
        border: 1.5px solid #FFD6E5;
        color: #1a1a1a;
    }

    .btn-outline:hover {
        background: #FFF0F5;
    }

    .btn-row {
        display: flex;
        gap: 12px;
        margin-top: 16px;
    }

    /* Dashboard grid */
    .dash-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 16px;
        margin-bottom: 20px;
    }

    .dash-stat {
        background: #FFF0F5;
        border: 1px solid #FFD6E5;
        border-radius: 10px;
        padding: 18px;
        text-align: center;
    }

    .dash-stat .num {
        font-family: 'Poppins', sans-serif;
        font-size: 28px;
        font-weight: 700;
        line-height: 1;
        margin-bottom: 8px;
        color: #FF6B8A;
    }

    .dash-stat .lbl {
        font-size: 12px;
        color: #999;
        font-weight: 500;
    }

    /* Forms */
    .form-row {
        margin-bottom: 16px;
    }

    .form-row label {
        display: block;
        font-size: 13px;
        font-weight: 600;
        color: #1a1a1a;
        margin-bottom: 8px;
    }

    .form-row input,
    .form-row select {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #FFD6E5;
        border-radius: 8px;
        font-family: 'Inter', sans-serif;
        font-size: 14px;
        background: white;
        color: #1a1a1a;
        outline: none;
        transition: border-color 0.2s;
    }

    .form-row input:focus,
    .form-row select:focus {
        border-color: #FF6B8A;
    }

    /* Slider */
    .slider-wrap {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .slider-wrap input[type=range] {
        flex: 1;
        height: 6px;
        accent-color: #FF6B8A;
        padding: 0;
        border: none;
        background: transparent;
    }

    /* Insights */
    .insight {
        border-left: 4px solid #FF6B8A;
        background: #FFF0F5;
        padding: 14px 16px;
        border-radius: 0 8px 8px 0;
        margin-bottom: 16px;
        font-size: 13px;
        line-height: 1.6;
    }

    .insight .ins-title {
        font-weight: 600;
        color: #FF6B8A;
        margin-bottom: 4px;
        font-size: 13px;
    }

    .insight.green {
        border-left-color: #4ECDC4;
        background: #F0FFFE;
    }

    .insight.green .ins-title {
        color: #4ECDC4;
    }

    .insight.blue {
        border-left-color: #9B8EC4;
        background: #F8F7FC;
    }

    .insight.blue .ins-title {
        color: #9B8EC4;
    }

    /* Tag row */
    .tag-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(80px, 1fr));
        gap: 10px;
        margin: 16px 0;
    }

    .tag {
        padding: 10px;
        border: 1.5px solid #FFD6E5;
        border-radius: 8px;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s;
        background: white;
        font-size: 12px;
        font-weight: 500;
    }

    .tag.active {
        background: #FF6B8A;
        border-color: #FF6B8A;
        color: white;
    }

    .tag:hover {
        border-color: #FF6B8A;
    }

    /* Poop buttons */
    .poop-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 8px;
        margin: 16px 0;
    }

    .poop-btn {
        padding: 12px 8px;
        border: 1.5px solid #FFD6E5;
        border-radius: 8px;
        cursor: pointer;
        text-align: center;
        transition: all 0.2s;
        background: white;
        font-family: 'Inter', sans-serif;
    }

    .poop-btn .poop-icon {
        font-size: 20px;
        display: block;
        margin-bottom: 4px;
    }

    .poop-btn .poop-label {
        font-size: 11px;
        color: #999;
        font-weight: 500;
    }

    .poop-btn:hover {
        border-color: #FF6B8A;
        background: #FFF0F5;
    }

    .poop-btn.selected {
        border-color: #FF6B8A;
        background: #FF6B8A;
        color: white;
    }

    .poop-btn.selected .poop-label {
        color: white;
    }

    /* Table */
    .poop-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
    }

    .poop-table th {
        background: #FFF0F5;
        padding: 10px 12px;
        text-align: left;
        font-weight: 600;
        color: #FF6B8A;
        border-bottom: 1px solid #FFD6E5;
    }

    .poop-table td {
        padding: 10px 12px;
        border-bottom: 1px solid #FFD6E5;
        vertical-align: top;
        line-height: 1.4;
    }

    .poop-table tr:hover td {
        background: #FFF8FB;
    }

    /* Badges */
    .poop-badge {
        display: inline-block;
        padding: 3px 10px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 600;
        background: #FFD6E5;
        color: #FF6B8A;
    }

    /* Recipe cards */
    .recipe-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
        gap: 16px;
        margin: 20px 0;
    }

    .recipe-card {
        background: white;
        border: 1px solid #FFD6E5;
        border-radius: 10px;
        padding: 16px;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s;
    }

    .recipe-card:hover {
        border-color: #FF6B8A;
        box-shadow: 0 4px 12px rgba(255, 107, 138, 0.15);
    }

    .r-emoji {
        font-size: 28px;
        margin-bottom: 8px;
    }

    .r-name {
        font-size: 13px;
        font-weight: 600;
        color: #1a1a1a;
        margin-bottom: 4px;
    }

    .r-meta {
        font-size: 11px;
        color: #999;
        margin-bottom: 8px;
    }

    .r-hearts {
        font-size: 12px;
        color: #FF6B8A;
    }

    /* Log entries */
    .log-entry {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        padding: 12px 0;
        border-bottom: 1px solid #FFD6E5;
    }

    .log-entry:last-child {
        border-bottom: none;
    }

    .log-icon {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        flex-shrink: 0;
        background: #FFF0F5;
    }

    .log-info strong {
        font-size: 13px;
        font-weight: 600;
        color: #1a1a1a;
    }

    .log-info .log-time {
        font-size: 12px;
        color: #999;
    }

    .empty-log {
        text-align: center;
        padding: 20px 0;
        color: #999;
    }

    .empty-icon {
        font-size: 32px;
        margin-bottom: 10px;
    }

    /* Milestone banner */
    .milestone-banner {
        background: linear-gradient(135deg, #FF6B8A 0%, #FFB6D6 100%);
        color: white;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 20px;
        position: relative;
        overflow: hidden;
    }

    .milestone-banner::before {
        content: '🎉';
        position: absolute;
        right: 16px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 32px;
        opacity: 0.3;
    }

    .mb-label {
        font-size: 12px;
        font-weight: 600;
        opacity: 0.9;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .mb-text {
        font-size: 14px;
        font-weight: 600;
        margin-top: 4px;
        line-height: 1.5;
    }

    /* Guide stages */
    .guide-stage {
        display: flex;
        gap: 14px;
        padding: 16px 0;
        border-bottom: 1px solid #FFD6E5;
        align-items: flex-start;
    }

    .guide-stage:last-child {
        border-bottom: none;
    }

    .stage-dot {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-family: 'Poppins', sans-serif;
        font-weight: 700;
        font-size: 16px;
        flex-shrink: 0;
        color: white;
        background: #FF6B8A;
    }

    .stage-info h4 {
        font-size: 14px;
        font-weight: 600;
        color: #1a1a1a;
        margin-bottom: 3px;
    }

    .stage-info p {
        font-size: 13px;
        color: #999;
        line-height: 1.5;
    }

    /* Weaning bar */
    .weaning-bar-wrap {
        margin: 16px 0 20px;
    }

    .weaning-bar-label {
        display: flex;
        justify-content: space-between;
        font-size: 12px;
        color: #999;
        margin-bottom: 8px;
        font-weight: 500;
    }

    .weaning-bar {
        height: 16px;
        border-radius: 8px;
        background: #FFD6E5;
        overflow: hidden;
        display: flex;
    }

    .bar-milk,
    .bar-solid {
        transition: width 0.5s ease;
    }

    .bar-milk {
        background: #A8CDD9;
    }

    .bar-solid {
        background: #FF6B8A;
    }

    .bar-legend {
        display: flex;
        gap: 16px;
        margin-top: 8px;
        font-size: 12px;
        color: #999;
    }

    .legend-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        display: inline-block;
        margin-right: 5px;
        vertical-align: middle;
    }

    /* Mobile adjustments */
    @media (max-width: 640px) {
        .logo-container {
            flex-direction: column;
            gap: 10px;
        }

        .tabs {
            overflow-x: auto;
        }

        .tab {
            padding: 12px 16px;
            font-size: 12px;
        }

        .dash-grid {
            grid-template-columns: 1fr;
        }

        .tracker-page {
            padding: 20px 12px;
        }

        .poop-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .recipe-grid {
            grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
        }
    }
</style>
@endsection

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,wght@0,300;0,600;0,800;1,300&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">

<div class="numnam-tracker-wrapper">
    <div class="numnam-header">
        <div class="logo-container">
            <div class="tracker-logo">Num<span>Nam</span></div>
            <button class="baby-age-pill" onclick="showAgeModal()" type="button" aria-label="Update baby age">
                👶 Baby: <span id="age-display">8 months</span>
            </button>
        </div>
    </div>

    <div class="tabs">
        <div class="tab active" onclick="showPage('dashboard', this)">📊 Dashboard</div>
        <div class="tab" onclick="showPage('log', this)">➕ Log</div>
        <div class="tab" onclick="showPage('poop', this)">💩 Poop Guide</div>
        <div class="tab" onclick="showPage('recipes', this)">🍽️ Recipes</div>
        <div class="tab" onclick="showPage('community', this)">💬 Community</div>
        <div class="tab" onclick="showPage('guide', this)">📖 Guide</div>
    </div>

    <!-- DASHBOARD -->
    <div id="page-dashboard" class="tracker-page active">
        <div id="milestone-area"></div>
        <div id="insight-area"></div>

        <div class="card">
            <div class="card-title">Today's Intake</div>
            <div class="card-sub" id="dash-date"></div>
            <div class="dash-grid">
                <div class="dash-stat milk">
                    <div class="num" id="dash-milk">0</div>
                    <div class="lbl">Milk (ml)</div>
                </div>
                <div class="dash-stat solid">
                    <div class="num" id="dash-solid">0</div>
                    <div class="lbl">Solids (ml)</div>
                </div>
                <div class="dash-stat water">
                    <div class="num" id="dash-water">0</div>
                    <div class="lbl">Water (ml)</div>
                </div>
                <div class="dash-stat poop">
                    <div class="num" id="dash-poop">—</div>
                    <div class="lbl">Last Poop</div>
                </div>
            </div>

            <div class="weaning-bar-wrap">
                <div class="weaning-bar-label">
                    <span>Nutrition Split</span>
                    <span id="balance-pct">—</span>
                </div>
                <div class="weaning-bar">
                    <div class="bar-milk" id="bar-milk" style="width: 100%"></div>
                    <div class="bar-solid" id="bar-solid" style="width: 0%"></div>
                </div>
                <div class="bar-legend">
                    <span><span class="legend-dot" style="background: #A8CDD9;"></span>Milk</span>
                    <span><span class="legend-dot" style="background: #E8835A;"></span>Solids</span>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-title">Today's Log</div>
            <div id="today-log-list">
                <div class="empty-log">
                    <div class="empty-icon">🍼</div>No entries yet. Tap <strong>Log</strong> to add!
                </div>
            </div>
        </div>
    </div>

    <!-- LOG PAGE -->
    <div id="page-log" class="tracker-page">
        <div class="section-title">Log a Feed</div>
        <div class="section-sub">Track milk, solids, water, or poop output</div>

        <div class="card">
            <div class="card-title">What are you logging?</div>
            <div class="tag-row" id="log-type-row">
                <div class="tag active" onclick="selectLogType('milk', this)">🍼 Milk</div>
                <div class="tag" onclick="selectLogType('solid', this)">🥣 Solids</div>
                <div class="tag" onclick="selectLogType('water', this)">💧 Water</div>
                <div class="tag" onclick="selectLogType('poop', this)">💩 Poop</div>
            </div>
        </div>

        <!-- MILK FORM -->
        <div id="form-milk" class="card">
            <div class="card-title">🍼 Milk Feed</div>
            <div class="card-sub">Formula or breast milk</div>
            <div class="form-row">
                <label>Milk Type</label>
                <select id="milk-type">
                    <option value="Formula">Formula</option>
                    <option value="Breast Milk">Breast Milk</option>
                    <option value="Expressed Milk">Expressed Milk</option>
                </select>
            </div>
            <div class="form-row">
                <label>Volume: <span id="milk-vol-display">180</span> ml</label>
                <div class="slider-wrap">
                    <input type="range" id="milk-slider" min="0" max="300" value="180" step="10" oninput="updateSlider(this, 'milk-vol-display')">
                    <input type="time" id="milk-time" required>
                </div>
            </div>
            <div class="form-row">
                <label>Notes (optional)</label>
                <input type="text" id="milk-notes" placeholder="e.g., seemed hungry, fell asleep quickly">
            </div>
            <div class="btn-row">
                <button class="btn btn-primary" onclick="logEntry('milk')">Log Milk Feed</button>
                <button class="btn btn-outline" onclick="resetForm('milk')">Clear</button>
            </div>
        </div>

        <!-- SOLID FORM -->
        <div id="form-solid" class="card" style="display:none">
            <div class="card-title">🥣 Solid Food</div>
            <div class="card-sub">Purees, mashed, or finger food</div>
            <div class="form-row">
                <label>Food Name</label>
                <input type="text" id="solid-food" placeholder="e.g., Carrot Puree, Banana Mash">
            </div>
            <div class="form-row">
                <label>Volume: <span id="solid-vol-display">100</span> ml</label>
                <div class="slider-wrap">
                    <input type="range" id="solid-slider" min="0" max="300" value="100" step="10" oninput="updateSlider(this, 'solid-vol-display')">
                    <input type="time" id="solid-time" required>
                </div>
            </div>
            <div class="form-row">
                <label>Texture</label>
                <select id="solid-texture">
                    <option value="Smooth Puree">Smooth Puree</option>
                    <option value="Thick Puree">Thick Puree</option>
                    <option value="Mashed with lumps">Mashed with lumps</option>
                    <option value="Soft finger food">Soft finger food</option>
                </select>
            </div>
            <div class="form-row">
                <label>Baby's Response</label>
                <select id="solid-finish">
                    <option value="Finished all">Finished all</option>
                    <option value="Left some">Left some</option>
                    <option value="Refused">Refused</option>
                </select>
            </div>
            <div class="form-row">
                <label>Notes (optional)</label>
                <input type="text" id="solid-notes" placeholder="e.g., new food, seemed to like it">
            </div>
            <div class="btn-row">
                <button class="btn btn-primary" onclick="logEntry('solid')">Log Solid Food</button>
                <button class="btn btn-outline" onclick="resetForm('solid')">Clear</button>
            </div>
        </div>

        <!-- WATER FORM -->
        <div id="form-water" class="card" style="display:none">
            <div class="card-title">💧 Water</div>
            <div class="form-row">
                <label>Volume: <span id="water-vol-display">30</span> ml</label>
                <div class="slider-wrap">
                    <input type="range" id="water-slider" min="0" max="120" value="30" step="5" oninput="updateSlider(this, 'water-vol-display')">
                    <input type="time" id="water-time" required>
                </div>
            </div>
            <div class="form-row">
                <label>Notes (optional)</label>
                <input type="text" id="water-notes" placeholder="e.g., in sippy cup">
            </div>
            <div class="btn-row">
                <button class="btn btn-primary" onclick="logEntry('water')">Log Water</button>
                <button class="btn btn-outline" onclick="resetForm('water')">Clear</button>
            </div>
        </div>

        <!-- POOP FORM -->
        <div id="form-poop" class="card" style="display:none">
            <div class="card-title">💩 Poop Log</div>
            <div class="card-sub">Select the closest match (Bristol Stool Chart – infant adapted)</div>
            <div class="poop-grid" id="poop-selector">
                <div class="poop-btn" onclick="selectPoop('Type 1', this)"><span class="poop-icon">🪨</span><span class="poop-label">Type 1<br>Hard</span></div>
                <div class="poop-btn" onclick="selectPoop('Type 2', this)"><span class="poop-icon">🔗</span><span class="poop-label">Type 2<br>Lumpy</span></div>
                <div class="poop-btn" onclick="selectPoop('Type 4', this)"><span class="poop-icon">✨</span><span class="poop-label">Type 4<br>Perfect</span></div>
                <div class="poop-btn" onclick="selectPoop('Type 6', this)"><span class="poop-icon">⚡</span><span class="poop-label">Type 6<br>Loose</span></div>
                <div class="poop-btn" onclick="selectPoop('Red/Undigested', this)"><span class="poop-icon">🍅</span><span class="poop-label">Red/<br>Undigested</span></div>
            </div>
            <div id="poop-insight" style="margin-top:14px;display:none"></div>
            <div class="form-row" style="margin-top:12px">
                <label>Time</label>
                <input type="time" id="poop-time" required>
            </div>
            <input type="hidden" id="poop-selected" value="">
            <div class="btn-row">
                <button class="btn btn-primary" onclick="logEntry('poop')">Log Poop</button>
                <button class="btn btn-outline" onclick="resetForm('poop')">Clear</button>
            </div>
        </div>
    </div>

    <!-- POOP GUIDE -->
    <div id="page-poop" class="tracker-page">
        <div class="section-title">💩 Poop Diagnostics</div>
        <div class="section-sub">What your baby's output is telling you</div>

        <div class="card">
            <table class="poop-table">
                <thead>
                    <tr>
                        <th>Type</th>
                        <th>Appearance</th>
                        <th>Age Range</th>
                        <th>What It Means</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><span class="poop-badge badge-red">Type 1</span></td>
                        <td>Hard, small balls</td>
                        <td>All ages</td>
                        <td><strong>Constipation risk</strong>. Increase fluids and healthy fats (ghee, oil). Add more fruits like prunes or pears.</td>
                    </tr>
                    <tr>
                        <td><span class="poop-badge badge-orange">Type 2</span></td>
                        <td>Lumpy, connected balls</td>
                        <td>All ages</td>
                        <td><strong>Mild constipation</strong>. Increase water & fibre slightly. Ensure good fat intake for lubrication.</td>
                    </tr>
                    <tr>
                        <td><span class="poop-badge badge-green">Type 4</span></td>
                        <td>Smooth, soft log (no cracks)</td>
                        <td>All ages</td>
                        <td><strong>Perfect!</strong> Fibre & fluid balance is ideal. Gut microbiome is thriving. Keep doing what you're doing.</td>
                    </tr>
                    <tr>
                        <td><span class="poop-badge badge-yellow">Type 6</span></td>
                        <td>Fluffy, mushy pieces</td>
                        <td>All ages</td>
                        <td><strong>Loose/Diarrhoea risk</strong>. Reduce high-fibre foods. Check for new foods or potential sensitivity. Hydrate well.</td>
                    </tr>
                    <tr>
                        <td><span class="poop-badge badge-orange">Red/<br>Undigested</span></td>
                        <td>Red, orange, or chunks of food visible</td>
                        <td>6+ months</td>
                        <td><strong>Digestion still learning</strong>. Chop/mash foods more finely. Mush them thoroughly. Normal at this stage!</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="card">
            <div class="card-title">🧠 Smart Rescue Logic</div>
            <div class="card-sub">If-this-then-that interventions</div>

            <div class="insight">
                <div class="ins-title">🪨 Hard Poop? → Hydration Rescue</div>
                Add 1 tsp of ghee/oil to next meal. Offer water sips. Add water-rich fruits.
            </div>
            <div class="insight blue">
                <div class="ins-title">⚡ Loose Poop? → Slow Down</div>
                Pause new high-fibre veggies. Focus on cooked foods. Monitor for 48 hours.
            </div>
            <div class="insight green">
                <div class="ins-title">✅ Perfect Poop? → Keep Going</div>
                You've nailed the nutrition balance! Stay consistent with what's working.
            </div>
        </div>
    </div>

    <!-- RECIPES -->
    <div id="page-recipes" class="tracker-page">
        <div class="section-title">🍽️ Recipe Swaps</div>
        <div class="section-sub">Mom-to-Mom favourites — filtered for your baby's age</div>

        <div id="recipes-insight" style="margin-bottom:14px"></div>

        <div class="recipe-grid" id="recipe-grid">
        </div>
    </div>

    <!-- COMMUNITY CHAT -->
    <div id="page-community" class="tracker-page">
        <div class="section-title">💬 Community Chat</div>
        <div class="section-sub">Connect with other parents on your weaning journey</div>

        <div class="card">
            <div id="community-rooms-container" style="display:grid;grid-template-columns:repeat(auto-fit,minmax(150px,1fr));gap:12px;">
            </div>
        </div>

        <div id="community-messages-area" style="display:none;">
            <div class="card">
                <div class="card-title" id="room-name-display"></div>
                <div id="messages-list" style="max-height:400px;overflow-y:auto;margin-bottom:16px;">
                </div>
                <div class="form-row">
                    <input type="text" id="message-input" placeholder="Share your question or experience...">
                </div>
                <button class="btn btn-primary" onclick="sendCommunityMessage()" style="width:100%;">Send Message</button>
                <button class="btn btn-outline" onclick="backToCommunityRooms()" style="width:100%;margin-top:8px;">← Back to Rooms</button>
            </div>
        </div>
    </div>

    <!-- GUIDE -->
    <div id="page-guide" class="tracker-page">
        <div class="section-title">📖 Weaning Guide</div>
        <div class="section-sub">Stage-by-stage advice tailored to your baby</div>

        <div class="card" style="background:linear-gradient(135deg,#fff8f0 0%,#fdf0e0 100%)">
            <div class="card-title">Age & Stage Reference</div>
            <div class="card-sub">Target volumes and texture goals</div>
            <div style="overflow-x:auto">
                <table class="poop-table" style="font-size: 0.78rem;">
                    <tr>
                        <th colspan="5" style="text-align:left;background:transparent;border:none;font-size:0.88rem;font-weight:600;padding-bottom:8px;">Sample Daily Targets</th>
                    </tr>
                    <tr style="background:#fff3e9">
                        <th>Age</th>
                        <th>Milk/Day</th>
                        <th>Solids/Day</th>
                        <th>Water</th>
                        <th>Texture Goal</th>
                    </tr>
                    <tr>
                        <td>4-6mo</td>
                        <td>600-900ml</td>
                        <td>0-100ml</td>
                        <td>None yet</td>
                        <td>Liquid → smooth puree</td>
                    </tr>
                    <tr>
                        <td>6-8mo</td>
                        <td>600-800ml</td>
                        <td>100-200ml</td>
                        <td>30-50ml sips</td>
                        <td>Smooth → thick puree</td>
                    </tr>
                    <tr>
                        <td>8-10mo</td>
                        <td>500-700ml</td>
                        <td>200-300ml</td>
                        <td>50-100ml</td>
                        <td>Lumpy mash</td>
                    </tr>
                    <tr>
                        <td>10-12mo</td>
                        <td>400-600ml</td>
                        <td>250-350ml</td>
                        <td>100-150ml</td>
                        <td>Soft finger food</td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="card">
            <div class="card-title">Milestone Tips</div>
            <div id="guide-stages"></div>
        </div>

        <div class="card">
            <div class="card-title">⚠️ Safety Rules</div>
            <div class="card-sub">Always keep these in mind</div>
            <div class="guide-stage">
                <div class="stage-dot s3">1</div>
                <div class="stage-info">
                    <h4>Never salt or sugar babies under 1</h4>
                    <p>Their kidneys can't process extra sodium & added sugars spike insulin. Plain is best.</p>
                </div>
            </div>
            <div class="guide-stage">
                <div class="stage-dot s3">2</div>
                <div class="stage-info">
                    <h4>Choking hazards: whole nuts, popcorn, hard candy, grapes</h4>
                    <p>Cut grapes lengthwise into 4 pieces. Roast nut butters into soft foods instead of whole nuts.</p>
                </div>
            </div>
            <div class="guide-stage" style="border-bottom:none">
                <div class="stage-dot s3">3</div>
                <div class="stage-info">
                    <h4>Never honey before 1 year (botulism risk)</h4>
                    <p>Use mashed fruit, mashed banana, or date paste as natural sweeteners instead.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- AGE MODAL -->
    <div id="age-modal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.4);z-index:200;align-items:center;justify-content:center;">
        <div style="background:white;border-radius:20px;padding:28px;max-width:340px;width:90%;margin:auto;position:relative;top:50%;transform:translateY(-50%)">
            <div style="font-family:'Fraunces',serif;font-size:1.2rem;font-weight:600;margin-bottom:14px;color:#C0502A">How old is your baby?</div>
            <div class="form-row">
                <label>Age (months)</label>
                <input type="number" id="age-input" min="4" max="36" value="8">
            </div>
            <div class="btn-row">
                <button class="btn btn-primary" onclick="saveAge()">Save</button>
                <button class="btn btn-outline" onclick="document.getElementById('age-modal').style.display='none'">Cancel</button>
            </div>
        </div>
    </div>

    <!-- GUEST CHECKOUT MODAL -->
    <div id="guest-checkout-modal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:300;align-items:center;justify-content:center;overflow-y:auto;">
        <div style="background:white;border-radius:20px;padding:28px;max-width:400px;width:90%;margin:auto;position:relative;margin-top:20px;margin-bottom:20px;">
            <div style="font-family:'Fraunces',serif;font-size:1.3rem;font-weight:600;margin-bottom:8px;color:#FF6B8A">Verify Your Details</div>
            <div style="font-size:13px;color:#999;margin-bottom:20px;">We need your information to save your tracker. Secure payment via Razorpay.</div>

            <div class="form-row">
                <label>Full Name *</label>
                <input type="text" id="guest-name" placeholder="Your name" required>
            </div>
            <div class="form-row">
                <label>Email *</label>
                <input type="email" id="guest-email" placeholder="your@email.com" required>
            </div>
            <div class="form-row">
                <label>Phone Number *</label>
                <input type="tel" id="guest-phone" placeholder="+91 9876543210" required>
            </div>
            <div class="form-row">
                <label>Address (optional)</label>
                <input type="text" id="guest-address" placeholder="Your delivery address">
            </div>

            <div style="background:#FFF0F5;border:1px solid #FFD6E5;border-radius:8px;padding:12px;margin-bottom:16px;font-size:12px;color:#666;line-height:1.5;">
                <strong style="color:#FF6B8A;">₹1 Verification Fee</strong><br>
                A small amount will be charged to verify your details. You can use this information for future orders.
            </div>

            <div class="btn-row">
                <button class="btn btn-primary" id="guest-checkout-btn" onclick="proceedWithRazorpay()" style="flex:1;">Pay & Verify (₹1)</button>
                <button class="btn btn-outline" onclick="closeGuestModal()" style="flex:1;">Cancel</button>
            </div>
        </div>
    </div>
</div>

<script>
    // NumNam API Integration - Backend-powered weaning tracker
    let currentUser = @json(auth() - > user());
    let babyProfile = null;
    let todayLogs = [];
    let recipes = [];
    let communityRooms = [];
    let currentLogType = 'milk';
    let selectedPoopType = '';

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', async () => {
        await loadBabyProfile();
        await loadRecipes();
        await loadCommunityRooms();
        renderDashboard();
        document.getElementById('dash-date').textContent = new Date().toLocaleDateString();
    });

    // API Helper: Get auth token from meta tag or localStorage
    function getAuthToken() {
        return document.querySelector('meta[name="csrf-token"]')?.content ||
            localStorage.getItem('authToken') || '';
    }

    // Load baby profile from API
    async function loadBabyProfile() {
        try {
            const response = await fetch('/api/v1/numnam/baby/profile', {
                credentials: 'include',
                headers: {
                    'Accept': 'application/json'
                }
            });
            const data = await response.json();
            if (data.data) {
                babyProfile = data.data;
                document.getElementById('age-display').textContent = babyProfile.age_months + ' months';
                await loadTodayLogs();
            }
        } catch (error) {
            console.log('Profile load - login required or API error', error);
        }
    }

    // Load today's logs from API
    async function loadTodayLogs() {
        if (!babyProfile) return;
        try {
            const response = await fetch('/api/v1/numnam/logs/today', {
                credentials: 'include',
                headers: {
                    'Accept': 'application/json'
                }
            });
            const data = await response.json();
            todayLogs = data.data || [];
        } catch (error) {
            console.log('Logs load error', error);
            todayLogs = [];
        }
    }

    // Load recipes from API
    async function loadRecipes() {
        try {
            const response = await fetch('/api/v1/numnam/recipes', {
                credentials: 'include',
                headers: {
                    'Accept': 'application/json'
                }
            });
            const data = await response.json();
            recipes = data.data || [];
        } catch (error) {
            console.log('Recipes load error', error);
            recipes = [];
        }
    }

    // Load community rooms from API
    async function loadCommunityRooms() {
        try {
            const response = await fetch('/api/v1/numnam/community/rooms', {
                credentials: 'include',
                headers: {
                    'Accept': 'application/json'
                }
            });
            const data = await response.json();
            communityRooms = data.data || [];
        } catch (error) {
            console.log('Community rooms load error', error);
            communityRooms = [];
        }
    }

    function showPage(id, el) {
        document.querySelectorAll('.tracker-page').forEach(p => p.classList.remove('active'));
        document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
        document.getElementById('page-' + id).classList.add('active');
        el.classList.add('active');
        if (id === 'dashboard') renderDashboard();
        if (id === 'recipes') renderRecipes();
        if (id === 'guide') renderGuide();
    }

    function showAgeModal() {
        if (!babyProfile) {
            alert('Please login to use the weaning tracker');
            return;
        }
        document.getElementById('age-input').value = babyProfile.age_months;
        document.getElementById('age-modal').style.display = 'flex';
    }

    async function saveAge() {
        const age = parseInt(document.getElementById('age-input').value) || babyProfile.age_months;
        try {
            const response = await fetch('/api/v1/numnam/baby/profile', {
                method: 'POST',
                credentials: 'include',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    age_months: age,
                    baby_name: babyProfile.baby_name,
                    weight_kg: babyProfile.weight_kg,
                    milk_type: babyProfile.milk_type
                })
            });
            const data = await response.json();
            babyProfile = data.data;
            document.getElementById('age-display').textContent = age + ' months';
            document.getElementById('age-modal').style.display = 'none';
            renderDashboard();
            await loadRecipes();
            renderRecipes();
            renderGuide();
        } catch (error) {
            alert('Error updating profile: ' + error.message);
        }
    }

    function selectLogType(type, el) {
        currentLogType = type;
        document.querySelectorAll('#log-type-row .tag').forEach(t => t.classList.remove('active'));
        el.classList.add('active');
        ['milk', 'solid', 'water', 'poop'].forEach(t => {
            document.getElementById('form-' + t).style.display = (t === type) ? 'block' : 'none';
        });
    }

    function selectPoop(type, el) {
        selectedPoopType = type;
        document.querySelectorAll('#poop-selector .poop-btn').forEach(b => b.classList.remove('selected'));
        el.classList.add('selected');
        document.getElementById('poop-selected').value = type;

        const insightEl = document.getElementById('poop-insight');
        const advice = getPoopAdvice(type);
        insightEl.innerHTML = `<div class="insight ${advice.cls}"><div class="ins-title">${advice.title}</div>${advice.text}</div>`;
        insightEl.style.display = 'block';
    }

    function getPoopAdvice(type) {
        if (type === 'Type 1') return {
            cls: '',
            title: '🪨 Hydration Rescue!',
            text: 'Baby seems backed up. Add 1 tsp of ghee or coconut oil to the next meal. Offer water sips.'
        };
        if (type === 'Type 2') return {
            cls: '',
            title: '💧 More fluids needed',
            text: 'Offer water sips after solids. Focus on water-rich fruits like melon or pear today.'
        };
        if (type === 'Type 4') return {
            cls: 'green',
            title: '✅ Perfect!',
            text: 'Fibre and fluid balance is just right. Keep doing what you\'re doing!'
        };
        if (type === 'Type 6') return {
            cls: 'blue',
            title: '⚡ Slow down on new foods',
            text: 'Possible sensitivity. Pause new high-fibre veggies for 2 days and monitor.'
        };
        if (type === 'Red/Undigested') return {
            cls: '',
            title: '🍅 Digestion Check',
            text: 'Undigested bits are normal! Gut is learning. Try mashing more for the next 2 days.'
        };
        return {
            cls: 'blue',
            title: 'ℹ️ Normal variation',
            text: 'Nothing to worry about. Keep an eye on it over the next day or two.'
        };
    }

    function updateSlider(el, displayId) {
        document.getElementById(displayId).textContent = el.value;
    }

    async function logEntry(type) {
        if (!babyProfile) {
            alert('Please login first');
            return;
        }

        const logData = {
            type: type,
            logged_at: new Date().toISOString()
        };

        if (type === 'milk') {
            logData.volume_ml = parseInt(document.getElementById('milk-slider').value);
            logData.milk_type = document.getElementById('milk-type').value;
        } else if (type === 'solid') {
            logData.volume_ml = parseInt(document.getElementById('solid-slider').value);
            logData.food_name = document.getElementById('solid-food').value || 'Solids';
            logData.texture = document.getElementById('solid-texture').value;
            logData.finish_level = document.getElementById('solid-finish').value;
        } else if (type === 'water') {
            logData.volume_ml = parseInt(document.getElementById('water-slider').value);
        } else if (type === 'poop') {
            if (!selectedPoopType) {
                alert('Please select a poop type!');
                return;
            }
            logData.poop_type = selectedPoopType;
        }

        try {
            const response = await fetch('/api/v1/numnam/logs', {
                method: 'POST',
                credentials: 'include',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(logData)
            });

            const data = await response.json();
            if (!response.ok) throw new Error(data.message || 'Failed to save log');

            await loadTodayLogs();
            renderDashboard();
            resetForm(type);

            const btn = event.target;
            const orig = btn.textContent;
            btn.textContent = '✓ Saved!';
            btn.style.background = '#4ECDC4';
            setTimeout(() => {
                btn.textContent = orig;
                btn.style.background = '';
            }, 1500);
        } catch (error) {
            alert('Error saving entry: ' + error.message);
        }
    }

    function resetForm(type) {
        if (type === 'milk') {
            document.getElementById('milk-slider').value = 180;
            document.getElementById('milk-vol-display').textContent = '180';
            document.getElementById('milk-type').value = 'Formula';
        } else if (type === 'solid') {
            document.getElementById('solid-slider').value = 100;
            document.getElementById('solid-vol-display').textContent = '100';
            document.getElementById('solid-food').value = '';
        } else if (type === 'water') {
            document.getElementById('water-slider').value = 30;
            document.getElementById('water-vol-display').textContent = '30';
        } else if (type === 'poop') {
            selectedPoopType = '';
            document.querySelectorAll('#poop-selector .poop-btn').forEach(b => b.classList.remove('selected'));
            document.getElementById('poop-insight').style.display = 'none';
        }
    }

    function renderDashboard() {
        if (!babyProfile) {
            document.getElementById('page-dashboard').innerHTML = '<div style="text-align:center;padding:40px;"><p>Please login to use the NumNam Tracker</p></div>';
            return;
        }

        let totalMilk = 0,
            totalSolid = 0,
            totalWater = 0,
            lastPoop = '—';

        todayLogs.forEach(log => {
            if (log.type === 'milk') totalMilk += log.volume_ml || 0;
            if (log.type === 'solid') totalSolid += log.volume_ml || 0;
            if (log.type === 'water') totalWater += log.volume_ml || 0;
            if (log.type === 'poop') lastPoop = log.poop_type?.replace('Type ', 'T') || '—';
        });

        document.getElementById('dash-milk').textContent = totalMilk;
        document.getElementById('dash-solid').textContent = totalSolid;
        document.getElementById('dash-water').textContent = totalWater;
        document.getElementById('dash-poop').textContent = lastPoop;

        const total = totalMilk + totalSolid;
        if (total > 0) {
            const milkPct = Math.round(totalMilk / total * 100);
            const solidPct = 100 - milkPct;
            document.getElementById('bar-milk').style.width = milkPct + '%';
            document.getElementById('bar-solid').style.width = solidPct + '%';
            document.getElementById('balance-pct').textContent = `${milkPct}% milk / ${solidPct}% solids`;
        } else {
            document.getElementById('bar-milk').style.width = '100%';
            document.getElementById('bar-solid').style.width = '0%';
            document.getElementById('balance-pct').textContent = '—';
        }

        renderInsights(todayLogs, totalMilk, totalSolid, lastPoop);
        renderMilestone();

        const logList = document.getElementById('today-log-list');
        if (todayLogs.length === 0) {
            logList.innerHTML = '<div class="empty-log"><div class="empty-icon">🍼</div>No entries yet. Tap <strong>Log</strong> to add!</div>';
        } else {
            const icons = {
                milk: {
                    icon: '🍼',
                    cls: 'milk'
                },
                solid: {
                    icon: '🥣',
                    cls: 'solid'
                },
                poop: {
                    icon: '💩',
                    cls: 'poop'
                },
                water: {
                    icon: '💧',
                    cls: 'water'
                }
            };
            logList.innerHTML = [...todayLogs].reverse().map(log => {
                const ic = icons[log.type];
                const time = new Date(log.logged_at).toLocaleTimeString([], {
                    hour: '2-digit',
                    minute: '2-digit'
                });
                let label = `${log.volume_ml}ml`;
                if (log.type === 'milk') label += ` ${log.milk_type}`;
                if (log.type === 'solid') label = `${log.food_name} (${log.volume_ml}ml)`;
                if (log.type === 'poop') label = log.poop_type;
                return `<div class="log-entry"><div class="log-icon ${ic.cls}">${ic.icon}</div><div class="log-info"><strong>${label}</strong><div class="log-time">${time}</div></div></div>`;
            }).join('');
        }
    }

    function renderInsights(todayLogs, totalMilk, totalSolid, lastPoop) {
        const area = document.getElementById('insight-area');
        let insights = [];

        const poopLog = todayLogs.filter(l => l.type === 'poop').pop();
        if (poopLog) {
            if (poopLog.poop_type === 'Type 1') {
                insights.push({
                    cls: '',
                    title: '🪨 Hydration Rescue!',
                    text: 'Add 1 tsp healthy fat (ghee, oil) to next meal. Offer water sips.'
                });
            } else if (poopLog.poop_type === 'Type 4') {
                insights.push({
                    cls: 'green',
                    title: '✅ Perfect Output!',
                    text: 'Fibre balance is ideal. Keep doing what you\'re doing!'
                });
            }
        }

        if (babyProfile.age_months >= 10 && totalSolid < 50 && totalMilk > 400) {
            insights.push({
                cls: 'blue',
                title: '🥛 Milk-Heavy Alert',
                text: `At ${babyProfile.age_months} months, try introducing bitter greens for palate tuning!`
            });
        }

        if (totalSolid > 100 && totalWater === 0) {
            insights.push({
                cls: '',
                title: '💧 Water Time!',
                text: 'Baby eating solids! Offer water sips to keep digestion happy.'
            });
        }

        if (insights.length === 0) {
            area.innerHTML = '';
        } else {
            area.innerHTML = insights.map(i => `<div class="insight ${i.cls}"><div class="ins-title">${i.title}</div>${i.text}</div>`).join('');
        }
    }

    function renderMilestone() {
        const area = document.getElementById('milestone-area');
        const age = babyProfile.age_months;
        let msg = null;

        if (age === 6) msg = {
            title: '🎉 First Spoon!',
            text: 'Focus on tongue & taste, not nutrition. Start with 1-2 spoons single veggie.'
        };
        else if (age === 8) msg = {
            title: '🌟 Bridge to Texture!',
            text: 'Baby getting 30% energy from solids. Make every bite nutrient-dense.'
        };
        else if (age === 10) msg = {
            title: '🍽️ Texture Challenge!',
            text: 'Time for mashed & soft lumps! Helps develop jaw muscles for speech.'
        };

        if (msg) {
            area.innerHTML = `<div class="milestone-banner"><div class="mb-label">Milestone at ${age} months</div><div class="mb-text">${msg.text}</div></div>`;
        } else {
            area.innerHTML = '';
        }
    }

    function renderRecipes() {
        if (!babyProfile) return;

        const grid = document.getElementById('recipe-grid');
        const age = babyProfile.age_months;
        const filtered = recipes.filter(r => r.min_age_months <= age);

        const insightEl = document.getElementById('recipes-insight');
        if (age >= 6 && age < 8) {
            insightEl.innerHTML = `<div class="insight"><div class="ins-title">💡 Stage Tip</div>Smooth & thick purées recommended. More textures unlock as baby grows!</div>`;
        } else if (age >= 8) {
            insightEl.innerHTML = `<div class="insight green"><div class="ins-title">💡 Stage Tip</div>Baby ready for lumpy mashes & soft finger foods! Build chewing skills & independence.</div>`;
        }

        grid.innerHTML = filtered.map(r => `
        <div class="recipe-card">
            <div class="r-emoji">${r.emoji}</div>
            <div class="r-name">${r.name}</div>
            <div class="r-meta">${r.texture}</div>
            <div class="r-hearts"><span class="heart-btn" onclick="toggleRecipeLike(${r.id}, this)">❤️</span> <span id="hearts-${r.id}">${r.hearts_count}</span></div>
        </div>
        `).join('');
    }

    async function toggleRecipeLike(recipeId, el) {
        if (!babyProfile) {
            alert('Please login to like recipes');
            return;
        }

        try {
            const response = await fetch(`/api/v1/numnam/recipes/${recipeId}/like`, {
                method: 'POST',
                credentials: 'include',
                headers: {
                    'Accept': 'application/json'
                }
            });
            const data = await response.json();
            if (data.data) {
                document.getElementById('hearts-' + recipeId).textContent = data.data.hearts_count;
            }
        } catch (error) {
            console.log('Error liking recipe:', error);
        }
    }

    function renderGuide() {
        const stages = [{
                age: '4-6 mo',
                emoji: '1️⃣',
                title: 'Spoon Skills',
                desc: 'Focus on tongue-thrust reflex & taste exposure, not nutrition yet.'
            },
            {
                age: '6-8 mo',
                emoji: '2️⃣',
                title: 'Texture Transition',
                desc: 'Move from smooth to slightly lumpy. Baby sits with support.'
            },
            {
                age: '8-10 mo',
                emoji: '3️⃣',
                title: 'Chewing Practice',
                desc: 'Soft lumps & mashes. Baby develops pincer grasp (thumb + fingers).'
            },
        ];

        const currentAge = babyProfile ? babyProfile.age_months : 0;
        document.getElementById('guide-stages').innerHTML = stages.map((s, i) => {
            return `<div class="guide-stage" style="${i === stages.length - 1 ? 'border-bottom:none' : ''}"><div class="stage-dot s${i+1}">${i+1}</div><div class="stage-info"><h4>${s.emoji} ${s.title} (${s.age})</h4><p>${s.desc}</p></div></div>`;
        }).join('');
    }

    // Community Chat Functions
    function renderCommunityRooms() {
        if (!communityRooms.length) {
            document.getElementById('community-rooms-container').innerHTML = '<p>Loading community rooms...</p>';
            return;
        }

        const roomsHtml = communityRooms.map(room => `
            <div style="background:white;border:1px solid #FFD6E5;border-radius:10px;padding:16px;text-align:center;cursor:pointer;transition:all 0.2s;" 
                 onclick="showCommunityRoom(${room.id}, '${room.name.replace(/'/g, "\\'")}')">
                <div style="font-size:28px;margin-bottom:8px;">${room.icon}</div>
                <div style="font-size:13px;font-weight:600;color:#1a1a1a;margin-bottom:4px;">${room.name}</div>
                <div style="font-size:11px;color:#999;">${room.description || 'Chat here'}</div>
            </div>
        `).join('');

        document.getElementById('community-rooms-container').innerHTML = roomsHtml;
    }

    let currentRoomId = null;
    let currentRoomMessages = [];

    async function showCommunityRoom(roomId, roomName) {
        currentRoomId = roomId;
        document.getElementById('community-rooms-container').style.display = 'none';
        document.getElementById('community-messages-area').style.display = 'block';
        document.getElementById('room-name-display').textContent = roomName;
        document.getElementById('message-input').value = '';

        await loadCommunityRoomMessages(roomId);
    }

    function backToCommunityRooms() {
        currentRoomId = null;
        document.getElementById('community-rooms-container').style.display = 'grid';
        document.getElementById('community-messages-area').style.display = 'none';
    }

    async function loadCommunityRoomMessages(roomId) {
        try {
            const response = await fetch(`/api/v1/numnam/community/rooms/${roomId}/messages`, {
                credentials: 'include',
                headers: {
                    'Accept': 'application/json'
                }
            });
            const data = await response.json();
            currentRoomMessages = data.data || [];
            renderCommunityMessages();
        } catch (error) {
            console.log('Error loading messages:', error);
        }
    }

    function renderCommunityMessages() {
        if (!currentRoomMessages.length) {
            document.getElementById('messages-list').innerHTML = '<p style="text-align:center;color:#999;">No messages yet. Be the first to share!</p>';
            return;
        }

        const messagesHtml = currentRoomMessages.map(msg => `
            <div style="border-bottom:1px solid #FFD6E5;padding:12px 0;">
                <div style="font-weight:600;color:#1a1a1a;margin-bottom:4px;">${msg.user.name}</div>
                <div style="color:#333;font-size:13px;margin-bottom:6px;line-height:1.5;">${msg.message}</div>
                <div style="font-size:11px;color:#999;display:flex;justify-content:space-between;">
                    <span>${new Date(msg.created_at).toLocaleString()}</span>
                    <span onclick="likeCommunityMessage(${msg.id}, this)" style="cursor:pointer;">❤️ <span id="likes-${msg.id}">${msg.likes_count}</span></span>
                </div>
            </div>
        `).join('');

        document.getElementById('messages-list').innerHTML = messagesHtml;
        document.getElementById('messages-list').scrollTop = document.getElementById('messages-list').scrollHeight;
    }

    async function sendCommunityMessage() {
        if (!babyProfile) {
            alert('Please login to send messages');
            return;
        }

        const message = document.getElementById('message-input').value.trim();
        if (!message || !currentRoomId) return;

        try {
            const response = await fetch(`/api/v1/numnam/community/rooms/${currentRoomId}/messages`, {
                method: 'POST',
                credentials: 'include',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    message: message
                })
            });

            if (!response.ok) throw new Error('Failed to send message');

            await loadCommunityRoomMessages(currentRoomId);
        } catch (error) {
            alert('Error sending message: ' + error.message);
        }
    }

    async function likeCommunityMessage(messageId, el) {
        if (!babyProfile) {
            alert('Please login to like messages');
            return;
        }

        try {
            const response = await fetch(`/api/v1/numnam/community/messages/${messageId}/like`, {
                method: 'POST',
                credentials: 'include',
                headers: {
                    'Accept': 'application/json'
                }
            });
            const data = await response.json();
            if (data.data) {
                document.getElementById('likes-' + messageId).textContent = data.data.likes_count;
            }
        } catch (error) {
            console.log('Error liking message:', error);
        }
    }

    // Re-render community rooms when community tab is clicked
    document.addEventListener('click', function(e) {
        if (e.target.textContent.includes('💬 Community')) {
            renderCommunityRooms();
        }
    });
</script>
@endsection
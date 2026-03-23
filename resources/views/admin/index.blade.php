<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NumNam CMS Admin</title>
    <style>
        :root {
            --bg: #f6f8fb;
            --panel: #ffffff;
            --text: #1f2937;
            --muted: #6b7280;
            --border: #dbe1ea;
            --brand: #0f766e;
            --brand-dark: #115e59;
            --danger: #b91c1c;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            background: radial-gradient(circle at top right, #d1fae5, #f6f8fb 45%);
            color: var(--text);
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
        }

        .layout {
            max-width: 1200px;
            margin: 32px auto;
            padding: 0 16px;
        }

        .card {
            background: var(--panel);
            border: 1px solid var(--border);
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.06);
            padding: 18px;
            margin-bottom: 16px;
        }

        h1 { margin: 0 0 10px; font-size: 24px; }
        h2 { margin: 0 0 12px; font-size: 18px; }
        p { margin: 0; color: var(--muted); }

        .toolbar {
            display: grid;
            gap: 10px;
            grid-template-columns: 1fr auto auto;
            align-items: center;
        }

        @media (max-width: 720px) {
            .toolbar { grid-template-columns: 1fr; }
        }

        input, textarea, select, button {
            width: 100%;
            padding: 10px 12px;
            border-radius: 8px;
            border: 1px solid var(--border);
            font-size: 14px;
        }

        textarea { min-height: 90px; resize: vertical; }

        button {
            background: var(--brand);
            color: #fff;
            border: none;
            cursor: pointer;
            transition: background .2s ease;
        }

        button:hover { background: var(--brand-dark); }
        button.danger { background: var(--danger); }

        .grid-2 {
            display: grid;
            gap: 12px;
            grid-template-columns: 1fr 1fr;
        }

        @media (max-width: 720px) {
            .grid-2 { grid-template-columns: 1fr; }
        }

        .output {
            white-space: pre-wrap;
            font-family: Consolas, Menlo, monospace;
            background: #0b1220;
            color: #d1f5ef;
            border-radius: 10px;
            padding: 14px;
            min-height: 180px;
            overflow: auto;
            max-height: 520px;
            line-height: 1.45;
            font-size: 12px;
        }

        .muted { color: var(--muted); font-size: 13px; }
    </style>
</head>
<body>
<div class="layout">
    <div class="card">
        <h1>NumNam CMS Admin Backend</h1>
        <p>AJAX admin console backed by protected JSON APIs at /api/v1/admin/*.</p>
    </div>

    <div class="card">
        <h2>Admin Login</h2>
        <form id="authForm" class="grid-2">
            <input name="email" type="email" placeholder="Admin email" value="admin@numnam.com" required>
            <input name="password" type="password" placeholder="Password" required>
            <button type="submit">Login & Save Token</button>
            <button id="logoutBtn" type="button" class="danger">Logout</button>
        </form>
        <p class="muted" id="authStatus" style="margin-top: 10px;">Not authenticated.</p>
    </div>

    <div class="card">
        <h2>Admin API Explorer</h2>
        <div class="toolbar">
            <select id="endpointSelect">
                <option value="dashboard">Dashboard</option>
                <option value="pages">Page Manager</option>
                <option value="homepage-sections">Homepage Section Manager</option>
                <option value="products">Product Manager</option>
                <option value="pricing-plans">Pricing Plan Manager</option>
                <option value="blogs">Blog Manager</option>
                <option value="media">Media Upload System</option>
                <option value="menus">Menu Manager</option>
                <option value="contact-messages">Contact Form Messages</option>
                <option value="site-settings">Site Settings</option>
            </select>
            <button id="loadBtn">Load Data</button>
            <button id="clearBtn" class="danger">Clear Output</button>
        </div>
        <p class="muted" style="margin-top: 10px;">Use the forms below to create/update records; responses are rendered in JSON.</p>
    </div>

    <div class="card">
        <h2>Quick Create Actions</h2>
        <div class="grid-2">
            <form id="pageForm">
                <h3>Create Page</h3>
                <input name="title" placeholder="Page title" required>
                <input name="slug" placeholder="Slug (optional)">
                <select name="status">
                    <option value="draft">draft</option>
                    <option value="published">published</option>
                    <option value="archived">archived</option>
                </select>
                <button type="submit">Create Page</button>
            </form>

            <form id="productForm">
                <h3>Create Product</h3>
                <input name="name" placeholder="Product name" required>
                <input name="price" type="number" step="0.01" min="0" placeholder="Price" required>
                <select name="status">
                    <option value="draft">draft</option>
                    <option value="published">published</option>
                    <option value="archived">archived</option>
                </select>
                <button type="submit">Create Product</button>
            </form>

            <form id="planForm">
                <h3>Create Pricing Plan</h3>
                <input name="name" placeholder="Plan name" required>
                <input name="price" type="number" step="0.01" min="0" placeholder="Price" required>
                <select name="billing_cycle">
                    <option value="monthly">monthly</option>
                    <option value="quarterly">quarterly</option>
                    <option value="yearly">yearly</option>
                    <option value="one_time">one_time</option>
                </select>
                <button type="submit">Create Plan</button>
            </form>

            <form id="blogForm">
                <h3>Create Blog</h3>
                <input name="title" placeholder="Blog title" required>
                <textarea name="content" placeholder="Blog content" required></textarea>
                <select name="status">
                    <option value="draft">draft</option>
                    <option value="published">published</option>
                    <option value="archived">archived</option>
                </select>
                <button type="submit">Create Blog</button>
            </form>

            <form id="menuForm">
                <h3>Create Menu</h3>
                <input name="name" placeholder="Menu name" required>
                <input name="location" placeholder="Location key (optional)">
                <button type="submit">Create Menu</button>
            </form>

            <form id="siteSettingsForm">
                <h3>Upsert Site Setting</h3>
                <input name="key" placeholder="Setting key" required>
                <input name="value" placeholder="Setting value">
                <input name="group" placeholder="Group (e.g. general)">
                <button type="submit">Save Setting</button>
            </form>

            <form id="sectionForm">
                <h3>Create Homepage Section</h3>
                <input name="page_id" type="number" min="1" placeholder="Homepage page_id" required>
                <select name="section_type" required>
                    <option value="hero_banner">hero_banner</option>
                    <option value="about_brand">about_brand</option>
                    <option value="product_highlights">product_highlights</option>
                    <option value="nutrition_benefits">nutrition_benefits</option>
                    <option value="pricing_plans">pricing_plans</option>
                    <option value="testimonials">testimonials</option>
                    <option value="call_to_action">call_to_action</option>
                    <option value="newsletter_signup">newsletter_signup</option>
                </select>
                <input name="title" placeholder="Section title">
                <textarea name="data_json" placeholder='JSON data (optional), e.g. {"heading":"New Headline"}'></textarea>
                <button type="submit">Create Section</button>
            </form>

            <form id="mediaForm" enctype="multipart/form-data">
                <h3>Upload Media</h3>
                <input name="file" type="file" required>
                <input name="title" placeholder="Title (optional)">
                <button type="submit">Upload File</button>
            </form>
        </div>
    </div>

    <div class="card">
        <h2>JSON Output</h2>
        <div id="output" class="output">Ready.</div>
    </div>
</div>

<script>
    const appBase = `${window.location.origin}${window.location.pathname.replace(/\/admin\/?$/, '')}`;
    const authBase = `${appBase}/api/v1/auth`;
    const apiBase = `${appBase}/api/v1/admin`;
    const output = document.getElementById('output');
    const authStatus = document.getElementById('authStatus');

    function getToken() {
        return localStorage.getItem('numnam_admin_token') || '';
    }

    function setToken(token) {
        localStorage.setItem('numnam_admin_token', token);
    }

    function clearToken() {
        localStorage.removeItem('numnam_admin_token');
    }

    function updateAuthStatus(text, isError = false) {
        authStatus.textContent = text;
        authStatus.style.color = isError ? '#b91c1c' : '#6b7280';
    }

    function log(data) {
        output.textContent = typeof data === 'string' ? data : JSON.stringify(data, null, 2);
    }

    async function request(path, options = {}) {
        const token = getToken();
        if (!token) {
            throw { status: 401, data: { message: 'Please login as admin first.' } };
        }

        const response = await fetch(`${apiBase}/${path}`, {
            headers: {
                'Accept': 'application/json',
                'Authorization': `Bearer ${token}`,
                ...(options.body instanceof FormData ? {} : { 'Content-Type': 'application/json' }),
                ...(options.headers || {}),
            },
            ...options,
        });

        const data = await response.json().catch(() => ({ message: 'Non-JSON response received.' }));

        if (!response.ok) {
            throw { status: response.status, data };
        }

        return data;
    }

    document.getElementById('authForm').addEventListener('submit', async (event) => {
        event.preventDefault();
        const fd = new FormData(event.currentTarget);

        try {
            const response = await fetch(`${authBase}/login`, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    email: fd.get('email'),
                    password: fd.get('password'),
                }),
            });

            const payload = await response.json();

            if (!response.ok || !payload.token) {
                throw payload;
            }

            if (!payload.user || !payload.user.is_admin) {
                clearToken();
                updateAuthStatus('Authenticated user is not an admin.', true);
                log(payload);
                return;
            }

            setToken(payload.token);
            updateAuthStatus(`Authenticated as ${payload.user.email}. Admin token stored.`);
            log(payload);
        } catch (error) {
            updateAuthStatus('Login failed. Check credentials.', true);
            log(error);
        }
    });

    document.getElementById('logoutBtn').addEventListener('click', async () => {
        const token = getToken();

        if (token) {
            try {
                await fetch(`${authBase}/logout`, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': `Bearer ${token}`,
                    },
                });
            } catch (error) {
                // Ignore logout request failures and still clear local token.
            }
        }

        clearToken();
        updateAuthStatus('Logged out.');
        log('Token cleared.');
    });

    if (getToken()) {
        updateAuthStatus('Token detected in browser storage. You can now call admin APIs.');
    }

    document.getElementById('loadBtn').addEventListener('click', async () => {
        const endpoint = document.getElementById('endpointSelect').value;

        try {
            const data = await request(endpoint);
            log(data);
        } catch (error) {
            log(error);
        }
    });

    document.getElementById('clearBtn').addEventListener('click', () => log('Cleared.'));

    function wireJsonForm(formId, endpoint, mapper) {
        document.getElementById(formId).addEventListener('submit', async (event) => {
            event.preventDefault();
            const form = event.currentTarget;
            const payload = mapper(new FormData(form));

            try {
                const data = await request(endpoint, {
                    method: 'POST',
                    body: JSON.stringify(payload),
                });
                log(data);
                form.reset();
            } catch (error) {
                log(error);
            }
        });
    }

    wireJsonForm('pageForm', 'pages', (fd) => ({
        title: fd.get('title'),
        slug: fd.get('slug') || null,
        status: fd.get('status'),
    }));

    wireJsonForm('productForm', 'products', (fd) => ({
        name: fd.get('name'),
        price: Number(fd.get('price') || 0),
        status: fd.get('status'),
        is_active: true,
    }));

    wireJsonForm('planForm', 'pricing-plans', (fd) => ({
        name: fd.get('name'),
        price: Number(fd.get('price') || 0),
        billing_cycle: fd.get('billing_cycle'),
        is_active: true,
    }));

    wireJsonForm('blogForm', 'blogs', (fd) => ({
        title: fd.get('title'),
        content: fd.get('content'),
        status: fd.get('status'),
    }));

    wireJsonForm('menuForm', 'menus', (fd) => ({
        name: fd.get('name'),
        location: fd.get('location') || null,
        is_active: true,
    }));

    wireJsonForm('siteSettingsForm', 'site-settings', (fd) => ({
        settings: [{
            key: fd.get('key'),
            value: fd.get('value') || '',
            group: fd.get('group') || 'general',
        }],
    }));

    wireJsonForm('sectionForm', 'homepage-sections/upsert', (fd) => {
        let data = {};
        const raw = (fd.get('data_json') || '').trim();

        if (raw) {
            try {
                data = JSON.parse(raw);
            } catch (error) {
                throw { status: 422, data: { message: 'Invalid JSON in section data.' } };
            }
        }

        return {
            page_id: Number(fd.get('page_id')),
            section_type: fd.get('section_type'),
            title: fd.get('title') || null,
            data,
        };
    });

    document.getElementById('mediaForm').addEventListener('submit', async (event) => {
        event.preventDefault();
        const form = event.currentTarget;
        const fd = new FormData(form);

        try {
            const data = await request('media', {
                method: 'POST',
                body: fd,
            });
            log(data);
            form.reset();
        } catch (error) {
            log(error);
        }
    });
</script>
</body>
</html>

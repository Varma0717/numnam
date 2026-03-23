<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Media Library Admin</title>
    <style>
        body { margin: 0; font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif; background: #f8fafc; color: #111827; }
        .container { max-width: 1200px; margin: 24px auto; padding: 0 16px; }
        .card { background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; padding: 16px; margin-bottom: 14px; }
        .grid { display: grid; gap: 12px; grid-template-columns: repeat(3, minmax(0, 1fr)); }
        .media-grid { display: grid; gap: 12px; grid-template-columns: repeat(4, minmax(0, 1fr)); }
        @media (max-width: 1000px) { .grid, .media-grid { grid-template-columns: 1fr 1fr; } }
        @media (max-width: 640px) { .grid, .media-grid { grid-template-columns: 1fr; } }
        input, select, button { width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #d1d5db; }
        button { border: none; background: #0f766e; color: #fff; font-weight: 600; cursor: pointer; }
        .item { border: 1px solid #d1fae5; border-radius: 10px; padding: 10px; background: #f0fdfa; }
        .meta { font-size: 12px; color: #4b5563; }
        .row { display: grid; grid-template-columns: 1fr auto; gap: 10px; }
        .output { white-space: pre-wrap; background: #0b1220; color: #d1f5ef; border-radius: 10px; padding: 12px; font-size: 12px; min-height: 120px; }
    </style>
</head>
<body>
<div class="container">
    <div class="card">
        <h1>Media Library</h1>
        <p>Upload, organize, and assign images to pages/products.</p>
    </div>

    <div class="card">
        <h2>Upload Images</h2>
        <form id="uploadForm" class="grid" enctype="multipart/form-data">
            <input type="file" name="file" accept="image/*" required>
            <input type="text" name="folder" placeholder="Folder (e.g. homepage)">
            <input type="text" name="collection" placeholder="Collection (e.g. hero)">
            <input type="text" name="title" placeholder="Title">
            <input type="text" name="alt_text" placeholder="Alt text">
            <button type="submit">Upload</button>
        </form>
    </div>

    <div class="card">
        <h2>Organize & Select</h2>
        <div class="row" style="margin-bottom: 10px;">
            <div class="grid">
                <input id="folderFilter" placeholder="Filter folder">
                <input id="collectionFilter" placeholder="Filter collection">
                <select id="entityType">
                    <option value="">Entity type (optional)</option>
                    <option value="page">page</option>
                    <option value="product">product</option>
                </select>
                <input id="entityId" type="number" min="1" placeholder="Entity ID (optional)">
            </div>
            <button id="loadBtn" type="button">Load Images</button>
        </div>
        <div id="mediaGrid" class="media-grid"></div>
    </div>

    <div class="card">
        <h2>Link Image to Page/Product</h2>
        <form id="linkForm" class="grid">
            <input type="number" min="1" name="media_id" placeholder="Media ID" required>
            <select name="entity_type" required>
                <option value="page">page</option>
                <option value="product">product</option>
            </select>
            <input type="number" min="1" name="entity_id" placeholder="Entity ID" required>
            <input type="text" name="role" placeholder="Role (banner, thumbnail, gallery)">
            <button type="submit">Attach</button>
        </form>
    </div>

    <div class="card">
        <h2>API Response</h2>
        <div id="output" class="output">Ready.</div>
    </div>
</div>

<script>
const base = `${window.location.origin}${window.location.pathname.replace('/admin/media', '')}/api/v1/admin`;
const output = document.getElementById('output');
const mediaGrid = document.getElementById('mediaGrid');

function print(data) {
    output.textContent = typeof data === 'string' ? data : JSON.stringify(data, null, 2);
}

async function call(path, options = {}) {
    const response = await fetch(`${base}/${path}`, {
        headers: {
            Accept: 'application/json',
            ...(options.body instanceof FormData ? {} : { 'Content-Type': 'application/json' }),
        },
        ...options,
    });

    const data = await response.json().catch(() => ({ message: 'Non-JSON response' }));

    if (!response.ok) {
        throw data;
    }

    return data;
}

async function loadMedia() {
    const params = new URLSearchParams();
    if (folderFilter.value.trim()) params.set('folder', folderFilter.value.trim());
    if (collectionFilter.value.trim()) params.set('collection', collectionFilter.value.trim());
    if (entityType.value && entityId.value) {
        params.set('entity_type', entityType.value);
        params.set('entity_id', entityId.value);
    }

    const data = await call(`media?${params.toString()}`);
    const rows = data.data?.data || [];

    mediaGrid.innerHTML = rows.map(item => `
        <article class="item">
            <div><strong>#${item.id}</strong> ${item.title || item.file_name}</div>
            <div class="meta">folder: ${item.folder} / ${item.collection}</div>
            <div class="meta">path: ${item.file_path}</div>
            <div class="meta">linked: ${(item.links || []).map(x => `${x.entity_type}:${x.entity_id}`).join(', ') || 'none'}</div>
        </article>
    `).join('') || '<div class="item">No media found</div>';

    print(data);
}

uploadForm.addEventListener('submit', async (e) => {
    e.preventDefault();
    try {
        const data = await call('media', { method: 'POST', body: new FormData(uploadForm) });
        print(data);
        uploadForm.reset();
        await loadMedia();
    } catch (err) {
        print(err);
    }
});

linkForm.addEventListener('submit', async (e) => {
    e.preventDefault();
    const fd = new FormData(linkForm);
    const mediaId = fd.get('media_id');

    try {
        const payload = {
            entity_type: fd.get('entity_type'),
            entity_id: Number(fd.get('entity_id')),
            role: fd.get('role') || 'gallery',
        };

        const data = await call(`media/${mediaId}/attach`, {
            method: 'POST',
            body: JSON.stringify(payload),
        });

        print(data);
        linkForm.reset();
        await loadMedia();
    } catch (err) {
        print(err);
    }
});

loadBtn.addEventListener('click', async () => {
    try {
        await loadMedia();
    } catch (err) {
        print(err);
    }
});

loadMedia().catch(print);
</script>
</body>
</html>

@extends('admin.layouts.app')

@section('title', 'Media Library - NumNam Admin')

@section('content')
<section class="admin-page-head">
    <h2>Media Library</h2>
    <p class="admin-page-sub">Upload, organize, and assign images to pages/products.</p>
</section>

<section class="admin-panel">
    <h3>Upload Images</h3>
    <form id="uploadForm" class="admin-media-grid admin-media-grid-3" enctype="multipart/form-data">
        <div class="admin-form-row">
            <label for="mediaFile">Image File</label>
            <input id="mediaFile" type="file" name="file" accept="image/*" required>
        </div>
        <div class="admin-form-row">
            <label for="mediaFolder">Folder</label>
            <input id="mediaFolder" type="text" name="folder" placeholder="homepage">
        </div>
        <div class="admin-form-row">
            <label for="mediaCollection">Collection</label>
            <input id="mediaCollection" type="text" name="collection" placeholder="hero">
        </div>
        <div class="admin-form-row">
            <label for="mediaTitle">Title</label>
            <input id="mediaTitle" type="text" name="title" placeholder="Hero banner image">
        </div>
        <div class="admin-form-row">
            <label for="mediaAlt">Alt Text</label>
            <input id="mediaAlt" type="text" name="alt_text" placeholder="Baby food products arranged on a table">
        </div>
        <div class="admin-form-row admin-form-row-action">
            <button class="admin-btn" type="submit">Upload</button>
        </div>
    </form>
</section>

<section class="admin-panel">
    <h3>Organize And Select</h3>
    <div class="admin-media-filter-row">
        <div class="admin-media-grid admin-media-grid-4">
            <div class="admin-form-row">
                <label for="folderFilter">Filter Folder</label>
                <input id="folderFilter" placeholder="homepage">
            </div>
            <div class="admin-form-row">
                <label for="collectionFilter">Filter Collection</label>
                <input id="collectionFilter" placeholder="hero">
            </div>
            <div class="admin-form-row">
                <label for="entityType">Entity Type</label>
                <select id="entityType">
                    <option value="">Optional</option>
                    <option value="page">page</option>
                    <option value="product">product</option>
                </select>
            </div>
            <div class="admin-form-row">
                <label for="entityId">Entity ID</label>
                <input id="entityId" type="number" min="1" placeholder="Optional">
            </div>
        </div>
        <button id="loadBtn" class="admin-btn-secondary" type="button">Load Images</button>
    </div>

    <div id="mediaGrid" class="admin-media-library-grid"></div>
</section>

<section class="admin-panel">
    <h3>Link Image To Page Or Product</h3>
    <form id="linkForm" class="admin-media-grid admin-media-grid-4">
        <div class="admin-form-row">
            <label for="mediaId">Media ID</label>
            <input id="mediaId" type="number" min="1" name="media_id" placeholder="Media ID" required>
        </div>
        <div class="admin-form-row">
            <label for="entityTypeAttach">Entity Type</label>
            <select id="entityTypeAttach" name="entity_type" required>
                <option value="page">page</option>
                <option value="product">product</option>
            </select>
        </div>
        <div class="admin-form-row">
            <label for="entityIdAttach">Entity ID</label>
            <input id="entityIdAttach" type="number" min="1" name="entity_id" placeholder="Entity ID" required>
        </div>
        <div class="admin-form-row">
            <label for="mediaRole">Role</label>
            <input id="mediaRole" type="text" name="role" placeholder="banner, thumbnail, gallery">
        </div>
        <div class="admin-form-row admin-form-row-action admin-form-row-span-full">
            <button class="admin-btn" type="submit">Attach</button>
        </div>
    </form>
</section>

<section class="admin-panel">
    <h3>API Response</h3>
    <pre id="output" class="admin-api-output">Ready.</pre>
</section>

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
                ...(options.body instanceof FormData ? {} : {
                    'Content-Type': 'application/json'
                }),
            },
            ...options,
        });

        const data = await response.json().catch(() => ({
            message: 'Non-JSON response'
        }));

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
        <article class="admin-media-item">
            ${item.file_path ? `<img src="${item.file_path}" alt="${item.alt_text || item.title || ''}" style="width:100%;height:120px;object-fit:cover;border-radius:2px;margin-bottom:8px;background:#f0f0f1;">` : ''}
            <div class="admin-media-title">#${item.id} ${item.title || item.file_name}</div>
            <p class="admin-media-meta">folder: ${item.folder} / ${item.collection}</p>
            <p class="admin-media-meta">linked: ${(item.links || []).map(x => `${x.entity_type}:${x.entity_id}`).join(', ') || 'none'}</p>
        </article>
    `).join('') || '<article class="admin-media-item"><p class="admin-media-meta">No media found.</p></article>';

        print(data);
    }

    uploadForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        try {
            const data = await call('media', {
                method: 'POST',
                body: new FormData(uploadForm)
            });
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
@endsection
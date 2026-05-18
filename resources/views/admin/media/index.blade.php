@extends('admin.layouts.app')

@section('title', 'Media Library - NumNam Admin')

@section('content')
<section class="admin-page-head">
    <h2>Media Library</h2>
    <p class="admin-page-sub">WordPress-style media management - upload, organize, search, and manage your files.</p>
</section>

<!-- Upload Section with Drag & Drop -->
<section class="admin-panel">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
        <h3>Upload Files</h3>
        <button id="bulkModeBtn" class="admin-btn-secondary" type="button" style="display:none;">Bulk Actions</button>
    </div>

    <div id="dropZone" class="media-drop-zone">
        <div class="drop-zone-content">
            <svg width="48" height="48" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
            </svg>
            <p style="margin: 1rem 0 0.5rem;">Drag and drop files here or click to browse</p>
            <p style="font-size: 0.875rem; color: #666;">Supported: JPG, PNG, GIF, WEBP, SVG, PDF (Max: 10MB)</p>
        </div>
        <input id="fileInput" type="file" multiple accept="image/*,application/pdf" style="display:none;">
    </div>

    <div id="uploadProgress" style="display:none; margin-top:1rem;">
        <div class="upload-progress-bar">
            <div id="progressBar" class="progress-fill"></div>
        </div>
        <p id="uploadStatus" style="margin-top:0.5rem; font-size:0.875rem;"></p>
    </div>

    <form id="uploadMetaForm" style="display:none; margin-top:1.5rem;" class="admin-media-grid admin-media-grid-3">
        <div class="admin-form-row">
            <label for="uploadFolder">Folder</label>
            <input id="uploadFolder" type="text" placeholder="general">
        </div>
        <div class="admin-form-row">
            <label for="uploadCollection">Collection</label>
            <input id="uploadCollection" type="text" placeholder="uploads">
        </div>
        <div class="admin-form-row">
            <label for="uploadTitle">Title (Optional)</label>
            <input id="uploadTitle" type="text" placeholder="Auto-generated if empty">
        </div>
    </form>
</section>

<!-- Search and Filter Section -->
<section class="admin-panel">
    <h3>Search & Filter</h3>
    <div class="admin-media-grid admin-media-grid-4">
        <div class="admin-form-row">
            <label for="searchInput">Search</label>
            <input id="searchInput" type="text" placeholder="Search by filename, title, or alt text...">
        </div>
        <div class="admin-form-row">
            <label for="folderFilter">Folder</label>
            <select id="folderFilter">
                <option value="">All Folders</option>
            </select>
        </div>
        <div class="admin-form-row">
            <label for="typeFilter">File Type</label>
            <select id="typeFilter">
                <option value="">All Types</option>
                <option value="image">Images</option>
                <option value="application">Documents</option>
            </select>
        </div>
        <div class="admin-form-row">
            <label for="sortBy">Sort By</label>
            <select id="sortBy">
                <option value="created_at">Date Added (Newest)</option>
                <option value="created_at_asc">Date Added (Oldest)</option>
                <option value="file_name">Filename (A-Z)</option>
                <option value="size">File Size</option>
            </select>
        </div>
    </div>
    <div style="margin-top:1rem; display:flex; gap:0.5rem; justify-content: space-between;">
        <div>
            <button id="applyFiltersBtn" class="admin-btn" type="button">Apply Filters</button>
            <button id="resetFiltersBtn" class="admin-btn-secondary" type="button">Reset</button>
        </div>
        <div id="bulkActions" style="display:none; gap:0.5rem;">
            <button id="selectAllBtn" class="admin-btn-secondary" type="button">Select All</button>
            <button id="deselectAllBtn" class="admin-btn-secondary" type="button">Deselect All</button>
            <button id="bulkDeleteBtn" class="admin-btn" type="button" style="background:#dc3545;">Delete Selected</button>
        </div>
    </div>
</section>

<!-- Media Grid -->
<section class="admin-panel">
    <div style="display:flex; justify-content:space-between; align-items:center;">
        <h3>Media Files</h3>
        <div id="mediaStats" style="font-size:0.875rem; color:#666;"></div>
    </div>

    <div id="mediaGrid" class="media-library-grid"></div>
    <div id="mediaLoader" style="text-align:center; padding:2rem; display:none;">
        <p>Loading...</p>
    </div>
    <div id="mediaEmpty" style="text-align:center; padding:2rem; display:none; color:#666;">
        <p>No media files found. Upload some files to get started!</p>
    </div>

    <!-- Pagination -->
    <div id="pagination" style="margin-top:1.5rem; display:flex; justify-content:center; gap:0.5rem;"></div>
</section>

<!-- Preview Modal -->
<div id="previewModal" class="media-modal" style="display:none;">
    <div class="media-modal-overlay"></div>
    <div class="media-modal-content">
        <button id="closeModal" class="media-modal-close">&times;</button>
        <div class="media-modal-body">
            <div class="media-modal-preview">
                <img id="previewImage" src="" alt="" style="max-width:100%; max-height:60vh;">
            </div>
            <div class="media-modal-details">
                <h3 id="detailFileName"></h3>
                <form id="detailsForm">
                    <input type="hidden" id="detailMediaId">
                    <div class="admin-form-row">
                        <label for="detailTitle">Title</label>
                        <input id="detailTitle" type="text">
                    </div>
                    <div class="admin-form-row">
                        <label for="detailAlt">Alt Text</label>
                        <input id="detailAlt" type="text">
                    </div>
                    <div class="admin-form-row">
                        <label for="detailCaption">Caption</label>
                        <textarea id="detailCaption" rows="3"></textarea>
                    </div>
                    <div class="admin-form-row">
                        <label for="detailFolder">Folder</label>
                        <input id="detailFolder" type="text">
                    </div>
                    <div class="media-detail-info">
                        <p><strong>URL:</strong> <span id="detailUrl"></span> <button type="button" id="copyUrlBtn" class="copy-btn">Copy</button></p>
                        <p><strong>File Size:</strong> <span id="detailSize"></span></p>
                        <p><strong>Dimensions:</strong> <span id="detailDimensions"></span></p>
                        <p><strong>Uploaded:</strong> <span id="detailDate"></span></p>
                        <p><strong>Uploaded By:</strong> <span id="detailUploader"></span></p>
                    </div>
                    <div style="display:flex; gap:0.5rem; margin-top:1rem;">
                        <button type="submit" class="admin-btn">Save Changes</button>
                        <button type="button" id="deleteMediaBtn" class="admin-btn" style="background:#dc3545;">Delete</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .media-drop-zone {
        border: 2px dashed #ccc;
        border-radius: 8px;
        padding: 3rem;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
        background: #fafafa;
    }

    .media-drop-zone:hover,
    .media-drop-zone.drag-over {
        border-color: #4a90e2;
        background: #f0f7ff;
    }

    .drop-zone-content svg {
        color: #666;
    }

    .upload-progress-bar {
        width: 100%;
        height: 8px;
        background: #e0e0e0;
        border-radius: 4px;
        overflow: hidden;
    }

    .progress-fill {
        height: 100%;
        background: linear-gradient(90deg, #4a90e2, #357abd);
        width: 0;
        transition: width 0.3s ease;
    }

    .media-library-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
        gap: 1rem;
        margin-top: 1.5rem;
    }

    .media-item {
        position: relative;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        overflow: hidden;
        cursor: pointer;
        transition: all 0.2s ease;
        background: white;
    }

    .media-item:hover {
        border-color: #4a90e2;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        transform: translateY(-2px);
    }

    .media-item.selected {
        border-color: #4a90e2;
        box-shadow: 0 0 0 3px rgba(74, 144, 226, 0.2);
    }

    .media-item-checkbox {
        position: absolute;
        top: 8px;
        left: 8px;
        z-index: 2;
        width: 20px;
        height: 20px;
        cursor: pointer;
    }

    .media-item-preview {
        width: 100%;
        height: 180px;
        object-fit: cover;
        display: block;
    }

    .media-item-info {
        padding: 0.75rem;
        font-size: 0.875rem;
    }

    .media-item-title {
        font-weight: 600;
        margin: 0 0 0.25rem;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .media-item-meta {
        color: #666;
        font-size: 0.75rem;
    }

    .media-modal {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        z-index: 9999;
    }

    .media-modal-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.7);
    }

    .media-modal-content {
        position: relative;
        max-width: 1200px;
        margin: 2rem auto;
        background: white;
        border-radius: 8px;
        max-height: calc(100vh - 4rem);
        overflow-y: auto;
    }

    .media-modal-close {
        position: absolute;
        top: 1rem;
        right: 1rem;
        background: white;
        border: none;
        font-size: 2rem;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        cursor: pointer;
        z-index: 10;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
    }

    .media-modal-body {
        display: grid;
        grid-template-columns: 1.5fr 1fr;
        gap: 2rem;
        padding: 2rem;
    }

    .media-modal-preview {
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f5f5f5;
        border-radius: 8px;
        padding: 1rem;
    }

    .media-detail-info {
        background: #f9f9f9;
        padding: 1rem;
        border-radius: 4px;
        margin-top: 1rem;
        font-size: 0.875rem;
    }

    .media-detail-info p {
        margin: 0.5rem 0;
    }

    .copy-btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
        background: #4a90e2;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        margin-left: 0.5rem;
    }

    .copy-btn:hover {
        background: #357abd;
    }

    @media (max-width: 768px) {
        .media-modal-body {
            grid-template-columns: 1fr;
        }

        .media-library-grid {
            grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        let currentPage = 1;
        let selectedFiles = new Set();
        let bulkMode = false;

        // Load media on page load
        loadMedia();
        loadFolders();

        // Drag and Drop
        const dropZone = document.getElementById('dropZone');
        const fileInput = document.getElementById('fileInput');

        dropZone.addEventListener('click', () => fileInput.click());

        dropZone.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropZone.classList.add('drag-over');
        });

        dropZone.addEventListener('dragleave', () => {
            dropZone.classList.remove('drag-over');
        });

        dropZone.addEventListener('drop', (e) => {
            e.preventDefault();
            dropZone.classList.remove('drag-over');
            const files = e.dataTransfer.files;
            if (files.length) handleFileUpload(files);
        });

        fileInput.addEventListener('change', (e) => {
            if (e.target.files.length) handleFileUpload(e.target.files);
        });

        // File Upload Handler
        function handleFileUpload(files) {
            const formData = new FormData();
            const folder = document.getElementById('uploadFolder').value || 'general';
            const collection = document.getElementById('uploadCollection').value || 'uploads';
            const title = document.getElementById('uploadTitle').value;

            let uploaded = 0;
            const total = files.length;

            document.getElementById('uploadProgress').style.display = 'block';
            document.getElementById('uploadStatus').textContent = `Uploading 0/${total} files...`;

            Array.from(files).forEach((file, index) => {
                const fileFormData = new FormData();
                fileFormData.append('file', file);
                fileFormData.append('folder', folder);
                fileFormData.append('collection', collection);
                if (title) fileFormData.append('title', title);

                const uploadUrl = '{{ route("admin.media.json.upload") }}';
                fetch(uploadUrl, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: fileFormData
                    })
                    .then(res => res.json())
                    .then(data => {
                        uploaded++;
                        const percent = (uploaded / total) * 100;
                        document.getElementById('progressBar').style.width = percent + '%';
                        document.getElementById('uploadStatus').textContent = `Uploaded ${uploaded}/${total} files`;

                        if (uploaded === total) {
                            setTimeout(() => {
                                document.getElementById('uploadProgress').style.display = 'none';
                                document.getElementById('progressBar').style.width = '0';
                                loadMedia();
                            }, 1000);
                        }
                    })
                    .catch(err => {
                        console.error('Upload error:', err);
                        alert('Upload failed for: ' + file.name);
                    });
            });
        }

        // Load Media
        function loadMedia(page = 1) {
            const params = new URLSearchParams();
            params.append('page', page);
            params.append('per_page', 60);

            const search = document.getElementById('searchInput').value;
            const folder = document.getElementById('folderFilter').value;
            const type = document.getElementById('typeFilter').value;
            const sortBy = document.getElementById('sortBy').value;

            if (search) params.append('search', search);
            if (folder) params.append('folder', folder);
            if (type) params.append('type', type);
            if (sortBy) {
                if (sortBy === 'created_at_asc') {
                    params.append('sort_by', 'created_at');
                    params.append('sort_order', 'asc');
                } else {
                    params.append('sort_by', sortBy);
                    params.append('sort_order', 'desc');
                }
            }

            document.getElementById('mediaLoader').style.display = 'block';
            document.getElementById('mediaGrid').innerHTML = '';

            const mediaUrl = '{{ route("admin.media.json") }}';
            fetch(mediaUrl + '?' + params.toString())
                .then(res => res.json())
                .then(response => {
                    document.getElementById('mediaLoader').style.display = 'none';

                    if (!response.data || response.data.length === 0) {
                        document.getElementById('mediaEmpty').style.display = 'block';
                        document.getElementById('mediaStats').textContent = '';
                        return;
                    }

                    document.getElementById('mediaEmpty').style.display = 'none';
                    document.getElementById('mediaStats').textContent =
                        `Showing ${response.meta.from}-${response.meta.to} of ${response.meta.total} files`;

                    renderMediaGrid(response.data);
                    renderPagination(response.meta);
                })
                .catch(err => {
                    console.error('Load error:', err);
                    document.getElementById('mediaLoader').style.display = 'none';
                });
        }
        const grid = document.getElementById('mediaGrid');
        grid.innerHTML = '';

        items.forEach(item => {
            const div = document.createElement('div');
            div.className = 'media-item';
            div.dataset.id = item.id;

            const isImage = item.mime_type.startsWith('image/');
            const preview = isImage ? item.url : '/assets/images/file-icon.png';

            div.innerHTML = `
                ${bulkMode ? `<input type="checkbox" class="media-item-checkbox" data-id="${item.id}">` : ''}
                <img src="${preview}" alt="${item.title || item.file_name}" class="media-item-preview">
                <div class="media-item-info">
                    <div class="media-item-title">${item.title || item.file_name}</div>
                    <div class="media-item-meta">${item.size_formatted}</div>
                </div>
            `;

            div.addEventListener('click', (e) => {
                if (!e.target.classList.contains('media-item-checkbox')) {
                    showPreviewModal(item);
                }
            });

            grid.appendChild(div);
        });

        // Bulk selection
        document.querySelectorAll('.media-item-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', (e) => {
                e.stopPropagation();
                const id = parseInt(checkbox.dataset.id);
                if (checkbox.checked) {
                    selectedFiles.add(id);
                    checkbox.closest('.media-item').classList.add('selected');
                } else {
                    selectedFiles.delete(id);
                    checkbox.closest('.media-item').classList.remove('selected');
                }
            });
        });
    }

    // Render Pagination
    function renderPagination(meta) {
        const pagination = document.getElementById('pagination');
        pagination.innerHTML = '';

        if (meta.last_page <= 1) return;

        for (let i = 1; i <= meta.last_page; i++) {
            const btn = document.createElement('button');
            btn.textContent = i;
            btn.className = i === meta.current_page ? 'admin-btn' : 'admin-btn-secondary';
            btn.addEventListener('click', () => loadMedia(i));
            pagination.appendChild(btn);
        }
    }

    // Load Folders
    function loadFolders() {
        const foldersUrl = '{{ route("admin.media.json.folders") }}';
        fetch(foldersUrl)
            .then(res => res.json())
            .then(response => {
                const select = document.getElementById('folderFilter');
                response.data.forEach(folder => {
                    const option = document.createElement('option');
                    option.value = folder.name;
                    option.textContent = `${folder.name} (${folder.count})`;
                    select.appendChild(option);
                });
            });
    }

    // Preview Modal
    function showPreviewModal(item) {
        document.getElementById('previewModal').style.display = 'block';
        document.getElementById('previewImage').src = item.url;
        document.getElementById('detailMediaId').value = item.id;
        document.getElementById('detailFileName').textContent = item.file_name;
        document.getElementById('detailTitle').value = item.title || '';
        document.getElementById('detailAlt').value = item.alt_text || '';
        document.getElementById('detailCaption').value = item.caption || '';
        document.getElementById('detailFolder').value = item.folder || '';
        document.getElementById('detailUrl').textContent = item.url;
        document.getElementById('detailSize').textContent = item.size_formatted;
        document.getElementById('detailDimensions').textContent = item.dimensions ?
            `${item.dimensions.width}x${item.dimensions.height}` : 'N/A';
        document.getElementById('detailDate').textContent = item.created_at;
        document.getElementById('detailUploader').textContent = item.uploaded_by;
    }

    document.getElementById('closeModal').addEventListener('click', () => {
        document.getElementById('previewModal').style.display = 'none';
    });

    document.querySelector('.media-modal-overlay').addEventListener('click', () => {
        document.getElementById('previewModal').style.display = 'none';
    });

    // Save Details
    document.getElementById('detailsForm').addEventListener('submit', (e) => {
        e.preventDefault();
        const id = document.getElementById('detailMediaId').value;
        const formData = new FormData(e.target);

        fetch(`/admin/media/json/${id}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    _method: 'PUT',
                    title: document.getElementById('detailTitle').value,
                    alt_text: document.getElementById('detailAlt').value,
                    caption: document.getElementById('detailCaption').value,
                    folder: document.getElementById('detailFolder').value,
                }),
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(res => res.json())
            .then(data => {
                alert('Media updated successfully!');
                loadMedia();
            });
    });

    // Delete Media
    document.getElementById('deleteMediaBtn').addEventListener('click', () => {
        if (!confirm('Are you sure you want to delete this file?')) return;

        const id = document.getElementById('detailMediaId').value;
        fetch(`/admin/media/json/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(res => res.json())
            .then(data => {
                document.getElementById('previewModal').style.display = 'none';
                loadMedia();
            });
    });

    // Copy URL
    document.getElementById('copyUrlBtn').addEventListener('click', () => {
        const url = document.getElementById('detailUrl').textContent;
        navigator.clipboard.writeText(url).then(() => {
            alert('URL copied to clipboard!');
        });
    });

    // Filters
    document.getElementById('applyFiltersBtn').addEventListener('click', () => loadMedia()); document.getElementById('resetFiltersBtn').addEventListener('click', () => {
        document.getElementById('searchInput').value = '';
        document.getElementById('folderFilter').value = '';
        document.getElementById('typeFilter').value = '';
        document.getElementById('sortBy').value = 'created_at';
        loadMedia();
    });

    // Bulk Mode
    document.getElementById('bulkModeBtn').addEventListener('click', () => {
        bulkMode = !bulkMode;
        document.getElementById('bulkActions').style.display = bulkMode ? 'flex' : 'none';
        selectedFiles.clear();
        loadMedia();
    });

    document.getElementById('selectAllBtn').addEventListener('click', () => {
        document.querySelectorAll('.media-item-checkbox').forEach(cb => {
            cb.checked = true;
            selectedFiles.add(parseInt(cb.dataset.id));
            cb.closest('.media-item').classList.add('selected');
        });
    });

    document.getElementById('deselectAllBtn').addEventListener('click', () => {
        document.querySelectorAll('.media-item-checkbox').forEach(cb => {
            cb.checked = false;
            cb.closest('.media-item').classList.remove('selected');
        });
        selectedFiles.clear();
    });

    document.getElementById('bulkDeleteBtn').addEventListener('click', () => {
        if (selectedFiles.size === 0) {
            alert('Please select files to delete');
            return;
        }

        if (!confirm(`Delete ${selectedFiles.size} selected files?`)) return;

        const bulkDeleteUrl = '{{ route("admin.media.json.bulk-delete") }}';
        fetch(bulkDeleteUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    ids: Array.from(selectedFiles)
                })
            })
            .then(res => res.json())
            .then(data => {
                selectedFiles.clear();
                loadMedia();
            });
    });
    });
</script>
@endsection
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
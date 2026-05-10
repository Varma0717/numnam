/**
 * NumNam Admin – global helpers
 */
(function () {
    'use strict';

    /* ---- Select-all / Bulk-action bar ---- */
    const selectAll = document.getElementById('select-all');
    const bulkBar = document.querySelector('.admin-bulk-bar');

    if (selectAll) {
        const checkboxes = () => document.querySelectorAll('input.row-check');

        selectAll.addEventListener('change', function () {
            checkboxes().forEach(cb => cb.checked = this.checked);
            updateBulkBar();
        });

        document.addEventListener('change', function (e) {
            if (e.target.classList.contains('row-check')) {
                updateBulkBar();
                // Keep select-all in sync
                const all = checkboxes();
                selectAll.checked = all.length > 0 && [...all].every(cb => cb.checked);
            }
        });

        function updateBulkBar() {
            if (!bulkBar) return;
            const count = [...checkboxes()].filter(cb => cb.checked).length;
            const countEl = bulkBar.querySelector('#bulk-count');
            if (countEl) countEl.textContent = count;
            bulkBar.style.display = count > 0 ? 'flex' : 'none';
        }
    }

    /* ---- Confirm dangerous actions ---- */
    document.querySelectorAll('[data-confirm]').forEach(el => {
        el.addEventListener('click', function (e) {
            if (!confirm(this.dataset.confirm || 'Are you sure?')) {
                e.preventDefault();
            }
        });
    });
})();

/* ======================================================================
   Media Picker — reusable modal for selecting images from Media Library
   ====================================================================== */
window.MediaPicker = (function () {
    'use strict';

    // Use web admin routes (session-authed) instead of API routes (JWT)
    const ADMIN_BASE = (function () {
        const p = window.location.pathname;
        const adminIdx = p.indexOf('/admin');
        return adminIdx > -1 ? p.substring(0, adminIdx) + '/admin' : '/admin';
    })();

    function csrfToken() {
        const meta = document.querySelector('meta[name="csrf-token"]');
        if (meta) return meta.content;
        const input = document.querySelector('input[name="_token"]');
        return input ? input.value : '';
    }

    let _overlay, _grid, _insertBtn, _selectedInfo, _folderFilter, _searchInput;
    let _mediaItems = [];
    let _selected = null;
    let _callback = null;
    let _multiSelect = false;
    let _selectedMulti = [];

    function init() {
        _overlay = document.getElementById('mediaPickerOverlay');
        if (!_overlay) return;

        _grid = document.getElementById('mpGrid');
        _insertBtn = document.getElementById('mpInsertBtn');
        _selectedInfo = document.getElementById('mpSelectedInfo');
        _folderFilter = document.getElementById('mpFolderFilter');
        _searchInput = document.getElementById('mpSearch');

        // Tabs
        _overlay.querySelectorAll('.mp-tab').forEach(tab => {
            tab.addEventListener('click', function () {
                _overlay.querySelectorAll('.mp-tab').forEach(t => t.classList.remove('mp-tab-active'));
                this.classList.add('mp-tab-active');
                const target = this.dataset.tab;
                document.getElementById('mpLibraryPanel').style.display = target === 'library' ? '' : 'none';
                document.getElementById('mpUploadPanel').style.display = target === 'upload' ? '' : 'none';
            });
        });

        // Upload drag & drop
        const dropzone = document.getElementById('mpDropzone');
        const fileInput = document.getElementById('mpFileInput');
        if (dropzone && fileInput) {
            dropzone.addEventListener('click', () => fileInput.click());
            dropzone.addEventListener('dragover', e => { e.preventDefault(); dropzone.classList.add('dragover'); });
            dropzone.addEventListener('dragleave', () => dropzone.classList.remove('dragover'));
            dropzone.addEventListener('drop', e => {
                e.preventDefault();
                dropzone.classList.remove('dragover');
                if (e.dataTransfer.files.length) {
                    fileInput.files = e.dataTransfer.files;
                    fileInput.dispatchEvent(new Event('change'));
                }
            });
            fileInput.addEventListener('change', () => {
                if (fileInput.files.length) {
                    document.getElementById('mpUploadFields').style.display = '';
                }
            });
        }

        // Upload form
        const uploadForm = document.getElementById('mpUploadForm');
        if (uploadForm) {
            uploadForm.addEventListener('submit', async (e) => {
                e.preventDefault();
                const status = document.getElementById('mpUploadStatus');
                status.textContent = 'Uploading...';
                try {
                    const fd = new FormData(uploadForm);
                    fd.set('_token', csrfToken());
                    const resp = await fetch(`${ADMIN_BASE}/media/json/upload`, {
                        method: 'POST',
                        headers: { Accept: 'application/json', 'X-CSRF-TOKEN': csrfToken() },
                        body: fd,
                    });
                    const data = await resp.json();
                    if (data.success || resp.ok) {
                        status.textContent = 'Uploaded!';
                        uploadForm.reset();
                        document.getElementById('mpUploadFields').style.display = 'none';
                        _overlay.querySelector('.mp-tab[data-tab="library"]').click();
                        await loadMedia();
                    } else {
                        status.textContent = data.message || 'Upload failed';
                    }
                } catch (err) {
                    status.textContent = 'Error: ' + err.message;
                }
            });
        }

        // Insert button
        _insertBtn.addEventListener('click', () => {
            if (_callback) {
                if (_multiSelect) {
                    _callback(_selectedMulti);
                } else if (_selected) {
                    _callback(_selected);
                }
            }
            close();
        });

        // Close on overlay background click
        _overlay.addEventListener('click', (e) => {
            if (e.target === _overlay) close();
        });

        if (_searchInput) {
            _searchInput.addEventListener('input', renderGrid);
        }
        if (_folderFilter) {
            _folderFilter.addEventListener('change', () => loadMedia());
        }

        loadFolders();
    }

    async function adminFetch(path) {
        const resp = await fetch(`${ADMIN_BASE}/${path}`, {
            headers: { Accept: 'application/json' },
        });
        return resp.json();
    }

    async function loadFolders() {
        try {
            const data = await adminFetch('media/json/folders');
            const folders = data.data || [];
            if (_folderFilter && folders.length) {
                _folderFilter.innerHTML = '<option value="">All Folders</option>' +
                    folders.map(f => `<option value="${escapeHtml(f)}">${escapeHtml(f)}</option>`).join('');
            }
        } catch (_) { /* ignore */ }
    }

    async function loadMedia() {
        if (!_grid) return;
        _grid.innerHTML = '<p style="text-align:center;color:var(--wp-muted);padding:20px;">Loading...</p>';

        const params = new URLSearchParams();
        if (_folderFilter && _folderFilter.value) params.set('folder', _folderFilter.value);
        params.set('per_page', '100');

        try {
            const data = await adminFetch(`media/json?${params.toString()}`);
            _mediaItems = (data.data?.data || data.data || []).map(item => ({
                id: item.id,
                url: item.metadata?.url || (item.file_path ? `${window.location.origin}/storage/${item.file_path}` : ''),
                title: item.title || item.file_name || '',
                folder: item.folder || '',
            }));
            renderGrid();
        } catch (err) {
            _grid.innerHTML = '<p style="color:var(--wp-error);padding:20px;">Failed to load media.</p>';
        }
    }

    function renderGrid() {
        if (!_grid) return;
        const query = (_searchInput?.value || '').toLowerCase();
        const filtered = _mediaItems.filter(item =>
            !query || item.title.toLowerCase().includes(query) || item.folder.toLowerCase().includes(query)
        );

        if (!filtered.length) {
            _grid.innerHTML = '<p style="text-align:center;color:var(--wp-muted);padding:40px;">No media found. Upload images using the Upload tab.</p>';
            return;
        }

        _grid.innerHTML = filtered.map(item => {
            const isSelected = _multiSelect
                ? _selectedMulti.some(s => s.id === item.id)
                : (_selected && _selected.id === item.id);
            return `<div class="mp-item${isSelected ? ' mp-selected' : ''}" data-id="${item.id}" data-url="${escapeAttr(item.url)}" data-title="${escapeAttr(item.title)}">
                <img src="${escapeAttr(item.url)}" alt="${escapeAttr(item.title)}" loading="lazy">
                <span class="mp-item-name">${escapeHtml(item.title)}</span>
            </div>`;
        }).join('');

        _grid.querySelectorAll('.mp-item').forEach(el => {
            el.addEventListener('click', () => {
                const mediaData = { id: Number(el.dataset.id), url: el.dataset.url, title: el.dataset.title };
                if (_multiSelect) {
                    const idx = _selectedMulti.findIndex(s => s.id === mediaData.id);
                    if (idx > -1) { _selectedMulti.splice(idx, 1); el.classList.remove('mp-selected'); }
                    else { _selectedMulti.push(mediaData); el.classList.add('mp-selected'); }
                    _insertBtn.disabled = _selectedMulti.length === 0;
                    _selectedInfo.textContent = _selectedMulti.length ? `${_selectedMulti.length} selected` : '';
                } else {
                    _grid.querySelectorAll('.mp-item').forEach(e => e.classList.remove('mp-selected'));
                    el.classList.add('mp-selected');
                    _selected = mediaData;
                    _insertBtn.disabled = false;
                    _selectedInfo.textContent = mediaData.title;
                }
            });
        });
    }

    function open(callback, options = {}) {
        _callback = callback;
        _multiSelect = !!options.multi;
        _selected = null;
        _selectedMulti = [];

        if (!_overlay) init();
        if (!_overlay) return;

        _insertBtn.disabled = true;
        _selectedInfo.textContent = '';
        _overlay.classList.add('active');
        _overlay.querySelector('.mp-tab[data-tab="library"]').click();
        loadMedia();
    }

    function close() {
        if (_overlay) _overlay.classList.remove('active');
        _callback = null;
    }

    function escapeAttr(s) { return (s || '').replace(/&/g, '&amp;').replace(/"/g, '&quot;').replace(/</g, '&lt;'); }
    function escapeHtml(s) { return (s || '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;'); }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

    return { open, close };
})();

/* ======================================================================
   Media Picker Form Helpers — wire up "Choose Image" buttons in forms
   ====================================================================== */
window.MediaPickerField = {
    /**
     * Single image field.
     * btn: the "Choose Image" button element
     * hiddenInput: the hidden input for the path/URL
     * previewWrap: container for <img> preview
     */
    bindSingle(btn, hiddenInput, previewWrap) {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            MediaPicker.open((media) => {
                hiddenInput.value = media.url;
                previewWrap.innerHTML = `
                    <img src="${media.url}" alt="${media.title || ''}">
                    <button type="button" class="mp-preview-remove" onclick="MediaPickerField.clearSingle(this)">&times;</button>
                `;
            });
        });
    },

    clearSingle(removeBtn) {
        const wrap = removeBtn.closest('.mp-preview-wrap');
        const hiddenInput = wrap.previousElementSibling?.matches('input[type=hidden]')
            ? wrap.previousElementSibling
            : wrap.parentElement.querySelector('input[type=hidden]');
        if (hiddenInput) hiddenInput.value = '';
        wrap.innerHTML = '';
    },

    /**
     * Gallery field (multiple images).
     * btn: "Add Images" button
     * hiddenInput: hidden textarea/input for gallery URLs (newline-separated or JSON)
     * gridContainer: container for gallery thumbnail grid
     */
    bindGallery(btn, hiddenInput, gridContainer) {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            MediaPicker.open((items) => {
                const current = hiddenInput.value.trim();
                const urls = current ? current.split('\n').filter(u => u.trim()) : [];
                items.forEach(m => {
                    if (!urls.includes(m.url)) urls.push(m.url);
                });
                hiddenInput.value = urls.join('\n');
                MediaPickerField.renderGallery(gridContainer, hiddenInput);
            }, { multi: true });
        });

        // Initial render
        MediaPickerField.renderGallery(gridContainer, hiddenInput);
    },

    renderGallery(container, hiddenInput) {
        const urls = hiddenInput.value.trim().split('\n').filter(u => u.trim());
        container.innerHTML = urls.map((url, i) => `
            <div class="mp-gallery-item" data-index="${i}">
                <img src="${url}" alt="">
                <button type="button" class="mp-preview-remove" onclick="MediaPickerField.removeGalleryItem(this, ${i})">&times;</button>
            </div>
        `).join('');
    },

    removeGalleryItem(btn, index) {
        const container = btn.closest('.mp-gallery-grid');
        const hiddenInput = container.previousElementSibling?.matches('textarea')
            ? container.previousElementSibling
            : container.parentElement.querySelector('textarea');
        if (!hiddenInput) return;
        const urls = hiddenInput.value.trim().split('\n').filter(u => u.trim());
        urls.splice(index, 1);
        hiddenInput.value = urls.join('\n');
        MediaPickerField.renderGallery(container, hiddenInput);
    },
};

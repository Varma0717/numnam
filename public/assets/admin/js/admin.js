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
            const countEl = bulkBar.querySelector('.bulk-count');
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

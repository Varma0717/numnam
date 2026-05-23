/**
 * Form Builder JavaScript
 * Handles dynamic form row addition/removal with proper field name indexing
 * Used for nutrition facts, shipping rules, and other repeatable fields
 */

(function () {
    'use strict';

    /**
     * Initialize form builder on document load
     */
    document.addEventListener('DOMContentLoaded', function () {
        initializeFormBuilders();
        attachFormBuilderListeners();
    });

    /**
     * Initialize all form builders on the page
     */
    function initializeFormBuilders() {
        const builders = document.querySelectorAll('.admin-form-builder');
        builders.forEach(builder => {
            const container = builder.querySelector('.admin-form-builder-rows');
            if (container) {
                reindexFormRows(container);
            }
        });
    }

    /**
     * Attach event listeners to form builder buttons
     */
    function attachFormBuilderListeners() {
        // Add row buttons
        document.querySelectorAll('.admin-btn-add-row').forEach(btn => {
            btn.addEventListener('click', handleAddRow);
        });

        // Remove row buttons
        document.querySelectorAll('.admin-form-builder-row-remove').forEach(btn => {
            btn.addEventListener('click', handleRemoveRow);
        });
    }

    /**
     * Handle adding a new form row
     */
    function handleAddRow(e) {
        e.preventDefault();
        const btn = e.currentTarget;
        const fieldName = btn.getAttribute('data-field');
        const container = document.querySelector(`[data-field="${fieldName}"].admin-form-builder-rows`);

        if (!container) return;

        // Remove empty state if exists
        const emptyState = container.querySelector('.admin-form-builder-empty');
        if (emptyState) {
            emptyState.remove();
        }

        // Create new row based on existing template
        const existingRow = container.querySelector('.admin-form-builder-row');
        let newRow;

        if (existingRow) {
            newRow = existingRow.cloneNode(true);
            // Clear all input values in the cloned row
            newRow.querySelectorAll('input, textarea, select').forEach(field => {
                if (field.type === 'checkbox' || field.type === 'radio') {
                    field.checked = false;
                } else {
                    field.value = '';
                }
            });
        } else {
            // Create a basic empty row structure
            newRow = document.createElement('div');
            newRow.className = 'admin-form-builder-row';
        }

        // Update row index
        const rowCount = container.querySelectorAll('.admin-form-builder-row').length;
        newRow.setAttribute('data-index', rowCount);

        // Re-index all field names
        updateFieldNamesForRow(newRow, rowCount);

        // Add remove button listener
        const removeBtn = newRow.querySelector('.admin-form-builder-row-remove');
        if (removeBtn) {
            removeBtn.addEventListener('click', handleRemoveRow);
        }

        container.appendChild(newRow);
        triggerFormChangedEvent(container);
    }

    /**
     * Handle removing a form row
     */
    function handleRemoveRow(e) {
        e.preventDefault();
        const btn = e.currentTarget;
        const row = btn.closest('.admin-form-builder-row');
        const container = row.closest('.admin-form-builder-rows');

        if (!row || !container) return;

        row.remove();

        // Reindex remaining rows
        reindexFormRows(container);

        // Show empty state if no rows left
        if (container.querySelectorAll('.admin-form-builder-row').length === 0) {
            const emptyState = document.createElement('div');
            emptyState.className = 'admin-form-builder-empty';
            emptyState.innerHTML = '<p>No items added yet. Click "Add Item" to get started.</p>';
            container.appendChild(emptyState);
        }

        triggerFormChangedEvent(container);
    }

    /**
     * Reindex all form rows in a container
     */
    function reindexFormRows(container) {
        const rows = container.querySelectorAll('.admin-form-builder-row');
        rows.forEach((row, index) => {
            row.setAttribute('data-index', index);
            updateFieldNamesForRow(row, index);
        });
    }

    /**
     * Update field names for a specific row with proper indexing
     */
    function updateFieldNamesForRow(row, index) {
        const inputs = row.querySelectorAll('input, textarea, select');
        inputs.forEach(input => {
            const currentName = input.getAttribute('name');
            if (currentName) {
                // Handle array notation: field[] -> field[index]
                const newName = currentName
                    .replace(/\[\d+\]/g, '[' + index + ']')
                    .replace(/\[\]$/g, '[' + index + ']');
                input.setAttribute('name', newName);
            }
        });
    }

    /**
     * Trigger custom event when form changes (for JSON serialization, etc.)
     */
    function triggerFormChangedEvent(container) {
        const event = new CustomEvent('formBuilderChanged', {
            detail: { container }
        });
        document.dispatchEvent(event);
    }

    // Expose functions globally if needed
    window.FormBuilder = {
        reindexRows: reindexFormRows,
        addRow: handleAddRow,
        removeRow: handleRemoveRow,
        initialize: initializeFormBuilders
    };
})();

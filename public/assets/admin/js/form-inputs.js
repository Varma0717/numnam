/**
 * Form Inputs JavaScript
 * Handles character counters, input validation, and interactive features
 */

(function () {
    'use strict';

    /**
     * Initialize form inputs on page load
     */
    document.addEventListener('DOMContentLoaded', function () {
        initializeCharacterCounters();
        initializeFormValidation();
        initializeConditionalFields();
    });

    /**
     * Initialize character counters for inputs and textareas with maxlength
     */
    function initializeCharacterCounters() {
        // Find all inputs and textareas with maxlength
        const fields = document.querySelectorAll(
            'input[maxlength], textarea[maxlength], .admin-input[maxlength], .admin-textarea[maxlength]'
        );

        fields.forEach(field => {
            const maxLength = parseInt(field.getAttribute('maxlength'), 10);
            if (!maxLength) return;

            // Create counter element
            const wrapper = field.closest('.admin-form-row') || field.parentElement;
            let counter = wrapper ? wrapper.querySelector('.admin-input-counter, .admin-textarea-counter') : null;

            if (!counter && wrapper) {
                counter = document.createElement('div');
                counter.className = field.tagName === 'TEXTAREA'
                    ? 'admin-textarea-counter'
                    : 'admin-input-counter';
                counter.innerHTML = `<span class="admin-counter-current">0</span><span class="admin-counter-max">/${maxLength}</span>`;
                wrapper.appendChild(counter);
            }

            // Initial count
            updateCharacterCount(field, counter);

            // Listen for input changes
            field.addEventListener('input', () => updateCharacterCount(field, counter));
            field.addEventListener('change', () => updateCharacterCount(field, counter));
        });
    }

    /**
     * Update character count display
     */
    function updateCharacterCount(field, counter) {
        if (!counter) return;

        const currentSpan = counter.querySelector('.admin-counter-current');
        const count = field.value.length;
        const maxLength = parseInt(field.getAttribute('maxlength'), 10);

        if (currentSpan) {
            currentSpan.textContent = count;

            // Change color if approaching limit (80% or more)
            if (count >= maxLength * 0.8) {
                counter.style.color = count >= maxLength ? 'var(--wp-error)' : 'var(--wp-warning)';
                currentSpan.style.fontWeight = 'bold';
            } else {
                counter.style.color = 'var(--wp-muted)';
                currentSpan.style.fontWeight = '600';
            }
        }
    }

    /**
     * Initialize form validation feedback
     */
    function initializeFormValidation() {
        const form = document.querySelector('form');
        if (!form) return;

        // Add visual feedback on form submission
        form.addEventListener('submit', function (e) {
            const isValid = form.checkValidity();
            if (!isValid) {
                // Scroll to first error
                const firstError = form.querySelector('input:invalid, select:invalid, textarea:invalid');
                if (firstError) {
                    firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    firstError.focus();
                }
            }
        });

        // Add live validation to individual fields
        const inputs = form.querySelectorAll('input[required], select[required], textarea[required]');
        inputs.forEach(input => {
            input.addEventListener('blur', validateField);
            input.addEventListener('change', validateField);
        });
    }

    /**
     * Validate individual field
     */
    function validateField(e) {
        const field = e.currentTarget;
        const isValid = field.checkValidity();

        // Remove existing error message
        const existingError = field.closest('.admin-form-row')?.querySelector('.admin-error-message');
        if (existingError) {
            existingError.remove();
        }

        // Show error if invalid
        if (!isValid && field.value) {
            const wrapper = field.closest('.admin-form-row');
            if (wrapper) {
                const errorMsg = document.createElement('div');
                errorMsg.className = 'admin-error-message';
                errorMsg.innerHTML = `
                    <span class="admin-error-icon">⚠</span>
                    <span class="admin-error-text">${field.validationMessage || 'This field is invalid'}</span>
                `;
                wrapper.appendChild(errorMsg);
            }
        }
    }

    /**
     * Initialize conditional field display based on checkbox/toggle states
     */
    function initializeConditionalFields() {
        // Find all checkboxes that might control conditional fields
        const toggles = document.querySelectorAll('.admin-toggle-label input[type="checkbox"]');

        toggles.forEach(toggle => {
            const fieldName = toggle.getAttribute('name');
            if (!fieldName) return;

            // Look for related conditional fields
            // Naming convention: field_enabled -> field_value fields become visible
            const baseName = fieldName.replace(/^settings\[/, '').replace(/\]$/, '').replace(/_enabled$/, '');
            const conditionalFields = document.querySelectorAll(
                `.${baseName}-condition, [data-condition="${fieldName}"]`
            );

            // Initial state
            updateConditionalFields(toggle, conditionalFields);

            // Listen for changes
            toggle.addEventListener('change', () => {
                updateConditionalFields(toggle, conditionalFields);
            });
        });
    }

    /**
     * Update visibility of conditional fields
     */
    function updateConditionalFields(toggle, conditionalFields) {
        const isChecked = toggle.checked;

        conditionalFields.forEach(field => {
            if (isChecked) {
                field.style.opacity = '1';
                field.style.pointerEvents = 'auto';
                field.querySelectorAll('input, select, textarea').forEach(f => {
                    f.disabled = false;
                });
            } else {
                field.style.opacity = '0.5';
                field.style.pointerEvents = 'none';
                field.querySelectorAll('input, select, textarea').forEach(f => {
                    f.disabled = true;
                });
            }
        });
    }

    /**
     * Format currency input
     */
    window.formatCurrency = function (input) {
        let value = input.value.replace(/[^0-9.]/g, '');
        if (value) {
            value = parseFloat(value).toFixed(2);
            input.value = value;
        }
    };

    /**
     * Format number input
     */
    window.formatNumber = function (input) {
        input.value = input.value.replace(/[^0-9-]/g, '');
    };

    /**
     * Format percentage input
     */
    window.formatPercentage = function (input) {
        let value = input.value.replace(/[^0-9.]/g, '');
        if (value && parseFloat(value) > 100) {
            value = '100';
        }
        input.value = value;
    };

    // Expose globally
    window.FormInputs = {
        initCounters: initializeCharacterCounters,
        initValidation: initializeFormValidation,
        initConditional: initializeConditionalFields,
        validateField: validateField
    };
})();

{{-- Shipping Rules Form Builder
   Replaces array notation with intuitive form builder for shipping zones
   Handles pincodes, states, and countries
   
   Usage in shipping zone form:
   <x-admin.shipping-rules :rules="$zone->rules" />
   
   Stores as JSON array: [{"type":"pincode","value":"500001"},{"type":"state","value":"Telangana"}]
--}}

@props([
'rules' => [],
])

@php
// Parse existing JSON data into array
$shippingRules = [];
if ($rules) {
if (is_string($rules)) {
$parsed = json_decode($rules, true);
if (is_array($parsed)) {
$shippingRules = $parsed;
}
} elseif (is_array($rules)) {
$shippingRules = $rules;
}
}
@endphp

<x-admin.form-section title="Shipping Rules" icon="box">
    <p class="admin-field-desc" style="margin-bottom: var(--space-lg);">
        Define where this shipping zone applies using pincodes, states, or countries. You can add multiple rules.
    </p>

    <div class="admin-form-builder">
        <div class="admin-form-builder-header">
            <h4 class="admin-form-builder-title">Add Shipping Rules</h4>
            <button
                type="button"
                class="admin-btn-small"
                onclick="addShippingRule()">
                + Add Rule
            </button>
        </div>

        <div class="admin-form-builder-rows" id="shipping_rules_container" data-field="shipping_rules">
            @forelse($shippingRules as $index => $rule)
            <div class="shipping-rule-row" data-index="{{ $index }}" style="display: grid; grid-template-columns: 140px 1fr 100px; gap: var(--space-lg); align-items: flex-end; padding: var(--space-lg); background: #fafbfc; border: 1px solid var(--wp-border); border-radius: 6px; margin-bottom: var(--space-lg);">
                <div class="admin-form-row" style="margin-bottom: 0;">
                    <label class="admin-input-label" style="margin-bottom: var(--space-sm); font-size: 13px;">Type</label>
                    <select
                        name="rule_type[]"
                        class="admin-select"
                        onchange="updateShippingJSON()">
                        <option value="pincode" @selected($rule['type']==='pincode' )>Pincode</option>
                        <option value="state" @selected($rule['type']==='state' )>State</option>
                        <option value="country" @selected($rule['type']==='country' )>Country</option>
                    </select>
                </div>

                <div class="admin-form-row" style="margin-bottom: 0;">
                    <label class="admin-input-label" style="margin-bottom: var(--space-sm); font-size: 13px;">Value</label>
                    <input
                        type="text"
                        name="rule_value[]"
                        class="admin-input"
                        value="{{ $rule['value'] }}"
                        placeholder="e.g., 500001, Telangana, IN"
                        oninput="updateShippingJSON()" />
                </div>

                <button
                    type="button"
                    class="admin-form-builder-row-remove"
                    onclick="removeShippingRule(this)"
                    style="margin-bottom: 0;">
                    × Remove
                </button>
            </div>
            @empty
            <div class="admin-form-builder-empty">
                <p>No rules added yet. Click "Add Rule" to get started.</p>
            </div>
            @endforelse
        </div>
    </div>

    <input
        type="hidden"
        id="shipping_rules_json"
        name="rules"
        value="{{ $rules ?? '[]' }}" />

    <div style="margin-top: var(--space-lg); padding: var(--space-md); background: #f0fdf4; border-left: 3px solid var(--wp-success); border-radius: 4px;">
        <p class="admin-field-desc" style="margin: 0; color: #15803d;">
            <strong>Examples:</strong>
            <br>• Pincode: 500001, 500002, 500003 (single or multiple comma-separated)
            <br>• State: Telangana, Karnataka, Maharashtra
            <br>• Country: IN (India), US (United States), etc.
        </p>
    </div>
</x-admin.form-section>

<script>
    function removeShippingRule(button) {
        button.closest('.shipping-rule-row').remove();
        updateShippingJSON();
    }

    function addShippingRule() {
        const container = document.getElementById('shipping_rules_container');
        const rows = container.querySelectorAll('.shipping-rule-row');
        const newIndex = rows.length;

        const newRow = document.createElement('div');
        newRow.className = 'shipping-rule-row';
        newRow.setAttribute('data-index', newIndex);
        newRow.style.cssText = 'display: grid; grid-template-columns: 140px 1fr 100px; gap: var(--space-lg); align-items: flex-end; padding: var(--space-lg); background: #fafbfc; border: 1px solid var(--wp-border); border-radius: 6px; margin-bottom: var(--space-lg);';

        newRow.innerHTML = `
        <div class="admin-form-row" style="margin-bottom: 0;">
            <label class="admin-input-label" style="margin-bottom: var(--space-sm); font-size: 13px;">Type</label>
            <select 
                name="rule_type[]" 
                class="admin-select"
                onchange="updateShippingJSON()"
            >
                <option value="pincode">Pincode</option>
                <option value="state">State</option>
                <option value="country">Country</option>
            </select>
        </div>
        
        <div class="admin-form-row" style="margin-bottom: 0;">
            <label class="admin-input-label" style="margin-bottom: var(--space-sm); font-size: 13px;">Value</label>
            <input 
                type="text" 
                name="rule_value[]" 
                class="admin-input"
                placeholder="e.g., 500001, Telangana, IN"
                oninput="updateShippingJSON()"
            />
        </div>
        
        <button 
            type="button" 
            class="admin-form-builder-row-remove"
            onclick="removeShippingRule(this)"
            style="margin-bottom: 0;"
        >
            × Remove
        </button>
    `;

        // Remove empty state message if exists
        const emptyState = container.querySelector('.admin-form-builder-empty');
        if (emptyState) {
            emptyState.remove();
        }

        container.appendChild(newRow);
        updateShippingJSON();
    }

    function updateShippingJSON() {
        const types = document.querySelectorAll('input[name="rule_type[]"], select[name="rule_type[]"]');
        const values = document.querySelectorAll('input[name="rule_value[]"]');
        const jsonInput = document.getElementById('shipping_rules_json');

        const rulesArray = [];
        document.querySelectorAll('.shipping-rule-row').forEach((row, index) => {
            const typeSelect = row.querySelector('select[name="rule_type[]"]');
            const valueInput = row.querySelector('input[name="rule_value[]"]');

            if (typeSelect && valueInput) {
                const type = typeSelect.value;
                const value = valueInput.value.trim();
                if (type && value) {
                    rulesArray.push({
                        type,
                        value
                    });
                }
            }
        });

        jsonInput.value = JSON.stringify(rulesArray);
    }

    // Initialize JSON on page load
    document.addEventListener('DOMContentLoaded', () => {
        updateShippingJSON();
    });
</script>

<style scoped>
    .admin-form-builder {
        background: var(--wp-white);
        border: 1px solid var(--wp-border);
        border-radius: 8px;
        padding: var(--space-lg);
    }

    .admin-form-builder-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: var(--space-lg);
        padding-bottom: var(--space-lg);
        border-bottom: 1px solid var(--wp-border);
    }

    .admin-form-builder-title {
        margin: 0;
        font-size: 15px;
        font-weight: 700;
        color: var(--wp-text);
    }

    .admin-btn-small {
        padding: 8px 12px;
        font-size: 13px;
        font-weight: 600;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        transition: all var(--transition-fast);
        background: linear-gradient(135deg, var(--wp-link) 0%, #0d5d61 100%);
        color: white;
    }

    .admin-btn-small:hover {
        background: linear-gradient(135deg, #0d5d61 0%, #094d4a 100%);
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(15, 118, 110, 0.2);
    }

    .admin-form-builder-rows {
        display: flex;
        flex-direction: column;
        gap: var(--space-lg);
    }

    .admin-form-builder-row-remove {
        padding: 8px 12px;
        font-size: 13px;
        font-weight: 600;
        background: var(--wp-error);
        color: white;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        transition: all var(--transition-fast);
    }

    .admin-form-builder-row-remove:hover {
        background: #dc2626;
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(239, 68, 68, 0.2);
    }

    .admin-form-builder-empty {
        padding: var(--space-2xl);
        text-align: center;
        background: #f9fafb;
        border: 2px dashed var(--wp-border);
        border-radius: 6px;
    }

    .admin-form-builder-empty p {
        margin: 0;
        font-size: 14px;
        color: var(--wp-muted);
    }

    .admin-form-row {
        margin-bottom: var(--space-xl);
    }

    .admin-input-label {
        display: block;
        font-size: 14px;
        font-weight: 600;
        color: var(--wp-text);
        line-height: 1.4;
    }

    .admin-input,
    .admin-select {
        width: 100%;
        padding: 10px 12px;
        border: 2px solid var(--wp-border);
        border-radius: 8px;
        font-size: 14px;
        color: var(--wp-text);
        font-family: inherit;
    }
</style>
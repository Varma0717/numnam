{{-- Nutrition Facts Form Builder
   Standalone implementation with repeatable nutrient rows
   Converts between form and JSON for storage
   
   Usage in products form:
   <x-admin.nutrition-facts :nutritionData="$product->nutrition_facts" />
   
   Stores as JSON: {"protein":"13g","carbs":"20g","fat":"5g"}
--}}

@props([
'nutritionData' => null,
])

@php
// Parse existing JSON data into array
$nutrients = [];
if ($nutritionData) {
if (is_string($nutritionData)) {
$parsed = json_decode($nutritionData, true);
if (is_array($parsed)) {
foreach ($parsed as $name => $value) {
$nutrients[] = ['name' => $name, 'value' => $value];
}
}
} elseif (is_array($nutritionData)) {
foreach ($nutritionData as $name => $value) {
$nutrients[] = ['name' => $name, 'value' => $value];
}
}
}
@endphp

<div class="admin-form-builder">
    <div class="admin-form-builder-header">
        <div class="admin-form-builder-header-content">
            <h3 class="admin-form-builder-title">🍎 Add Nutrients</h3>
            <p class="admin-form-builder-description">Track nutritional content per serving for your products</p>
        </div>
        <button
            type="button"
            class="admin-btn-add-row"
            data-field="nutrition_facts"
            aria-label="Add new nutrient">
            <span class="icon">+</span>
            <span class="text">Add Nutrient</span>
        </button>
    </div>

    <div class="admin-form-builder-rows" data-field="nutrition_facts" id="nutrition-facts-rows">
        @if(count($nutrients) > 0)
        @foreach($nutrients as $index => $nutrient)
        <div class="admin-form-builder-row" data-index="{{ $index }}">
            <div class="admin-form-builder-row-handle" aria-label="Drag to reorder" title="Drag to reorder">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="7" cy="5" r="1.5" fill="currentColor" />
                    <circle cx="13" cy="5" r="1.5" fill="currentColor" />
                    <circle cx="7" cy="10" r="1.5" fill="currentColor" />
                    <circle cx="13" cy="10" r="1.5" fill="currentColor" />
                    <circle cx="7" cy="15" r="1.5" fill="currentColor" />
                    <circle cx="13" cy="15" r="1.5" fill="currentColor" />
                </svg>
            </div>
            <div class="admin-form-builder-row-content">
                <div class="admin-form-row" style="margin-bottom: 0;">
                    <label class="admin-input-label" style="margin-bottom: var(--space-sm);">Nutrient Name</label>
                    <input
                        type="text"
                        name="nutrition_facts_name[]"
                        class="admin-input"
                        value="{{ $nutrient['name'] }}"
                        placeholder="e.g., Protein, Carbs, Fat, Fiber" />
                </div>
                <div class="admin-form-row" style="margin-bottom: 0;">
                    <label class="admin-input-label" style="margin-bottom: var(--space-sm);">Value with Unit</label>
                    <input
                        type="text"
                        name="nutrition_facts_value[]"
                        class="admin-input"
                        value="{{ $nutrient['value'] }}"
                        placeholder="e.g., 13g, 20g, 15%" />
                </div>
            </div>
            <button
                type="button"
                class="admin-form-builder-row-delete"
                title="Delete this item"
                aria-label="Delete this item">
                <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M3 6h12M7 9v4M11 9v4M3.75 6L4.5 15c0 .8.7 1.5 1.5 1.5h6c.8 0 1.5-.7 1.5-1.5l.75-9M7 2.5h4"
                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </button>
        </div>
        @endforeach
        @else
        <div class="admin-form-builder-empty">
            <div class="admin-form-builder-empty-icon">
                <svg width="48" height="48" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect width="48" height="48" fill="url(#paint0_linear)" opacity="0.1" rx="24" />
                    <path d="M24 16v16M16 24h16" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    <defs>
                        <linearGradient id="paint0_linear" x1="0" y1="0" x2="48" y2="48" gradientUnits="userSpaceOnUse">
                            <stop stop-color="#0891b2" />
                            <stop offset="1" stop-color="#06b6d4" />
                        </linearGradient>
                    </defs>
                </svg>
            </div>
            <h4 class="admin-form-builder-empty-title">No items yet</h4>
            <p class="admin-form-builder-empty-text">Get started by adding your first nutrient</p>
        </div>
        @endif
    </div>
</div>

<div class="admin-form-section-wrapper" style="margin-top: var(--space-lg);">
    <p class="admin-field-desc" style="font-style: italic;">
        💡 Tip: Common nutrients include Protein, Carbohydrates, Fat, Fiber, Sugars, Sodium. Add values with units (g for grams, mg for milligrams, % for percentage).
    </p>
</div>

<input
    type="hidden"
    id="nutrition_facts_json"
    name="nutrition_facts"
    value="{{ $nutritionData ?? '{}' }}" />

<script>
    (function() {
        const NUTRIENT_ROW_TEMPLATE = `
            <div class="admin-form-builder-row" data-index="0">
                <div class="admin-form-builder-row-handle" aria-label="Drag to reorder" title="Drag to reorder">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="7" cy="5" r="1.5" fill="currentColor" />
                        <circle cx="13" cy="5" r="1.5" fill="currentColor" />
                        <circle cx="7" cy="10" r="1.5" fill="currentColor" />
                        <circle cx="13" cy="10" r="1.5" fill="currentColor" />
                        <circle cx="7" cy="15" r="1.5" fill="currentColor" />
                        <circle cx="13" cy="15" r="1.5" fill="currentColor" />
                    </svg>
                </div>
                <div class="admin-form-builder-row-content">
                    <div class="admin-form-row" style="margin-bottom: 0;">
                        <label class="admin-input-label" style="margin-bottom: var(--space-sm);">Nutrient Name</label>
                        <input type="text" name="nutrition_facts_name[]" class="admin-input" placeholder="e.g., Protein, Carbs, Fat, Fiber" />
                    </div>
                    <div class="admin-form-row" style="margin-bottom: 0;">
                        <label class="admin-input-label" style="margin-bottom: var(--space-sm);">Value with Unit</label>
                        <input type="text" name="nutrition_facts_value[]" class="admin-input" placeholder="e.g., 13g, 20g, 15%" />
                    </div>
                </div>
                <button type="button" class="admin-form-builder-row-delete" title="Delete this item" aria-label="Delete this item">
                    <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M3 6h12M7 9v4M11 9v4M3.75 6L4.5 15c0 .8.7 1.5 1.5 1.5h6c.8 0 1.5-.7 1.5-1.5l.75-9M7 2.5h4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </button>
            </div>
        `;

        const container = document.getElementById('nutrition-facts-rows');
        const addBtn = document.querySelector('[data-field="nutrition_facts"].admin-btn-add-row');
        const jsonInput = document.getElementById('nutrition_facts_json');

        function updateIndices() {
            const rows = container.querySelectorAll('.admin-form-builder-row');
            rows.forEach((row, idx) => row.dataset.index = idx);
        }

        function updateJSON() {
            const names = document.querySelectorAll('input[name="nutrition_facts_name[]"]');
            const values = document.querySelectorAll('input[name="nutrition_facts_value[]"]');
            const data = {};

            names.forEach((nameInput, idx) => {
                const name = nameInput.value.trim();
                const value = values[idx] ? values[idx].value.trim() : '';
                if (name && value) {
                    data[name] = value;
                }
            });

            jsonInput.value = Object.keys(data).length > 0 ? JSON.stringify(data) : '{}';
        }

        function addRow() {
            const row = document.createElement('div');
            row.innerHTML = NUTRIENT_ROW_TEMPLATE;
            container.appendChild(row.firstElementChild);
            updateIndices();
            updateJSON();
        }

        // Add row button
        addBtn?.addEventListener('click', addRow);

        // Delete row
        document.addEventListener('click', (e) => {
            if (e.target.closest('.admin-form-builder-row-delete')) {
                e.target.closest('.admin-form-builder-row').remove();
                updateIndices();
                updateJSON();
            }
        });

        // Update JSON on input change
        document.addEventListener('input', (e) => {
            if (e.target.matches('input[name="nutrition_facts_name[]"], input[name="nutrition_facts_value[]"]')) {
                updateJSON();
            }
        });

        // Initial JSON generation
        updateJSON();
    })();
</script>

<style scoped>
    .admin-form-builder {
        background: var(--wp-white);
        border: 1px solid var(--wp-border);
        border-radius: 12px;
        overflow: hidden;
        margin-bottom: var(--space-lg);
    }

    .admin-form-builder-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: var(--space-lg);
        padding: var(--space-xl) var(--space-lg);
        background: linear-gradient(to right, #f8fafc 0%, #f1f5f9 100%);
        border-bottom: 1px solid var(--wp-border);
    }

    .admin-form-builder-rows {
        display: flex;
        flex-direction: column;
    }

    .admin-form-builder-row {
        display: flex;
        align-items: flex-start;
        gap: var(--space-md);
        padding: var(--space-lg);
        border-bottom: 1px solid var(--wp-border);
    }

    .admin-form-builder-row:last-child {
        border-bottom: none;
    }

    .admin-form-builder-row-content {
        flex: 1;
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: var(--space-lg);
    }

    .admin-form-builder-row-handle {
        cursor: grab;
        color: var(--wp-muted);
        padding: var(--space-sm);
    }

    .admin-form-builder-row-delete {
        background: none;
        border: none;
        color: var(--wp-error);
        cursor: pointer;
        padding: var(--space-sm);
        display: flex;
        align-items: center;
        justify-content: center;
        transition: color var(--transition-fast);
    }

    .admin-form-builder-row-delete:hover {
        color: #c1162b;
    }

    .admin-form-builder-empty {
        text-align: center;
        padding: var(--space-2xl) var(--space-lg);
        color: var(--wp-muted);
    }

    .admin-form-row {
        margin-bottom: 0;
    }

    .admin-input-label {
        display: block;
        font-size: 13px;
        font-weight: 600;
        color: var(--wp-text);
        line-height: 1.4;
        margin-bottom: var(--space-sm);
    }
</style>
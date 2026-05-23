{{-- Nutrition Facts Form Builder
   Replaces raw JSON textarea input with intuitive form builder
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

<x-admin.form-section title="Nutrition Facts" icon="chart-bar">
    <p class="admin-field-desc" style="margin-bottom: var(--space-lg);">
        Add nutritional information per serving. Data will be stored as structured JSON.
    </p>

    <x-admin.form-builder
        :rows="$nutrients"
        fieldName="nutrition_facts"
        label="Add Nutrients"
        addButtonText="Add Nutrient">
        @foreach($nutrients as $index => $nutrient)
        <div class="admin-form-builder-row" data-index="{{ $index }}" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: var(--space-lg); align-items: flex-end; padding: var(--space-lg); background: #fafbfc; border: 1px solid var(--wp-border); border-radius: 6px; margin-bottom: var(--space-lg);">
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

            <button
                type="button"
                class="admin-form-builder-row-remove"
                onclick="removeNutrientRow(this)"
                style="margin-bottom: 0;">
                × Remove
            </button>
        </div>
        @endforeach

        @if(empty($nutrients))
        <div class="admin-form-builder-empty">
            <p>No nutrients added yet. Click "Add Nutrient" to get started.</p>
        </div>
        @endif
    </x-admin.form-builder>

    <input
        type="hidden"
        id="nutrition_facts_json"
        name="nutrition_facts"
        value="{{ $nutritionData ?? '{}' }}" />

    <p class="admin-field-desc" style="margin-top: var(--space-lg); font-style: italic;">
        💡 Tip: Common nutrients include Protein, Carbohydrates, Fat, Fiber, Sugars, Sodium. Add values with units (g for grams, mg for milligrams, % for percentage).
    </p>
</x-admin.form-section>

<script>
    function removeNutrientRow(button) {
        button.closest('.admin-form-builder-row').remove();
        updateNutritionJSON();
    }

    function addNutrientRow() {
        const container = document.querySelector('[data-field="nutrition_facts"]');
        const rows = container.querySelectorAll('.admin-form-builder-row');
        const newIndex = rows.length;

        const newRow = document.createElement('div');
        newRow.className = 'admin-form-builder-row';
        newRow.setAttribute('data-index', newIndex);
        newRow.style.cssText = 'display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: var(--space-lg); align-items: flex-end; padding: var(--space-lg); background: #fafbfc; border: 1px solid var(--wp-border); border-radius: 6px; margin-bottom: var(--space-lg);';

        newRow.innerHTML = `
        <div class="admin-form-row" style="margin-bottom: 0;">
            <label class="admin-input-label" style="margin-bottom: var(--space-sm);">Nutrient Name</label>
            <input 
                type="text" 
                name="nutrition_facts_name[]" 
                class="admin-input"
                placeholder="e.g., Protein, Carbs, Fat, Fiber"
            />
        </div>
        
        <div class="admin-form-row" style="margin-bottom: 0;">
            <label class="admin-input-label" style="margin-bottom: var(--space-sm);">Value with Unit</label>
            <input 
                type="text" 
                name="nutrition_facts_value[]" 
                class="admin-input"
                placeholder="e.g., 13g, 20g, 15%"
            />
        </div>
        
        <button 
            type="button" 
            class="admin-form-builder-row-remove"
            onclick="removeNutrientRow(this)"
            style="margin-bottom: 0;"
        >
            × Remove
        </button>
    `;

        container.appendChild(newRow);
        updateNutritionJSON();
    }

    function updateNutritionJSON() {
        const names = document.querySelectorAll('input[name="nutrition_facts_name[]"]');
        const values = document.querySelectorAll('input[name="nutrition_facts_value[]"]');
        const jsonInput = document.getElementById('nutrition_facts_json');

        const nutritionData = {};
        names.forEach((nameInput, index) => {
            const name = nameInput.value.trim();
            const value = values[index].value.trim();
            if (name && value) {
                nutritionData[name] = value;
            }
        });

        jsonInput.value = Object.keys(nutritionData).length > 0 ? JSON.stringify(nutritionData) : '{}';
    }

    // Update JSON whenever inputs change
    document.addEventListener('change', (e) => {
        if (e.target.name === 'nutrition_facts_name[]' || e.target.name === 'nutrition_facts_value[]') {
            updateNutritionJSON();
        }
    });

    document.addEventListener('input', (e) => {
        if (e.target.name === 'nutrition_facts_name[]' || e.target.name === 'nutrition_facts_value[]') {
            updateNutritionJSON();
        }
    });
</script>

<style scoped>
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
</style>
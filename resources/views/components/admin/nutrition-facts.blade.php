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

<x-admin.form-builder
    :rows="$nutrients"
    fieldName="nutrition_facts"
    label="🍎 Add Nutrients"
    addButtonText="Add Nutrient"
    description="Track nutritional content per serving for your products">
    @foreach($nutrients as $index => $nutrient)
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
    @endforeach

    @if(empty($nutrients))
    <div class="admin-form-row" style="margin-bottom: 0;">
        <label class="admin-input-label" style="margin-bottom: var(--space-sm);">Nutrient Name</label>
        <input
            type="text"
            name="nutrition_facts_name[]"
            class="admin-input"
            placeholder="e.g., Protein, Carbs, Fat, Fiber" />
    </div>

    <div class="admin-form-row" style="margin-bottom: 0;">
        <label class="admin-input-label" style="margin-bottom: var(--space-sm);">Value with Unit</label>
        <input
            type="text"
            name="nutrition_facts_value[]"
            class="admin-input"
            placeholder="e.g., 13g, 20g, 15%" />
    </div>
    @endif
</x-admin.form-builder>

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
    function updateNutritionJSON() {
        const names = document.querySelectorAll('input[name="nutrition_facts_name[]"]');
        const values = document.querySelectorAll('input[name="nutrition_facts_value[]"]');
        const jsonInput = document.getElementById('nutrition_facts_json');

        const nutritionData = {};
        names.forEach((nameInput, index) => {
            const name = nameInput.value.trim();
            const value = values[index] ? values[index].value.trim() : '';
            if (name && value) {
                nutritionData[name] = value;
            }
        });

        jsonInput.value = Object.keys(nutritionData).length > 0 ? JSON.stringify(nutritionData) : '{}';
    }

    // Update JSON when form-builder rows are added
    const addButton = document.querySelector('[data-field="nutrition_facts"]').closest('.admin-form-builder').querySelector('.admin-btn-add-row');
    if (addButton) {
        addButton.addEventListener('click', () => {
            setTimeout(updateNutritionJSON, 100);
        });
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

    // Update JSON when rows are deleted
    document.addEventListener('click', (e) => {
        if (e.target.closest('.admin-form-builder-row-delete')) {
            setTimeout(updateNutritionJSON, 100);
        }
    });

    // Initialize JSON on page load
    updateNutritionJSON();
</script>

<style scoped>
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
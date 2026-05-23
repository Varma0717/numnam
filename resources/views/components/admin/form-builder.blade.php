{{-- Form Builder Component
   Provides dynamic repeatable fields for complex data (JSON arrays)
   Handles add/remove row functionality and field indexing
   
   Usage:
   <x-admin.form-builder 
     :rows="$nutritionFacts" 
     fieldName="nutrition_facts"
     label="Nutrition Facts"
   >
     <x-admin.form-builder-row :index="$index">
       <x-admin.input name="name" label="Nutrient" />
       <x-admin.input name="value" label="Value" />
     </x-admin.form-builder-row>
   </x-admin.form-builder>
--}}

@props([
'rows' => [],
'fieldName' => '',
'label' => '',
'addButtonText' => 'Add Item',
])

<div class="admin-form-builder">
    @if($label)
    <div class="admin-form-builder-header">
        <h4 class="admin-form-builder-title">{{ $label }}</h4>
        <button
            type="button"
            class="admin-btn-small admin-btn-add-row"
            data-field="{{ $fieldName }}">
            + {{ $addButtonText }}
        </button>
    </div>
    @endif

    <div class="admin-form-builder-rows" data-field="{{ $fieldName }}">
        @forelse($rows as $index => $row)
        <div class="admin-form-builder-row" data-index="{{ $index }}">
            {{ $slot }}
            <button
                type="button"
                class="admin-form-builder-row-remove"
                title="Remove this row">
                × Remove
            </button>
        </div>
        @empty
        <div class="admin-form-builder-empty">
            <p>No items yet. Click "{{ $addButtonText }}" to add one.</p>
        </div>
        @endforelse
    </div>
</div>

<style scoped>
    .admin-form-builder {
        background: var(--wp-white);
        border: 1px solid var(--wp-border);
        border-radius: 8px;
        padding: var(--space-lg);
        margin-bottom: var(--space-lg);
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
        background: var(--wp-success);
        color: white;
    }

    .admin-btn-small:hover {
        background: #059669;
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(16, 185, 129, 0.2);
    }

    .admin-btn-add-row {
        background: linear-gradient(135deg, var(--wp-link) 0%, #0d5d61 100%);
    }

    .admin-btn-add-row:hover {
        background: linear-gradient(135deg, #0d5d61 0%, #094d4a 100%);
    }

    .admin-form-builder-rows {
        display: flex;
        flex-direction: column;
        gap: var(--space-lg);
    }

    .admin-form-builder-row {
        display: grid;
        grid-template-columns: 1fr auto;
        gap: var(--space-lg);
        align-items: flex-end;
        padding: var(--space-lg);
        background: #fafbfc;
        border: 1px solid var(--wp-border);
        border-radius: 6px;
        transition: all var(--transition-base);
    }

    .admin-form-builder-row:hover {
        background: #f3f4f6;
        box-shadow: var(--shadow-sm);
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
        white-space: nowrap;
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

    @media (max-width: 782px) {
        .admin-form-builder {
            padding: var(--space-md);
        }

        .admin-form-builder-header {
            flex-direction: column;
            align-items: flex-start;
            gap: var(--space-md);
            margin-bottom: var(--space-md);
            padding-bottom: var(--space-md);
        }

        .admin-form-builder-row {
            grid-template-columns: 1fr;
        }

        .admin-form-builder-row-remove {
            width: 100%;
        }
    }
</style>
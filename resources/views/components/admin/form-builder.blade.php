{{-- Form Builder Component
   Provides dynamic repeatable fields for complex data (JSON arrays)
   Handles add/remove row functionality and field indexing
   Modern, elegant UI with great UX
   
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
'description' => '',
])

<div class="admin-form-builder">
    @if($label)
    <div class="admin-form-builder-header">
        <div class="admin-form-builder-header-content">
            <h3 class="admin-form-builder-title">{{ $label }}</h3>
            @if($description)
            <p class="admin-form-builder-description">{{ $description }}</p>
            @endif
        </div>
        <button
            type="button"
            class="admin-btn-add-row"
            data-field="{{ $fieldName }}"
            aria-label="Add new {{ strtolower($label) }}">
            <span class="icon">+</span>
            <span class="text">{{ $addButtonText }}</span>
        </button>
    </div>
    @endif

    <div class="admin-form-builder-rows" data-field="{{ $fieldName }}">
        @forelse($rows as $index => $row)
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
                {{ $slot }}
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
        @empty
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
            <p class="admin-form-builder-empty-text">Get started by adding your first {{ strtolower($label) }}</p>
        </div>
        @endforelse
    </div>
</div>

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

    .admin-form-builder-header-content {
        flex: 1;
        min-width: 0;
    }

    .admin-form-builder-title {
        margin: 0 0 var(--space-xs) 0;
        font-size: 16px;
        font-weight: 700;
        color: var(--wp-text);
        letter-spacing: -0.3px;
    }

    .admin-form-builder-description {
        margin: 0;
        font-size: 13px;
        color: var(--wp-muted);
        line-height: 1.4;
    }

    .admin-btn-add-row {
        display: flex;
        align-items: center;
        gap: var(--space-sm);
        padding: 10px 16px;
        font-size: 13px;
        font-weight: 600;
        color: white;
        background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%);
        border: none;
        border-radius: 8px;
        cursor: pointer;
        transition: all var(--transition-fast);
        white-space: nowrap;
        box-shadow: 0 2px 4px rgba(6, 182, 212, 0.2);
    }

    .admin-btn-add-row:hover {
        background: linear-gradient(135deg, #0891b2 0%, #0e7490 100%);
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(6, 182, 212, 0.3);
    }

    .admin-btn-add-row:active {
        transform: translateY(0);
        box-shadow: 0 2px 4px rgba(6, 182, 212, 0.2);
    }

    .admin-btn-add-row:focus {
        outline: 2px solid #06b6d4;
        outline-offset: 2px;
    }

    .admin-btn-add-row .icon {
        font-size: 18px;
        font-weight: 300;
    }

    .admin-form-builder-rows {
        display: flex;
        flex-direction: column;
        gap: var(--space-md);
        padding: var(--space-lg);
    }

    .admin-form-builder-row {
        display: grid;
        grid-template-columns: 32px 1fr 32px;
        gap: var(--space-lg);
        align-items: flex-start;
        padding: var(--space-lg);
        background: #ffffff;
        border: 1px solid var(--wp-border);
        border-radius: 10px;
        transition: all var(--transition-base);
        animation: slideIn 0.3s ease-out;
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(-8px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .admin-form-builder-row:hover {
        border-color: #cbd5e1;
        background: #f8fafc;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
    }

    .admin-form-builder-row-handle {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        color: #cbd5e1;
        cursor: grab;
        transition: all var(--transition-fast);
        flex-shrink: 0;
    }

    .admin-form-builder-row-handle:hover {
        color: var(--wp-text);
    }

    .admin-form-builder-row-handle:active {
        cursor: grabbing;
    }

    .admin-form-builder-row-content {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: var(--space-lg);
        width: 100%;
        align-items: flex-start;
    }

    .admin-form-builder-row-delete {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        color: var(--wp-error);
        background: transparent;
        border: 1px solid transparent;
        border-radius: 6px;
        cursor: pointer;
        transition: all var(--transition-fast);
        flex-shrink: 0;
    }

    .admin-form-builder-row-delete:hover {
        background: #fef2f2;
        border-color: #fecaca;
        color: #991b1b;
    }

    .admin-form-builder-row-delete:focus {
        outline: 2px solid var(--wp-error);
        outline-offset: 2px;
    }

    .admin-form-builder-empty {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: var(--space-3xl) var(--space-lg);
        text-align: center;
    }

    .admin-form-builder-empty-icon {
        margin-bottom: var(--space-lg);
        color: #0891b2;
    }

    .admin-form-builder-empty-title {
        margin: 0 0 var(--space-xs) 0;
        font-size: 15px;
        font-weight: 700;
        color: var(--wp-text);
    }

    .admin-form-builder-empty-text {
        margin: 0;
        font-size: 13px;
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

        .admin-form-builder-row-delete {
            width: 100%;
        }
    }
</style>
</style>
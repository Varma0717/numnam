{{-- Form Section Component (Postbox wrapper)
   Groups related form fields under a collapsible section with header
   
   Usage:
   <x-admin.form-section title="Personal Information" :collapsible="true">
     <!-- form fields here -->
   </x-admin.form-section>
--}}

@props([
'title' => '',
'collapsible' => false,
'icon' => null,
])

<section class="postbox" data-collapsible="{{ $collapsible ? 'true' : 'false' }}">
    @if($title)
    <div class="postbox-header">
        @if($icon)
        <span class="postbox-icon">
            <i class="icon-{{ $icon }}"></i>
        </span>
        @endif
        <h3 class="postbox-title">{{ $title }}</h3>
        @if($collapsible)
        <button type="button" class="postbox-toggle" aria-expanded="true">
            <span class="toggle-indicator">−</span>
        </button>
        @endif
    </div>
    @endif
    <div class="inside">
        {{ $slot }}
    </div>
</section>

<style scoped>
    .postbox {
        background: var(--wp-white);
        border: 1px solid var(--wp-border);
        border-radius: 8px;
        margin-bottom: var(--space-lg);
        box-shadow: var(--shadow-sm);
        transition: all var(--transition-base);
    }

    .postbox:hover {
        box-shadow: var(--shadow-md);
    }

    .postbox-header {
        display: flex;
        align-items: center;
        gap: var(--space-md);
        padding: var(--space-lg);
        background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%);
        border-bottom: 1px solid var(--wp-border);
        border-radius: 8px 8px 0 0;
        cursor: pointer;
    }

    .postbox[data-collapsible="true"] .postbox-header {
        user-select: none;
    }

    .postbox-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 24px;
        height: 24px;
        color: var(--wp-link);
        font-size: 16px;
    }

    .postbox-title {
        margin: 0;
        flex: 1;
        font-size: 15px;
        font-weight: 700;
        color: var(--wp-text);
        line-height: 1.4;
    }

    .postbox-toggle {
        background: none;
        border: none;
        color: var(--wp-link);
        font-size: 20px;
        padding: 0;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 24px;
        height: 24px;
        transition: all var(--transition-fast);
    }

    .postbox-toggle:hover {
        color: var(--wp-link-hover);
        transform: scale(1.1);
    }

    .postbox-toggle[aria-expanded="false"] .toggle-indicator::after {
        content: '+';
    }

    .toggle-indicator::before {
        content: '−';
    }

    .inside {
        padding: var(--space-lg);
    }

    @media (max-width: 782px) {
        .postbox-header {
            gap: var(--space-md);
            padding: var(--space-md);
        }

        .postbox-title {
            font-size: 14px;
        }

        .inside {
            padding: var(--space-md);
        }
    }
</style>
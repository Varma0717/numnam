{{-- Error Message Component
   Displays validation error messages in a professional format
   
   Usage:
   <x-admin.error-message message="Field is required" />
--}}

@props([
'message' => '',
])

@if($message)
<div class="admin-error-message">
    <span class="admin-error-icon">⚠</span>
    <span class="admin-error-text">{{ $message }}</span>
</div>
@endif

<style scoped>
    .admin-error-message {
        display: flex;
        align-items: flex-start;
        gap: var(--space-sm);
        margin-top: var(--space-sm);
        padding: 8px 10px;
        background: rgba(239, 68, 68, 0.05);
        border-left: 3px solid var(--wp-error);
        border-radius: 4px;
        font-size: 13px;
        color: var(--wp-error);
        animation: slideInDown var(--transition-fast) ease-out;
    }

    .admin-error-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        width: 18px;
        height: 18px;
        font-size: 14px;
        font-weight: 700;
    }

    .admin-error-text {
        flex: 1;
        line-height: 1.4;
    }

    @keyframes slideInDown {
        from {
            opacity: 0;
            transform: translateY(-4px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>
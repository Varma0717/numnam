{{-- Enhanced Input Component
   Provides consistent, professional text input field with optional features
   
   Usage:
   <x-admin.input 
     name="field_name" 
     label="Field Label"
     value="current_value"
     type="text|email|number|password"
     placeholder="Placeholder text"
     icon-left="icon-name"
     icon-right="icon-name"
     required="true"
     maxlength="100"
     errors="$errors"
   />
--}}

@props([
'name',
'label' => null,
'value' => '',
'type' => 'text',
'placeholder' => '',
'iconLeft' => null,
'iconRight' => null,
'required' => false,
'maxlength' => null,
'step' => null,
'min' => null,
'max' => null,
'errors' => null,
'hint' => null,
'disabled' => false,
'readonly' => false,
])

<div class="admin-form-row @error($name) has-error @endError">
    @if($label)
    <label for="{{ $name }}" class="@if($required) required @endif">
        {{ $label }}
    </label>
    @endif

    <div class="admin-input-wrapper">
        @if($iconLeft)
        <span class="admin-input-icon admin-input-icon-left">
            <i class="icon-{{ $iconLeft }}"></i>
        </span>
        @endif

        <input
            type="{{ $type }}"
            id="{{ $name }}"
            name="{{ $name }}"
            value="{{ old($name, $value) }}"
            placeholder="{{ $placeholder }}"
            class="admin-input @error($name) admin-input-error @enderror"
            @if($required) required @endif
            @if($maxlength) maxlength="{{ $maxlength }}" @endif
            @if($step !==null) step="{{ $step }}" @endif
            @if($min !==null) min="{{ $min }}" @endif
            @if($max !==null) max="{{ $max }}" @endif
            @if($disabled) disabled @endif
            @if($readonly) readonly @endif />

        @if($iconRight)
        <span class="admin-input-icon admin-input-icon-right">
            <i class="icon-{{ $iconRight }}"></i>
        </span>
        @endif
    </div>

    @if($maxlength)
    <div class="admin-input-counter">
        <span class="admin-counter-current">0</span>
        <span class="admin-counter-max">/{{ $maxlength }}</span>
    </div>
    @endif

    @error($name)
    <div class="form-error">
        <i class="icon-alert-circle"></i>
        <span>{{ $message }}</span>
    </div>
    @enderror

    @if($hint)
    <p class="form-helper">{{ $hint }}</p>
    @endif
</div>

<style scoped>
    .admin-input-label {
        display: block;
        font-size: 14px;
        font-weight: 600;
        color: var(--wp-text);
        margin-bottom: var(--space-sm);
        line-height: 1.4;
    }

    .admin-required-indicator {
        color: var(--wp-error);
        margin-left: 2px;
    }

    .admin-input-wrapper {
        position: relative;
        display: flex;
        align-items: center;
    }

    .admin-input {
        width: 100%;
        padding: 10px 12px;
        border: 2px solid var(--wp-border);
        border-radius: 8px;
        font-size: 14px;
        color: var(--wp-text);
        background: var(--wp-white);
        transition: all var(--transition-base);
        font-family: inherit;
    }

    .admin-input:hover {
        border-color: var(--wp-link);
    }

    .admin-input:focus {
        border-color: var(--wp-link);
        box-shadow: 0 0 0 3px rgba(15, 118, 110, 0.1);
        outline: none;
        background: linear-gradient(90deg, rgba(15, 118, 110, 0.03) 0%, transparent 100%);
    }

    .admin-input:disabled {
        background: #f3f4f6;
        color: var(--wp-muted);
        cursor: not-allowed;
    }

    .admin-input-error {
        border-color: var(--wp-error);
        background: rgba(239, 68, 68, 0.02);
    }

    .admin-input-error:focus {
        box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
    }

    .admin-input-icon {
        position: absolute;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
        color: var(--wp-muted);
        font-size: 16px;
        pointer-events: none;
    }

    .admin-input-icon-left {
        left: 0;
        padding-left: var(--space-sm);
    }

    .admin-input-icon-right {
        right: 0;
        padding-right: var(--space-sm);
    }

    .admin-input-counter {
        font-size: 12px;
        color: var(--wp-muted);
        margin-top: 4px;
        text-align: right;
    }

    .admin-counter-current {
        font-weight: 600;
        color: var(--wp-text);
    }
</style>
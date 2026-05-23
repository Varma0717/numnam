{{-- Enhanced Textarea Component
   Provides consistent, professional textarea field with word counter
   
   Usage:
   <x-admin.textarea 
     name="description" 
     label="Description"
     value="current_value"
     placeholder="Enter description"
     rows="4"
     maxlength="500"
     hint="Max 500 characters"
   />
--}}

@props([
'name',
'label' => null,
'value' => '',
'placeholder' => '',
'rows' => '4',
'required' => false,
'maxlength' => null,
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

    <textarea
        id="{{ $name }}"
        name="{{ $name }}"
        placeholder="{{ $placeholder }}"
        rows="{{ $rows }}"
        class="admin-textarea @error($name) admin-input-error @enderror"
        @if($required) required @endif
        @if($maxlength) maxlength="{{ $maxlength }}" @endif
        @if($disabled) disabled @endif
        @if($readonly) readonly @endif>{!! old($name, $value) !!}</textarea>

    @if($maxlength)
    <div class="admin-textarea-counter">
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

    .admin-textarea {
        width: 100%;
        padding: 10px 12px;
        border: 2px solid var(--wp-border);
        border-radius: 8px;
        font-size: 14px;
        color: var(--wp-text);
        background: var(--wp-white);
        font-family: inherit;
        resize: vertical;
        transition: all var(--transition-base);
        line-height: 1.5;
    }

    .admin-textarea:hover {
        border-color: var(--wp-link);
    }

    .admin-textarea:focus {
        border-color: var(--wp-link);
        box-shadow: 0 0 0 3px rgba(15, 118, 110, 0.1);
        outline: none;
        background: linear-gradient(90deg, rgba(15, 118, 110, 0.03) 0%, transparent 100%);
    }

    .admin-textarea:disabled {
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

    .admin-textarea-counter {
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
{{-- Enhanced Select Component
   Provides consistent, professional dropdown field
   
   Usage:
   <x-admin.select 
     name="category_id" 
     label="Category"
     :options="$categories"
     value="current_value"
     required="true"
     errors="$errors"
   />
   
   For array options:
   :options="['value1' => 'Label 1', 'value2' => 'Label 2']"
--}}

@props([
'name',
'label' => null,
'options' => [],
'value' => '',
'required' => false,
'errors' => null,
'hint' => null,
'disabled' => false,
'placeholder' => '— Select —',
])

<div class="admin-form-row @error($name) has-error @endError">
    @if($label)
    <label for="{{ $name }}" class="@if($required) required @endif">
        {{ $label }}
    </label>
    @endif

    <div class="admin-select-wrapper">
        <select
            id="{{ $name }}"
            name="{{ $name }}"
            class="admin-select @error($name) admin-input-error @enderror"
            @if($required) required @endif
            @if($disabled) disabled @endif>
            @if($placeholder)
            <option value="">{{ $placeholder }}</option>
            @endif

            @forelse($options as $optValue => $optLabel)
            @if(is_array($optLabel))
            {{-- Option group --}}
            <optgroup label="{{ $optValue }}">
                @foreach($optLabel as $gVal => $gLabel)
                <option
                    value="{{ $gVal }}"
                    @selected(old($name, $value)==$gVal)>
                    {{ $gLabel }}
                </option>
                @endforeach
            </optgroup>
            @else
            {{-- Regular option --}}
            <option
                value="{{ $optValue }}"
                @selected(old($name, $value)==$optValue)>
                {{ $optLabel }}
            </option>
            @endif
            @empty
            <option value="" disabled>No options available</option>
            @endforelse
        </select>
        <span class="admin-select-arrow">
            <svg width="12" height="8" viewBox="0 0 12 8" fill="none">
                <path d="M1 1L6 6L11 1" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
            </svg>
        </span>
    </div>

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

@error($name)
<x-admin.error-message :message="$message" />
@enderror

@if($hint)
<p class="admin-field-desc">{{ $hint }}</p>
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

    .admin-select-wrapper {
        position: relative;
    }

    .admin-select {
        width: 100%;
        padding: 10px 12px;
        padding-right: 32px;
        border: 2px solid var(--wp-border);
        border-radius: 8px;
        font-size: 14px;
        color: var(--wp-text);
        background: var(--wp-white);
        appearance: none;
        cursor: pointer;
        transition: all var(--transition-base);
        font-family: inherit;
    }

    .admin-select:hover {
        border-color: var(--wp-link);
    }

    .admin-select:focus {
        border-color: var(--wp-link);
        box-shadow: 0 0 0 3px rgba(15, 118, 110, 0.1);
        outline: none;
        background: linear-gradient(90deg, rgba(15, 118, 110, 0.03) 0%, transparent 100%);
    }

    .admin-select:disabled {
        background: #f3f4f6;
        color: var(--wp-muted);
        cursor: not-allowed;
    }

    .admin-select-error {
        border-color: var(--wp-error);
        background: rgba(239, 68, 68, 0.02);
    }

    .admin-select-error:focus {
        box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
    }

    .admin-select-arrow {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        display: flex;
        align-items: center;
        justify-content: center;
        width: 20px;
        height: 20px;
        color: var(--wp-muted);
        pointer-events: none;
    }

    .admin-select:focus~.admin-select-arrow {
        color: var(--wp-link);
    }
</style>
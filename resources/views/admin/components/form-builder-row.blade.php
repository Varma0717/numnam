{{-- Form Builder Row Component
   Individual row within a form builder
   Provides field naming and templating
   
   Usage (inside form-builder):
   <x-admin.form-builder-row :index="$index">
     <x-admin.input 
       name="item[{{ $index }}][name]"
label="Item Name"
/>
<x-admin.input
    name="item[{{ $index }}][value]"
    label="Item Value" />
</x-admin.form-builder-row>
--}}

@props([
'index' => 0,
])

<div class="admin-form-builder-row-content" data-index="{{ $index }}">
    {{ $slot }}
</div>

<style scoped>
    .admin-form-builder-row-content {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: var(--space-md);
        width: 100%;
    }

    @media (max-width: 782px) {
        .admin-form-builder-row-content {
            grid-template-columns: 1fr;
        }
    }
</style>
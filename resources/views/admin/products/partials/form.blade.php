@php($isEdit = isset($product))

<div class="admin-grid" style="grid-template-columns:repeat(2,minmax(0,1fr));">
    <select name="category_id">
        <option value="">Select category</option>
        @foreach($categories as $category)
            <option value="{{ $category->id }}" @selected(old('category_id', $product->category_id ?? null) == $category->id)>{{ $category->name }}</option>
        @endforeach
    </select>

    <input name="name" value="{{ old('name', $product->name ?? '') }}" placeholder="Product name" required>

    <input name="slug" value="{{ old('slug', $product->slug ?? '') }}" placeholder="Slug (optional)">
    <input name="age_group" value="{{ old('age_group', $product->age_group ?? '6M+') }}" placeholder="Age group (e.g. 6M+)" required>

    <select name="type" required>
        @foreach(['puree' => 'Puree', 'puffs' => 'Puffs', 'cookies' => 'Cookies'] as $key => $label)
            <option value="{{ $key }}" @selected(old('type', $product->type ?? 'puree') === $key)>{{ $label }}</option>
        @endforeach
    </select>

    <input name="stock" type="number" min="0" value="{{ old('stock', $product->stock ?? 0) }}" placeholder="Stock" required>

    <input name="price" type="number" step="0.01" min="0" value="{{ old('price', $product->price ?? '') }}" placeholder="Price" required>
    <input name="sale_price" type="number" step="0.01" min="0" value="{{ old('sale_price', $product->sale_price ?? '') }}" placeholder="Sale price">
</div>

<input name="image" value="{{ old('image', $product->image ?? '') }}" placeholder="Primary image URL">

<textarea name="short_description" placeholder="Short description">{{ old('short_description', $product->short_description ?? '') }}</textarea>
<textarea name="description" placeholder="Long description">{{ old('description', $product->description ?? '') }}</textarea>
<textarea name="ingredients" placeholder="Ingredients">{{ old('ingredients', $product->ingredients ?? '') }}</textarea>

<textarea name="gallery" placeholder="Gallery URLs (one per line)">{{ old('gallery', isset($product) ? implode(PHP_EOL, $product->gallery ?? []) : '') }}</textarea>
<textarea name="badges" placeholder="Badges (comma separated)">{{ old('badges', isset($product) ? implode(', ', $product->badges ?? []) : '') }}</textarea>
<textarea name="nutrition_facts" placeholder='Nutrition facts JSON, e.g. {"protein":"13g"}'>{{ old('nutrition_facts', isset($product) ? json_encode($product->nutrition_facts ?? [], JSON_PRETTY_PRINT) : '') }}</textarea>

<div style="display:flex; gap:.8rem; margin:.5rem 0;">
    <label style="display:flex; align-items:center; gap:.35rem;"><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $product->is_active ?? true))> Active</label>
    <label style="display:flex; align-items:center; gap:.35rem;"><input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $product->is_featured ?? false))> Featured</label>
</div>

<button class="admin-btn" type="submit">{{ $isEdit ? 'Update Product' : 'Create Product' }}</button>

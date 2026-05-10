<form method="POST" action="{{ $action }}" class="admin-grid" style="grid-template-columns:repeat(2,minmax(0,1fr)); gap:.7rem;">
    @csrf
    @if($method !== 'POST')
        @method($method)
    @endif

    <input name="code" value="{{ old('code', $coupon?->code) }}" placeholder="Code" required>

    <select name="type" required>
        <option value="fixed" @selected(old('type', $coupon?->type) === 'fixed')>Fixed</option>
        <option value="percent" @selected(old('type', $coupon?->type) === 'percent')>Percent</option>
    </select>

    <input name="value" type="number" min="0" step="0.01" value="{{ old('value', $coupon?->value) }}" placeholder="Value" required>
    <input name="min_subtotal" type="number" min="0" step="0.01" value="{{ old('min_subtotal', $coupon?->min_subtotal) }}" placeholder="Minimum subtotal">

    <input name="starts_at" type="datetime-local" value="{{ old('starts_at', optional($coupon?->starts_at)->format('Y-m-d\TH:i')) }}">
    <input name="ends_at" type="datetime-local" value="{{ old('ends_at', optional($coupon?->ends_at)->format('Y-m-d\TH:i')) }}">

    <input name="usage_limit" type="number" min="1" value="{{ old('usage_limit', $coupon?->usage_limit) }}" placeholder="Usage limit">

    <label><input type="hidden" name="is_active" value="0"><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $coupon?->is_active ?? true))> Active</label>

    <button class="admin-btn" type="submit">Save coupon</button>
</form>

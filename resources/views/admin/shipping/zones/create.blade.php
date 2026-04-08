@extends('admin.layouts.app')

@section('title', 'Add Shipping Zone')

@section('content')
<div class="admin-page-header">
    <h2>Add Shipping Zone</h2>
    <p class="admin-desc">Create a new shipping zone with regions and delivery methods.</p>
</div>

<form method="POST" action="{{ route('admin.shipping.zones.store') }}">
    @csrf

    {{-- Zone Details --}}
    <section class="postbox">
        <div class="postbox-header">
            <h3>Zone Details</h3>
        </div>
        <div class="inside">
            <div class="admin-form-row">
                <label for="zone_name">Zone Name</label>
                <input type="text" id="zone_name" name="name" value="{{ old('name') }}" placeholder="e.g. Metro Cities, South India" required>
            </div>
            <div class="admin-form-row">
                <label class="admin-toggle-label">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" value="1" @checked(old('is_active', true))>
                    <span>Active</span>
                </label>
            </div>
        </div>
    </section>

    {{-- Regions --}}
    <section class="postbox" style="margin-top:16px;">
        <div class="postbox-header">
            <h3>Regions</h3>
        </div>
        <div class="inside" id="regions-container">
            <p class="admin-field-desc">Define which pincodes, states, or countries this zone covers.</p>
            <div class="region-row" style="display:grid; grid-template-columns:140px 1fr 40px; gap:8px; margin-bottom:8px; align-items:end;">
                <div>
                    <label style="font-size:12px; font-weight:600;">Type</label>
                    <select name="regions[0][type]">
                        <option value="pincode">Pincode</option>
                        <option value="state">State</option>
                        <option value="country">Country</option>
                    </select>
                </div>
                <div>
                    <label style="font-size:12px; font-weight:600;">Value</label>
                    <input type="text" name="regions[0][value]" placeholder="e.g. 500001, Telangana, IN">
                </div>
                <div></div>
            </div>
            <button type="button" class="admin-btn-secondary" onclick="addRegionRow()">+ Add Region</button>
        </div>
    </section>

    {{-- Methods --}}
    <section class="postbox" style="margin-top:16px;">
        <div class="postbox-header">
            <h3>Shipping Methods</h3>
        </div>
        <div class="inside" id="methods-container">
            <div class="method-row" style="border:1px solid var(--wp-border); padding:12px; border-radius:4px; margin-bottom:12px;">
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:8px;">
                    <div class="admin-form-row">
                        <label>Method Name</label>
                        <input type="text" name="methods[0][name]" placeholder="e.g. Standard Delivery" value="">
                    </div>
                    <div class="admin-form-row">
                        <label>Type</label>
                        <select name="methods[0][type]" onchange="toggleMethodFields(this)">
                            <option value="flat_rate">Flat Rate</option>
                            <option value="free_shipping">Free Shipping</option>
                            <option value="weight_based">Weight Based</option>
                        </select>
                    </div>
                </div>
                <div style="display:grid; grid-template-columns:repeat(4, 1fr); gap:8px; margin-top:8px;">
                    <div class="admin-form-row method-cost-field">
                        <label>Cost (₹)</label>
                        <input type="number" step="0.01" name="methods[0][cost]" value="0">
                    </div>
                    <div class="admin-form-row method-free-above-field">
                        <label>Free Above (₹)</label>
                        <input type="number" step="0.01" name="methods[0][free_above]" value="">
                    </div>
                    <div class="admin-form-row method-weight-field" style="display:none;">
                        <label>Min Weight (kg)</label>
                        <input type="number" step="0.01" name="methods[0][min_weight]" value="">
                    </div>
                    <div class="admin-form-row method-weight-field" style="display:none;">
                        <label>Max Weight (kg)</label>
                        <input type="number" step="0.01" name="methods[0][max_weight]" value="">
                    </div>
                    <div class="admin-form-row method-weight-field" style="display:none;">
                        <label>Cost/kg (₹)</label>
                        <input type="number" step="0.01" name="methods[0][cost_per_kg]" value="">
                    </div>
                </div>
                <label class="admin-toggle-label" style="margin-top:8px;">
                    <input type="hidden" name="methods[0][is_active]" value="0">
                    <input type="checkbox" name="methods[0][is_active]" value="1" checked>
                    <span>Active</span>
                </label>
            </div>
            <button type="button" class="admin-btn-secondary" onclick="addMethodRow()">+ Add Method</button>
        </div>
    </section>

    <div style="margin-top:16px; display:flex; gap:8px;">
        <button class="admin-btn" type="submit">Create Zone</button>
        <a href="{{ route('admin.settings.index', ['tab' => 'shipping']) }}" class="admin-btn-secondary" style="text-decoration:none;">Cancel</a>
    </div>
</form>

<script>
    let regionIndex = 1;
    let methodIndex = 1;

    function addRegionRow() {
        const html = `<div class="region-row" style="display:grid; grid-template-columns:140px 1fr 40px; gap:8px; margin-bottom:8px; align-items:end;">
        <div><select name="regions[${regionIndex}][type]"><option value="pincode">Pincode</option><option value="state">State</option><option value="country">Country</option></select></div>
        <div><input type="text" name="regions[${regionIndex}][value]" placeholder="e.g. 500001, Telangana, IN"></div>
        <div><button type="button" class="admin-btn-danger" onclick="this.closest('.region-row').remove()">&times;</button></div>
    </div>`;
        document.getElementById('regions-container').insertAdjacentHTML('beforeend', html);
        regionIndex++;
    }

    function addMethodRow() {
        const html = `<div class="method-row" style="border:1px solid var(--wp-border); padding:12px; border-radius:4px; margin-bottom:12px;">
        <div style="display:flex; justify-content:flex-end;"><button type="button" class="admin-btn-danger" onclick="this.closest('.method-row').remove()">&times; Remove</button></div>
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:8px;">
            <div class="admin-form-row"><label>Method Name</label><input type="text" name="methods[${methodIndex}][name]" placeholder="e.g. Express Delivery"></div>
            <div class="admin-form-row"><label>Type</label><select name="methods[${methodIndex}][type]" onchange="toggleMethodFields(this)"><option value="flat_rate">Flat Rate</option><option value="free_shipping">Free Shipping</option><option value="weight_based">Weight Based</option></select></div>
        </div>
        <div style="display:grid; grid-template-columns:repeat(4, 1fr); gap:8px; margin-top:8px;">
            <div class="admin-form-row method-cost-field"><label>Cost (₹)</label><input type="number" step="0.01" name="methods[${methodIndex}][cost]" value="0"></div>
            <div class="admin-form-row method-free-above-field"><label>Free Above (₹)</label><input type="number" step="0.01" name="methods[${methodIndex}][free_above]" value=""></div>
            <div class="admin-form-row method-weight-field" style="display:none;"><label>Min Weight (kg)</label><input type="number" step="0.01" name="methods[${methodIndex}][min_weight]" value=""></div>
            <div class="admin-form-row method-weight-field" style="display:none;"><label>Max Weight (kg)</label><input type="number" step="0.01" name="methods[${methodIndex}][max_weight]" value=""></div>
            <div class="admin-form-row method-weight-field" style="display:none;"><label>Cost/kg (₹)</label><input type="number" step="0.01" name="methods[${methodIndex}][cost_per_kg]" value=""></div>
        </div>
        <label class="admin-toggle-label" style="margin-top:8px;"><input type="hidden" name="methods[${methodIndex}][is_active]" value="0"><input type="checkbox" name="methods[${methodIndex}][is_active]" value="1" checked><span>Active</span></label>
    </div>`;
        document.getElementById('methods-container').insertAdjacentHTML('beforeend', html);
        methodIndex++;
    }

    function toggleMethodFields(select) {
        const row = select.closest('.method-row');
        const type = select.value;
        row.querySelectorAll('.method-weight-field').forEach(el => el.style.display = type === 'weight_based' ? '' : 'none');
        row.querySelectorAll('.method-cost-field').forEach(el => el.style.display = type === 'free_shipping' ? 'none' : '');
        row.querySelectorAll('.method-free-above-field').forEach(el => el.style.display = type === 'free_shipping' ? '' : 'none');
    }
</script>
@endsection
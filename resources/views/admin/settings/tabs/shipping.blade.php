{{-- Settings > Shipping Tab --}}
@php
try {
$zones = \App\Models\ShippingZone::with(['regions', 'methods'])->orderBy('sort_order')->get();
} catch (\Throwable $e) {
$zones = collect();
}
$hasShippingRoutes = Route::has('admin.shipping.zones.create');
@endphp

<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;">
    <div>
        <h3 style="margin:0; font-size:14px; font-weight:600;">Shipping Zones</h3>
        <p class="admin-desc" style="margin:4px 0 0;">Define shipping zones with regions and delivery methods.</p>
    </div>
    @if($hasShippingRoutes)
    <a href="{{ route('admin.shipping.zones.create') }}" class="admin-btn" style="text-decoration:none;">Add Shipping Zone</a>
    @endif
</div>

@if($zones->isEmpty())
<section class="admin-panel">
    <div class="admin-empty">
        <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
            <rect x="1" y="3" width="15" height="13" />
            <polygon points="16 8 20 8 23 11 23 16 16 16 16 8" />
            <circle cx="5.5" cy="18.5" r="2.5" />
            <circle cx="18.5" cy="18.5" r="2.5" />
        </svg>
        <p>No shipping zones configured yet.</p>
        @if($hasShippingRoutes)
        <a href="{{ route('admin.shipping.zones.create') }}" class="admin-btn" style="text-decoration:none;">Create First Zone</a>
        @endif
    </div>
</section>
@else
@foreach($zones as $zone)
<section class="postbox" style="margin-bottom:16px;">
    <div class="postbox-header" style="display:flex; justify-content:space-between; align-items:center;">
        <h3 style="margin:0;">
            {{ $zone->name }}
            @if(!$zone->is_active)
            <span class="status-badge status-badge--paused" style="margin-left:8px;">Disabled</span>
            @endif
        </h3>
        <div style="display:flex; gap:8px; padding-right:12px;">
            @if($hasShippingRoutes)
            <a href="{{ route('admin.shipping.zones.edit', $zone) }}" class="admin-link">Edit</a>
            <form method="POST" action="{{ route('admin.shipping.zones.destroy', $zone) }}" style="display:inline;" onsubmit="return confirm('Delete this shipping zone?')">
                @csrf @method('DELETE')
                <button type="submit" class="admin-btn-danger">Delete</button>
            </form>
            @endif
        </div>
    </div>
    <div class="inside">
        {{-- Regions --}}
        <div style="margin-bottom:12px;">
            <strong style="font-size:12px; text-transform:uppercase; color:var(--wp-muted); letter-spacing:0.5px;">Regions</strong>
            @if($zone->regions->isEmpty())
            <p class="admin-muted" style="margin:4px 0 0;">No regions — this zone won't match any orders.</p>
            @else
            <div style="margin-top:4px; display:flex; flex-wrap:wrap; gap:4px;">
                @foreach($zone->regions as $region)
                <span style="display:inline-block; padding:2px 8px; background:#f0f0f1; border:1px solid var(--wp-border); border-radius:3px; font-size:12px;">
                    {{ ucfirst($region->type) }}: {{ $region->value }}
                </span>
                @endforeach
            </div>
            @endif
        </div>

        {{-- Methods --}}
        <strong style="font-size:12px; text-transform:uppercase; color:var(--wp-muted); letter-spacing:0.5px;">Shipping Methods</strong>
        @if($zone->methods->isEmpty())
        <p class="admin-muted" style="margin:4px 0 0;">No methods configured.</p>
        @else
        <table class="admin-table" style="margin-top:6px;">
            <thead>
                <tr>
                    <th>Method</th>
                    <th>Type</th>
                    <th>Cost</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($zone->methods as $method)
                <tr>
                    <td><strong>{{ $method->name }}</strong></td>
                    <td>{{ ucwords(str_replace('_', ' ', $method->type)) }}</td>
                    <td>
                        @if($method->type === 'free_shipping')
                        Free {{ $method->free_above ? '(above ₹' . number_format($method->free_above, 0) . ')' : '' }}
                        @elseif($method->type === 'weight_based')
                        ₹{{ number_format($method->cost, 0) }} + ₹{{ number_format($method->cost_per_kg ?? 0, 0) }}/kg
                        @else
                        ₹{{ number_format($method->cost, 0) }}
                        @endif
                    </td>
                    <td>
                        <span class="status-badge status-badge--{{ $method->is_active ? 'active' : 'paused' }}">
                            {{ $method->is_active ? 'Active' : 'Disabled' }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>
</section>
@endforeach
@endif
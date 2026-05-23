{{-- Settings > Shipping Tab --}}
@php
try {
$zones = \App\Models\ShippingZone::with(['regions', 'methods'])->orderBy('sort_order')->get();
} catch (\Throwable $e) {
$zones = collect();
}
$hasShippingRoutes = Route::has('admin.shipping.zones.create');
@endphp

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--space-2xl);">
    <div>
        <h3 style="margin: 0; font-size: 20px; font-weight: 600; color: var(--wp-text);">🚚 Shipping Zones</h3>
        <p style="margin: 8px 0 0; color: var(--wp-muted); font-size: 13px;">Define shipping zones with regions and delivery methods</p>
    </div>
    @if($hasShippingRoutes)
    <a href="{{ route('admin.shipping.zones.create') }}" class="admin-btn" style="text-decoration: none;">➕ Add Shipping Zone</a>
    @endif
</div>

@if($zones->isEmpty())
<section class="postbox">
    <div style="padding: var(--space-2xl); text-align: center;">
        <div style="font-size: 48px; margin-bottom: var(--space-lg);">📦</div>
        <h4 style="margin: 0 0 var(--space-md); color: var(--wp-text); font-weight: 600;">No Shipping Zones Yet</h4>
        <p style="color: var(--wp-muted); margin: 0 0 var(--space-lg);">Create your first shipping zone to enable delivery options</p>
        @if($hasShippingRoutes)
        <a href="{{ route('admin.shipping.zones.create') }}" class="admin-btn" style="text-decoration: none;">Create First Zone</a>
        @endif
    </div>
</section>
@else
@foreach($zones as $zone)
<section class="postbox" style="margin-bottom: var(--space-2xl);">
    <div class="postbox-header" style="display: flex; justify-content: space-between; align-items: center;">
        <h3 style="margin: 0; display: flex; align-items: center; gap: var(--space-md);">
            📍 {{ $zone->name }}
            @if(!$zone->is_active)
            <span style="font-size: 11px; font-weight: 600; color: #999; background: #f0f0f1; padding: 4px 8px; border-radius: 4px; text-transform: uppercase; margin-left: 8px;">Disabled</span>
            @endif
        </h3>
        <div style="display: flex; gap: var(--space-md);">
            @if($hasShippingRoutes)
            <a href="{{ route('admin.shipping.zones.edit', $zone) }}" class="admin-btn-secondary" style="text-decoration: none; font-size: 13px;">Edit</a>
            <form method="POST" action="{{ route('admin.shipping.zones.destroy', $zone) }}" style="display: inline;" onsubmit="return confirm('Delete this shipping zone?')">
                @csrf @method('DELETE')
                <button type="submit" class="admin-btn-danger" style="font-size: 13px;">Delete</button>
            </form>
            @endif
        </div>
    </div>
    <div class="inside">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: var(--space-2xl);">
            {{-- Regions --}}
            <div>
                <h4 style="font-size: 13px; font-weight: 600; text-transform: uppercase; color: var(--wp-muted); letter-spacing: 0.5px; margin: 0 0 var(--space-lg);">🗺️ Coverage Areas</h4>
                @if($zone->regions->isEmpty())
                <p style="color: var(--wp-muted); font-size: 13px; margin: 0;">No regions configured — this zone won't match any orders.</p>
                @else
                <div style="display: flex; flex-wrap: wrap; gap: var(--space-sm);">
                    @foreach($zone->regions as $region)
                    <span style="display: inline-block; padding: var(--space-sm) var(--space-md); background: linear-gradient(90deg, rgba(15, 118, 110, 0.1) 0%, transparent 100%); border: 1px solid var(--wp-border); border-radius: 6px; font-size: 12px; font-weight: 500;">
                        {{ ucfirst($region->type) }}: <strong>{{ $region->value }}</strong>
                    </span>
                    @endforeach
                </div>
                @endif
            </div>

            {{-- Methods Summary --}}
            <div>
                <h4 style="font-size: 13px; font-weight: 600; text-transform: uppercase; color: var(--wp-muted); letter-spacing: 0.5px; margin: 0 0 var(--space-lg);">🚛 Delivery Methods</h4>
                @if($zone->methods->isEmpty())
                <p style="color: var(--wp-muted); font-size: 13px; margin: 0;">No delivery methods configured.</p>
                @else
                <div style="display: flex; flex-direction: column; gap: var(--space-md);">
                    @foreach($zone->methods as $method)
                    <div style="padding: var(--space-md); background: #f9f9f9; border-radius: 6px; border: 1px solid var(--wp-border);">
                        <div style="font-weight: 600; color: var(--wp-text); font-size: 13px;">{{ $method->name }}</div>
                        <div style="font-size: 12px; color: var(--wp-muted); margin-top: 4px;">
                            @if($method->type === 'free_shipping')
                            🎁 Free {{ $method->free_above ? '(above ₹' . number_format($method->free_above, 0) . ')' : '' }}
                            @elseif($method->type === 'weight_based')
                            ⚖️ ₹{{ number_format($method->cost, 0) }} + ₹{{ number_format($method->cost_per_kg ?? 0, 0) }}/kg
                            @else
                            💰 ₹{{ number_format($method->cost, 0) }}
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endforeach
@endif
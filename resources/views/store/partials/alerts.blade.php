@php
$flashQueue = [];

if (session('status')) {
$flashQueue[] = [
'message' => session('status'),
'type' => 'success',
];
}

foreach ($errors->all() as $error) {
$flashQueue[] = [
'message' => $error,
'type' => 'error',
];
}
@endphp

@if(!empty($flashQueue))
<script id="numnam-flash-data" type="application/json">
    {
        !!json_encode($flashQueue, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) !!
    }
</script>
<script>
    window.__numnamFlashQueue = window.__numnamFlashQueue || [];
    const flashDataEl = document.getElementById('numnam-flash-data');

    if (flashDataEl) {
        try {
            const flashQueuePayload = JSON.parse(flashDataEl.textContent || '[]');
            if (Array.isArray(flashQueuePayload)) {
                window.__numnamFlashQueue.push(...flashQueuePayload);
            }
        } catch (error) {
            console.error('Failed to parse flash queue payload', error);
        }
    }
</script>
@endif
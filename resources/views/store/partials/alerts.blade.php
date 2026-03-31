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
<input type="hidden" id="numnam-flash-data" value="{{ e(json_encode($flashQueue)) }}">
<script>
    window.__numnamFlashQueue = window.__numnamFlashQueue || [];
    const flashInput = document.getElementById('numnam-flash-data');
    if (flashInput && flashInput.value) {
        try {
            window.__numnamFlashQueue.push(...JSON.parse(flashInput.value));
        } catch (error) {
            console.error('Failed to parse flash queue payload', error);
        }
    }
</script>
@endif
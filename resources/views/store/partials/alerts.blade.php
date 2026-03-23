@if(session('status'))
    <script>
        window.__numnamFlashQueue = window.__numnamFlashQueue || [];
        window.__numnamFlashQueue.push({
            message: @json(session('status')),
            type: 'success'
        });
    </script>
@endif

@if($errors->any())
    <script>
        window.__numnamFlashQueue = window.__numnamFlashQueue || [];
        @foreach($errors->all() as $error)
            window.__numnamFlashQueue.push({
                message: @json($error),
                type: 'error'
            });
        @endforeach
    </script>
@endif

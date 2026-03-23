@if(session('status'))
    <section class="section notice-box" data-auto-dismiss>
        <p style="margin:0; font-weight:800; color:var(--accent-dark);">{{ session('status') }}</p>
    </section>
@endif

@if($errors->any())
    <section class="section" style="border-color:#ffd9d9; background:#fff7f7;" data-auto-dismiss>
        <ul style="margin:0; padding-left:1rem; color:var(--danger);">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </section>
@endif

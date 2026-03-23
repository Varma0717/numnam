<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'NumNam Store')</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&family=Lobster+Two:wght@400;700&family=Mulish:wght@400;700&display=swap">
    <link rel="stylesheet" href="{{ url('assets/store/css/base.css') }}">
    <link rel="stylesheet" href="{{ url('assets/store/css/components/header.css') }}">
    <link rel="stylesheet" href="{{ url('assets/store/css/components/footer.css') }}">
    <link rel="stylesheet" href="{{ url('assets/store/css/components/cards.css') }}">
    <link rel="stylesheet" href="{{ url('assets/store/css/components/forms.css') }}">
    <link rel="stylesheet" href="{{ url('assets/store/css/pages/home.css') }}">
    <link rel="stylesheet" href="{{ url('assets/store/css/pages/catalog.css') }}">
    <link rel="stylesheet" href="{{ url('assets/store/css/pages/checkout.css') }}">
</head>
<body>
<div class="page-shell">
    @include('store.partials.header')

    <main class="page">
        @include('store.partials.alerts')
        @yield('content')
    </main>

    @include('store.partials.footer')
</div>
<script src="{{ url('assets/store/js/components/header.js') }}" defer></script>
<script src="{{ url('assets/store/js/store.js') }}" defer></script>
</body>
</html>

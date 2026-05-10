@extends('store.layouts.app')

@section('title', 'NumNam - Login')

@section('content')
<section class="section py-12 sm:py-16">
    <div class="mx-auto w-full max-w-md px-4">
        <div class="rounded-3xl border border-slate-200 bg-white px-7 py-8 shadow-sm sm:px-9 sm:py-10">
            @if ($errors->any())
            <div class="mb-5 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                @foreach ($errors->all() as $error)<p>{{ $error }}</p>@endforeach
            </div>
            @endif
            <span class="inline-flex rounded-full border border-numnam-200 bg-white px-3 py-1 text-xs font-semibold uppercase tracking-[0.14em] text-numnam-700">Customer Login</span>
            <h1 class="mt-4 text-2xl font-extrabold tracking-tight text-slate-900">Welcome back</h1>
            <p class="mt-1 text-sm text-slate-500">Use your account credentials to sign in.</p>
            <form class="mt-6 space-y-4" method="POST" action="{{ route('store.login.submit') }}">
                @csrf
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-slate-700" for="email">Email</label>
                    <input class="h-11 w-full rounded-xl border border-slate-200 bg-white px-3.5 text-sm text-slate-900 outline-none transition focus:border-numnam-400 focus:ring-2 focus:ring-numnam-200" id="email" type="email" name="email" value="{{ old('email') }}" placeholder="you@example.com" required autofocus>
                </div>
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-slate-700" for="password">Password</label>
                    <input class="h-11 w-full rounded-xl border border-slate-200 bg-white px-3.5 text-sm text-slate-900 outline-none transition focus:border-numnam-400 focus:ring-2 focus:ring-numnam-200" id="password" type="password" name="password" placeholder="Password" required>
                </div>
                <label class="flex cursor-pointer items-center gap-2 text-sm text-slate-600">
                    <input class="h-4 w-4 rounded border-slate-300 text-numnam-600 focus:ring-numnam-300" type="checkbox" name="remember">
                    Remember me
                </label>
                <button class="mt-2 h-11 w-full rounded-full bg-numnam-600 text-sm font-semibold text-white transition hover:bg-numnam-700 focus:outline-none focus:ring-2 focus:ring-numnam-400 focus:ring-offset-2" type="submit">Login</button>
            </form>
            <p class="mt-5 text-center text-sm text-slate-500">New user? <a class="font-semibold text-numnam-700 hover:underline" href="{{ route('store.register') }}">Create account</a></p>
        </div>
    </div>
</section>
@endsection
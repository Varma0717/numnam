@extends('store.layouts.app')
@section('title', 'Forgot Password — NumNam')

@section('content')
<section class="section py-12 sm:py-16">
    <div class="mx-auto w-full max-w-md px-4">
        <div class="rounded-3xl border border-slate-200 bg-white px-7 py-8 shadow-sm sm:px-9 sm:py-10">
            <span class="inline-flex rounded-full border border-numnam-200 bg-white px-3 py-1 text-xs font-semibold uppercase tracking-[0.14em] text-numnam-700">Password Reset</span>
            <h1 class="mt-4 text-2xl font-extrabold tracking-tight text-slate-900">Forgot Password</h1>
            <p class="mt-1 text-sm text-slate-500">Enter your email and we'll send you a reset link.</p>

            @if(session('status'))
            <div class="mt-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">{{ session('status') }}</div>
            @endif

            <form class="mt-6 space-y-4" method="POST" action="{{ route('store.password.email') }}">
                @csrf
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-slate-700" for="email">Email Address</label>
                    <input class="h-11 w-full rounded-xl border border-slate-200 bg-white px-3.5 text-sm text-slate-900 outline-none transition focus:border-numnam-400 focus:ring-2 focus:ring-numnam-200" id="email" type="email" name="email" required autofocus value="{{ old('email') }}" placeholder="you@example.com">
                    @error('email')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <button class="h-11 w-full rounded-full bg-numnam-600 text-sm font-semibold text-white transition hover:bg-numnam-700 focus:outline-none focus:ring-2 focus:ring-numnam-400 focus:ring-offset-2" type="submit">Send Reset Link</button>
            </form>
            <p class="mt-5 text-center text-sm text-slate-500"><a class="font-semibold text-numnam-700 hover:underline" href="{{ route('store.login') }}">Back to Login</a></p>
        </div>
    </div>
</section>
@endsection
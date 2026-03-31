@extends('store.layouts.app')

@section('title', 'NumNam - Register')

@section('content')
<section class="section py-12 sm:py-16">
    <div class="mx-auto w-full max-w-md px-4">
        <div class="rounded-3xl border border-slate-200 bg-white px-7 py-8 shadow-sm sm:px-9 sm:py-10">
            @if ($errors->any())
            <div class="mb-5 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                @foreach ($errors->all() as $error)<p>{{ $error }}</p>@endforeach
            </div>
            @endif
            <span class="inline-flex rounded-full border border-numnam-200 bg-white px-3 py-1 text-xs font-semibold uppercase tracking-[0.14em] text-numnam-700">Create Account</span>
            <h1 class="mt-4 text-2xl font-extrabold tracking-tight text-slate-900">Join NumNam</h1>
            <p class="mt-1 text-sm text-slate-500">Start your journey to better baby nutrition.</p>
            <form class="mt-6 space-y-4" method="POST" action="{{ route('store.register.submit') }}">
                @csrf
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-slate-700" for="name">Full Name</label>
                    <input class="h-11 w-full rounded-xl border border-slate-200 bg-white px-3.5 text-sm text-slate-900 outline-none transition focus:border-numnam-400 focus:ring-2 focus:ring-numnam-200" id="name" type="text" name="name" value="{{ old('name') }}" placeholder="Your full name" required>
                </div>
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-slate-700" for="email">Email</label>
                    <input class="h-11 w-full rounded-xl border border-slate-200 bg-white px-3.5 text-sm text-slate-900 outline-none transition focus:border-numnam-400 focus:ring-2 focus:ring-numnam-200" id="email" type="email" name="email" value="{{ old('email') }}" placeholder="you@example.com" required>
                </div>
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-slate-700" for="password">Password</label>
                    <input class="h-11 w-full rounded-xl border border-slate-200 bg-white px-3.5 text-sm text-slate-900 outline-none transition focus:border-numnam-400 focus:ring-2 focus:ring-numnam-200" id="password" type="password" name="password" placeholder="Password" required>
                </div>
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-slate-700" for="password_confirmation">Confirm Password</label>
                    <input class="h-11 w-full rounded-xl border border-slate-200 bg-white px-3.5 text-sm text-slate-900 outline-none transition focus:border-numnam-400 focus:ring-2 focus:ring-numnam-200" id="password_confirmation" type="password" name="password_confirmation" placeholder="Confirm password" required>
                </div>
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-slate-700" for="referral_code">Referral Code <span class="font-normal text-slate-400">(optional)</span></label>
                    <input class="h-11 w-full rounded-xl border border-slate-200 bg-white px-3.5 text-sm text-slate-900 outline-none transition focus:border-numnam-400 focus:ring-2 focus:ring-numnam-200" id="referral_code" type="text" name="referral_code" value="{{ old('referral_code', $ref) }}" placeholder="Referral code">
                </div>
                <button class="mt-2 h-11 w-full rounded-full bg-numnam-600 text-sm font-semibold text-white transition hover:bg-numnam-700 focus:outline-none focus:ring-2 focus:ring-numnam-400 focus:ring-offset-2" type="submit">Create Account</button>
            </form>
            <p class="mt-5 text-center text-sm text-slate-500">Already have an account? <a class="font-semibold text-numnam-700 hover:underline" href="{{ route('store.login') }}">Login</a></p>
        </div>
    </div>
</section>
@endsection
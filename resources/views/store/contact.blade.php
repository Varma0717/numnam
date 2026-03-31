@extends('store.layouts.app')

@section('title', 'NumNam - Contact')

@section('content')
<section class="section pb-8 pt-4 sm:pt-8">
    <div class="relative overflow-hidden rounded-3xl border border-slate-200/90 bg-gradient-to-br from-[#fffaf4] via-white to-[#fff3e6] px-6 py-10 sm:px-10 lg:px-12">
        <div class="pointer-events-none absolute -left-16 -top-20 h-56 w-56 rounded-full bg-numnam-200/45 blur-3xl"></div>
        <div class="pointer-events-none absolute -bottom-20 -right-16 h-56 w-56 rounded-full bg-orange-100/65 blur-3xl"></div>
        <div class="relative max-w-3xl">
            <span class="inline-flex w-fit rounded-full border border-numnam-200 bg-white/90 px-3 py-1 text-xs font-semibold uppercase tracking-[0.14em] text-numnam-700">Support</span>
            <h1 class="mt-4 text-3xl font-extrabold tracking-tight text-slate-900 sm:text-4xl">Contact the NumNam care team</h1>
            <p class="mt-3 max-w-2xl text-base leading-relaxed text-slate-600">Messages are stored securely and available in our admin contact management portal.</p>
        </div>
    </div>
</section>

<section class="section pb-12">
    <div class="mx-auto max-w-2xl">
        @if(session('success'))
        <div class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">{{ session('success') }}</div>
        @endif
        <div class="rounded-3xl border border-slate-200 bg-white px-7 py-8 shadow-sm sm:px-9 sm:py-10">
            <form method="POST" action="{{ route('store.contact.submit') }}" class="space-y-5">
                @csrf
                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-slate-700" for="contact-name">Name <span class="text-red-500">*</span></label>
                        <input class="h-11 w-full rounded-xl border border-slate-200 bg-white px-3.5 text-sm text-slate-900 outline-none transition focus:border-numnam-400 focus:ring-2 focus:ring-numnam-200" id="contact-name" name="first_name" placeholder="Your full name" required>
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-slate-700" for="contact-email">Email <span class="text-red-500">*</span></label>
                        <input class="h-11 w-full rounded-xl border border-slate-200 bg-white px-3.5 text-sm text-slate-900 outline-none transition focus:border-numnam-400 focus:ring-2 focus:ring-numnam-200" id="contact-email" type="email" name="email" placeholder="you@example.com" required>
                    </div>
                </div>
                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-slate-700" for="contact-phone">Phone</label>
                        <input class="h-11 w-full rounded-xl border border-slate-200 bg-white px-3.5 text-sm text-slate-900 outline-none transition focus:border-numnam-400 focus:ring-2 focus:ring-numnam-200" id="contact-phone" name="phone" placeholder="+91 00000 00000">
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-slate-700" for="contact-query">Query type <span class="text-red-500">*</span></label>
                        <select class="h-11 w-full rounded-xl border border-slate-200 bg-white px-3.5 text-sm text-slate-900 outline-none transition focus:border-numnam-400 focus:ring-2 focus:ring-numnam-200" id="contact-query" name="query_type" required>
                            <option value="general">General</option>
                            <option value="order">Order</option>
                            <option value="wholesale">Wholesale</option>
                            <option value="press">Press</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-slate-700" for="contact-company">Company <span class="font-normal text-slate-400">(optional)</span></label>
                    <input class="h-11 w-full rounded-xl border border-slate-200 bg-white px-3.5 text-sm text-slate-900 outline-none transition focus:border-numnam-400 focus:ring-2 focus:ring-numnam-200" id="contact-company" name="company" placeholder="Your company name">
                </div>
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-slate-700" for="contact-message">Message <span class="text-red-500">*</span></label>
                    <textarea class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-3 text-sm text-slate-900 outline-none transition focus:border-numnam-400 focus:ring-2 focus:ring-numnam-200" id="contact-message" name="message" rows="5" placeholder="How can we help?" required></textarea>
                </div>
                <button class="h-11 w-full rounded-full bg-numnam-600 text-sm font-semibold text-white transition hover:bg-numnam-700 focus:outline-none focus:ring-2 focus:ring-numnam-400 focus:ring-offset-2 sm:w-auto sm:px-10" type="submit">Send Message</button>
            </form>
        </div>
    </div>
</section>
@endsection
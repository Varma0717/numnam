@extends('store.layouts.app')

@section('title', 'NumNam Community - Connect with Other Parents')

@section('content')
<section class="section py-8">
    <div class="mb-12">
        <h1 class="text-4xl font-black tracking-tight text-slate-900 mb-3">NumNam Community</h1>
        <p class="text-lg text-slate-600 max-w-2xl">Join thousands of parents sharing their weaning journey, recipes, tips, and experiences.</p>
    </div>

    @auth
    <div class="grid gap-8 lg:grid-cols-3">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Community Rooms Grid -->
            <div id="rooms-container" class="grid gap-4 md:grid-cols-2">
                <div class="col-span-2 text-center py-12">
                    <div class="inline-block">
                        <svg class="h-12 w-12 text-slate-300 mx-auto mb-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="text-slate-500">Loading community rooms...</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Stats Card -->
            <div class="rounded-2xl border border-slate-200 bg-gradient-to-br from-numnam-50 to-white p-6 shadow-sm">
                <h3 class="font-bold text-slate-900 mb-6 text-lg">Community Stats</h3>
                <div class="space-y-4">
                    <div class="flex items-center justify-between pb-4 border-b border-slate-200">
                        <div>
                            <p class="text-sm text-slate-600">Active Members</p>
                            <p class="text-2xl font-bold text-numnam-600 mt-1" id="member-count">—</p>
                        </div>
                        <svg class="h-8 w-8 text-numnam-200" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.856-1.487M15 10h.01M13 16H9m4 0a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                    <div class="flex items-center justify-between pb-4 border-b border-slate-200">
                        <div>
                            <p class="text-sm text-slate-600">Discussion Rooms</p>
                            <p class="text-2xl font-bold text-numnam-600 mt-1" id="room-count">—</p>
                        </div>
                        <svg class="h-8 w-8 text-numnam-200" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                        </svg>
                    </div>
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-slate-600">Messages Shared</p>
                            <p class="text-2xl font-bold text-numnam-600 mt-1" id="message-count">—</p>
                        </div>
                        <svg class="h-8 w-8 text-numnam-200" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Guidelines Card -->
            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm p-6">
                <h3 class="font-bold text-slate-900 mb-4 flex items-center gap-2">
                    <svg class="h-5 w-5 text-amber-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z" />
                    </svg>
                    Community Guidelines
                </h3>
                <ul class="space-y-3 text-sm">
                    <li class="flex gap-3">
                        <span class="text-green-600 font-bold mt-0.5">✓</span>
                        <span class="text-slate-700">Be respectful and supportive</span>
                    </li>
                    <li class="flex gap-3">
                        <span class="text-green-600 font-bold mt-0.5">✓</span>
                        <span class="text-slate-700">Share experiences, not medical advice</span>
                    </li>
                    <li class="flex gap-3">
                        <span class="text-green-600 font-bold mt-0.5">✓</span>
                        <span class="text-slate-700">No commercial promotion</span>
                    </li>
                    <li class="flex gap-3">
                        <span class="text-green-600 font-bold mt-0.5">✓</span>
                        <span class="text-slate-700">Keep discussions focused and kind</span>
                    </li>
                </ul>
            </div>

            <!-- Featured Member Card -->
            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm p-6">
                <h3 class="font-bold text-slate-900 mb-4">💡 Pro Tip</h3>
                <p class="text-sm text-slate-700 leading-relaxed">Engage authentically with the community. Your experiences and questions help other parents tremendously. No question is too small!</p>
            </div>
        </div>
    </div>

    @else
    <div class="rounded-3xl border-2 border-numnam-200 bg-gradient-to-br from-numnam-50 to-white p-12 text-center shadow-sm">
        <svg class="h-16 w-16 text-numnam-300 mx-auto mb-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 12H9m6 0H9m6 0a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <h2 class="text-2xl font-bold text-slate-900 mb-3">Join Our Community</h2>
        <p class="text-lg text-slate-600 mb-8 max-w-md mx-auto">Connect with thousands of parents, share your weaning journey, and get support from our caring community.</p>
        <div class="flex gap-4 justify-center">
            <a href="{{ route('store.login') }}" class="inline-flex items-center rounded-full bg-numnam-600 px-8 py-3 font-semibold text-white transition hover:bg-numnam-700 shadow-lg hover:shadow-xl">
                <svg class="h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4M10 17l5-5m0 0l-5-5" />
                </svg>
                Sign In
            </a>
            <a href="{{ route('store.register') }}" class="inline-flex items-center rounded-full border-2 border-numnam-600 px-8 py-3 font-semibold text-numnam-600 transition hover:bg-numnam-50">
                Create Account
            </a>
        </div>
    </div>
    @endauth
</section>
@endsection

@section('scripts')
@auth
<script>
    async function loadCommunityStats() {
        try {
            const response = await fetch('/api/v1/numnam/community/stats', {
                headers: {
                    'Accept': 'application/json'
                }
            });

            if (response.ok) {
                const data = await response.json();
                document.getElementById('room-count').textContent = data.active_rooms;
                document.getElementById('message-count').textContent = data.total_messages;
                document.getElementById('member-count').textContent = data.active_members;
            }
        } catch (error) {
            console.error('Error loading stats:', error);
        }
    }

    async function loadCommunityRooms() {
        try {
            const response = await fetch('/api/v1/numnam/community/rooms', {
                headers: {
                    'Accept': 'application/json'
                }
            });

            if (!response.ok) throw new Error('Failed to load rooms');

            const data = await response.json();
            const rooms = data.data || [];

            if (rooms.length === 0) {
                document.getElementById('rooms-container').innerHTML = `
                    <div class="col-span-2 text-center py-12">
                        <p class="text-slate-500">No community rooms available yet. Check back soon!</p>
                    </div>
                `;
                return;
            }

            const container = document.getElementById('rooms-container');
            container.innerHTML = rooms.map(room => `
                <a href="{{ route('store.community.show', '') }}/${room.slug}" class="group rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition hover:shadow-lg hover:border-numnam-300">
                    <div class="flex items-start justify-between mb-3">
                        <div class="text-4xl">${room.icon || '💬'}</div>
                        <span class="inline-flex items-center rounded-full bg-numnam-100 px-3 py-1 text-xs font-semibold text-numnam-700">
                            ${room.message_count} messages
                        </span>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 group-hover:text-numnam-600 transition">${room.name}</h3>
                    <p class="mt-2 text-sm text-slate-600">${room.description}</p>
                    <div class="mt-4 flex items-center justify-between pt-4 border-t border-slate-100">
                        <span class="text-xs text-slate-500">${room.last_activity || 'No activity yet'}</span>
                        <svg class="h-5 w-5 text-numnam-400 group-hover:translate-x-1 transition" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                    </div>
                </a>
            `).join('');
        } catch (error) {
            console.error('Error loading rooms:', error);
            document.getElementById('rooms-container').innerHTML = `
                <div class="col-span-2 text-center py-12">
                    <p class="text-red-600">Failed to load community rooms. Please try again.</p>
                </div>
            `;
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        loadCommunityStats();
        loadCommunityRooms();
    });
</script>
@endauth
@endsection
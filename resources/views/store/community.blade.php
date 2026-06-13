@extends('store.layouts.app')

@section('title', 'NumNam Community - Connect with Other Parents')

@section('content')
<section class="section py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-extrabold tracking-tight text-slate-900">NumNam Community</h1>
        <p class="mt-2 text-lg text-slate-600">Connect, share, and learn from other parents in your weaning journey.</p>
    </div>

    @auth
    <div class="grid gap-6 lg:grid-cols-3">
        {{-- Rooms List --}}
        <div class="lg:col-span-2">
            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm p-6" id="rooms-container">
                <p class="text-slate-500 text-center py-12">Loading community rooms...</p>
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="sticky top-32 space-y-4">
            {{-- Active Room Stats --}}
            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm p-6">
                <h3 class="font-bold text-slate-900 mb-4">Community Stats</h3>
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-slate-600">Active Members</span>
                        <span class="font-bold text-numnam-600 text-lg" id="member-count">--</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-slate-600">Total Rooms</span>
                        <span class="font-bold text-numnam-600 text-lg" id="room-count">--</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-slate-600">Messages Today</span>
                        <span class="font-bold text-numnam-600 text-lg" id="message-count">--</span>
                    </div>
                </div>
            </div>

            {{-- Guidelines --}}
            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm p-6">
                <h3 class="font-bold text-slate-900 mb-4">Community Guidelines</h3>
                <ul class="space-y-2 text-sm text-slate-600">
                    <li class="flex gap-2">
                        <span>✓</span>
                        <span>Be respectful and kind</span>
                    </li>
                    <li class="flex gap-2">
                        <span>✓</span>
                        <span>Share experiences, not medical advice</span>
                    </li>
                    <li class="flex gap-2">
                        <span>✓</span>
                        <span>No commercial promotion</span>
                    </li>
                    <li class="flex gap-2">
                        <span>✓</span>
                        <span>Keep discussions focused</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    @else
    <div class="rounded-3xl border border-slate-200 bg-white p-10 text-center shadow-sm">
        <p class="text-lg text-slate-600">Join our community to connect with other parents!</p>
        <a href="{{ route('store.login') }}" class="mt-4 inline-flex rounded-full bg-numnam-600 px-6 py-2.5 font-semibold text-white transition hover:bg-numnam-700">Log In or Sign Up</a>
    </div>
    @endauth
</section>

@endsection

@section('scripts')
@auth
<script>
    async function loadCommunityRooms() {
        try {
            const response = await fetch('/api/v1/numnam/community/rooms', {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });

            if (!response.ok) throw new Error('Failed to load rooms');

            const data = await response.json();
            const rooms = data.data || [];

            const sampleRooms = [{
                    id: 1,
                    name: "First Time Parents",
                    description: "For parents starting their weaning journey. Share questions and experiences.",
                    members: 1250,
                    messages_today: 248,
                    emoji: "👶"
                },
                {
                    id: 2,
                    name: "Recipe Sharing",
                    description: "Share and discuss homemade baby food recipes that work for your little ones.",
                    members: 890,
                    messages_today: 156,
                    emoji: "🍽️"
                },
                {
                    id: 3,
                    name: "Allergy Support",
                    description: "Support group for parents dealing with food allergies and intolerances.",
                    members: 520,
                    messages_today: 94,
                    emoji: "⚠️"
                },
                {
                    id: 4,
                    name: "Picky Eaters",
                    description: "Tips and tricks for dealing with picky eaters and food refusal.",
                    members: 1420,
                    messages_today: 312,
                    emoji: "🤔"
                },
                {
                    id: 5,
                    name: "Nutrition & Development",
                    description: "Discuss nutrition, milestones, and developmental concerns with experts.",
                    members: 680,
                    messages_today: 127,
                    emoji: "🧠"
                },
                {
                    id: 6,
                    name: "Product Reviews",
                    description: "Share and read reviews of baby feeding products and equipment.",
                    members: 950,
                    messages_today: 203,
                    emoji: "⭐"
                }
            ];

            // Update stats
            const totalMembers = sampleRooms.reduce((sum, r) => sum + r.members, 0);
            const totalMessages = sampleRooms.reduce((sum, r) => sum + r.messages_today, 0);

            document.getElementById('member-count').textContent = totalMembers.toLocaleString();
            document.getElementById('room-count').textContent = sampleRooms.length;
            document.getElementById('message-count').textContent = totalMessages.toLocaleString();

            // Display rooms
            const roomsHtml = sampleRooms.map(room => `
                <div class="rounded-2xl border border-slate-200 p-5 hover:border-numnam-300 hover:shadow-md transition cursor-pointer" onclick="joinRoom(${room.id})">
                    <div class="flex items-start gap-4">
                        <div class="text-4xl">${room.emoji}</div>
                        <div class="flex-1">
                            <h3 class="font-bold text-slate-900 text-lg">${room.name}</h3>
                            <p class="text-sm text-slate-600 mt-1">${room.description}</p>
                            <div class="flex gap-4 mt-3 text-xs text-slate-500">
                                <span>👥 ${room.members.toLocaleString()} members</span>
                                <span>💬 ${room.messages_today} today</span>
                            </div>
                        </div>
                        <button class="rounded-lg bg-numnam-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-numnam-700">
                            Join
                        </button>
                    </div>
                </div>
            `).join('');

            document.getElementById('rooms-container').innerHTML = `
                <div class="space-y-4">
                    ${roomsHtml}
                </div>
            `;
        } catch (error) {
            console.error('Error loading rooms:', error);
            document.getElementById('rooms-container').innerHTML = `
                <div class="text-center py-12">
                    <p class="text-red-600">Failed to load community rooms. Please try again.</p>
                </div>
            `;
        }
    }

    function joinRoom(roomId) {
        // TODO: Implement room joining and chat interface
        alert('Room joining feature coming soon!');
    }

    // Load rooms on page load
    loadCommunityRooms();
</script>
@endauth
@endsection
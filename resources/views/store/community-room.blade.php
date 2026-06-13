@extends('store.layouts.app')

@section('title', $room->name . ' - NumNam Community')

@section('content')
<section class="section py-8">
    <!-- Header with Back Button -->
    <div class="mb-8 flex items-center justify-between">
        <div>
            <a href="{{ route('store.community') }}" class="text-numnam-600 hover:text-numnam-700 font-semibold flex items-center gap-2 mb-4">
                ← Back to Community
            </a>
            <div class="flex items-center gap-3">
                <div class="text-4xl">{{ $room->icon ?? '💬' }}</div>
                <div>
                    <h1 class="text-3xl font-extrabold tracking-tight text-slate-900">{{ $room->name }}</h1>
                    <p class="mt-1 text-slate-600">{{ $room->description }}</p>
                </div>
            </div>
        </div>
    </div>

    @auth
    <div class="grid gap-6 lg:grid-cols-3">
        <!-- Messages List -->
        <div class="lg:col-span-2">
            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm p-6 space-y-4">
                <!-- Messages Container -->
                <div id="messages-container" class="space-y-4 max-h-96 overflow-y-auto mb-6">
                    @forelse($messages as $message)
                    <div class="border-b border-slate-200 pb-4 last:border-b-0">
                        <div class="flex items-start justify-between mb-2">
                            <h4 class="font-bold text-slate-900">{{ $message->user->name }}</h4>
                            <span class="text-xs text-slate-500">{{ $message->created_at->diffForHumans() }}</span>
                        </div>
                        <p class="text-slate-700 text-sm leading-relaxed">{{ $message->message }}</p>
                        <div class="mt-2 flex gap-2">
                            <button class="text-xs text-slate-500 hover:text-red-600 transition" onclick="likeMessage({{ $message->id }}, this)">
                                ❤️ <span class="likes-count">{{ $message->likes_count ?? 0 }}</span>
                            </button>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8 text-slate-500">
                        <p>No messages yet. Be the first to share!</p>
                    </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                @if($messages->hasPages())
                <div class="mt-4">
                    {{ $messages->links() }}
                </div>
                @endif

                <!-- Message Form -->
                <div class="border-t border-slate-200 pt-6">
                    <form id="message-form" class="space-y-4">
                        @csrf
                        <textarea
                            name="message"
                            id="message-input"
                            placeholder="Share your question or experience..."
                            rows="3"
                            class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm focus:border-numnam-400 focus:ring-1 focus:ring-numnam-400"></textarea>
                        <button
                            type="button"
                            onclick="sendMessage({{ $room->id }})"
                            class="w-full rounded-lg bg-numnam-600 px-4 py-2.5 font-semibold text-white transition hover:bg-numnam-700">
                            Send Message
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="sticky top-32 space-y-4">
            <!-- Room Info -->
            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm p-6">
                <h3 class="font-bold text-slate-900 mb-4">Room Info</h3>
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-slate-600">Total Messages</span>
                        <span class="font-bold text-numnam-600" id="total-messages">{{ $messages->total() }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-slate-600">Status</span>
                        <span class="inline-flex rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">
                            Active
                        </span>
                    </div>
                </div>
            </div>

            <!-- Guidelines -->
            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm p-6">
                <h3 class="font-bold text-slate-900 mb-4">Guidelines</h3>
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
        <p class="text-lg text-slate-600">Join our community to participate in discussions!</p>
        <a href="{{ route('store.login') }}" class="mt-4 inline-flex rounded-full bg-numnam-600 px-6 py-2.5 font-semibold text-white transition hover:bg-numnam-700">Log In or Sign Up</a>
    </div>
    @endauth
</section>

@endsection

@section('scripts')
@auth
<script>
    async function sendMessage(roomId) {
        const message = document.getElementById('message-input').value.trim();

        if (!message) {
            alert('Please enter a message');
            return;
        }

        try {
            const response = await fetch(`/api/v1/numnam/community/rooms/${roomId}/messages`, {
                method: 'POST',
                credentials: 'include',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    message
                })
            });

            if (!response.ok) {
                const error = await response.json();
                alert(error.message || 'Failed to send message');
                return;
            }

            // Clear input and reload messages
            document.getElementById('message-input').value = '';
            window.location.reload();
        } catch (error) {
            console.error('Error sending message:', error);
            alert('Failed to send message. Please try again.');
        }
    }

    async function likeMessage(messageId, button) {
        try {
            const response = await fetch(`/api/v1/numnam/community/messages/${messageId}/like`, {
                method: 'POST',
                credentials: 'include',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });

            if (!response.ok) throw new Error('Failed to like');

            const data = await response.json();
            button.querySelector('.likes-count').textContent = data.data.likes_count || 0;
        } catch (error) {
            console.error('Error liking message:', error);
        }
    }
</script>
@endauth
@endsection
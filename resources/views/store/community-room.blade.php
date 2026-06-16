@extends('store.layouts.app')

@section('title', $room->name . ' - NumNam Community')

@section('content')
<section class="section py-8">
    <!-- Header -->
    <div class="mb-8">
        <a href="{{ route('store.community') }}" class="inline-flex items-center text-numnam-600 hover:text-numnam-700 font-semibold mb-6 transition">
            <svg class="h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M19 12H5M12 19l-7-7 7-7" />
            </svg>
            Back to Community
        </a>
        <div class="flex items-center gap-4 mb-2">
            <div class="text-5xl">{{ $room->icon ?? '💬' }}</div>
            <div>
                <h1 class="text-4xl font-black text-slate-900">{{ $room->name }}</h1>
                <p class="text-lg text-slate-600 mt-2">{{ $room->description }}</p>
            </div>
        </div>
    </div>

    @auth
    <div class="grid gap-8 lg:grid-cols-3">
        <!-- Messages Area -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Message Composer -->
            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm p-6">
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-slate-900 mb-3">Share Your Thoughts</label>
                    <textarea
                        id="message-input"
                        placeholder="Share your question, experience, or tip with the community..."
                        rows="4"
                        class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-base placeholder-slate-400 focus:border-numnam-400 focus:ring-2 focus:ring-numnam-200 transition resize-none"></textarea>
                </div>
                <div class="flex gap-3">
                    <button
                        onclick="sendMessage({{ $room->id }})"
                        class="flex-1 inline-flex items-center justify-center rounded-lg bg-numnam-600 px-6 py-3 font-semibold text-white transition hover:bg-numnam-700 shadow-md hover:shadow-lg">
                        <svg class="h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M16 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2M16 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        Post Message
                    </button>
                </div>
            </div>

            <!-- Messages List -->
            <div id="messages-container" class="space-y-6">
                <div class="text-center py-12">
                    <svg class="h-12 w-12 text-slate-300 mx-auto mb-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                    </svg>
                    <p class="text-slate-500">Loading messages...</p>
                </div>
            </div>

            <!-- Pagination -->
            <div id="pagination-container" class="flex justify-center gap-2"></div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Room Stats -->
            <div class="rounded-2xl border border-slate-200 bg-gradient-to-br from-numnam-50 to-white shadow-sm p-6">
                <h3 class="font-bold text-slate-900 mb-6 text-lg">Room Statistics</h3>
                <div class="space-y-4">
                    <div class="flex items-center justify-between pb-4 border-b border-slate-200">
                        <span class="text-slate-700">Total Messages</span>
                        <span class="text-2xl font-bold text-numnam-600" id="message-count">0</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-slate-700">Status</span>
                        <span class="inline-flex rounded-full bg-green-100 px-3 py-1 text-xs font-bold text-green-700">
                            🟢 Active
                        </span>
                    </div>
                </div>
            </div>

            <!-- Guidelines -->
            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm p-6">
                <h3 class="font-bold text-slate-900 mb-4 flex items-center gap-2">
                    <svg class="h-5 w-5 text-amber-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z" />
                    </svg>
                    Community Guidelines
                </h3>
                <ul class="space-y-3 text-sm">
                    <li class="flex gap-3">
                        <span class="text-green-600 font-bold">✓</span>
                        <span class="text-slate-700">Be respectful and kind</span>
                    </li>
                    <li class="flex gap-3">
                        <span class="text-green-600 font-bold">✓</span>
                        <span class="text-slate-700">Share experiences only</span>
                    </li>
                    <li class="flex gap-3">
                        <span class="text-green-600 font-bold">✓</span>
                        <span class="text-slate-700">No commercial content</span>
                    </li>
                    <li class="flex gap-3">
                        <span class="text-green-600 font-bold">✓</span>
                        <span class="text-slate-700">Keep discussions focused</span>
                    </li>
                </ul>
            </div>

            <!-- Need Help -->
            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm p-6">
                <h3 class="font-bold text-slate-900 mb-3">💡 Tips</h3>
                <p class="text-sm text-slate-700">Ask specific questions and share what worked for your baby. Other parents learn best from real experiences!</p>
            </div>
        </div>
    </div>

    @else
    <div class="rounded-3xl border-2 border-numnam-200 bg-gradient-to-br from-numnam-50 to-white p-12 text-center shadow-sm">
        <svg class="h-16 w-16 text-numnam-300 mx-auto mb-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 12H9m6 0H9" />
        </svg>
        <h2 class="text-2xl font-bold text-slate-900 mb-3">Join the Conversation</h2>
        <p class="text-slate-600 mb-8 max-w-md mx-auto">Sign in to share your experiences, ask questions, and connect with other parents.</p>
        <a href="{{ route('store.login') }}" class="inline-flex items-center rounded-full bg-numnam-600 px-8 py-3 font-semibold text-white transition hover:bg-numnam-700 shadow-lg">
            <svg class="h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4M10 17l5-5m0 0l-5-5" />
            </svg>
            Sign In to Participate
        </a>
    </div>
    @endauth
</section>
@endsection

@section('scripts')
@auth
<script>
    let currentPage = 1;
    let totalPages = 1;

    function formatTime(dateString) {
        const date = new Date(dateString);
        const now = new Date();
        const diff = now - date;

        const seconds = Math.floor(diff / 1000);
        const minutes = Math.floor(seconds / 60);
        const hours = Math.floor(minutes / 60);
        const days = Math.floor(hours / 24);

        if (minutes < 1) return 'just now';
        if (minutes < 60) return `${minutes}m ago`;
        if (hours < 24) return `${hours}h ago`;
        if (days < 7) return `${days}d ago`;

        return date.toLocaleDateString();
    }

    async function loadMessages(page = 1) {
        try {
            const response = await fetch(`/api/v1/numnam/community/rooms/{{ $room->id }}/messages?page=${page}`, {
                headers: {
                    'Accept': 'application/json'
                }
            });

            if (!response.ok) throw new Error('Failed to load messages');

            const data = await response.json();
            const messages = data.data || [];
            const pagination = data.pagination || {};

            currentPage = pagination.current_page || 1;
            totalPages = pagination.last_page || 1;

            if (messages.length === 0) {
                document.getElementById('messages-container').innerHTML = `
                    <div class="text-center py-12">
                        <svg class="h-12 w-12 text-slate-300 mx-auto mb-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" /></svg>
                        <p class="text-slate-500">No messages yet. Be the first to share!</p>
                    </div>
                `;
                document.getElementById('message-count').textContent = 0;
                return;
            }

            document.getElementById('message-count').textContent = pagination.total || 0;

            const container = document.getElementById('messages-container');
            container.innerHTML = messages.map(msg => `
                <div class="rounded-2xl border border-slate-200 bg-white shadow-sm p-6 hover:shadow-md transition">
                    <!-- Message Header -->
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center gap-3 flex-1">
                            <div class="h-10 w-10 rounded-full bg-gradient-to-br from-numnam-400 to-numnam-600 flex items-center justify-center text-white font-bold text-sm">
                                ${msg.user.name.charAt(0).toUpperCase()}
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-900">${msg.user.name}</h4>
                                <p class="text-xs text-slate-500">${formatTime(msg.created_at)}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Message Content -->
                    <p class="text-slate-700 leading-relaxed mb-4">${msg.message.replace(/</g, '&lt;').replace(/>/g, '&gt;')}</p>

                    <!-- Message Actions -->
                    <div class="flex items-center gap-4 pt-4 border-t border-slate-100">
                        <button onclick="toggleLike(${msg.id}, this)" class="flex items-center gap-2 text-slate-600 hover:text-red-600 transition font-semibold text-sm group">
                            <svg class="h-5 w-5 group-hover:fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
                            <span class="likes-count">${msg.likes_count || 0}</span>
                        </button>
                        <button onclick="toggleComments(${msg.id}, this)" class="flex items-center gap-2 text-slate-600 hover:text-numnam-600 transition font-semibold text-sm">
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                            <span class="comments-count">${msg.comments_count || 0}</span>
                        </button>
                    </div>

                    <!-- Comments Section -->
                    <div class="comments-section mt-4 hidden">
                        <div class="comments-list bg-slate-50 rounded-lg p-4 mb-4 max-h-48 overflow-y-auto"></div>
                        <form onsubmit="addComment(event, ${msg.id})" class="space-y-3">
                            <textarea placeholder="Add a comment..." rows="2" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-numnam-400 focus:ring-1 focus:ring-numnam-200 resize-none" required></textarea>
                            <button type="submit" class="w-full bg-numnam-600 text-white font-semibold py-2 rounded-lg hover:bg-numnam-700 transition text-sm">Reply</button>
                        </form>
                    </div>
                </div>
            `).join('');

            renderPagination();
        } catch (error) {
            console.error('Failed to load messages:', error);
            document.getElementById('messages-container').innerHTML = `
                <div class="text-center py-12">
                    <p class="text-red-600">Failed to load messages. Please try again.</p>
                </div>
            `;
        }
    }

    function renderPagination() {
        const container = document.getElementById('pagination-container');
        if (totalPages <= 1) {
            container.innerHTML = '';
            return;
        }

        let html = '';
        if (currentPage > 1) {
            html += `<button onclick="loadMessages(${currentPage - 1})" class="px-4 py-2 rounded-lg border border-slate-200 hover:bg-slate-50 text-sm font-semibold">← Previous</button>`;
        }
        html += `<span class="px-4 py-2">Page ${currentPage} of ${totalPages}</span>`;
        if (currentPage < totalPages) {
            html += `<button onclick="loadMessages(${currentPage + 1})" class="px-4 py-2 rounded-lg border border-slate-200 hover:bg-slate-50 text-sm font-semibold">Next →</button>`;
        }
        container.innerHTML = html;
    }

    async function sendMessage() {
        const message = document.getElementById('message-input').value.trim();

        if (!message) {
            alert('Please enter a message');
            return;
        }

        try {
            const response = await fetch(`/api/v1/numnam/community/rooms/{{ $room->id }}/messages`, {
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

            document.getElementById('message-input').value = '';
            loadMessages(1);
        } catch (error) {
            console.error('Error:', error);
            alert('Failed to send message');
        }
    }

    async function toggleLike(messageId, button) {
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
            button.querySelector('.likes-count').textContent = data.likes_count || 0;
            button.classList.toggle('text-red-600');
        } catch (error) {
            console.error('Error:', error);
        }
    }

    function toggleComments(messageId, button) {
        const section = button.closest('.rounded-2xl').querySelector('.comments-section');
        section.classList.toggle('hidden');
    }

    async function addComment(e, messageId) {
        e.preventDefault();
        const form = e.target;
        const comment = form.querySelector('textarea').value.trim();

        if (!comment) return;

        try {
            const response = await fetch(`/api/v1/numnam/community/messages/${messageId}/comments`, {
                method: 'POST',
                credentials: 'include',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    comment
                })
            });

            if (!response.ok) throw new Error('Failed to add comment');

            form.querySelector('textarea').value = '';
            loadMessages(currentPage);
        } catch (error) {
            console.error('Error:', error);
            alert('Failed to add comment');
        }
    }

    document.addEventListener('DOMContentLoaded', () => loadMessages(1));
</script>
@endauth
@endsection
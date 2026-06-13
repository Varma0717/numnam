@php
$babyProfile = auth()->user()->babyProfile;
if (!$babyProfile) {
$babyProfile = auth()->user()->babyProfiles()->first();
}
@endphp

<div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
    @if(!$babyProfile)
    <div class="rounded-xl border border-amber-200 bg-amber-50 p-4 text-center">
        <p class="text-sm font-medium text-amber-800">
            Please set up your baby's profile to start tracking.
        </p>
        <a href="#" onclick="document.querySelector('[data-tab=profile]').click()" class="mt-2 inline-block rounded-full bg-numnam-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-numnam-700">
            Go to Profile
        </a>
    </div>
    @else
    <div class="mb-8">
        <h3 class="mb-2 text-lg font-bold text-slate-900">Today's Overview</h3>
        <p class="text-sm text-slate-500">{{ $babyProfile->baby_name }}, {{ $babyProfile->age_months }} months old</p>
    </div>

    {{-- Stats Grid --}}
    <div class="grid grid-cols-2 gap-4 sm:gap-6 mb-8">
        {{-- Milk Stats --}}
        <div class="rounded-2xl border border-blue-200 bg-gradient-to-br from-blue-50 to-blue-100 p-4">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-semibold text-blue-700">Milk Intake</p>
                    <p class="mt-2 text-2xl font-bold text-blue-900" id="milk-total">0 ml</p>
                    <p class="mt-1 text-xs text-blue-600" id="milk-target">Target: -- ml</p>
                </div>
                <span class="text-2xl">🍼</span>
            </div>
        </div>

        {{-- Solids Stats --}}
        <div class="rounded-2xl border border-orange-200 bg-gradient-to-br from-orange-50 to-orange-100 p-4">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-semibold text-orange-700">Solids Eaten</p>
                    <p class="mt-2 text-2xl font-bold text-orange-900" id="solids-count">0</p>
                    <p class="mt-1 text-xs text-orange-600">Meals logged</p>
                </div>
                <span class="text-2xl">🥣</span>
            </div>
        </div>

        {{-- Water Stats --}}
        <div class="rounded-2xl border border-cyan-200 bg-gradient-to-br from-cyan-50 to-cyan-100 p-4">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-semibold text-cyan-700">Water Intake</p>
                    <p class="mt-2 text-2xl font-bold text-cyan-900" id="water-total">0 ml</p>
                    <p class="mt-1 text-xs text-cyan-600">Updated today</p>
                </div>
                <span class="text-2xl">💧</span>
            </div>
        </div>

        {{-- Poop Stats --}}
        <div class="rounded-2xl border border-amber-200 bg-gradient-to-br from-amber-50 to-amber-100 p-4">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-semibold text-amber-700">Poop Tracking</p>
                    <p class="mt-2 text-2xl font-bold text-amber-900" id="poop-count">0</p>
                    <p class="mt-1 text-xs text-amber-600">Times logged</p>
                </div>
                <span class="text-2xl">💩</span>
            </div>
        </div>
    </div>

    {{-- Quick Links to Features --}}
    <div class="mb-8 grid grid-cols-2 gap-3 sm:grid-cols-5">
        <a href="{{ route('store.tools.logging') }}" class="rounded-lg border border-slate-200 bg-gradient-to-br from-blue-50 to-blue-100 p-4 text-center transition hover:shadow-md hover:border-blue-300">
            <div class="text-2xl mb-2">📝</div>
            <div class="text-xs font-semibold text-slate-900">Logging</div>
            <div class="text-xs text-slate-500">Feed & Poop</div>
        </a>

        <a href="{{ route('store.recipes') }}" class="rounded-lg border border-slate-200 bg-gradient-to-br from-orange-50 to-orange-100 p-4 text-center transition hover:shadow-md hover:border-orange-300">
            <div class="text-2xl mb-2">🍽️</div>
            <div class="text-xs font-semibold text-slate-900">Recipes</div>
            <div class="text-xs text-slate-500">Food Ideas</div>
        </a>

        <a href="{{ route('store.products') }}" class="rounded-lg border border-slate-200 bg-gradient-to-br from-green-50 to-green-100 p-4 text-center transition hover:shadow-md hover:border-green-300">
            <div class="text-2xl mb-2">🛍️</div>
            <div class="text-xs font-semibold text-slate-900">Shop</div>
            <div class="text-xs text-slate-500">Products</div>
        </a>

        <a href="{{ route('store.community') }}" class="rounded-lg border border-slate-200 bg-gradient-to-br from-purple-50 to-purple-100 p-4 text-center transition hover:shadow-md hover:border-purple-300">
            <div class="text-2xl mb-2">💬</div>
            <div class="text-xs font-semibold text-slate-900">Community</div>
            <div class="text-xs text-slate-500">Chat Rooms</div>
        </a>

        <a href="{{ route('store.blog.index') }}" class="rounded-lg border border-slate-200 bg-gradient-to-br from-pink-50 to-pink-100 p-4 text-center transition hover:shadow-md hover:border-pink-300">
            <div class="text-2xl mb-2">📚</div>
            <div class="text-xs font-semibold text-slate-900">Learn</div>
            <div class="text-xs text-slate-500">Articles</div>
        </a>
    </div>

    {{-- Quick Log Buttons --}}
    <div class="mb-8 rounded-2xl border border-slate-200 bg-slate-50 p-4">
        <h4 class="mb-4 font-semibold text-slate-900">Quick Log</h4>
        <div class="grid grid-cols-2 gap-3 sm:grid-cols-4">
            <button class="quick-log-btn rounded-lg bg-blue-500 px-3 py-2 text-center text-sm font-semibold text-white transition hover:bg-blue-600" data-type="milk">
                <span class="text-lg">🍼</span>
                <div class="mt-1 text-xs">Log Milk</div>
            </button>
            <button class="quick-log-btn rounded-lg bg-orange-500 px-3 py-2 text-center text-sm font-semibold text-white transition hover:bg-orange-600" data-type="solid">
                <span class="text-lg">🥣</span>
                <div class="mt-1 text-xs">Log Solid</div>
            </button>
            <button class="quick-log-btn rounded-lg bg-cyan-500 px-3 py-2 text-center text-sm font-semibold text-white transition hover:bg-cyan-600" data-type="water">
                <span class="text-lg">💧</span>
                <div class="mt-1 text-xs">Log Water</div>
            </button>
            <button class="quick-log-btn rounded-lg bg-amber-500 px-3 py-2 text-center text-sm font-semibold text-white transition hover:bg-amber-600" data-type="poop">
                <span class="text-lg">💩</span>
                <div class="mt-1 text-xs">Log Poop</div>
            </button>
        </div>
    </div>

    {{-- Recent Logs --}}
    <div>
        <div class="mb-4 flex items-center justify-between">
            <h4 class="font-semibold text-slate-900">Today's Logs</h4>
            <a href="{{ route('store.tools.logging') }}" class="text-sm font-semibold text-numnam-600 transition hover:text-numnam-700">View All →</a>
        </div>
        <div class="space-y-2" id="recent-logs">
            <div class="rounded-lg border border-slate-200 bg-slate-50 p-3 text-center text-sm text-slate-500">
                No logs yet. Start logging to see updates!
            </div>
        </div>
    </div>

    @endif
</div>

@if($babyProfile)
<script>
    // Load today's data when overview tab is clicked
    async function loadTodayOverview() {
        try {
            const response = await fetch('/api/v1/numnam/logs/today', {
                credentials: 'include',
                headers: {
                    'Accept': 'application/json'
                }
            });

            if (!response.ok) throw new Error('Failed to load logs');

            const data = await response.json();
            const logs = data.data || [];

            // Calculate totals
            let milkTotal = 0,
                solidsCount = 0,
                waterTotal = 0,
                poopCount = 0;
            const recentLogs = [];

            logs.forEach(log => {
                switch (log.type) {
                    case 'milk':
                        milkTotal += log.volume_ml || 0;
                        break;
                    case 'solid':
                        solidsCount++;
                        break;
                    case 'water':
                        waterTotal += log.volume_ml || 0;
                        break;
                    case 'poop':
                        poopCount++;
                        break;
                }

                // Keep last 5 logs
                if (recentLogs.length < 5) {
                    recentLogs.push(log);
                }
            });

            // Update stats
            document.getElementById('milk-total').textContent = milkTotal + ' ml';
            document.getElementById('solids-count').textContent = solidsCount;
            document.getElementById('water-total').textContent = waterTotal + ' ml';
            document.getElementById('poop-count').textContent = poopCount;

            // Update recent logs
            const logsHtml = recentLogs.length > 0 ?
                recentLogs.map(log => `
                    <div class="flex items-center justify-between rounded-lg border border-slate-200 p-3">
                        <div class="flex items-center gap-3">
                            <span class="text-lg">${getLogEmoji(log.type)}</span>
                            <div class="text-sm">
                                <p class="font-semibold text-slate-900">${getLogLabel(log)}</p>
                                <p class="text-xs text-slate-500">${new Date(log.logged_at).toLocaleTimeString()}</p>
                            </div>
                        </div>
                        <button class="delete-log-btn text-xs text-red-600 hover:text-red-700" data-id="${log.id}">Delete</button>
                    </div>
                `).join('') :
                '<div class="rounded-lg border border-slate-200 bg-slate-50 p-3 text-center text-sm text-slate-500">No logs yet. Start logging to see updates!</div>';

            document.getElementById('recent-logs').innerHTML = logsHtml;

            // Attach delete handlers
            document.querySelectorAll('.delete-log-btn').forEach(btn => {
                btn.addEventListener('click', async function() {
                    if (confirm('Are you sure you want to delete this log?')) {
                        await fetch(`/api/v1/numnam/logs/${this.dataset.id}`, {
                            method: 'DELETE',
                            credentials: 'include',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                            }
                        });
                        loadTodayOverview();
                    }
                });
            });
        } catch (error) {
            console.error('Error loading overview:', error);
        }
    }

    function getLogEmoji(type) {
        const emojis = {
            milk: '🍼',
            solid: '🥣',
            water: '💧',
            poop: '💩'
        };
        return emojis[type] || '📝';
    }

    function getLogLabel(log) {
        if (log.type === 'milk') return `${log.volume_ml}ml ${log.milk_type || 'milk'}`;
        if (log.type === 'solid') return `${log.food_name || 'Food'} (${log.food_type || 'mixed'})`;
        if (log.type === 'water') return `${log.volume_ml}ml water`;
        if (log.type === 'poop') return `Poop: ${log.poop_type || 'logged'}`;
        return 'Log entry';
    }

    // Load data when page loads
    loadTodayOverview();

    // Setup quick log buttons
    document.querySelectorAll('.quick-log-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            window.location.href = "{{ route('store.tools.logging') }}?type=" + this.dataset.type;
        });
    });
</script>
@endif
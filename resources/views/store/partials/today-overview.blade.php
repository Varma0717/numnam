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
                <svg class="h-6 w-6 text-blue-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M7 15c0 1.657.895 3.096 2.209 3.872.826.492 1.786.793 2.791.793s1.965-.301 2.791-.793C16.105 18.096 17 16.657 17 15V5c0-2.761-2.239-5-5-5s-5 2.239-5 5v10Z" />
                </svg>
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
                <svg class="h-6 w-6 text-orange-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M3 2v6h6M21 22v-6h-6M6 13c0-3.866 3.134-7 7-7s7 3.134 7 7-3.134 7-7 7-7-3.134-7-7Z" />
                </svg>
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
                <svg class="h-6 w-6 text-cyan-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 2c.96 0 1.92.38 2.65 1.13.73.75 1.13 1.77 1.13 2.82 0 .82-.26 1.62-.77 2.31.53.16 1.1.24 1.68.24 2.54 0 4.8-1.07 6.46-2.86l1.41 1.41c-2.12 2.3-5.07 3.65-8.32 3.65-.58 0-1.13-.08-1.66-.23.51.69.77 1.49.77 2.31 0 1.05-.4 2.07-1.13 2.82-.73.75-1.69 1.13-2.65 1.13s-1.92-.38-2.65-1.13c-.73-.75-1.13-1.77-1.13-2.82 0-.82.26-1.62.77-2.31-.53-.16-1.1-.24-1.68-.24-2.54 0-4.8 1.07-6.46 2.86L2.07 14.5c2.12-2.3 5.07-3.65 8.32-3.65.58 0 1.13.08 1.66.23-.51-.69-.77-1.49-.77-2.31 0-1.05.4-2.07 1.13-2.82.73-.75 1.69-1.13 2.65-1.13Z" />
                </svg>
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
                <svg class="h-6 w-6 text-amber-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <ellipse cx="12" cy="13" rx="9" ry="5" />
                    <path d="M3 13c0 2.209 4.03 4 9 4s9-1.791 9-4M3 13v4c0 2.209 4.03 4 9 4s9-1.791 9-4v-4" />
                </svg>
            </div>
        </div>
    </div>

    {{-- Quick Links to Features --}}
    <div class="mb-8 grid grid-cols-2 gap-3 sm:grid-cols-4">
        <a href="{{ route('store.recipes') }}" class="rounded-lg border border-slate-200 bg-gradient-to-br from-orange-50 to-orange-100 p-4 text-center transition hover:shadow-md hover:border-orange-300">
            <svg class="mx-auto mb-2 h-6 w-6 text-orange-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M3 2v6h6M21 22v-6h-6M6 13c0-3.866 3.134-7 7-7s7 3.134 7 7-3.134 7-7 7-7-3.134-7-7Z" />
            </svg>
            <div class="text-xs font-semibold text-slate-900">Recipes</div>
            <div class="text-xs text-slate-500">Food Ideas</div>
        </a>

        <a href="{{ route('store.products') }}" class="rounded-lg border border-slate-200 bg-gradient-to-br from-green-50 to-green-100 p-4 text-center transition hover:shadow-md hover:border-green-300">
            <svg class="mx-auto mb-2 h-6 w-6 text-green-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="9" cy="21" r="1" />
                <circle cx="20" cy="21" r="1" />
                <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6" />
            </svg>
            <div class="text-xs font-semibold text-slate-900">Shop</div>
            <div class="text-xs text-slate-500">Products</div>
        </a>

        <a href="{{ route('store.community') }}" class="rounded-lg border border-slate-200 bg-gradient-to-br from-purple-50 to-purple-100 p-4 text-center transition hover:shadow-md hover:border-purple-300">
            <svg class="mx-auto mb-2 h-6 w-6 text-purple-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" />
            </svg>
            <div class="text-xs font-semibold text-slate-900">Community</div>
            <div class="text-xs text-slate-500">Chat Rooms</div>
        </a>

        <a href="{{ route('store.blog.index') }}" class="rounded-lg border border-slate-200 bg-gradient-to-br from-pink-50 to-pink-100 p-4 text-center transition hover:shadow-md hover:border-pink-300">
            <svg class="mx-auto mb-2 h-6 w-6 text-pink-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20M4 4v6h6M4 10h6" />
            </svg>
            <div class="text-xs font-semibold text-slate-900">Learn</div>
            <div class="text-xs text-slate-500">Articles</div>
        </a>
    </div>

    {{-- Quick Log Buttons --}}
    <div class="mb-8 rounded-2xl border border-slate-200 bg-white p-4">
        <h4 class="mb-4 font-semibold text-slate-900">Quick Log</h4>
        <div class="grid grid-cols-2 gap-3 sm:grid-cols-4">
            <button class="quick-log-btn flex flex-col items-center justify-center rounded-lg px-4 py-4 text-center text-sm font-semibold text-white shadow-md transition" style="background-color: #1e40af;" onmouseover="this.style.backgroundColor='#1e3a8a'" onmouseout="this.style.backgroundColor='#1e40af'" data-type="milk">
                <svg class="mb-1 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M7 15c0 1.657.895 3.096 2.209 3.872.826.492 1.786.793 2.791.793s1.965-.301 2.791-.793C16.105 18.096 17 16.657 17 15V5c0-2.761-2.239-5-5-5s-5 2.239-5 5v10Z" />
                </svg>
                <div class="text-xs">Log Milk</div>
            </button>
            <button class="quick-log-btn flex flex-col items-center justify-center rounded-lg px-4 py-4 text-center text-sm font-semibold text-white shadow-md transition" style="background-color: #b45309;" onmouseover="this.style.backgroundColor='#92400e'" onmouseout="this.style.backgroundColor='#b45309'" data-type="solid">
                <svg class="mb-1 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M3 2v6h6M21 22v-6h-6M6 13c0-3.866 3.134-7 7-7s7 3.134 7 7-3.134 7-7 7-7-3.134-7-7Z" />
                </svg>
                <div class="text-xs">Log Solid</div>
            </button>
            <button class="quick-log-btn flex flex-col items-center justify-center rounded-lg px-4 py-4 text-center text-sm font-semibold text-white shadow-md transition" style="background-color: #0891b2;" onmouseover="this.style.backgroundColor='#06778d'" onmouseout="this.style.backgroundColor='#0891b2'" data-type="water">
                <svg class="mb-1 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 2c.96 0 1.92.38 2.65 1.13.73.75 1.13 1.77 1.13 2.82 0 .82-.26 1.62-.77 2.31.53.16 1.1.24 1.68.24 2.54 0 4.8-1.07 6.46-2.86l1.41 1.41c-2.12 2.3-5.07 3.65-8.32 3.65-.58 0-1.13-.08-1.66-.23.51.69.77 1.49.77 2.31 0 1.05-.4 2.07-1.13 2.82-.73.75-1.69 1.13-2.65 1.13s-1.92-.38-2.65-1.13c-.73-.75-1.13-1.77-1.13-2.82 0-.82.26-1.62.77-2.31-.53-.16-1.1-.24-1.68-.24-2.54 0-4.8 1.07-6.46 2.86L2.07 14.5c2.12-2.3 5.07-3.65 8.32-3.65.58 0 1.13.08 1.66.23-.51-.69-.77-1.49-.77-2.31 0-1.05.4-2.07 1.13-2.82.73-.75 1.69-1.13 2.65-1.13Z" />
                </svg>
                <div class="text-xs">Log Water</div>
            </button>
            <button class="quick-log-btn flex flex-col items-center justify-center rounded-lg px-4 py-4 text-center text-sm font-semibold text-white shadow-md transition" style="background-color: #b45309;" onmouseover="this.style.backgroundColor='#92400e'" onmouseout="this.style.backgroundColor='#b45309'" data-type="poop">
                <svg class="mb-1 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <ellipse cx="12" cy="13" rx="9" ry="5" />
                    <path d="M3 13c0 2.209 4.03 4 9 4s9-1.791 9-4M3 13v4c0 2.209 4.03 4 9 4s9-1.791 9-4v-4" />
                </svg>
                <div class="text-xs">Log Poop</div>
            </button>
        </div>
    </div>

    {{-- Recent Logs --}}
    <div>
        <div class="mb-4 flex items-center justify-between">
            <h4 class="font-semibold text-slate-900">Today's Logs</h4>
            <a href="{{ route('store.tools.numnam') }}" class="text-sm font-semibold text-numnam-600 transition hover:text-numnam-700">View All →</a>
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
                            ${getLogIcon(log.type)}
                            <div class="text-sm">
                                <p class="font-semibold text-slate-900">${getLogLabel(log)}</p>
                                <p class="text-xs text-slate-500">${formatTimeIST(log.logged_at)}</p>
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

    function getLogIcon(type) {
        const icons = {
            milk: '<svg class="h-5 w-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M7 15c0 1.657.895 3.096 2.209 3.872.826.492 1.786.793 2.791.793s1.965-.301 2.791-.793C16.105 18.096 17 16.657 17 15V5c0-2.761-2.239-5-5-5s-5 2.239-5 5v10Z"/></svg>',
            solid: '<svg class="h-5 w-5 text-orange-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 2v6h6M21 22v-6h-6M6 13c0-3.866 3.134-7 7-7s7 3.134 7 7-3.134 7-7 7-7-3.134-7-7Z"/></svg>',
            water: '<svg class="h-5 w-5 text-cyan-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2c.96 0 1.92.38 2.65 1.13.73.75 1.13 1.77 1.13 2.82 0 .82-.26 1.62-.77 2.31.53.16 1.1.24 1.68.24 2.54 0 4.8-1.07 6.46-2.86l1.41 1.41c-2.12 2.3-5.07 3.65-8.32 3.65-.58 0-1.13-.08-1.66-.23.51.69.77 1.49.77 2.31 0 1.05-.4 2.07-1.13 2.82-.73.75-1.69 1.13-2.65 1.13s-1.92-.38-2.65-1.13c-.73-.75-1.13-1.77-1.13-2.82 0-.82.26-1.62.77-2.31-.53-.16-1.1-.24-1.68-.24-2.54 0-4.8 1.07-6.46 2.86L2.07 14.5c2.12-2.3 5.07-3.65 8.32-3.65.58 0 1.13.08 1.66.23-.51-.69-.77-1.49-.77-2.31 0-1.05.4-2.07 1.13-2.82.73-.75 1.69-1.13 2.65-1.13Z"/></svg>',
            poop: '<svg class="h-5 w-5 text-amber-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><ellipse cx="12" cy="13" rx="9" ry="5"/><path d="M3 13c0 2.209 4.03 4 9 4s9-1.791 9-4M3 13v4c0 2.209 4.03 4 9 4s9-1.791 9-4v-4"/></svg>'
        };
        return icons[type] || '<svg class="h-5 w-5 text-slate-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="12" y1="11" x2="12" y2="17"/><line x1="9" y1="14" x2="15" y2="14"/></svg>';
    }

    function formatTimeIST(dateString) {
        const date = new Date(dateString);
        // Use timezone parameter to convert to IST (no manual offset needed)
        return date.toLocaleTimeString('en-IN', {
            hour: '2-digit',
            minute: '2-digit',
            timeZone: 'Asia/Kolkata',
            hour12: true
        });
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
            window.location.href = "{{ route('store.tools.numnam') }}?type=" + this.dataset.type;
        });
    });
</script>
@endif
@extends('store.layouts.app')

@section('title', 'NumNam - Feeding & Poop Logger')

@section('content')
<section class="section py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-extrabold tracking-tight text-slate-900">Daily Feeding Log</h1>
        <p class="mt-2 text-lg text-slate-600">Track your baby's milk, solids, water, and poop throughout the day.</p>
    </div>

    @auth
    <div class="grid gap-8 lg:grid-cols-3">
        {{-- Log Form --}}
        <div class="lg:col-span-2">
            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
                <div id="log-form-container">
                    {{-- Tab Navigation --}}
                    <div class="mb-6 flex gap-2 border-b border-slate-200">
                        <button class="log-type-btn active px-4 py-3 font-semibold text-slate-900 border-b-2 border-numnam-600 transition hover:text-numnam-600" data-type="milk">
                            🍼 Milk
                        </button>
                        <button class="log-type-btn px-4 py-3 font-semibold text-slate-500 border-b-2 border-transparent transition hover:text-slate-900" data-type="solid">
                            🥣 Solids
                        </button>
                        <button class="log-type-btn px-4 py-3 font-semibold text-slate-500 border-b-2 border-transparent transition hover:text-slate-900" data-type="water">
                            💧 Water
                        </button>
                        <button class="log-type-btn px-4 py-3 font-semibold text-slate-500 border-b-2 border-transparent transition hover:text-slate-900" data-type="poop">
                            💩 Poop
                        </button>
                    </div>

                    {{-- Milk Form --}}
                    <form id="milk-form" class="log-form space-y-4">
                        <input type="hidden" name="type" value="milk">

                        <div>
                            <label class="mb-2 block text-sm font-semibold text-slate-700">Milk Type</label>
                            <select name="milk_type" required class="w-full rounded-lg border border-slate-200 bg-white px-4 py-2.5 text-sm focus:border-numnam-400 focus:ring-1 focus:ring-numnam-400">
                                <option value="">Select milk type</option>
                                <option value="breast">Breast Milk</option>
                                <option value="formula">Formula</option>
                                <option value="combination">Combination</option>
                            </select>
                        </div>

                        <div>
                            <label class="mb-2 block text-sm font-semibold text-slate-700">Volume (ml)</label>
                            <input type="number" name="volume_ml" placeholder="e.g., 150" min="0" step="10" required class="w-full rounded-lg border border-slate-200 bg-white px-4 py-2.5 text-sm focus:border-numnam-400 focus:ring-1 focus:ring-numnam-400">
                        </div>

                        <div>
                            <label class="mb-2 block text-sm font-semibold text-slate-700">Notes (optional)</label>
                            <textarea name="notes" placeholder="Any observations..." rows="3" class="w-full rounded-lg border border-slate-200 bg-white px-4 py-2.5 text-sm focus:border-numnam-400 focus:ring-1 focus:ring-numnam-400"></textarea>
                        </div>

                        <button type="submit" class="w-full rounded-lg bg-blue-500 px-4 py-2.5 font-semibold text-white transition hover:bg-blue-600">Log Milk 🍼</button>
                    </form>

                    {{-- Solid Form --}}
                    <form id="solid-form" class="log-form hidden space-y-4">
                        <input type="hidden" name="type" value="solid">

                        <div>
                            <label class="mb-2 block text-sm font-semibold text-slate-700">Food Name</label>
                            <input type="text" name="food_name" placeholder="e.g., Apple puree" required class="w-full rounded-lg border border-slate-200 bg-white px-4 py-2.5 text-sm focus:border-numnam-400 focus:ring-1 focus:ring-numnam-400">
                        </div>

                        <div>
                            <label class="mb-2 block text-sm font-semibold text-slate-700">Food Type</label>
                            <select name="food_type" required class="w-full rounded-lg border border-slate-200 bg-white px-4 py-2.5 text-sm focus:border-numnam-400 focus:ring-1 focus:ring-numnam-400">
                                <option value="">Select type</option>
                                <option value="veggie">Vegetable</option>
                                <option value="fruit">Fruit</option>
                                <option value="protein">Protein</option>
                                <option value="grain">Grain</option>
                                <option value="dairy">Dairy</option>
                                <option value="mixed">Mixed</option>
                            </select>
                        </div>

                        <div>
                            <label class="mb-2 block text-sm font-semibold text-slate-700">Texture</label>
                            <select name="texture" required class="w-full rounded-lg border border-slate-200 bg-white px-4 py-2.5 text-sm focus:border-numnam-400 focus:ring-1 focus:ring-numnam-400">
                                <option value="">Select texture</option>
                                <option value="smooth">Smooth puree</option>
                                <option value="thick">Thick puree</option>
                                <option value="mashed">Mashed</option>
                                <option value="lumpy">Lumpy</option>
                                <option value="chopped">Chopped</option>
                            </select>
                        </div>

                        <div>
                            <label class="mb-2 block text-sm font-semibold text-slate-700">Amount Finished</label>
                            <select name="finish_level" required class="w-full rounded-lg border border-slate-200 bg-white px-4 py-2.5 text-sm focus:border-numnam-400 focus:ring-1 focus:ring-numnam-400">
                                <option value="">Select amount</option>
                                <option value="all">All of it</option>
                                <option value="most">Most of it</option>
                                <option value="half">About half</option>
                                <option value="few">Just a few bites</option>
                                <option value="floor">Mostly on floor</option>
                                <option value="refused">Refused</option>
                            </select>
                        </div>

                        <div>
                            <label class="mb-2 block text-sm font-semibold text-slate-700">Notes (optional)</label>
                            <textarea name="notes" placeholder="Reactions, preferences, etc..." rows="3" class="w-full rounded-lg border border-slate-200 bg-white px-4 py-2.5 text-sm focus:border-numnam-400 focus:ring-1 focus:ring-numnam-400"></textarea>
                        </div>

                        <button type="submit" class="w-full rounded-lg bg-orange-500 px-4 py-2.5 font-semibold text-white transition hover:bg-orange-600">Log Solid 🥣</button>
                    </form>

                    {{-- Water Form --}}
                    <form id="water-form" class="log-form hidden space-y-4">
                        <input type="hidden" name="type" value="water">

                        <div>
                            <label class="mb-2 block text-sm font-semibold text-slate-700">Volume (ml)</label>
                            <input type="number" name="volume_ml" placeholder="e.g., 100" min="0" step="10" required class="w-full rounded-lg border border-slate-200 bg-white px-4 py-2.5 text-sm focus:border-numnam-400 focus:ring-1 focus:ring-numnam-400">
                        </div>

                        <div>
                            <label class="mb-2 block text-sm font-semibold text-slate-700">Notes (optional)</label>
                            <textarea name="notes" placeholder="Any observations..." rows="3" class="w-full rounded-lg border border-slate-200 bg-white px-4 py-2.5 text-sm focus:border-numnam-400 focus:ring-1 focus:ring-numnam-400"></textarea>
                        </div>

                        <button type="submit" class="w-full rounded-lg bg-cyan-500 px-4 py-2.5 font-semibold text-white transition hover:bg-cyan-600">Log Water 💧</button>
                    </form>

                    {{-- Poop Form --}}
                    <form id="poop-form" class="log-form hidden space-y-4">
                        <input type="hidden" name="type" value="poop">

                        <div>
                            <label class="mb-2 block text-sm font-semibold text-slate-700">Poop Type</label>
                            <select name="poop_type" required class="w-full rounded-lg border border-slate-200 bg-white px-4 py-2.5 text-sm focus:border-numnam-400 focus:ring-1 focus:ring-numnam-400">
                                <option value="">Select type</option>
                                <option value="Type 1 (Hard pellets)">Type 1 - Hard pellets</option>
                                <option value="Type 2 (Lumpy sausage)">Type 2 - Lumpy sausage</option>
                                <option value="Type 3 (Sausage with cracks)">Type 3 - Sausage with cracks</option>
                                <option value="Type 4 (Smooth sausage)">Type 4 - Smooth sausage</option>
                                <option value="Type 5 (Soft blobs)">Type 5 - Soft blobs</option>
                                <option value="Type 6 (Fluffy pieces)">Type 6 - Fluffy pieces</option>
                                <option value="Type 7 (Watery)">Type 7 - Watery</option>
                                <option value="Red/Undigested">Red/Undigested</option>
                                <option value="Green/Mucous">Green/Mucous</option>
                            </select>
                        </div>

                        <div>
                            <label class="mb-2 block text-sm font-semibold text-slate-700">Notes (optional)</label>
                            <textarea name="notes" placeholder="Color, smell, consistency, concerns..." rows="3" class="w-full rounded-lg border border-slate-200 bg-white px-4 py-2.5 text-sm focus:border-numnam-400 focus:ring-1 focus:ring-numnam-400"></textarea>
                        </div>

                        <button type="submit" class="w-full rounded-lg bg-amber-500 px-4 py-2.5 font-semibold text-white transition hover:bg-amber-600">Log Poop 💩</button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Today's Summary --}}
        <div>
            <div class="sticky top-32 rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="mb-6 text-lg font-bold text-slate-900">Today's Summary</h3>

                <div class="space-y-4">
                    <div class="rounded-lg border border-blue-200 bg-blue-50 p-3">
                        <p class="text-xs font-semibold text-blue-600 uppercase">Milk</p>
                        <p class="mt-1 text-2xl font-bold text-blue-900" id="summary-milk">0 ml</p>
                    </div>

                    <div class="rounded-lg border border-orange-200 bg-orange-50 p-3">
                        <p class="text-xs font-semibold text-orange-600 uppercase">Solids</p>
                        <p class="mt-1 text-2xl font-bold text-orange-900" id="summary-solids">0</p>
                    </div>

                    <div class="rounded-lg border border-cyan-200 bg-cyan-50 p-3">
                        <p class="text-xs font-semibold text-cyan-600 uppercase">Water</p>
                        <p class="mt-1 text-2xl font-bold text-cyan-900" id="summary-water">0 ml</p>
                    </div>

                    <div class="rounded-lg border border-amber-200 bg-amber-50 p-3">
                        <p class="text-xs font-semibold text-amber-600 uppercase">Poop</p>
                        <p class="mt-1 text-2xl font-bold text-amber-900" id="summary-poop">0</p>
                    </div>
                </div>

                <hr class="my-6 border-slate-200">

                <div class="space-y-2" id="logs-list">
                    <p class="text-center text-sm text-slate-500">Loading logs...</p>
                </div>
            </div>
        </div>
    </div>

    @else
    <div class="rounded-3xl border border-slate-200 bg-white p-10 text-center shadow-sm">
        <p class="text-lg text-slate-600">Please log in to use the feeding logger.</p>
        <a href="{{ route('store.login') }}" class="mt-4 inline-flex rounded-full bg-numnam-600 px-6 py-2.5 font-semibold text-white transition hover:bg-numnam-700">Log In</a>
    </div>
    @endauth
</section>

@endsection

@section('scripts')
<script>
    const typeButtons = document.querySelectorAll('.log-type-btn');
    const logForms = document.querySelectorAll('.log-form');

    // Tab switching
    typeButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            const type = this.dataset.type;

            typeButtons.forEach(b => {
                b.classList.remove('active', 'border-numnam-600', 'text-slate-900');
                b.classList.add('border-transparent', 'text-slate-500');
            });

            this.classList.add('active', 'border-numnam-600', 'text-slate-900');
            this.classList.remove('border-transparent', 'text-slate-500');

            logForms.forEach(form => form.classList.add('hidden'));
            document.getElementById(type + '-form').classList.remove('hidden');
        });
    });

    // Form submission
    logForms.forEach(form => {
        form.addEventListener('submit', async function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const data = Object.fromEntries(formData);

            try {
                const response = await fetch('/api/v1/numnam/logs', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(data)
                });

                if (response.ok) {
                    this.reset();
                    loadTodayLogs();

                    // Show success message
                    const successMsg = document.createElement('div');
                    successMsg.className = 'fixed top-4 right-4 rounded-lg bg-emerald-500 text-white px-4 py-3 shadow-lg';
                    successMsg.textContent = '✓ Log saved!';
                    document.body.appendChild(successMsg);
                    setTimeout(() => successMsg.remove(), 3000);
                } else {
                    alert('Failed to save log. Please try again.');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
            }
        });
    });

    // Load today's logs
    async function loadTodayLogs() {
        try {
            const response = await fetch('/api/v1/numnam/logs/today', {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });

            if (!response.ok) throw new Error('Failed to load logs');

            const data = await response.json();
            const logs = data.data || [];

            let milkTotal = 0,
                solidsCount = 0,
                waterTotal = 0,
                poopCount = 0;

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
            });

            document.getElementById('summary-milk').textContent = milkTotal + ' ml';
            document.getElementById('summary-solids').textContent = solidsCount;
            document.getElementById('summary-water').textContent = waterTotal + ' ml';
            document.getElementById('summary-poop').textContent = poopCount;

            // Build logs list
            const logsList = logs.slice(0, 10).map(log => `
                <div class="flex items-center justify-between rounded-lg border border-slate-200 p-2 text-sm">
                    <span>${getLogLabel(log)}</span>
                    <span class="text-xs text-slate-400">${new Date(log.logged_at).toLocaleTimeString()}</span>
                </div>
            `).join('');

            document.getElementById('logs-list').innerHTML = logsList || '<p class="text-center text-sm text-slate-500">No logs yet today</p>';
        } catch (error) {
            console.error('Error loading logs:', error);
        }
    }

    function getLogLabel(log) {
        if (log.type === 'milk') return `🍼 ${log.volume_ml}ml ${log.milk_type}`;
        if (log.type === 'solid') return `🥣 ${log.food_name} (${log.food_type})`;
        if (log.type === 'water') return `💧 ${log.volume_ml}ml water`;
        if (log.type === 'poop') return `💩 ${log.poop_type}`;
        return '📝 Log';
    }

    // Load logs on page load
    loadTodayLogs();

    // Auto-refresh every 30 seconds
    setInterval(loadTodayLogs, 30000);

    // Check URL parameter for initial type
    const urlParams = new URLSearchParams(window.location.search);
    const type = urlParams.get('type');
    if (type) {
        document.querySelector(`[data-type="${type}"]`).click();
    }
</script>
@endsection
<x-layout title="Admin Dashboard | VoltTrack">
    <main class="p-6 md:p-8 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto">
            <div class="mb-6">
                @if (session('success'))
                    <div class="mb-4 rounded-xl border border-emerald-300 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-700">
                        {{ session('success') }}
                    </div>
                @endif
                <h1 class="text-3xl md:text-4xl font-bold text-white mt-1">Admin Dashboard</h1>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-6">
                <div class="bg-white border border-slate-200 rounded-xl p-5">
                    <p class="text-xs text-slate-500 uppercase font-semibold">Total Boarding Houses</p>
                    <p class="mt-2 text-2xl font-bold text-slate-900">{{ number_format($totalBoardingHouses) }}</p>
                </div>
                <div class="bg-white border border-slate-200 rounded-xl p-5">
                    <p class="text-xs text-slate-500 uppercase font-semibold">Active Rentals</p>
                    <p class="mt-2 text-2xl font-bold text-slate-900">{{ number_format($activeRentals) }}</p>
                </div>
                <div class="bg-white border border-slate-200 rounded-xl p-5">
                    <p class="text-xs text-slate-500 uppercase font-semibold">Overdue Payments</p>
                    <p class="mt-2 text-2xl font-bold text-rose-600">{{ number_format($overduePayments) }}</p>
                </div>
                <div class="bg-white border border-slate-200 rounded-xl p-5">
                    <p id="collectedRangeLabel" class="text-xs text-slate-500 uppercase font-semibold">Collected This Month</p>
                    <p id="collectedRangeValue" class="mt-2 text-2xl font-bold text-slate-900">PHP {{ number_format($collectedMonthly, 2) }}</p>
                </div>
            </div>

            <div class="flex flex-wrap gap-2 mb-6">
                <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-semibold">
                    Approved Residents: {{ number_format($approvedResidents) }}
                </span>
                <span class="px-3 py-1 bg-amber-100 text-amber-700 rounded-full text-xs font-semibold">
                    Pending Approvals: {{ number_format($pendingApprovals) }}
                </span>
                <span class="px-3 py-1 bg-slate-200 text-slate-700 rounded-full text-xs font-semibold">
                    Pending/Overdue Bills: {{ number_format($pendingBillsCount) }}
                </span>
            </div>

            <div class="bg-white border border-slate-200 rounded-xl p-5 mb-6">
                <div class="flex flex-col gap-4 md:flex-row md:items-start md:justify-between">
                    <div>
                        <p class="text-xs text-slate-500 uppercase font-semibold">Collection Overview</p>
                        <h2 id="collectionChartTitle" class="mt-1 text-xl font-bold text-slate-900">
                            Weekly Collection
                        </h2>
                        <p id="collectionChartSubtitle" class="mt-1 text-sm text-slate-500">
                            Paid bills for the last 7 days
                        </p>
                    </div>

                    <div class="flex flex-col gap-2 sm:flex-row sm:items-center">
                        <label class="sr-only" for="collectionChartRange">Collection range</label>
                        <select
                            id="collectionChartRange"
                            class="rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm font-bold text-slate-700 outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                        >
                            <option value="daily">Daily</option>
                            <option value="weekly">Weekly</option>
                            <option value="monthly">Monthly</option>
                            <option value="yearly">Yearly</option>
                        </select>
                    </div>
                </div>

                <div class="mt-6 h-80 w-full rounded-xl border border-slate-100 bg-slate-50 p-3">
                    <canvas id="collectionChart" class="h-full w-full"></canvas>
                </div>
            </div>

            <div class="bg-white border border-slate-200 rounded-xl overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-slate-900">Pending Transactions</h2>
                    <p class="text-xs text-slate-500">Showing {{ $pending_bills->count() }} of {{ number_format($pendingBillsCount) }}</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-slate-50 text-slate-600 uppercase text-xs">
                            <tr>
                                <th class="px-5 py-3 text-left font-extrabold">
                                    Resident
                                </th>
                                <th class="px-5 py-3 text-center font-extrabold">
                                    Property
                                </th>
                                <th class="px-5 py-3 text-center font-extrabold">
                                    Utility
                                </th>
                                <th class="px-5 py-3 text-center font-extrabold">
                                    Balance
                                </th>
                                <th class="px-5 py-3 text-center font-extrabold">
                                    Reading Date
                                </th>
                                <th class="px-5 py-3 text-center font-extrabold">
                                    Status
                                </th>
                                <th class="px-5 py-3 text-right font-extrabold">
                                    Action
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($pending_bills as $bill)
                                <tr>
                                    <td class="px-5 py-4">
                                        <p class="font-semibold text-slate-900">{{ $bill->user?->first_name }} {{ $bill->user?->last_name }}</p>
                                        <p class="text-xs text-slate-500">{{ $bill->user?->email ?? 'No email' }}</p>
                                    </td>
                                    <td class="px-5 py-4 text-center">{{ $bill->user?->property?->property_unit_id ?? 'N/A' }}</td>
                                    <td class="px-5 py-4 text-center">{{ $bill->utility_type }}</td>
                                    <td class="px-5 py-4 text-center font-semibold">PHP {{ number_format($bill->total_bill, 2) }}</td>
                                    <td class="px-5 py-4 text-center">{{ $bill->reading_date?->format('M d, Y') ?? 'N/A' }}</td>
                                    <td class="px-5 py-4 text-center">
                                        <span class="px-2 py-1 rounded text-xs font-semibold {{ $bill->status === 'Overdue' ? 'bg-rose-100 text-rose-700' : 'bg-amber-100 text-amber-700' }}">
                                            {{ $bill->status }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-4 text-right">
                                        @if($bill->user_id)
                                            <a href="{{ route('admin.residentInfo', ['id' => $bill->user_id, 'highlight_bill' => $bill->id]) }}" data-instant-nav class="text-blue-600 font-semibold hover:underline">View</a>
                                        @else
                                            <span class="text-slate-400">N/A</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-5 py-8 text-center text-slate-500">
                                        No pending or overdue bills right now.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($pending_bills->hasPages())
                    <div class="flex flex-col gap-3 border-t border-slate-100 bg-slate-50/50 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
                        <p class="text-xs font-bold text-slate-400">
                            Showing
                            <span class="text-slate-600">{{ number_format($pending_bills->firstItem() ?? 0) }}-{{ number_format($pending_bills->lastItem() ?? 0) }}</span>
                            of {{ number_format($pending_bills->total()) }} transactions
                        </p>
                        <x-pagination-links :paginator="$pending_bills" />
                    </div>
                @endif
            </div>
        </div>
    </main>

    <script>
        (() => {
            if (typeof window.__adminCollectionChartCleanup === 'function') {
                window.__adminCollectionChartCleanup();
            }

            const select = document.getElementById('collectionChartRange');
            const canvas = document.getElementById('collectionChart');
            const title = document.getElementById('collectionChartTitle');
            const subtitle = document.getElementById('collectionChartSubtitle');
            const context = canvas.getContext('2d');
            const chartData = @json($collectionChart);
            const collectedRangeLabel = document.getElementById('collectedRangeLabel');
            const collectedRangeValue = document.getElementById('collectedRangeValue');

            if (!select || !canvas || !title || !subtitle || !context) {
                return;
            }

            const money = new Intl.NumberFormat('en-PH', {
                style: 'currency',
                currency: 'PHP',
                minimumFractionDigits: 2,
            });

            function resizeCanvas() {
                const ratio = window.devicePixelRatio || 1;
                const rect = canvas.getBoundingClientRect();

                canvas.width = rect.width * ratio;
                canvas.height = rect.height * ratio;
                context.setTransform(ratio, 0, 0, ratio, 0, 0);
            }

            function drawChart(range) {
                const data = chartData[range] || chartData.weekly || { title: '', subtitle: '', items: [] };
                const items = data.items || [];
                const rect = canvas.getBoundingClientRect();
                const width = rect.width;
                const height = rect.height;
                const padding = {
                    top: 22,
                    right: 18,
                    bottom: 48,
                    left: 64,
                };
                const chartWidth = width - padding.left - padding.right;
                const chartHeight = height - padding.top - padding.bottom;
                const maxValue = Math.max(...items.map((item) => Number(item.value)), 0);
                const scaleMax = maxValue === 0 ? 1 : maxValue * 1.2;
                const barGap = range === 'monthly' ? 42 : 28;
                const slotWidth = items.length ? chartWidth / items.length : chartWidth;
                const barWidth = Math.max(12, Math.min(80, slotWidth - barGap));

                title.textContent = data.title;
                subtitle.textContent = data.subtitle;
                const rangeLabelMap = {
                    daily: 'Collected Today',
                    weekly: 'Collected This Week',
                    monthly: 'Collected This Month',
                    yearly: 'Collected This Yearly Range',
                };
                if (collectedRangeLabel) {
                    collectedRangeLabel.textContent = rangeLabelMap[range] || 'Collected Amount';
                }
                if (collectedRangeValue) {
                    collectedRangeValue.textContent = money.format(Number(data.total || 0));
                }

                context.clearRect(0, 0, width, height);
                context.fillStyle = '#f8fafc';
                context.fillRect(0, 0, width, height);

                context.strokeStyle = '#e2e8f0';
                context.lineWidth = 1;
                context.fillStyle = '#64748b';
                context.font = '600 11px Inter, sans-serif';
                context.textAlign = 'right';
                context.textBaseline = 'middle';

                for (let index = 0; index <= 4; index++) {
                    const y = padding.top + (chartHeight / 4) * index;
                    const value = scaleMax - (scaleMax / 4) * index;

                    context.beginPath();
                    context.moveTo(padding.left, y);
                    context.lineTo(width - padding.right, y);
                    context.stroke();
                    context.fillText(money.format(value).replace('.00', ''), padding.left - 10, y);
                }

                if (!items.length) {
                    context.fillStyle = '#64748b';
                    context.font = '700 13px Inter, sans-serif';
                    context.textAlign = 'center';
                    context.textBaseline = 'middle';
                    context.fillText('No collection data available', padding.left + chartWidth / 2, padding.top + chartHeight / 2);
                    return;
                }

                items.forEach((item, index) => {
                    const value = Number(item.value);
                    const x = padding.left + index * slotWidth + (barGap / 2);
                    const barHeight = (value / scaleMax) * chartHeight;
                    const y = padding.top + chartHeight - barHeight;

                    context.fillStyle = '#3b82f6';
                    context.beginPath();
                    if (typeof context.roundRect === 'function') {
                        context.roundRect(x, y, barWidth, barHeight, 10);
                    } else {
                        context.rect(x, y, barWidth, barHeight);
                    }
                    context.fill();

                    context.fillStyle = '#0f172a';
                    context.font = '800 11px Inter, sans-serif';
                    context.textAlign = 'center';
                    context.textBaseline = 'bottom';

                    if (value > 0) {
                        context.fillText(money.format(value).replace('.00', ''), x + barWidth / 2, y - 8);
                    }

                    context.fillStyle = '#475569';
                    context.font = '700 11px Inter, sans-serif';
                    context.textBaseline = 'top';
                    context.fillText(item.label, x + barWidth / 2, padding.top + chartHeight + 16);
                });
            }

            function render() {
                resizeCanvas();
                drawChart(select.value);
            }

            select.addEventListener('change', render);
            window.addEventListener('resize', render);
            render();

            window.__adminCollectionChartCleanup = () => {
                select.removeEventListener('change', render);
                window.removeEventListener('resize', render);
            };
        })();
    </script>
</x-layout>

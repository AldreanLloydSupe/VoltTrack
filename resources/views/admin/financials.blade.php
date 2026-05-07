<x-layout title="Financials | VoltTrack">
    <main class="min-h-screen bg-slate-50 p-6 md:p-8">
        <div class="mx-auto max-w-7xl">
            <div class="mb-6">
                @if (session('success'))
                    <div class="mb-4 rounded-xl border border-emerald-300 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-700">
                        {{ session('success') }}
                    </div>
                @endif

                @if (! $canSaveSettings)
                    <div class="mb-4 rounded-xl border border-amber-300 bg-amber-50 px-4 py-3 text-sm font-semibold text-amber-700">
                        Run the financial settings migration before saving service fee defaults.
                    </div>
                @endif

                <p class="text-xs font-bold uppercase tracking-[0.2em] text-slate-200">Utilities</p>
                <h1 class="mt-1 text-3xl font-black text-white md:text-4xl">Financials</h1>
                <p class="mt-2 max-w-2xl text-sm text-slate-200">
                    Track service fee income from paid bills, monitor paid penalty and VAT collections, and set billing defaults.
                </p>
            </div>

            <div class="mb-6 grid grid-cols-1 gap-4 md:grid-cols-3">
                <section class="rounded-xl border border-slate-200 bg-white p-5">
                    <p class="text-xs font-semibold uppercase text-slate-500">Service Fee Collected This Month</p>
                    <p class="mt-2 text-3xl font-black text-slate-900">PHP {{ number_format($serviceFeeCollected, 2) }}</p>
                    <p class="mt-2 text-xs font-semibold text-slate-400">Paid bills only. Resets each month.</p>
                </section>

                <section class="rounded-xl border border-slate-200 bg-white p-5">
                    <p class="text-xs font-semibold uppercase text-slate-500">Penalty Fee Collected This Month</p>
                    <p class="mt-2 text-3xl font-black text-rose-600">PHP {{ number_format($penaltyCollected, 2) }}</p>
                    <p class="mt-2 text-xs font-semibold text-slate-400">Paid bills only. Resets each month.</p>
                </section>

                <section class="rounded-xl border border-slate-200 bg-white p-5">
                    <p class="text-xs font-semibold uppercase text-slate-500">VAT Collected This Month</p>
                    <p class="mt-2 text-3xl font-black text-emerald-600">PHP {{ number_format($vatCollected, 2) }}</p>
                    <p class="mt-2 text-xs font-semibold text-slate-400">12% VAT from paid bills. Resets each month.</p>
                </section>
            </div>

            <section class="mb-6 rounded-xl border border-slate-200 bg-white p-5">
                <div class="flex flex-col gap-2 md:flex-row md:items-start md:justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase text-slate-500">Service Fee Income</p>
                        <h2 id="financialChartTitle" class="mt-1 text-xl font-bold text-slate-900">{{ $monthlyServiceFeeChart['title'] }}</h2>
                        <p id="financialChartSubtitle" class="mt-1 text-sm text-slate-500">{{ $monthlyServiceFeeChart['subtitle'] }}</p>
                    </div>
                    <div class="rounded-xl bg-blue-50 px-4 py-3 text-right">
                        <p class="text-[10px] font-black uppercase tracking-[0.18em] text-blue-600">12 Month Combined Total</p>
                        <p id="financialChartTotal" class="mt-1 text-lg font-black text-[#1e3a8a]">PHP {{ number_format($monthlyServiceFeeChart['total'], 2) }}</p>
                    </div>
                </div>

                <div class="mt-5 flex flex-wrap gap-3 text-xs font-bold text-slate-600">
                    <span class="inline-flex items-center gap-2 rounded-full bg-blue-50 px-3 py-1.5">
                        <span class="h-2.5 w-2.5 rounded-full bg-[#3b82f6]"></span>
                        Service Fee: PHP {{ number_format($monthlyServiceFeeChart['serviceFeeTotal'], 2) }}
                    </span>
                    <span class="inline-flex items-center gap-2 rounded-full bg-rose-50 px-3 py-1.5">
                        <span class="h-2.5 w-2.5 rounded-full bg-[#e11d48]"></span>
                        Penalty: PHP {{ number_format($monthlyServiceFeeChart['penaltyTotal'], 2) }}
                    </span>
                    <span class="inline-flex items-center gap-2 rounded-full bg-emerald-50 px-3 py-1.5">
                        <span class="h-2.5 w-2.5 rounded-full bg-[#059669]"></span>
                        VAT: PHP {{ number_format($monthlyServiceFeeChart['vatTotal'], 2) }}
                    </span>
                </div>

                <div class="mt-6 h-80 w-full rounded-xl border border-slate-100 bg-slate-50 p-3">
                    <canvas id="financialServiceFeeChart" class="h-full w-full"></canvas>
                </div>
            </section>

            <section class="rounded-xl border border-slate-200 bg-white p-5">
                <div class="mb-5">
                    <p class="text-xs font-semibold uppercase text-slate-500">Billing Defaults</p>
                    <h2 class="mt-1 text-xl font-bold text-slate-900">Service Fees and Rate Calculators</h2>
                </div>

                <form id="financial-settings-form" method="POST" action="{{ route('admin.financials.update') }}" class="grid grid-cols-1 gap-4 md:grid-cols-[1fr_1fr_auto] md:items-end">
                    @csrf
                    <input id="electricity-price-per-unit-input" type="hidden" name="electricity_price_per_unit" value="{{ old('electricity_price_per_unit', $electricityPricePerUnit) }}">
                    <input id="water-price-per-unit-input" type="hidden" name="water_price_per_unit" value="{{ old('water_price_per_unit', $waterPricePerUnit) }}">

                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-700">Electricity Service Fee</label>
                        <input
                            type="number"
                            step="0.01"
                            min="0"
                            name="electricity_service_fee"
                            value="{{ old('electricity_service_fee', $electricityServiceFee) }}"
                            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-slate-700"
                            required
                        >
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-700">Water Service Fee</label>
                        <input
                            type="number"
                            step="0.01"
                            min="0"
                            name="water_service_fee"
                            value="{{ old('water_service_fee', $waterServiceFee) }}"
                            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-slate-700"
                            required
                        >
                    </div>

                    <button
                        type="submit"
                        class="rounded-lg bg-[#001D4E] px-5 py-2.5 text-sm font-bold text-white transition-colors hover:bg-blue-900 disabled:cursor-not-allowed disabled:bg-slate-400"
                        @disabled(! $canSaveSettings)
                    >
                        Save Defaults
                    </button>
                </form>

                <div class="mt-6 grid grid-cols-1 gap-5 lg:grid-cols-2">
                    <div class="rounded-xl border border-slate-200 bg-slate-50 p-5">
                        <p class="text-xs font-black uppercase tracking-[0.18em] text-slate-500">Electricity Price Calculator</p>
                        <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div>
                                <label class="mb-2 block text-sm font-semibold text-slate-700">Overall Electricity Bill</label>
                                <input id="electric-total-bill" type="number" step="0.01" min="0" class="w-full rounded-lg border border-slate-300 px-3 py-2" placeholder="PHP 0.00">
                            </div>
                            <div>
                                <label class="mb-2 block text-sm font-semibold text-slate-700">Total kWh Used</label>
                                <input id="electric-total-units" type="number" step="0.01" min="0" class="w-full rounded-lg border border-slate-300 px-3 py-2" placeholder="0.00 kWh">
                            </div>
                        </div>
                        <div class="mt-4 rounded-lg bg-white px-4 py-3">
                            <p class="text-[10px] font-black uppercase tracking-[0.18em] text-slate-400">Price Per kWh</p>
                            <p id="electric-price-result" class="mt-1 text-2xl font-black text-[#1e3a8a]">PHP {{ number_format($electricityPricePerUnit, 2) }}</p>
                            <p class="mt-1 text-xs font-semibold text-slate-400">This value is saved with the fees and used in new electricity bills.</p>
                        </div>
                    </div>

                    <div class="rounded-xl border border-slate-200 bg-slate-50 p-5">
                        <p class="text-xs font-black uppercase tracking-[0.18em] text-slate-500">Water Price Calculator</p>
                        <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div>
                                <label class="mb-2 block text-sm font-semibold text-slate-700">Overall Water Bill</label>
                                <input id="water-total-bill" type="number" step="0.01" min="0" class="w-full rounded-lg border border-slate-300 px-3 py-2" placeholder="PHP 0.00">
                            </div>
                            <div>
                                <label class="mb-2 block text-sm font-semibold text-slate-700">Total m3 Used</label>
                                <input id="water-total-units" type="number" step="0.01" min="0" class="w-full rounded-lg border border-slate-300 px-3 py-2" placeholder="0.00 m3">
                            </div>
                        </div>
                        <div class="mt-4 rounded-lg bg-white px-4 py-3">
                            <p class="text-[10px] font-black uppercase tracking-[0.18em] text-slate-400">Price Per m3</p>
                            <p id="water-price-result" class="mt-1 text-2xl font-black text-[#1e3a8a]">PHP {{ number_format($waterPricePerUnit, 2) }}</p>
                            <p class="mt-1 text-xs font-semibold text-slate-400">This value is saved with the fees and used in new water bills.</p>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </main>

    <script>
        (() => {
            if (typeof window.__financialChartCleanup === 'function') {
                window.__financialChartCleanup();
            }

            const canvas = document.getElementById('financialServiceFeeChart');
            const context = canvas?.getContext('2d');
            const chartData = @json($monthlyServiceFeeChart);
            const money = new Intl.NumberFormat('en-PH', {
                style: 'currency',
                currency: 'PHP',
                minimumFractionDigits: 2,
            });

            const calculateRate = (billId, unitsId, resultId, savedInputId) => {
                const billInput = document.getElementById(billId);
                const unitsInput = document.getElementById(unitsId);
                const result = document.getElementById(resultId);
                const savedInput = document.getElementById(savedInputId);

                if (!billInput || !unitsInput || !result || !savedInput) {
                    return () => {};
                }

                const update = () => {
                    const totalBill = Number(billInput.value || 0);
                    const totalUnits = Number(unitsInput.value || 0);
                    const existingRate = Number(savedInput.value || 0);
                    const rate = totalUnits > 0 ? totalBill / totalUnits : existingRate;
                    savedInput.value = rate.toFixed(2);
                    result.textContent = money.format(rate);
                };

                billInput.addEventListener('input', update);
                unitsInput.addEventListener('input', update);
                update();

                return () => {
                    billInput.removeEventListener('input', update);
                    unitsInput.removeEventListener('input', update);
                };
            };

            const cleanupElectric = calculateRate('electric-total-bill', 'electric-total-units', 'electric-price-result', 'electricity-price-per-unit-input');
            const cleanupWater = calculateRate('water-total-bill', 'water-total-units', 'water-price-result', 'water-price-per-unit-input');

            if (!canvas || !context) {
                window.__financialChartCleanup = () => {
                    cleanupElectric();
                    cleanupWater();
                };
                return;
            }

            function resizeCanvas() {
                const ratio = window.devicePixelRatio || 1;
                const rect = canvas.getBoundingClientRect();

                canvas.width = rect.width * ratio;
                canvas.height = rect.height * ratio;
                context.setTransform(ratio, 0, 0, ratio, 0, 0);
            }

            function drawChart() {
                const items = chartData.items || [];
                const rect = canvas.getBoundingClientRect();
                const width = rect.width;
                const height = rect.height;
                const padding = {
                    top: 22,
                    right: 18,
                    bottom: 50,
                    left: 64,
                };
                const chartWidth = width - padding.left - padding.right;
                const chartHeight = height - padding.top - padding.bottom;
                const maxValue = Math.max(
                    ...items.flatMap((item) => [Number(item.service_fee || 0), Number(item.penalty || 0), Number(item.vat || 0)]),
                    0
                );
                const scaleMax = maxValue === 0 ? 1 : maxValue * 1.2;
                const slotWidth = items.length ? chartWidth / items.length : chartWidth;
                const barWidth = Math.max(8, Math.min(20, (slotWidth - 30) / 3));
                const groupWidth = (barWidth * 3) + 12;

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
                    context.fillText('No service fee income yet', padding.left + chartWidth / 2, padding.top + chartHeight / 2);
                    return;
                }

                const drawBar = (x, value, color) => {
                    const barHeight = (value / scaleMax) * chartHeight;
                    const y = padding.top + chartHeight - barHeight;

                    context.fillStyle = color;
                    context.beginPath();
                    if (typeof context.roundRect === 'function') {
                        context.roundRect(x, y, barWidth, barHeight, 8);
                    } else {
                        context.rect(x, y, barWidth, barHeight);
                    }
                    context.fill();

                    if (value > 0) {
                        context.fillStyle = '#0f172a';
                        context.font = '800 10px Inter, sans-serif';
                        context.textAlign = 'center';
                        context.textBaseline = 'bottom';
                        context.fillText(money.format(value).replace('.00', ''), x + barWidth / 2, y - 6);
                    }
                };

                items.forEach((item, index) => {
                    const serviceFeeValue = Number(item.service_fee || 0);
                    const penaltyValue = Number(item.penalty || 0);
                    const vatValue = Number(item.vat || 0);
                    const groupX = padding.left + index * slotWidth + (slotWidth - groupWidth) / 2;

                    drawBar(groupX, serviceFeeValue, '#3b82f6');
                    drawBar(groupX + barWidth + 6, penaltyValue, '#e11d48');
                    drawBar(groupX + (barWidth + 6) * 2, vatValue, '#059669');

                    context.fillStyle = '#475569';
                    context.font = '700 10px Inter, sans-serif';
                    context.textAlign = 'center';
                    context.textBaseline = 'top';
                    context.fillText(item.label, groupX + groupWidth / 2, padding.top + chartHeight + 16);
                });
            }

            function render() {
                resizeCanvas();
                drawChart();
            }

            window.addEventListener('resize', render);
            render();

            window.__financialChartCleanup = () => {
                window.removeEventListener('resize', render);
                cleanupElectric();
                cleanupWater();
            };
        })();
    </script>
</x-layout>

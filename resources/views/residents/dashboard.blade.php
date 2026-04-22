<x-layout title="Resident Dashboard | VoltTrack">
    @php
        $electricBars = $electricBill
            ? collect([0.42, 0.58, 0.31, 0.64, 0.43, 0.71, 0.88])
            : collect([0.42, 0.58, 0.31, 0.64, 0.43, 0.71, 0.88]);
        $waterBars = $waterBill
            ? collect([0.63, 0.42, 0.67, 0.54, 0.89, 0.28, 0.41])
            : collect([0.63, 0.42, 0.67, 0.54, 0.89, 0.28, 0.41]);
        $displayBills = $bills->isNotEmpty()
            ? $bills
            : collect([
                (object) [
                    'payment_reference' => '#INV-882190',
                    'billing_period_start' => now()->subMonths(1)->startOfMonth(),
                    'billing_period_end' => now()->subMonths(1)->endOfMonth(),
                    'total_bill' => 241.10,
                    'status' => 'Paid',
                ],
                (object) [
                    'payment_reference' => '#INV-881044',
                    'billing_period_start' => now()->subMonths(2)->startOfMonth(),
                    'billing_period_end' => now()->subMonths(2)->endOfMonth(),
                    'total_bill' => 228.45,
                    'status' => 'Paid',
                ],
                (object) [
                    'payment_reference' => '#INV-879912',
                    'billing_period_start' => now()->subMonths(3)->startOfMonth(),
                    'billing_period_end' => now()->subMonths(3)->endOfMonth(),
                    'total_bill' => 215.12,
                    'status' => 'Paid',
                ],
                (object) [
                    'payment_reference' => '#INV-878201',
                    'billing_period_start' => now()->subMonths(4)->startOfMonth(),
                    'billing_period_end' => now()->subMonths(4)->endOfMonth(),
                    'total_bill' => 267.00,
                    'status' => 'Pending',
                ],
                (object) [
                    'payment_reference' => '#INV-876110',
                    'billing_period_start' => now()->subMonths(5)->startOfMonth(),
                    'billing_period_end' => now()->subMonths(5)->endOfMonth(),
                    'total_bill' => 233.15,
                    'status' => 'Paid',
                ],
            ]);
    @endphp

    <div class="min-h-screen bg-[#eef2f7]">
        <header class="border-b border-[#2f8cff]/40 bg-[#1b2d73] px-6 py-3 text-white shadow-[0_6px_30px_rgba(10,28,74,0.2)]">
            <div class="mx-auto flex max-w-7xl items-center justify-between">
                <div>
                    <h1 class="text-4xl font-black tracking-tight">VoltTrack</h1>
                </div>

                <div class="flex items-center gap-4">
                    <div class="text-right">
                        <p class="text-sm font-semibold">{{ $user->first_name }} {{ $user->last_name }}</p>
                        <p class="text-xs uppercase tracking-[0.22em] text-blue-100">Resident</p>
                    </div>

                    <div class="flex h-12 w-12 items-center justify-center rounded-full border-2 border-white/25 bg-white/15 text-lg font-bold">
                        {{ strtoupper(substr($user->first_name, 0, 1)) }}
                    </div>

                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="rounded-full border border-white/20 px-4 py-2 text-xs font-bold uppercase tracking-[0.2em] text-white transition-all hover:bg-white/10">
                            Log Out
                        </button>
                    </form>
                </div>
            </div>
        </header>

        <main class="mx-auto grid max-w-7xl gap-6 px-6 py-6 lg:grid-cols-[minmax(0,2fr)_320px]">
            <section class="overflow-hidden rounded-[28px] border border-slate-200 bg-white shadow-[0_20px_70px_rgba(15,23,42,0.08)]">
                <div class="grid border-b border-slate-200 lg:grid-cols-2">
                    <div class="border-b border-slate-200 p-6 lg:border-b-0 lg:border-r">
                        <div class="mb-4 flex items-start justify-between">
                            <div>
                                <p class="text-[11px] font-black uppercase tracking-[0.22em] text-slate-400">Electricity</p>
                                <div class="mt-1 flex items-end gap-2">
                                    <span class="text-4xl font-black tracking-tight text-[#1846c0]">{{ number_format($electricBill?->consumption ?? 10.24, 2) }}</span>
                                    <span class="pb-1 text-xs font-bold uppercase tracking-[0.2em] text-slate-500">kWh</span>
                                </div>
                            </div>
                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#e7ecff] text-[#1846c0] shadow-inner">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 2L6 14h5l-1 8 7-12h-5l1-8z" />
                                </svg>
                            </div>
                        </div>

                        <div class="mt-8 flex h-28 items-end gap-2">
                            @foreach ($electricBars as $bar)
                                <div
                                    class="flex-1 rounded-t-[4px] {{ $loop->last ? 'bg-[#1846c0]' : 'bg-[#9fb6ef]' }}"
                                    style="height: {{ $bar * 100 }}%;"
                                ></div>
                            @endforeach
                        </div>
                    </div>

                    <div class="p-6">
                        <div class="mb-4 flex items-start justify-between">
                            <div>
                                <p class="text-[11px] font-black uppercase tracking-[0.22em] text-slate-400">Water</p>
                                <div class="mt-1 flex items-end gap-2">
                                    <span class="text-4xl font-black tracking-tight text-[#334155]">{{ number_format($waterBill?->consumption ?? 12.80, 1) }}</span>
                                    <span class="pb-1 text-xs font-bold uppercase tracking-[0.2em] text-slate-500">m³</span>
                                </div>
                            </div>
                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#edf2ff] text-[#64748b] shadow-inner">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3s5 5.2 5 9a5 5 0 11-10 0c0-3.8 5-9 5-9z" />
                                </svg>
                            </div>
                        </div>

                        <div class="mt-8 flex h-28 items-end gap-2">
                            @foreach ($waterBars as $bar)
                                <div
                                    class="flex-1 rounded-t-[4px] {{ $loop->last ? 'bg-[#627194]' : 'bg-[#c9d2e6]' }}"
                                    style="height: {{ $bar * 100 }}%;"
                                ></div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="p-6">
                    <div class="mb-4 flex items-center justify-between">
                        <div>
                            <h2 class="text-sm font-black uppercase tracking-[0.2em] text-slate-500">Payment History</h2>
                            <p class="mt-1 text-sm text-slate-400">Latest billing activity for your account.</p>
                        </div>
                        <a href="#" class="text-xs font-black uppercase tracking-[0.18em] text-[#1846c0] hover:underline">View All</a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full min-w-[680px] border-collapse text-left">
                            <thead>
                                <tr class="border-b border-slate-200 text-[11px] font-black uppercase tracking-[0.18em] text-slate-400">
                                    <th class="py-3 pr-4">Reference ID</th>
                                    <th class="px-4 py-3">Billing Period</th>
                                    <th class="px-4 py-3">Amount</th>
                                    <th class="px-4 py-3">Status</th>
                                    <th class="py-3 pl-4 text-right">Slip</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($displayBills as $bill)
                                    @php
                                        $status = strtolower($bill->status);
                                        $statusClasses = match ($status) {
                                            'paid' => 'text-emerald-500',
                                            'overdue' => 'text-red-500',
                                            default => 'text-amber-500',
                                        };
                                    @endphp
                                    <tr class="border-b border-slate-100 text-sm text-slate-700 last:border-b-0">
                                        <td class="py-4 pr-4 font-semibold text-slate-600">{{ $bill->payment_reference ?: '#INV-' . str_pad((string) $loop->iteration, 6, '0', STR_PAD_LEFT) }}</td>
                                        <td class="px-4 py-4">
                                            {{ \Illuminate\Support\Carbon::parse($bill->billing_period_start)->format('M d') }}
                                            -
                                            {{ \Illuminate\Support\Carbon::parse($bill->billing_period_end)->format('M d, Y') }}
                                        </td>
                                        <td class="px-4 py-4 font-bold text-slate-900">₱{{ number_format($bill->total_bill, 2) }}</td>
                                        <td class="px-4 py-4">
                                            <span class="text-[11px] font-black uppercase tracking-[0.16em] {{ $statusClasses }}">
                                                {{ $bill->status }}
                                            </span>
                                        </td>
                                        <td class="py-4 pl-4 text-right">
                                            <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 text-slate-400">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17h6M9 13h6M9 9h6M7 3h7l3 3v15H7z" />
                                                </svg>
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            <aside class="grid gap-6">
                <section class="overflow-hidden rounded-[28px] bg-[#1846c0] text-white shadow-[0_24px_60px_rgba(24,70,192,0.28)]">
                    <div class="border-t-4 border-[#33a6ff] px-8 py-9">
                        <p class="text-[11px] font-black uppercase tracking-[0.26em] text-blue-100">
                            Next Due Date: {{ $latestBill ? \Illuminate\Support\Carbon::parse($latestBill->billing_period_end)->format('M d, Y') : now()->addDays(6)->format('M d, Y') }}
                        </p>
                        <p class="mt-3 text-5xl font-black tracking-tight">₱{{ number_format($totalDue, 2) }}</p>

                        <div class="mt-8 space-y-3 text-sm">
                            <div class="flex items-center justify-between border-b border-white/10 pb-2">
                                <span class="text-blue-100">Electricity Fixed</span>
                                <span class="font-semibold">₱{{ number_format($electricFixed, 2) }}</span>
                            </div>
                            <div class="flex items-center justify-between border-b border-white/10 pb-2">
                                <span class="text-blue-100">Usage Variable</span>
                                <span class="font-semibold">₱{{ number_format($electricVariable, 2) }}</span>
                            </div>
                            <div class="flex items-center justify-between pb-2">
                                <span class="text-blue-100">Service Fee</span>
                                <span class="font-semibold">₱{{ number_format($waterServiceFee, 2) }}</span>
                            </div>
                        </div>

                        <button class="mt-10 w-full rounded-full border border-white/60 px-5 py-4 text-sm font-bold tracking-[0.16em] text-white transition-all hover:bg-white/10">
                            View Billing History
                        </button>
                    </div>
                </section>

                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-1 xl:grid-cols-2">
                    <section class="rounded-[24px] bg-white p-6 shadow-[0_16px_50px_rgba(15,23,42,0.08)]">
                        <p class="text-[11px] font-black uppercase tracking-[0.22em] text-slate-400">Peak Demand</p>
                        <p class="mt-6 text-3xl font-black text-[#1846c0]">{{ number_format($peakDemand, 1) }} <span class="text-sm uppercase tracking-[0.2em] text-slate-500">kW</span></p>
                        <div class="mt-8 h-1.5 rounded-full bg-slate-200">
                            <div class="h-1.5 rounded-full bg-[#1846c0]" style="width: {{ min(100, max(28, $peakDemand * 4.5)) }}%"></div>
                        </div>
                    </section>

                    <section class="rounded-[24px] bg-white p-6 shadow-[0_16px_50px_rgba(15,23,42,0.08)]">
                        <p class="text-[11px] font-black uppercase tracking-[0.22em] text-slate-400">Sustainability Score</p>
                        <p class="mt-6 text-3xl font-black text-[#d94841]">{{ $sustainabilityScore }}/100</p>
                        <div class="mt-8 flex gap-2">
                            @for ($i = 1; $i <= 5; $i++)
                                <div class="h-1.5 flex-1 rounded-full {{ $i <= ceil($sustainabilityScore / 20) ? 'bg-[#1846c0]' : 'bg-slate-200' }}"></div>
                            @endfor
                        </div>
                    </section>
                </div>
            </aside>
        </main>
    </div>
</x-layout>

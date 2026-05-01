<x-layout title="Resident Dashboard | VoltTrack" :show-admin-nav="false">
    @php
        $electricBars = $electricBill
            ? collect([0.42, 0.58, 0.31, 0.64, 0.43, 0.71, 0.88])
            : collect([0, 0, 0, 0, 0, 0, 0]);
        $waterBars = $waterBill
            ? collect([0.63, 0.42, 0.67, 0.54, 0.89, 0.28, 0.41])
            : collect([0, 0, 0, 0, 0, 0, 0]);
    @endphp

    <div class="min-h-screen bg-[#eef2f7]">
        <header class="border-b border-[#2f8cff]/40 bg-[#1b2d73] px-6 py-3 text-white shadow-[0_6px_30px_rgba(10,28,74,0.2)]">
            <div class="mx-auto flex max-w-7xl items-center justify-between">
                <div>
                    <h1 class="text-4xl font-black tracking-tight">VoltTrack</h1>
                </div>

                <div class="flex items-center gap-4">
                    <details class="relative">
                        <summary class="list-none cursor-pointer">
                            <div class="relative flex h-11 w-11 items-center justify-center rounded-full border border-white/20 bg-white/10 transition-all hover:bg-white/15">
                                <i class="fas fa-bell text-sm text-white"></i>
                                @if(($adminReplies ?? collect())->isNotEmpty())
                                    <span class="absolute -right-1 -top-1 inline-flex min-w-5 items-center justify-center rounded-full bg-emerald-400 px-1.5 py-0.5 text-[10px] font-black text-[#0f172a]">
                                        {{ ($adminReplies ?? collect())->count() }}
                                    </span>
                                @endif
                            </div>
                        </summary>

                        <div class="absolute right-0 z-30 mt-3 w-96 rounded-xl border border-slate-200 bg-white text-slate-900 shadow-2xl">
                            <div class="border-b border-slate-100 px-4 py-3">
                                <p class="text-sm font-bold">Admin Replies</p>
                                <p class="text-xs text-slate-500">Latest {{ ($adminReplies ?? collect())->count() }}</p>
                            </div>

                            <div class="max-h-96 overflow-y-auto">
                                @forelse(($adminReplies ?? collect()) as $reply)
                                    <button
                                        type="button"
                                        class="resident-reply-open block w-full border-b border-slate-100 px-4 py-3 text-left transition-colors last:border-b-0 hover:bg-slate-50"
                                        data-subject="{{ $reply->subject }}"
                                        data-admin="{{ trim(($reply->repliedBy?->first_name ?? 'Admin') . ' ' . ($reply->repliedBy?->last_name ?? '')) }}"
                                        data-sent="{{ $reply->replied_at?->format('M d, Y g:i A') ?? 'N/A' }}"
                                        data-message="{{ $reply->message }}"
                                        data-reply="{{ $reply->reply_message }}"
                                        data-delete-url="{{ route('notifications.destroy', $reply) }}"
                                    >
                                        <span class="block text-sm font-semibold text-slate-900">{{ $reply->subject }}</span>
                                        <span class="mt-1 block text-xs text-slate-500">
                                            From {{ $reply->repliedBy?->first_name ?? 'Admin' }} {{ $reply->repliedBy?->last_name }} &bull; {{ $reply->replied_at?->diffForHumans() }}
                                        </span>
                                        <span class="mt-2 block line-clamp-2 text-sm text-slate-700 whitespace-pre-line">{{ $reply->reply_message }}</span>
                                    </button>
                                @empty
                                    <div class="px-4 py-6 text-center text-sm text-slate-500">
                                        No admin replies yet.
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </details>

                    <details class="group relative">
                        <summary class="flex cursor-pointer list-none items-center gap-3 rounded-full border border-white/15 px-3 py-1.5 transition-all hover:bg-white/10">
                            <div class="text-right">
                                <p class="text-sm font-semibold">{{ $user->first_name }} {{ $user->last_name }}</p>
                                <p class="text-xs uppercase tracking-[0.22em] text-blue-100">Resident</p>
                            </div>

                            <div class="h-12 w-12 overflow-hidden rounded-full border-2 border-white/25 bg-white">
                                <img src="{{ asset('image/profile.png') }}" alt="Profile logo" class="h-full w-full object-cover">
                            </div>
                        </summary>

                        <div class="absolute right-0 z-20 mt-2 w-44 overflow-hidden rounded-xl border border-slate-200 bg-white py-1 text-sm shadow-xl">
                            <a href="{{ route('resident.contactAdmin') }}" class="block px-4 py-2.5 font-semibold text-slate-700 transition-colors hover:bg-slate-50">
                                Contact Admin
                            </a>
                            <a href="{{ route('resident.settings') }}" class="block px-4 py-2.5 font-semibold text-slate-700 transition-colors hover:bg-slate-50">
                                Settings
                            </a>
                        </div>
                    </details>

                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="rounded-full border border-white/20 px-4 py-2 text-xs font-bold uppercase tracking-[0.2em] text-white transition-all hover:bg-white/10">
                            Log Out
                        </button>
                    </form>
                </div>
            </div>
        </header>

        <div id="resident-reply-modal" class="fixed inset-0 z-[80] hidden bg-slate-950/60 px-4 py-10">
            <div class="absolute inset-0" data-resident-reply-close></div>
            <div class="relative mx-auto mt-10 max-h-[86vh] w-full max-w-2xl overflow-y-auto rounded-2xl bg-white text-slate-900 shadow-2xl">
                <div class="sticky top-0 z-10 flex items-start justify-between gap-4 border-b border-slate-100 bg-white px-6 py-5">
                    <div>
                        <p class="text-[11px] font-black uppercase tracking-[0.2em] text-slate-400">Admin Reply</p>
                        <h2 id="resident-reply-subject" class="mt-1 text-xl font-black text-[#1e3a8a]"></h2>
                        <p id="resident-reply-meta" class="mt-1 text-xs font-semibold text-slate-400"></p>
                    </div>
                    <button type="button" data-resident-reply-close class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full border border-slate-200 text-slate-400 transition-colors hover:bg-slate-50 hover:text-slate-700" aria-label="Close reply">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <div class="px-6 py-5">
                    <p class="text-[10px] font-black uppercase tracking-[0.18em] text-slate-400">Your Message</p>
                    <div id="resident-reply-original" class="mt-2 rounded-xl bg-slate-50 p-4 text-sm leading-6 text-slate-700 whitespace-pre-line"></div>

                    <p class="mt-5 text-[10px] font-black uppercase tracking-[0.18em] text-blue-700">Admin Answer</p>
                    <div id="resident-reply-answer" class="mt-2 rounded-xl border border-blue-100 bg-blue-50 p-4 text-sm leading-6 text-slate-700 whitespace-pre-line"></div>

                    <div class="mt-5 flex flex-wrap justify-end gap-3">
                        <form id="resident-reply-delete-form" method="POST" onsubmit="return confirm('Delete this message permanently?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="rounded-lg border border-red-200 px-5 py-2.5 text-xs font-black uppercase tracking-[0.16em] text-red-600 transition-colors hover:bg-red-50">
                                Delete
                            </button>
                        </form>
                        <a href="{{ route('resident.contactAdmin') }}" class="rounded-lg bg-[#001D4E] px-5 py-2.5 text-xs font-black uppercase tracking-[0.16em] text-white transition-colors hover:bg-[#163571]">
                            Reply Back
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <main class="mx-auto grid max-w-7xl gap-6 px-6 py-6 lg:grid-cols-[minmax(0,2fr)_320px]">
            <section class="overflow-hidden rounded-[28px] border border-slate-200 bg-white shadow-[0_20px_70px_rgba(15,23,42,0.08)]">
                <div class="grid border-b border-slate-200 lg:grid-cols-2">
                    <div class="border-b border-slate-200 p-6 lg:border-b-0 lg:border-r">
                        <div class="mb-4 flex items-start justify-between">
                            <div>
                                <p class="text-[11px] font-black uppercase tracking-[0.22em] text-slate-400">Electricity</p>
                                <div class="mt-1 flex items-end gap-2">
                                    <span class="text-4xl font-black tracking-tight text-[#1846c0]">PHP {{ number_format($electricBill?->consumption ?? 0, 2) }}</span>
                                    <span class="pb-1 text-xs font-bold uppercase tracking-[0.2em] text-slate-500"> per kWh</span>
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
                                    <span class="text-4xl font-black tracking-tight text-[#334155]">PHP {{ number_format($waterBill?->consumption ?? 0, 1) }}</span>
                                    <span class="pb-1 text-xs font-bold uppercase tracking-[0.2em] text-slate-500">per m3</span>
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
                            <p class="mt-1 text-sm text-slate-400">This will update once admin records your bills and payments.</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <button
                                type="button"
                                id="filter-all-utilities"
                                class="rounded-lg border border-slate-200 px-3 py-1.5 text-[10px] font-black uppercase tracking-[0.16em] text-slate-600 transition-colors hover:bg-slate-50"
                            >
                                All Utilities
                            </button>
                            <button
                                type="button"
                                id="filter-last-month"
                                class="rounded-lg border border-slate-200 px-3 py-1.5 text-[10px] font-black uppercase tracking-[0.16em] text-slate-600 transition-colors hover:bg-slate-50"
                            >
                                Last Month
                            </button>
                        </div>
                        @if ($bills->isNotEmpty())
                            <a href="{{ route('resident.history') }}" class="text-xs font-black uppercase tracking-[0.18em] text-[#1846c0] hover:underline">
                                View All
                            </a>
                        @endif
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
                                @forelse ($bills as $bill)
                                    @php
                                        $status = strtolower($bill->status);
                                        $statusClasses = match ($status) {
                                            'paid' => 'text-emerald-500',
                                            'overdue' => 'text-red-500',
                                            default => 'text-amber-500',
                                        };
                                        $receiptBaseBill = (float) $bill->consumption * (float) $bill->price_per_unit;
                                        $receiptServiceFee = (float) $bill->service_fee;
                                        $receiptSubtotal = (float) ($bill->base_total_bill ?? $bill->total_bill);
                                        $receiptPenalty = max((float) $bill->total_bill - $receiptSubtotal, 0);
                                        $receiptVat = $receiptSubtotal * 0.12;
                                        $receiptTotal = (float) $bill->total_bill + $receiptVat;
                                        $receiptUnitLabel = $bill->utility_type === 'Electricity' ? 'kWh' : 'm3';
                                    @endphp
                                    <tr class="border-b border-slate-100 text-sm text-slate-700 last:border-b-0">
                                        <td class="py-4 pr-4 font-semibold text-slate-600">{{ $bill->payment_reference ?: 'No reference yet' }}</td>
                                        <td class="px-4 py-4">
                                            {{ \Illuminate\Support\Carbon::parse($bill->billing_period_start)->format('M d') }}
                                            -
                                            {{ \Illuminate\Support\Carbon::parse($bill->billing_period_end)->format('M d, Y') }}
                                            <span
                                                class="hidden receipt-filter-data"
                                                data-utility="{{ $bill->utility_type }}"
                                                data-billing-end="{{ \Illuminate\Support\Carbon::parse($bill->billing_period_end)->format('Y-m-d') }}"
                                            ></span>
                                        </td>
                                        <td class="px-4 py-4 font-bold text-slate-900">PHP {{ number_format($bill->total_bill, 2) }}</td>
                                        <td class="px-4 py-4">
                                            <span class="text-[11px] font-black uppercase tracking-[0.16em] {{ $statusClasses }}">
                                                {{ $bill->status }}
                                            </span>
                                        </td>
                                        <td class="py-4 pl-4 text-right">
                                            <button
                                                type="button"
                                                class="receipt-slip-button inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 text-slate-400 transition-all hover:border-[#1846c0] hover:bg-[#e7ecff] hover:text-[#1846c0]"
                                                title="View receipt"
                                                aria-label="View receipt for {{ $bill->payment_reference ?: 'bill #' . $bill->id }}"
                                                data-reference="{{ $bill->payment_reference ?: 'No reference yet' }}"
                                                data-resident="{{ $user->first_name }} {{ $user->last_name }}"
                                                data-utility="{{ $bill->utility_type }}"
                                                data-status="{{ $bill->status }}"
                                                data-billing-start="{{ $bill->billing_period_start?->format('M d, Y') ?? 'N/A' }}"
                                                data-billing-end="{{ $bill->billing_period_end?->format('M d, Y') ?? 'N/A' }}"
                                                data-units="{{ number_format((float) $bill->consumption, 2) }} {{ $receiptUnitLabel }}"
                                                data-base-bill="PHP {{ number_format($receiptBaseBill, 2) }}"
                                                data-service-fee="PHP {{ number_format($receiptServiceFee, 2) }}"
                                                data-subtotal="PHP {{ number_format($receiptSubtotal, 2) }}"
                                                data-penalty="PHP {{ number_format($receiptPenalty, 2) }}"
                                                data-has-penalty="{{ $receiptPenalty > 0 ? 'true' : 'false' }}"
                                                data-penalty-days="{{ (int) ($bill->penalty_days_applied ?? 0) }}"
                                                data-vat="PHP {{ number_format($receiptVat, 2) }}"
                                                data-total="PHP {{ number_format($receiptTotal, 2) }}"
                                            >
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17h6M9 13h6M9 9h6M7 3h7l3 3v15H7z" />
                                                </svg>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="py-10 text-center text-sm text-slate-400">
                                            No payment history yet. Paid bills will appear here after admin records them.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            <aside class="grid gap-6">
                <section class="overflow-hidden rounded-[28px] bg-[#1846c0] text-white shadow-[0_24px_60px_rgba(24,70,192,0.28)]">
                    <div class="border-t-4 border-[#33a6ff] px-8 py-9">
                        <p class="text-[11px] font-black uppercase tracking-[0.26em] text-blue-100">
                            Next Due Date: {{ $nextDueBill ? \Illuminate\Support\Carbon::parse($nextDueBill->billing_period_end)->format('M d, Y') : 'No Due Date' }}
                        </p>
                        <p class="mt-3 text-5xl font-black tracking-tight">PHP {{ number_format($totalDue, 2) }}</p>

                        <div class="mt-8 space-y-3 text-sm">
                            <div class="flex items-center justify-between border-b border-white/10 pb-2">
                                <span class="text-blue-100">Electric Usage</span>
                                <span class="font-semibold">PHP {{ number_format($electricUsage, 2) }}</span>
                            </div>
                            <div class="flex items-center justify-between border-b border-white/10 pb-2">
                                <span class="text-blue-100">Electric Service Fee</span>
                                <span class="font-semibold">PHP {{ number_format($electricServiceFee, 2) }}</span>
                            </div>
                            <div class="flex items-center justify-between border-b border-white/10 pb-2">
                                <span class="text-blue-100">Water Usage</span>
                                <span class="font-semibold">PHP {{ number_format($waterUsage, 2) }}</span>
                            </div>
                            <div class="flex items-center justify-between pb-2">
                                <span class="text-blue-100">Water Service Fee</span>
                                <span class="font-semibold">PHP {{ number_format($waterServiceFee, 2) }}</span>
                            </div>
                        </div>

                        <div class="mt-10 rounded-3xl border border-white/20 bg-white/6 px-5 py-4 text-center text-sm text-blue-100">
                            Waiting for admin billing records.
                        </div>
                    </div>
                </section>

                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-1 xl:grid-cols-2">
                    <section class="rounded-[24px] bg-white p-6 shadow-[0_16px_50px_rgba(15,23,42,0.08)]">
                        <p class="text-[11px] font-black uppercase tracking-[0.22em] text-slate-400">Peak Demand</p>
                        <p class="mt-6 text-3xl font-black text-[#1846c0]">{{ number_format($peakDemand, 1) }} <span class="text-sm uppercase tracking-[0.2em] text-slate-500">kW</span></p>
                        <div class="mt-8 h-1.5 rounded-full bg-slate-200">
                            <div class="h-1.5 rounded-full bg-[#1846c0]" style="width: {{ $peakDemand > 0 ? min(100, max(28, $peakDemand * 4.5)) : 0 }}%"></div>
                        </div>
                    </section>

                    <section class="rounded-[24px] bg-white p-6 shadow-[0_16px_50px_rgba(15,23,42,0.08)]">
                        <p class="text-[11px] font-black uppercase tracking-[0.22em] text-slate-400">Sustainability Score</p>
                        <p class="mt-6 text-3xl font-black text-[#d94841]">{{ $sustainabilityScore }}/100</p>
                        <div class="mt-8 flex gap-2">
                            @for ($i = 1; $i <= 5; $i++)
                                <div class="h-1.5 flex-1 rounded-full {{ $i <= ceil($sustainabilityScore / 20) && $sustainabilityScore > 0 ? 'bg-[#1846c0]' : 'bg-slate-200' }}"></div>
                            @endfor
                        </div>
                    </section>
                </div>
            </aside>
        </main>

        <div id="receipt-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-950/70 p-6">
            <div class="absolute inset-0" data-receipt-close></div>

            <div class="relative max-h-[90vh] w-full max-w-md overflow-y-auto rounded-3xl bg-white shadow-2xl">
                <div class="bg-[#F8FAFC] px-10 pb-10 pt-12">
                    <div class="mb-10 relative flex items-start justify-center">
                        <div class="text-center">
                            <h2 class="mb-1 text-4xl font-black tracking-tighter text-[#001D4E]">VoltTrack</h2>
                            <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Tagum City</p>
                        </div>

                        <button type="button" data-receipt-close class="absolute right-0 top-0 flex h-9 w-9 items-center justify-center rounded-full border border-slate-200 text-slate-400 transition-all hover:border-slate-300 hover:bg-white hover:text-slate-700" aria-label="Close receipt">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <div class="mb-10 text-center">
                        <p class="mb-2 text-[10px] font-black uppercase tracking-widest text-slate-400">Total Amount Payable</p>
                        <h3 id="receipt-total-display" class="text-6xl font-black tracking-tighter text-[#001D4E]">PHP 0.00</h3>
                    </div>

                    <div class="mb-8 w-full rounded-2xl border border-slate-100 bg-white p-8 shadow-sm">
                        <div class="mb-6 border-b border-slate-900/10 pb-5">
                            <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">Reference ID</span>
                            <p id="receipt-reference" class="mt-1 text-sm font-bold text-slate-700">No reference yet</p>
                        </div>

                        <div class="mb-6 grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="text-[9px] font-black uppercase tracking-widest text-slate-400">Resident</span>
                                <p id="receipt-resident" class="mt-1 font-bold text-slate-700">N/A</p>
                            </div>
                            <div>
                                <span class="text-[9px] font-black uppercase tracking-widest text-slate-400">Utility</span>
                                <p id="receipt-utility" class="mt-1 font-bold text-slate-700">N/A</p>
                            </div>
                            <div>
                                <span class="text-[9px] font-black uppercase tracking-widest text-slate-400">Billing Start</span>
                                <p id="receipt-billing-start" class="mt-1 font-bold text-slate-700">N/A</p>
                            </div>
                            <div>
                                <span class="text-[9px] font-black uppercase tracking-widest text-slate-400">Billing End</span>
                                <p id="receipt-billing-end" class="mt-1 font-bold text-slate-700">N/A</p>
                            </div>
                        </div>

                        <div class="mb-4 flex items-center justify-between border-b border-slate-900/10 pb-4">
                            <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">Units Used</span>
                            <span id="receipt-units" class="text-sm font-bold text-slate-600">0.00</span>
                        </div>

                        <div class="space-y-4">
                            <div class="flex items-center justify-between text-xs">
                                <span class="text-[9px] font-bold uppercase tracking-widest text-slate-400">Base Bill</span>
                                <span id="receipt-base-bill" class="font-bold text-slate-600">PHP 0.00</span>
                            </div>
                            <div class="flex items-center justify-between text-xs">
                                <span class="text-[9px] font-bold uppercase tracking-widest text-slate-400">Service Fee</span>
                                <span id="receipt-service-fee" class="font-bold text-slate-600">PHP 0.00</span>
                            </div>
                            <div class="flex items-center justify-between text-xs">
                                <span class="text-[9px] font-bold uppercase tracking-widest text-slate-400">Subtotal</span>
                                <span id="receipt-subtotal" class="font-bold text-slate-600">PHP 0.00</span>
                            </div>
                            <div id="receipt-penalty-row" class="hidden items-center justify-between text-xs">
                                <span id="receipt-penalty-label" class="text-[9px] font-bold uppercase tracking-widest text-slate-400">Penalty (5% Daily)</span>
                                <span id="receipt-penalty" class="font-bold text-red-600">PHP 0.00</span>
                            </div>
                            <div class="flex items-center justify-between text-xs">
                                <span class="text-[9px] font-bold uppercase tracking-widest text-slate-400">VAT (12%)</span>
                                <span id="receipt-vat" class="font-bold text-slate-600">PHP 0.00</span>
                            </div>
                            <div class="flex items-center justify-between border-t border-slate-900/10 pt-4 text-xs">
                                <span class="text-[9px] font-black uppercase tracking-widest text-slate-500">Status</span>
                                <span id="receipt-status" class="font-black uppercase tracking-widest text-[#001D4E]">N/A</span>
                            </div>
                        </div>
                    </div>

                    <button type="button" data-receipt-close class="w-full rounded-xl bg-[#1e3a8a] py-4 text-center text-[11px] font-black uppercase tracking-[0.2em] text-white shadow-lg transition-all hover:bg-blue-900 active:scale-95">
                        Done
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('button:not([type="submit"])').forEach((button) => {
                button.addEventListener('click', (event) => {
                    event.preventDefault();
                });
            });

            const allUtilitiesButton = document.getElementById('filter-all-utilities');
            const lastMonthButton = document.getElementById('filter-last-month');
            const historyRows = document.querySelectorAll('tbody tr');

            if (allUtilitiesButton) {
                allUtilitiesButton.addEventListener('click', (event) => {
                    event.preventDefault();
                    historyRows.forEach((row) => {
                        row.classList.remove('hidden');
                    });
                });
            }

            if (lastMonthButton) {
                lastMonthButton.addEventListener('click', (event) => {
                    event.preventDefault();
                    const now = new Date();
                    const lastMonthDate = new Date(now.getFullYear(), now.getMonth() - 1, 1);
                    const lastMonth = lastMonthDate.getMonth();
                    const lastMonthYear = lastMonthDate.getFullYear();

                    historyRows.forEach((row) => {
                        const meta = row.querySelector('.receipt-filter-data');
                        if (!meta) {
                            row.classList.remove('hidden');
                            return;
                        }

                        const billingEnd = meta.dataset.billingEnd;
                        if (!billingEnd) {
                            row.classList.add('hidden');
                            return;
                        }

                        const billDate = new Date(`${billingEnd}T00:00:00`);
                        const isLastMonth = billDate.getMonth() === lastMonth && billDate.getFullYear() === lastMonthYear;
                        row.classList.toggle('hidden', !isLastMonth);
                    });
                });
            }
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const modal = document.getElementById('receipt-modal');
            const slipButtons = document.querySelectorAll('.receipt-slip-button');
            const closeButtons = document.querySelectorAll('[data-receipt-close]');
            const fields = {
                total: document.getElementById('receipt-total-display'),
                reference: document.getElementById('receipt-reference'),
                resident: document.getElementById('receipt-resident'),
                utility: document.getElementById('receipt-utility'),
                billingStart: document.getElementById('receipt-billing-start'),
                billingEnd: document.getElementById('receipt-billing-end'),
                units: document.getElementById('receipt-units'),
                baseBill: document.getElementById('receipt-base-bill'),
                serviceFee: document.getElementById('receipt-service-fee'),
                subtotal: document.getElementById('receipt-subtotal'),
                penalty: document.getElementById('receipt-penalty'),
                penaltyLabel: document.getElementById('receipt-penalty-label'),
                penaltyRow: document.getElementById('receipt-penalty-row'),
                vat: document.getElementById('receipt-vat'),
                status: document.getElementById('receipt-status'),
            };

            if (!modal) {
                return;
            }

            const setText = (field, value) => {
                if (field) {
                    field.textContent = value || 'N/A';
                }
            };

            const openReceipt = (button) => {
                setText(fields.total, button.dataset.total);
                setText(fields.reference, button.dataset.reference);
                setText(fields.resident, button.dataset.resident);
                setText(fields.utility, button.dataset.utility);
                setText(fields.billingStart, button.dataset.billingStart);
                setText(fields.billingEnd, button.dataset.billingEnd);
                setText(fields.units, button.dataset.units);
                setText(fields.baseBill, button.dataset.baseBill);
                setText(fields.serviceFee, button.dataset.serviceFee);
                setText(fields.subtotal, button.dataset.subtotal);
                setText(fields.penalty, button.dataset.penalty);
                setText(fields.vat, button.dataset.vat);
                setText(fields.status, button.dataset.status);

                if (fields.penaltyLabel) {
                    const days = parseInt(button.dataset.penaltyDays || '0', 10);
                    fields.penaltyLabel.textContent = days > 0
                        ? `Penalty (5% Daily x ${days} day${days > 1 ? 's' : ''})`
                        : 'Penalty (5% Daily)';
                }

                if (fields.penaltyRow) {
                    fields.penaltyRow.classList.toggle('hidden', button.dataset.hasPenalty !== 'true');
                    fields.penaltyRow.classList.toggle('flex', button.dataset.hasPenalty === 'true');
                }

                modal.classList.remove('hidden');
                modal.classList.add('flex');
                document.body.classList.add('overflow-hidden');
            };

            const closeReceipt = () => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                document.body.classList.remove('overflow-hidden');
            };

            slipButtons.forEach((button) => {
                button.addEventListener('click', () => openReceipt(button));
            });

            closeButtons.forEach((button) => {
                button.addEventListener('click', closeReceipt);
            });

            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape' && !modal.classList.contains('hidden')) {
                    closeReceipt();
                }
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const modal = document.getElementById('resident-reply-modal');
            const subject = document.getElementById('resident-reply-subject');
            const meta = document.getElementById('resident-reply-meta');
            const original = document.getElementById('resident-reply-original');
            const answer = document.getElementById('resident-reply-answer');
            const deleteForm = document.getElementById('resident-reply-delete-form');

            if (!modal) {
                return;
            }

            const closeModal = () => {
                modal.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            };

            document.querySelectorAll('[data-resident-reply-close]').forEach((button) => {
                button.addEventListener('click', closeModal);
            });

            document.querySelectorAll('.resident-reply-open').forEach((button) => {
                button.addEventListener('click', () => {
                    subject.textContent = button.dataset.subject || 'Admin Reply';
                    meta.textContent = `From ${button.dataset.admin || 'Admin'} - ${button.dataset.sent || 'N/A'}`;
                    original.textContent = button.dataset.message || 'N/A';
                    answer.textContent = button.dataset.reply || 'N/A';
                    deleteForm.action = button.dataset.deleteUrl;
                    modal.classList.remove('hidden');
                    document.body.classList.add('overflow-hidden');
                });
            });

            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape' && !modal.classList.contains('hidden')) {
                    closeModal();
                }
            });
        });
    </script>
</x-layout>

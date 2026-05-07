<x-layout title="Payment History | VoltTrack" :show-admin-nav="false">
    <div class="min-h-screen bg-[#eef2f7]">
        <header class="border-b border-[#2f8cff]/40 bg-[#1b2d73] px-6 py-3 text-white shadow-[0_6px_30px_rgba(10,28,74,0.2)]">
            <div class="mx-auto flex max-w-7xl items-center justify-between">
                <div>
                    <h1 class="text-4xl font-black tracking-tight">VoltTrack</h1>
                    <p class="mt-1 text-xs uppercase tracking-[0.24em] text-blue-100">Resident Payment History</p>
                </div>

                <div class="flex items-center gap-4">
                    <a href="{{ route('resident.dashboard') }}" class="rounded-full border border-white/20 px-4 py-2 text-xs font-bold uppercase tracking-[0.2em] text-white transition-all hover:bg-white/10">
                        Back
                    </a>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="rounded-full border border-white/20 px-4 py-2 text-xs font-bold uppercase tracking-[0.2em] text-white transition-all hover:bg-white/10">
                            Log Out
                        </button>
                    </form>
                </div>
            </div>
        </header>

        <main class="mx-auto max-w-7xl px-6 py-8">
            <section class="overflow-hidden rounded-[28px] border border-slate-200 bg-white shadow-[0_20px_70px_rgba(15,23,42,0.08)]">
                <div class="border-b border-slate-200 px-6 py-5">
                    <h2 class="text-lg font-black uppercase tracking-[0.2em] text-slate-700">All Payment History</h2>
                    <p class="mt-1 text-sm text-slate-400">Bills and payments recorded for {{ $user->first_name }} {{ $user->last_name }}.</p>
                </div>

                <div class="overflow-x-auto p-6">
                    <table class="w-full min-w-[760px] border-collapse text-left">
                        <thead>
                            <tr class="border-b border-slate-200 text-[11px] font-black uppercase tracking-[0.18em] text-slate-400">
                                <th class="py-3 pr-4">Reference ID</th>
                                <th class="px-4 py-3">Utility</th>
                                <th class="px-4 py-3">Billing Period</th>
                                <th class="px-4 py-3">Amount</th>
                                <th class="px-4 py-3">Status</th>
                                <th class="px-4 py-3">Paid At</th>
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
                                    $receiptSubtotal = (float) $bill->base_amount;
                                    $receiptPenalty = (float) $bill->penalty_amount;
                                    $receiptVat = (float) $bill->vat_amount;
                                    $receiptTotal = (float) $bill->amount_payable;
                                    $receiptUnitLabel = $bill->utility_type === 'Electricity' ? 'kWh' : 'm3';
                                @endphp
                                <tr class="border-b border-slate-100 text-sm text-slate-700 last:border-b-0">
                                    <td class="py-4 pr-4 font-semibold text-slate-600">{{ $bill->payment_reference ?: 'No reference yet' }}</td>
                                    <td class="px-4 py-4">{{ $bill->utility_type }}</td>
                                    <td class="px-4 py-4">
                                        {{ \Illuminate\Support\Carbon::parse($bill->billing_period_start)->format('M d') }}
                                        -
                                        {{ \Illuminate\Support\Carbon::parse($bill->billing_period_end)->format('M d, Y') }}
                                    </td>
                                    <td class="px-4 py-4 font-bold text-slate-900">PHP {{ number_format($bill->amount_payable, 2) }}</td>
                                    <td class="px-4 py-4">
                                        <span class="text-[11px] font-black uppercase tracking-[0.16em] {{ $statusClasses }}">
                                            {{ $bill->status }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-4 text-slate-500">
                                        {{ $bill->paid_at ? \Illuminate\Support\Carbon::parse($bill->paid_at)->format('M d, Y h:i A') : 'Not paid yet' }}
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
                                            data-billing-start="{{ $bill->billing_period_start?->format('M d, Y') ?? \Illuminate\Support\Carbon::parse($bill->billing_period_start)->format('M d, Y') }}"
                                            data-billing-end="{{ $bill->billing_period_end?->format('M d, Y') ?? \Illuminate\Support\Carbon::parse($bill->billing_period_end)->format('M d, Y') }}"
                                            data-previous-reading="{{ number_format((float) $bill->previous_reading, 2) }} {{ $receiptUnitLabel }}"
                                            data-current-reading="{{ number_format((float) $bill->current_reading, 2) }} {{ $receiptUnitLabel }}"
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
                                    <td colspan="7" class="py-12 text-center text-sm text-slate-400">
                                        No payment history yet. Bills will appear here after admin records them.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>
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
                        <div class="space-y-4">
                            <div class="flex items-start justify-between gap-4 border-b border-slate-900/10 pb-4 text-xs">
                                <span class="text-[9px] font-black uppercase tracking-widest text-slate-400">Reference ID</span>
                                <span id="receipt-reference" class="max-w-[12rem] break-words text-right font-bold text-slate-700">No reference yet</span>
                            </div>
                            <div class="flex items-center justify-between text-xs">
                                <span class="text-[9px] font-bold uppercase tracking-widest text-slate-400">Resident</span>
                                <span id="receipt-resident" class="text-right font-bold text-slate-600">N/A</span>
                            </div>
                            <div class="flex items-center justify-between text-xs">
                                <span class="text-[9px] font-bold uppercase tracking-widest text-slate-400">Utility</span>
                                <span id="receipt-utility" class="font-bold text-slate-600">N/A</span>
                            </div>
                            <div class="flex items-center justify-between text-xs">
                                <span class="text-[9px] font-bold uppercase tracking-widest text-slate-400">Billing Start</span>
                                <span id="receipt-billing-start" class="font-bold text-slate-600">N/A</span>
                            </div>
                            <div class="flex items-center justify-between text-xs">
                                <span class="text-[9px] font-bold uppercase tracking-widest text-slate-400">Billing End</span>
                                <span id="receipt-billing-end" class="font-bold text-slate-600">N/A</span>
                            </div>
                            <div class="flex items-center justify-between text-xs">
                                <span class="text-[9px] font-bold uppercase tracking-widest text-slate-400">Previous Reading</span>
                                <span id="receipt-previous-reading" class="font-bold text-slate-600">N/A</span>
                            </div>
                            <div class="flex items-center justify-between text-xs">
                                <span class="text-[9px] font-bold uppercase tracking-widest text-slate-400">Current Reading</span>
                                <span id="receipt-current-reading" class="font-bold text-slate-600">N/A</span>
                            </div>
                            <div class="flex items-center justify-between border-b border-slate-900/10 pb-4 text-xs">
                                <span class="text-[9px] font-bold uppercase tracking-widest text-slate-400">Units Used</span>
                                <span id="receipt-units" class="font-bold text-slate-600">0.00</span>
                            </div>
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
        (() => {
            const initReceiptSlipModal = () => {
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
                    previousReading: document.getElementById('receipt-previous-reading'),
                    currentReading: document.getElementById('receipt-current-reading'),
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

                if (!modal || modal.dataset.bound === 'true') {
                    return;
                }

                modal.dataset.bound = 'true';

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
                    setText(fields.previousReading, button.dataset.previousReading);
                    setText(fields.currentReading, button.dataset.currentReading);
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
            };

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initReceiptSlipModal);
            } else {
                initReceiptSlipModal();
            }
        })();
    </script>
</x-layout>

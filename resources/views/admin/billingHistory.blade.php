{{-- Renders the Billing History view for VoltTrack. --}}
<x-layout title="Admin Dashboard | VoltTrack">
    <div class="mx-auto min-h-screen max-w-7xl bg-slate-50 p-8">
        <div class="mb-8">
            <p class="mb-1 text-[10px] font-black uppercase tracking-[0.2em] text-blue-100">
                Billing History >
            </p>

            {{-- Conditional message/block --}}
            @if(session('success'))
                <div class="mb-4 mt-3 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-700">
                    {{ session('success') }}
                </div>
            @endif

            <div class="flex items-center justify-between">
                <h1 class="mt-1 text-5xl font-black text-white">
                    Billing History
                </h1>

                <div class="flex items-center gap-4">
                    {{-- Form --}}
                    <form action="{{ route('admin.billingHistory') }}" method="GET" class="flex items-center gap-3">
                        <div class="relative w-80">
                            <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                            <input
                                type="text"
                                name="search"
                                value="{{ request('search') }}"
                                placeholder="Search paid renter name..."
                                class="w-full rounded-full border border-slate-200 bg-white py-2.5 pl-12 pr-4 shadow-sm transition-all focus:outline-none focus:ring-2 focus:ring-blue-500/10"
                            >
                        </div>

                        <button type="submit" class="rounded-full bg-[#001D4E] px-6 py-2.5 text-sm font-bold text-white shadow-sm transition-all duration-200 hover:bg-blue-900">
                            Search
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="overflow-hidden rounded-[15px] border border-slate-200 bg-white shadow-[0_15px_40px_-15px_rgba(0,0,0,0.08)]">
            <div class="overflow-x-auto">
                {{-- Data table --}}
                <table class="w-full min-w-[980px] border-collapse text-left">
                    {{-- Table columns --}}
                    <thead class="border-b border-slate-100 bg-slate-50/50">
                        <tr class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">
                            <th class="px-10 py-6">Renter Name</th>
                            <th class="px-10 py-6">Utility Type</th>
                            <th class="px-10 py-6">Date of Transactions</th>
                            <th class="px-10 py-6">Total Bill</th>
                            <th class="px-10 py-6 text-center">Status</th>
                            <th class="px-10 py-6 text-center">Slip</th>
                        </tr>
                    </thead>
                    {{-- Table rows --}}
                    <tbody class="divide-y divide-slate-100">
                        {{-- List rendering --}}
                        @forelse($bills as $bill)
                            @php
                                $residentName = trim(($bill->user?->first_name ?? '') . ' ' . ($bill->user?->last_name ?? ''));
                                $receiptResident = $residentName !== '' ? $residentName : 'Unknown Resident';
                                $receiptUnitLabel = $bill->utility_type === 'Electricity' ? 'kWh' : 'm3';
                                $receiptBaseBill = (float) $bill->consumption * (float) $bill->price_per_unit;
                                $receiptServiceFee = (float) $bill->service_fee;
                                $receiptSubtotal = (float) $bill->base_amount;
                                $receiptPenalty = (float) $bill->penalty_amount;
                                $receiptVat = (float) $bill->vat_amount;
                                $receiptTotal = (float) $bill->amount_payable;
                            @endphp
                            <tr class="group transition-colors hover:bg-slate-50/50">
                                <td class="px-10 py-6">
                                    <p class="text-base font-bold text-[#0f172a] group-hover:text-blue-700">
                                        {{ $bill->user?->first_name }} {{ $bill->user?->last_name ?? 'Unknown' }}
                                    </p>
                                    <p class="text-[11px] font-medium text-slate-400">{{ $bill->user?->email ?? 'N/A' }}</p>
                                </td>
                                <td class="px-10 py-6 text-sm font-medium text-slate-600">{{ $bill->utility_type }}</td>
                                <td class="px-10 py-6 text-sm font-medium text-slate-500">{{ $bill->reading_date?->format('M d, Y') ?? 'N/A' }}</td>
                                <td class="px-10 py-6 text-sm font-bold text-[#1e3a8a]">&#8369;{{ number_format($bill->amount_payable, 2) }}</td>
                                <td class="px-10 py-6 text-center">
                                    <span class="inline-flex items-center rounded-full bg-blue-100 px-4 py-1.5 text-[10px] font-black uppercase tracking-wider text-blue-600">
                                        <span class="mr-2 h-1.5 w-1.5 rounded-full bg-blue-600"></span> Paid
                                    </span>
                                </td>
                                <td class="px-10 py-6 text-center">
                                    <button
                                        type="button"
                                        class="admin-receipt-slip-button inline-flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 text-slate-400 transition-all hover:border-[#1846c0] hover:bg-[#e7ecff] hover:text-[#1846c0]"
                                        title="View receipt"
                                        aria-label="View receipt for {{ $bill->payment_reference ?: 'bill #' . $bill->id }}"
                                        data-reference="{{ $bill->payment_reference ?: 'No reference yet' }}"
                                        data-resident="{{ $receiptResident }}"
                                        data-utility="{{ $bill->utility_type }}"
                                        data-billing-start="{{ $bill->billing_period_start?->format('M d, Y') ?? 'N/A' }}"
                                        data-billing-end="{{ $bill->billing_period_end?->format('M d, Y') ?? 'N/A' }}"
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
                                        <i class="fas fa-receipt text-sm"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-10 py-6 text-center text-slate-500">
                                    No paid bills found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="flex items-center justify-between border-t border-slate-100 bg-slate-50/50 px-10 py-5">
                <p class="text-xs font-bold italic text-slate-400">
                    Showing
                    <span class="text-slate-600">{{ number_format($bills->firstItem() ?? 0) }}-{{ number_format($bills->lastItem() ?? 0) }}</span>
                    of {{ number_format($bills->total()) }} paid bills
                </p>

                <x-pagination-links :paginator="$bills" />
            </div>
        </div>

        <div id="admin-billing-receipt-modal" class="fixed inset-0 z-[9999] hidden items-center justify-center overflow-hidden bg-slate-950/70 p-2">
            <div class="absolute inset-0" data-admin-billing-receipt-close></div>

            <div class="relative max-h-[calc(100vh-1.5rem)] w-full max-w-sm overflow-y-auto rounded-xl bg-white shadow-2xl">
                <div class="bg-[#F8FAFC] px-4 pb-3 pt-3">
                    <div class="mb-2 relative flex items-start justify-center">
                        <button type="button" data-admin-billing-receipt-save class="absolute left-0 top-0 flex h-7 w-7 items-center justify-center rounded-full border border-slate-200 text-slate-400 transition-all hover:border-[#1846c0] hover:bg-[#e7ecff] hover:text-[#1846c0]" aria-label="Save receipt as image" title="Save as image">
                            <i class="fas fa-download text-[11px]"></i>
                        </button>

                        <div class="text-center">
                            <h2 class="text-xl font-black tracking-tighter text-[#001D4E]">VoltTrack</h2>
                            <p class="text-[8px] font-bold uppercase tracking-widest text-slate-400">Tagum City</p>
                        </div>

                        <button type="button" data-admin-billing-receipt-close class="absolute right-0 top-0 flex h-7 w-7 items-center justify-center rounded-full border border-slate-200 text-slate-400 transition-all hover:border-slate-300 hover:bg-white hover:text-slate-700" aria-label="Close receipt">
                            <i class="fas fa-times text-[11px]"></i>
                        </button>
                    </div>

                    <div class="mb-2 text-center">
                        <p class="mb-0.5 text-[8px] font-black uppercase tracking-widest text-slate-400">Total Amount Payable</p>
                        <h3 id="admin-billing-receipt-total-display" class="text-3xl font-black tracking-tighter text-[#001D4E]">PHP 0.00</h3>
                    </div>

                    <div class="mb-2 w-full rounded-xl border border-slate-100 bg-white p-3 shadow-sm">
                        <div class="space-y-1.5">
                            <div class="flex items-start justify-between gap-3 border-b border-slate-900/10 pb-1.5 text-[11px]">
                                <span class="text-[8px] font-black uppercase tracking-widest text-slate-400">Reference ID</span>
                                <span id="admin-billing-receipt-reference" class="max-w-[10rem] break-words text-right font-bold text-slate-700">No reference yet</span>
                            </div>
                            <div class="flex items-center justify-between text-[11px]">
                                <span class="text-[8px] font-bold uppercase tracking-widest text-slate-400">Resident</span>
                                <span id="admin-billing-receipt-resident" class="text-right font-bold text-slate-600">N/A</span>
                            </div>
                            <div class="flex items-center justify-between text-[11px]">
                                <span class="text-[8px] font-bold uppercase tracking-widest text-slate-400">Utility</span>
                                <span id="admin-billing-receipt-utility" class="font-bold text-slate-600">N/A</span>
                            </div>
                            <div class="flex items-center justify-between text-[11px]">
                                <span class="text-[8px] font-bold uppercase tracking-widest text-slate-400">Billing Start</span>
                                <span id="admin-billing-receipt-billing-start" class="font-bold text-slate-600">N/A</span>
                            </div>
                            <div class="flex items-center justify-between text-[11px]">
                                <span class="text-[8px] font-bold uppercase tracking-widest text-slate-400">Billing End</span>
                                <span id="admin-billing-receipt-billing-end" class="font-bold text-slate-600">N/A</span>
                            </div>
                            <div class="flex items-center justify-between text-[11px]">
                                <span class="text-[8px] font-bold uppercase tracking-widest text-slate-400">Previous Reading</span>
                                <span id="admin-billing-receipt-previous-reading" class="font-bold text-slate-600">N/A</span>
                            </div>
                            <div class="flex items-center justify-between text-[11px]">
                                <span class="text-[8px] font-bold uppercase tracking-widest text-slate-400">Current Reading</span>
                                <span id="admin-billing-receipt-current-reading" class="font-bold text-slate-600">N/A</span>
                            </div>
                            <div class="flex items-center justify-between border-b border-slate-900/10 pb-1.5 text-[11px]">
                                <span class="text-[8px] font-bold uppercase tracking-widest text-slate-400">Units Used</span>
                                <span id="admin-billing-receipt-units" class="font-bold text-slate-600">0.00</span>
                            </div>
                            <div class="flex items-center justify-between text-[11px]">
                                <span class="text-[8px] font-bold uppercase tracking-widest text-slate-400">Base Bill</span>
                                <span id="admin-billing-receipt-base-bill" class="font-bold text-slate-600">PHP 0.00</span>
                            </div>
                            <div class="flex items-center justify-between text-[11px]">
                                <span class="text-[8px] font-bold uppercase tracking-widest text-slate-400">Service Fee</span>
                                <span id="admin-billing-receipt-service-fee" class="font-bold text-slate-600">PHP 0.00</span>
                            </div>
                            <div class="flex items-center justify-between text-[11px]">
                                <span class="text-[8px] font-bold uppercase tracking-widest text-slate-400">Subtotal</span>
                                <span id="admin-billing-receipt-subtotal" class="font-bold text-slate-600">PHP 0.00</span>
                            </div>
                            <div id="admin-billing-receipt-penalty-row" class="hidden items-center justify-between text-[11px]">
                                <span id="admin-billing-receipt-penalty-label" class="text-[8px] font-bold uppercase tracking-widest text-slate-400">Penalty (5% Daily)</span>
                                <span id="admin-billing-receipt-penalty" class="font-bold text-red-600">PHP 0.00</span>
                            </div>
                            <div class="flex items-center justify-between text-[11px]">
                                <span class="text-[8px] font-bold uppercase tracking-widest text-slate-400">VAT (12%)</span>
                                <span id="admin-billing-receipt-vat" class="font-bold text-slate-600">PHP 0.00</span>
                            </div>
                        </div>
                    </div>

                    <button type="button" data-admin-billing-receipt-close class="w-full rounded-lg bg-[#1e3a8a] py-2 text-center text-[10px] font-black uppercase tracking-[0.2em] text-white shadow-lg transition-all hover:bg-blue-900 active:scale-95">
                        Done
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        (() => {
            const initAdminBillingReceiptModal = () => {
                const modal = document.getElementById('admin-billing-receipt-modal');
                const slipButtons = document.querySelectorAll('.admin-receipt-slip-button');
                const closeButtons = document.querySelectorAll('[data-admin-billing-receipt-close]');
                const saveButtons = document.querySelectorAll('[data-admin-billing-receipt-save]');
                const fields = {
                    total: document.getElementById('admin-billing-receipt-total-display'),
                    reference: document.getElementById('admin-billing-receipt-reference'),
                    resident: document.getElementById('admin-billing-receipt-resident'),
                    utility: document.getElementById('admin-billing-receipt-utility'),
                    billingStart: document.getElementById('admin-billing-receipt-billing-start'),
                    billingEnd: document.getElementById('admin-billing-receipt-billing-end'),
                    previousReading: document.getElementById('admin-billing-receipt-previous-reading'),
                    currentReading: document.getElementById('admin-billing-receipt-current-reading'),
                    units: document.getElementById('admin-billing-receipt-units'),
                    baseBill: document.getElementById('admin-billing-receipt-base-bill'),
                    serviceFee: document.getElementById('admin-billing-receipt-service-fee'),
                    subtotal: document.getElementById('admin-billing-receipt-subtotal'),
                    penalty: document.getElementById('admin-billing-receipt-penalty'),
                    penaltyLabel: document.getElementById('admin-billing-receipt-penalty-label'),
                    penaltyRow: document.getElementById('admin-billing-receipt-penalty-row'),
                    vat: document.getElementById('admin-billing-receipt-vat'),
                };

                if (!modal || modal.dataset.bound === 'true') {
                    return;
                }

                modal.dataset.bound = 'true';
                const modalHome = modal.parentElement;

                const setText = (field, value) => {
                    if (field) {
                        field.textContent = value || 'N/A';
                    }
                };

                const drawWrappedText = (context, text, x, y, maxWidth, lineHeight) => {
                    const words = String(text || '').split(' ');
                    let line = '';

                    words.forEach((word) => {
                        const nextLine = line ? `${line} ${word}` : word;

                        if (context.measureText(nextLine).width > maxWidth && line) {
                            context.fillText(line, x, y);
                            line = word;
                            y += lineHeight;
                        } else {
                            line = nextLine;
                        }
                    });

                    context.fillText(line, x, y);
                    return y;
                };

                const drawRoundedRect = (context, x, y, width, height, radius) => {
                    context.beginPath();
                    context.moveTo(x + radius, y);
                    context.lineTo(x + width - radius, y);
                    context.quadraticCurveTo(x + width, y, x + width, y + radius);
                    context.lineTo(x + width, y + height - radius);
                    context.quadraticCurveTo(x + width, y + height, x + width - radius, y + height);
                    context.lineTo(x + radius, y + height);
                    context.quadraticCurveTo(x, y + height, x, y + height - radius);
                    context.lineTo(x, y + radius);
                    context.quadraticCurveTo(x, y, x + radius, y);
                    context.closePath();
                };

                const saveReceiptImage = () => {
                    const canvas = document.createElement('canvas');
                    const scale = 2;
                    const width = 420;
                    const height = 680;
                    canvas.width = width * scale;
                    canvas.height = height * scale;

                    const context = canvas.getContext('2d');
                    context.scale(scale, scale);
                    context.fillStyle = '#f8fafc';
                    context.fillRect(0, 0, width, height);
                    drawRoundedRect(context, 18, 18, width - 36, height - 36, 18);
                    context.fillStyle = '#ffffff';
                    context.fill();
                    context.strokeStyle = '#e2e8f0';
                    context.lineWidth = 1;
                    context.stroke();

                    context.textAlign = 'center';
                    context.fillStyle = '#001D4E';
                    context.font = '900 30px Inter, Arial, sans-serif';
                    context.fillText('VoltTrack', width / 2, 62);
                    context.fillStyle = '#94a3b8';
                    context.font = '700 10px Inter, Arial, sans-serif';
                    context.fillText('TAGUM CITY', width / 2, 80);
                    context.fillText('ADMIN BILLING RECEIPT', width / 2, 103);

                    context.fillStyle = '#001D4E';
                    context.font = '900 30px Inter, Arial, sans-serif';
                    context.fillText(fields.total?.textContent || 'PHP 0.00', width / 2, 143);

                    const rows = [
                        ['Reference ID', fields.reference?.textContent],
                        ['Resident', fields.resident?.textContent],
                        ['Utility', fields.utility?.textContent],
                        ['Billing Start', fields.billingStart?.textContent],
                        ['Billing End', fields.billingEnd?.textContent],
                        ['Previous Reading', fields.previousReading?.textContent],
                        ['Current Reading', fields.currentReading?.textContent],
                        ['Units Used', fields.units?.textContent],
                        ['Base Bill', fields.baseBill?.textContent],
                        ['Service Fee', fields.serviceFee?.textContent],
                        ['Subtotal', fields.subtotal?.textContent],
                    ];

                    if (fields.penaltyRow && !fields.penaltyRow.classList.contains('hidden')) {
                        rows.push([fields.penaltyLabel?.textContent || 'Penalty', fields.penalty?.textContent]);
                    }

                    rows.push(['VAT (12%)', fields.vat?.textContent]);

                    let y = 185;
                    rows.forEach(([label, value], index) => {
                        if (index === 8) {
                            context.strokeStyle = '#e2e8f0';
                            context.beginPath();
                            context.moveTo(42, y - 18);
                            context.lineTo(width - 42, y - 18);
                            context.stroke();
                        }

                        context.textAlign = 'left';
                        context.fillStyle = '#64748b';
                        context.font = '800 10px Inter, Arial, sans-serif';
                        context.fillText(String(label).toUpperCase(), 42, y);
                        context.textAlign = 'right';
                        context.fillStyle = '#334155';
                        context.font = '800 12px Inter, Arial, sans-serif';
                        context.fillText(value || 'N/A', width - 42, y);
                        y += 31;
                    });

                    context.textAlign = 'center';
                    context.fillStyle = '#94a3b8';
                    context.font = '700 10px Inter, Arial, sans-serif';
                    drawWrappedText(context, 'Generated from VoltTrack admin billing history', width / 2, height - 38, width - 90, 14);

                    const reference = (fields.reference?.textContent || 'receipt').replace(/[^a-z0-9_-]+/gi, '-').replace(/^-|-$/g, '');
                    const link = document.createElement('a');
                    link.download = `volttrack-admin-${reference || 'receipt'}.png`;
                    link.href = canvas.toDataURL('image/png');
                    link.click();
                };

                const openReceipt = (button) => {
                    if (modal.parentElement !== document.body) {
                        document.body.appendChild(modal);
                    }

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

                    if (modalHome && modal.parentElement === document.body) {
                        modalHome.appendChild(modal);
                    }
                };

                slipButtons.forEach((button) => {
                    button.addEventListener('click', () => openReceipt(button));
                });

                closeButtons.forEach((button) => {
                    button.addEventListener('click', closeReceipt);
                });

                saveButtons.forEach((button) => {
                    button.addEventListener('click', saveReceiptImage);
                });

                document.addEventListener('keydown', (event) => {
                    if (event.key === 'Escape' && !modal.classList.contains('hidden')) {
                        closeReceipt();
                    }
                });
            };

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initAdminBillingReceiptModal);
            } else {
                initAdminBillingReceiptModal();
            }
        })();
    </script>
</x-layout>
<x-layout title="Receipt | VoltTrack" :show-admin-nav="false">
    @php
        $baseBill = (float) $bill->consumption * (float) $bill->price_per_unit;
        $serviceFee = (float) $bill->service_fee;
        $subtotal = (float) $bill->base_amount;
        $penalty = (float) $bill->penalty_amount;
        $penaltyDays = (int) ($bill->penalty_days_applied ?? 0);
        $vat = (float) $bill->vat_amount;
        $totalBill = (float) $bill->amount_payable;
        $unitLabel = $bill->utility_type === 'Electricity' ? 'kWh' : 'm3';
    @endphp

    <div class="receipt-page w-full min-h-screen bg-[#334155] flex items-center justify-center font-sans p-6">
        <div class="w-full max-w-md bg-white rounded-3xl overflow-hidden shadow-2xl">
            <div class="bg-[#F8FAFC] px-10 pt-12 pb-10">
                <div class="mb-10 relative flex items-start justify-center">
                    <div class="text-center">
                        <h1 class="text-4xl font-black text-[#001D4E] tracking-tighter mb-1">VoltTrack</h1>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Tagum City</p>
                    </div>

                </div>

                <div class="text-center mb-10">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Total Amount Payable</p>
                    <h2 class="text-6xl font-black tracking-tighter text-[#001D4E]">PHP {{ number_format($totalBill, 2) }}</h2>
                </div>

                <div class="w-full bg-white rounded-2xl p-8 shadow-sm border border-slate-100 mb-8">
                    <div class="space-y-4">
                        <div class="flex items-start justify-between gap-4 border-b border-slate-900/10 pb-4 text-xs">
                            <span class="font-black text-slate-400 uppercase tracking-widest text-[9px]">Reference ID</span>
                            <span class="max-w-[12rem] text-right font-bold text-slate-700 break-words">{{ $bill->payment_reference ?: 'No reference yet' }}</span>
                        </div>
                        <div class="flex justify-between items-center text-xs">
                            <span class="font-bold text-slate-400 uppercase tracking-widest text-[9px]">Resident</span>
                            <span class="font-bold text-slate-600 text-right">{{ $user->first_name }} {{ $user->last_name }}</span>
                        </div>
                        <div class="flex justify-between items-center text-xs">
                            <span class="font-bold text-slate-400 uppercase tracking-widest text-[9px]">Utility</span>
                            <span class="font-bold text-slate-600">{{ $bill->utility_type }}</span>
                        </div>
                        <div class="flex justify-between items-center text-xs">
                            <span class="font-bold text-slate-400 uppercase tracking-widest text-[9px]">Billing Start</span>
                            <span class="font-bold text-slate-600">{{ $bill->billing_period_start?->format('M d, Y') ?? 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between items-center text-xs">
                            <span class="font-bold text-slate-400 uppercase tracking-widest text-[9px]">Billing End</span>
                            <span class="font-bold text-slate-600">{{ $bill->billing_period_end?->format('M d, Y') ?? 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between items-center text-xs">
                            <span class="font-bold text-slate-400 uppercase tracking-widest text-[9px]">Previous Reading</span>
                            <span class="font-bold text-slate-600">{{ number_format((float) $bill->previous_reading, 2) }} {{ $unitLabel }}</span>
                        </div>
                        <div class="flex justify-between items-center text-xs">
                            <span class="font-bold text-slate-400 uppercase tracking-widest text-[9px]">Current Reading</span>
                            <span class="font-bold text-slate-600">{{ number_format((float) $bill->current_reading, 2) }} {{ $unitLabel }}</span>
                        </div>
                        <div class="flex justify-between items-center border-b border-slate-900/10 pb-4 text-xs">
                            <span class="font-bold text-slate-400 uppercase tracking-widest text-[9px]">Units Used</span>
                            <span class="font-bold text-slate-600">{{ number_format((float) $bill->consumption, 2) }} {{ $unitLabel }}</span>
                        </div>
                        <div class="flex justify-between items-center text-xs">
                            <span class="font-bold text-slate-400 uppercase tracking-widest text-[9px]">Base Bill</span>
                            <span class="font-bold text-slate-600">PHP {{ number_format($baseBill, 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center text-xs">
                            <span class="font-bold text-slate-400 uppercase tracking-widest text-[9px]">Service Fee</span>
                            <span class="font-bold text-slate-600">PHP {{ number_format($serviceFee, 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center text-xs">
                            <span class="font-bold text-slate-400 uppercase tracking-widest text-[9px]">Subtotal</span>
                            <span class="font-bold text-slate-600">PHP {{ number_format($subtotal, 2) }}</span>
                        </div>
                        @if($penalty > 0)
                            <div class="flex justify-between items-center text-xs">
                                <span class="font-bold text-slate-400 uppercase tracking-widest text-[9px]">
                                    Penalty (5% Daily{{ $penaltyDays > 0 ? ' x '.$penaltyDays.' day'.($penaltyDays > 1 ? 's' : '') : '' }})
                                </span>
                                <span class="font-bold text-red-600">PHP {{ number_format($penalty, 2) }}</span>
                            </div>
                        @endif
                        <div class="flex justify-between items-center text-xs">
                            <span class="font-bold text-slate-400 uppercase tracking-widest text-[9px]">VAT (12%)</span>
                            <span class="font-bold text-slate-600">PHP {{ number_format($vat, 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center border-t border-slate-900/10 pt-4 text-xs">
                            <span class="font-black text-slate-500 uppercase tracking-widest text-[9px]">Total</span>
                            <span class="font-black text-[#001D4E]">PHP {{ number_format($totalBill, 2) }}</span>
                        </div>
                    </div>
                </div>

                <a href="{{ route('resident.dashboard') }}" class="block w-full bg-[#1e3a8a] hover:bg-blue-900 text-white text-[11px] font-black py-4 rounded-xl shadow-lg uppercase tracking-[0.2em] text-center transition-all active:scale-95">
                    Done
                </a>
            </div>
        </div>
    </div>
</x-layout>

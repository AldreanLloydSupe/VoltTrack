<x-layout title="Receipt | VoltTrack" :show-admin-nav="false">
    @php
        $baseBill = (float) $bill->consumption * (float) $bill->price_per_unit;
        $serviceFee = (float) $bill->service_fee;
        $subtotal = (float) $bill->total_bill;
        $status = strtolower($bill->status);
        $paidAfterDueDate = $status === 'paid'
            && $bill->paid_at
            && $bill->billing_period_end
            && $bill->paid_at->copy()->startOfDay()->gt($bill->billing_period_end->copy()->startOfDay());
        $penalty = ($status === 'overdue' || $paidAfterDueDate) ? $subtotal * 0.05 : 0;
        $vat = $subtotal * 0.12;
        $totalBill = $subtotal + $penalty + $vat;
        $unitLabel = $bill->utility_type === 'Electricity' ? 'kWh' : 'm3';
        $statusClasses = match ($status) {
            'paid' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
            'overdue' => 'bg-red-50 text-red-700 border-red-100',
            default => 'bg-amber-50 text-amber-700 border-amber-100',
        };
    @endphp

    <div class="w-full min-h-screen bg-[#334155] flex items-center justify-center font-sans p-6">
        <div class="w-full max-w-md bg-white rounded-3xl overflow-hidden shadow-2xl">
            <div class="bg-[#F8FAFC] px-10 pt-12 pb-10">
                <div class="mb-10 relative flex items-start justify-center">
                    <div class="text-center">
                        <h1 class="text-4xl font-black text-[#001D4E] tracking-tighter mb-1">VoltTrack</h1>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Tagum City</p>
                    </div>

                    <span class="absolute right-0 top-0 rounded-full border px-3 py-1 text-[10px] font-black uppercase tracking-[0.16em] {{ $statusClasses }}">
                        {{ $bill->status }}
                    </span>
                </div>

                <div class="text-center mb-10">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Total Amount Payable</p>
                    <h2 class="text-6xl font-black tracking-tighter text-[#001D4E]">PHP {{ number_format($totalBill, 2) }}</h2>
                </div>

                <div class="w-full bg-white rounded-2xl p-8 shadow-sm border border-slate-100 mb-8">
                    <div class="mb-6 border-b border-slate-900/10 pb-5">
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Reference ID</span>
                        <p class="mt-1 text-sm font-bold text-slate-700">{{ $bill->payment_reference ?: 'No reference yet' }}</p>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-6 text-sm">
                        <div>
                            <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Resident</span>
                            <p class="mt-1 font-bold text-slate-700">{{ $user->first_name }} {{ $user->last_name }}</p>
                        </div>
                        <div>
                            <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Utility</span>
                            <p class="mt-1 font-bold text-slate-700">{{ $bill->utility_type }}</p>
                        </div>
                        <div>
                            <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Billing Start</span>
                            <p class="mt-1 font-bold text-slate-700">{{ $bill->billing_period_start?->format('M d, Y') ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Billing End</span>
                            <p class="mt-1 font-bold text-slate-700">{{ $bill->billing_period_end?->format('M d, Y') ?? 'N/A' }}</p>
                        </div>
                    </div>

                    <div class="flex justify-between items-center mb-4 pb-4 border-b border-slate-900/10">
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Units Used</span>
                        <span class="text-sm font-bold text-slate-600">{{ number_format((float) $bill->consumption, 2) }} {{ $unitLabel }}</span>
                    </div>

                    <div class="space-y-4">
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
                                <span class="font-bold text-slate-400 uppercase tracking-widest text-[9px]">Penalty (5%)</span>
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

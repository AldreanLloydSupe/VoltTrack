<x-layout title="{{ $resident->first_name }} {{ $resident->last_name }} | VoltTrack">
<div class="max-w-7xl mx-auto p-8 bg-slate-50 min-h-screen">
    @if (session('success'))
        <div class="mb-4 rounded-xl border border-emerald-300 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="mb-2">
        <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">
            <a href="{{ route('admin.residentList') }}" class="hover:text-black transition-all">Residents List</a>
            > <span class="text-blue-600 text-shadow-slate-400 text-shadow-xs">{{ $resident->first_name }} {{ $resident->last_name }}</span>
        </p>
    </div>

    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-5xl font-bold text-[#F8FAFC] tracking-tight">{{ $resident->first_name }} {{ $resident->last_name }}</h1>
            <p class="text-slate-300 font-medium mt-2">Account ID: #{{ $resident->id }} • House No. {{ $resident->properties->first()?->property_unit_id ?? 'N/A' }}</p>
        </div>
        
        <div class="flex space-x-4">
            <a href="{{ route('admin.resident.edit', $resident->id) }}" data-instant-nav class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg font-bold text-sm shadow-md transition-all">
                UPDATE ACCOUNT
            </a>
            <form action="{{ route('admin.resident.destroy', $resident->id) }}" method="POST" onsubmit="return confirm('Delete this resident permanently? This cannot be undone.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-rose-500 hover:bg-rose-600 text-white px-6 py-2 rounded-lg font-bold text-sm shadow-md transition-all">
                    DELETE ACCOUNT
                </button>
            </form>

            <div class="relative inline-block group">
                <button class="bg-[#001D4E] hover:bg-slate-700 text-white px-6 py-2 rounded-lg font-bold text-sm shadow-md flex items-center transition-all duration-200">
                    <i class="fas fa-plus-circle mr-2"></i> 
                    CREATE NEW BILL
                    <i class="fas fa-chevron-down ml-2 text-[10px] opacity-70 transition-transform duration-200 group-hover:rotate-180"></i>
                </button>

                <div class="absolute right-0 hidden group-hover:block pt-2 z-50">
                    <div class="w-56 bg-white rounded-xl shadow-2xl border border-slate-100 py-2 animate-in fade-in zoom-in-95 duration-150">
                        <a href="{{ route('admin.Create.createNewElectricityBill', ['resident_id' => $resident->id]) }}" data-instant-nav class="flex items-center px-4 py-3 hover:bg-slate-50 transition-colors group/item">
                            <div class="w-10 h-10 bg-slate-100 rounded-lg flex items-center justify-center mr-3 group-hover/item:bg-blue-50 transition-colors">
                                <i class="fas fa-bolt text-[#001D4E]"></i>
                            </div>
                            <span class="font-bold text-slate-700">
                                Electricity Bill
                            </span>
                        </a>

                        <a href="{{ route('admin.Create.createNewWaterBill', ['resident_id' => $resident->id]) }}" data-instant-nav class="flex items-center px-4 py-3 hover:bg-slate-50 transition-colors group/item">
                            <div class="w-10 h-10 bg-slate-100 rounded-lg flex items-center justify-center mr-3 group-hover/item:bg-blue-50 transition-colors">
                                <i class="fas fa-tint text-[#001D4E]"></i>
                            </div>
                            <span class="font-bold text-slate-700">
                                Water Bill
                            </span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @php
        $lastPaidBill = $resident->bills
            ->where('status', 'Paid')
            ->sortByDesc('paid_at')
            ->first();
        $daysRented = 0;

        if ($resident->created_at) {
            $daysRented = max(
                0,
                $resident->created_at->copy()->startOfDay()->diffInDays(now()->startOfDay(), false)
            );
        }
    @endphp

    <div class="grid grid-cols-3 gap-8 mb-10">
        <div class="bg-white p-8 rounded-2xl shadow-[0_15px_30px_-10px_rgba(0,0,0,0.05)] border border-slate-100 relative overflow-hidden">
            <p class="text-[11px] font-black text-slate-400 uppercase tracking-widest mb-4">
                Current Balance
            </p>
            <h2 class="text-4xl font-bold text-[#1e3a8a]">
                ₱{{ number_format($resident->bills->where('status', '!=', 'Paid')->sum('total_bill'), 2) }}
            </h2>
            <div class="mt-6 inline-flex items-center px-3 py-1.5 bg-blue-50 text-blue-600 rounded-lg text-xs font-bold border border-blue-100">
                <i class="far fa-clock mr-2"></i>
                {{ $resident->bills->where('status', 'Overdue')->count() }} overdue bills
            </div>
            <i class="fas fa-wallet absolute top-8 right-8 text-slate-200 text-2xl"></i>
        </div>

        <div class="bg-white p-8 rounded-2xl shadow-[0_15px_30px_-10px_rgba(0,0,0,0.05)] border border-slate-100">
            <p class="text-[11px] font-black text-slate-400 uppercase tracking-widest mb-4">
                Last Amount Paid
            </p>
            <h2 class="text-4xl font-bold text-[#1e3a8a]">
                ₱{{ number_format($lastPaidBill?->total_bill ?? 0, 2) }}
            </h2>
            <p class="mt-6 text-xs font-bold text-slate-400">
                {{ $lastPaidBill?->paid_at?->format('M d, Y') ?? 'No payments' }}
            </p>
        </div>

        <div class="bg-white p-8 rounded-2xl shadow-[0_15px_30px_-10px_rgba(0,0,0,0.05)] border border-slate-100">
            <p class="text-[11px] font-black text-slate-400 uppercase tracking-widest mb-4">
                No. of Days Rented
            </p>
            <h2 class="text-4xl font-bold text-[#1e3a8a]">
                {{ $daysRented }} {{ $daysRented === 1 ? 'Day' : 'Days' }}
            </h2>
            <p class="mt-6 text-xs font-bold text-slate-400">
                Since {{ $resident->created_at?->format('M d, Y') ?? 'N/A' }}
            </p>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-xl overflow-visible">
        <div class="p-6 border-b border-slate-100 flex justify-between items-center">
            <h3 class="text-xl font-bold text-[#1e3a8a]">
                Recent Transactions
            </h3>
            <form method="GET" action="{{ route('admin.residentInfo', $resident->id) }}" class="flex space-x-3">
                <div class="relative">
                    <i class="fas fa-filter pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-xs text-slate-500"></i>
                    <select
                        name="utility"
                        onchange="this.form.submit()"
                        class="appearance-none rounded-lg border border-slate-200 bg-slate-50 py-2 pl-8 pr-8 text-xs font-bold text-slate-500 outline-none"
                    >
                        <option value="all" {{ ($selectedUtility ?? 'all') === 'all' ? 'selected' : '' }}>All Utilities</option>
                        <option value="Water" {{ ($selectedUtility ?? 'all') === 'Water' ? 'selected' : '' }}>Water</option>
                        <option value="Electricity" {{ ($selectedUtility ?? 'all') === 'Electricity' ? 'selected' : '' }}>Electricity</option>
                    </select>
                    <i class="fas fa-chevron-down pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-[10px] text-slate-400"></i>
                </div>

                <div class="relative">
                    <i class="far fa-calendar pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-xs text-slate-500"></i>
                    <select
                        name="months"
                        onchange="this.form.submit()"
                        class="appearance-none rounded-lg border border-slate-200 bg-slate-50 py-2 pl-8 pr-8 text-xs font-bold text-slate-500 outline-none"
                    >
                        <option value="1" {{ (int) ($selectedMonths ?? 6) === 1 ? 'selected' : '' }}>Last 1 Month</option>
                        <option value="3" {{ (int) ($selectedMonths ?? 6) === 3 ? 'selected' : '' }}>Last 3 Months</option>
                        <option value="6" {{ (int) ($selectedMonths ?? 6) === 6 ? 'selected' : '' }}>Last 6 Months</option>
                        <option value="12" {{ (int) ($selectedMonths ?? 6) === 12 ? 'selected' : '' }}>Last 12 Months</option>
                    </select>
                    <i class="fas fa-chevron-down pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-[10px] text-slate-400"></i>
                </div>
            </form>
        </div>

        <div class="overflow-x-auto">
        <table class="w-full min-w-[980px] text-left">
            <thead class="bg-slate-50/50 text-[10px] font-black text-slate-400 uppercase tracking-widest">
                <tr>
                    <th class="px-8 py-5">Due Date</th>
                    <th class="px-8 py-5">Utility Type</th>
                    <th class="px-8 py-5">Usage</th>
                    <th class="px-8 py-5">Amount</th>
                    <th class="px-8 py-5 text-center">Status</th>
                    <th class="px-8 py-5 text-center">Done</th>
                    <th class="px-8 py-5 text-center">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($residentBills as $bill)
                @php($isHighlightedBill = (string) request('highlight_bill') === (string) $bill->id)
                <tr
                    id="bill-row-{{ $bill->id }}"
                    data-highlighted-bill="{{ $isHighlightedBill ? 'true' : 'false' }}"
                    class="{{ $isHighlightedBill ? 'bg-blue-100 ring-2 ring-inset ring-blue-400 shadow-[inset_4px_0_0_#f59e0b]' : 'hover:bg-slate-50/50' }} transition-colors"
                >
                    <td class="px-8 py-6 text-sm font-bold text-slate-700">{{ $bill->billing_period_end->format('M d, Y') }}</td>
                    <td class="px-8 py-6 text-sm font-bold text-slate-800">{{ $bill->utility_type }}</td>
                    <td class="px-8 py-6">
                        <p class="text-sm font-bold text-slate-800">{{ $bill->consumption }} {{ $bill->utility_type === 'Electricity' ? 'kWh' : 'm³' }}</p>
                        <p class="text-[10px] text-slate-400 italic font-medium">Current: {{ $bill->current_reading }}</p>
                    </td>
                    <td class="px-8 py-6 text-sm font-bold text-[#1e3a8a]">₱{{ number_format($bill->total_bill, 2) }}</td>
                    <td class="px-8 py-6 text-center">
                        <span class="
                            @if($bill->status === 'Pending') bg-amber-400 text-white
                            @elseif($bill->status === 'Paid') bg-blue-100 text-blue-600
                            @elseif($bill->status === 'Overdue') bg-red-100 text-red-600
                            @endif
                            text-[10px] font-black px-3 py-1 rounded-full uppercase">{{ $bill->status }}</span>
                    </td>
                    <td class="px-8 py-6 text-center">
                        @if($bill->is_done)
                            <i class="fas fa-check text-emerald-400"></i>
                        @else
                            <span class="text-slate-400">-</span>
                        @endif
                    </td>
                    <td class="px-8 py-6 text-center">
                        @if($bill->status !== 'Paid')
                            <div class="flex items-center justify-center gap-3">
                                <a href="{{ route('admin.bills.edit', $bill->id) }}" data-instant-nav class="text-[11px] font-black text-blue-600 uppercase hover:underline">
                                    Update
                                </a>
                                <form
                                    action="{{ route('admin.bills.updateStatus', $bill->id) }}"
                                    method="POST"
                                    onsubmit="return confirm('Set this {{ $bill->utility_type }} bill to Paid and mark it as done?');"
                                >
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="Paid">
                                    <button type="submit" class="rounded-full bg-emerald-500 px-3 py-1.5 text-[10px] font-black uppercase tracking-[0.14em] text-white shadow-sm transition-colors hover:bg-emerald-600">
                                        Paid
                                    </button>
                                </form>
                            </div>
                        @else
                            <span class="text-[11px] font-black text-slate-400 uppercase">Paid</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-8 py-6 text-center text-slate-500">
                        No bills found for this resident
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        </div>

        @if($residentBills->hasPages())
            <div class="flex flex-col gap-3 border-t border-slate-100 bg-slate-50/50 px-8 py-5 sm:flex-row sm:items-center sm:justify-between">
                <p class="text-xs font-bold text-slate-400">
                    Showing
                    <span class="text-slate-600">{{ number_format($residentBills->firstItem() ?? 0) }}-{{ number_format($residentBills->lastItem() ?? 0) }}</span>
                    of {{ number_format($residentBills->total()) }} transactions
                </p>
                <x-pagination-links :paginator="$residentBills" />
            </div>
        @endif
    </div>
</div>

<script>
    (() => {
        const highlightedRow = document.querySelector('[data-highlighted-bill="true"]');

        if (!highlightedRow) {
            return;
        }

        window.requestAnimationFrame(() => {
            highlightedRow.scrollIntoView({
                behavior: 'smooth',
                block: 'center',
            });
        });
    })();
</script>

</x-layout>

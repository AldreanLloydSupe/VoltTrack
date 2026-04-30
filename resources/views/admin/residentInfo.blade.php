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
            > <span class="text-blue-600">{{ $resident->first_name }} {{ $resident->last_name }}</span>
        </p>
    </div>

    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-5xl font-bold text-[#1e3a8a] tracking-tight">{{ $resident->first_name }} {{ $resident->last_name }}</h1>
            <p class="text-slate-500 font-medium mt-2">Account ID: #{{ $resident->id }} • House No. {{ $resident->properties->first()?->property_unit_id ?? 'N/A' }}</p>
        </div>
        
        <div class="flex space-x-4">
            <a href="{{ route('admin.resident.edit', $resident->id) }}" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg font-bold text-sm shadow-md transition-all">
                UPDATE ACCOUNT
            </a>
            <form action="{{ route('admin.resident.destroy', $resident->id) }}" method="POST" onsubmit="return confirm('Delete this account?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-rose-500 hover:bg-rose-600 text-white px-6 py-2 rounded-lg font-bold text-sm shadow-md transition-all">
                    DELETE ACCOUNT
                </button>
            </form>

            <div class="relative inline-block group">
                <button class="bg-[#001D4E] hover:bg-black text-white px-6 py-2 rounded-lg font-bold text-sm shadow-md flex items-center transition-all duration-200">
                    <i class="fas fa-plus-circle mr-2"></i> 
                    CREATE NEW BILL
                    <i class="fas fa-chevron-down ml-2 text-[10px] opacity-70 transition-transform duration-200 group-hover:rotate-180"></i>
                </button>

                <div class="absolute right-0 hidden group-hover:block pt-2 z-50">
                    <div class="w-56 bg-white rounded-xl shadow-2xl border border-slate-100 py-2 animate-in fade-in zoom-in-95 duration-150">
                        <a href="{{ route('admin.Create.createNewElectricityBill', ['resident_id' => $resident->id]) }}" class="flex items-center px-4 py-3 hover:bg-slate-50 transition-colors group/item">
                            <div class="w-10 h-10 bg-slate-100 rounded-lg flex items-center justify-center mr-3 group-hover/item:bg-blue-50 transition-colors">
                                <i class="fas fa-bolt text-[#001D4E]"></i>
                            </div>
                            <span class="font-bold text-slate-700">
                                Electricity Bill
                            </span>
                        </a>

                        <a href="{{ route('admin.Create.createNewWaterBill', ['resident_id' => $resident->id]) }}" class="flex items-center px-4 py-3 hover:bg-slate-50 transition-colors group/item">
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
            <div class="flex space-x-3">
                <div class="px-4 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-bold text-slate-500 cursor-pointer flex items-center">
                    <i class="fas fa-filter mr-2"></i> 
                    All Utilities
                    <i class="fas fa-chevron-down ml-2 opacity-50"></i>
                </div>
                <div class="px-4 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-bold text-slate-500 cursor-pointer flex items-center">
                    <i class="far fa-calendar mr-2"></i> 
                    Last 6 Months
                    <i class="fas fa-chevron-down ml-2 opacity-50"></i>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
        <table class="w-full min-w-[980px] text-left">
            <thead class="bg-slate-50/50 text-[10px] font-black text-slate-400 uppercase tracking-widest">
                <tr>
                    <th class="px-8 py-5">Billing Date</th>
                    <th class="px-8 py-5">Utility Type</th>
                    <th class="px-8 py-5">Usage</th>
                    <th class="px-8 py-5">Amount</th>
                    <th class="px-8 py-5 text-center">Status</th>
                    <th class="px-8 py-5 text-center">Done</th>
                    <th class="px-8 py-5 text-right">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($resident->bills as $bill)
                <tr class="hover:bg-slate-50/50 transition-colors">
                    <td class="px-8 py-6 text-sm font-bold text-slate-700">{{ $bill->reading_date->format('M d, Y') }}</td>
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
                    <td class="px-8 py-6 text-right">
                        @if($bill->status !== 'Paid')
                            <details class="relative inline-block text-left z-30">
                                <summary class="list-none cursor-pointer text-[11px] font-black text-blue-600 uppercase hover:underline">
                                    Update
                                </summary>
                                <div class="absolute right-0 top-full z-40 mt-2 w-36 rounded-lg border border-slate-200 bg-white shadow-lg py-1">
                                    <form action="{{ route('admin.bills.updateStatus', $bill->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="Paid">
                                        <button type="submit" class="w-full px-3 py-2 text-left text-[11px] font-bold uppercase text-blue-600 hover:bg-slate-50">
                                            Paid
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.bills.updateStatus', $bill->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="Overdue">
                                        <button type="submit" class="w-full px-3 py-2 text-left text-[11px] font-bold uppercase text-rose-600 hover:bg-slate-50">
                                            Overdue
                                        </button>
                                    </form>
                                </div>
                            </details>
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
    </div>
</div>
</x-layout>

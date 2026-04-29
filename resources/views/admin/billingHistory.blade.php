<x-layout title="Admin Dashboard | VoltTrack">
    <div class="max-w-7xl mx-auto p-8 bg-slate-50 min-h-screen">
    
        <div class="mb-8">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1">
                Billing History >
            </p>

            @if(session('success'))
                <div class="mt-3 mb-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-700 text-sm font-semibold">
                    {{ session('success') }}
                </div>
            @endif
            
            <div class="flex justify-between items-center">
                <h1 class="text-5xl font-black text-[#0f172a] mt-1">
                    Billing History
                </h1>
                
                <div class="flex items-center gap-4">


                    <form action="{{ route('admin.billingHistory') }}" method="GET" class="flex items-center gap-3">
                        <div class="relative w-80">
                            <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search Renter Name..." 
                                class="w-full pl-12 pr-4 py-2.5 bg-white border border-slate-200 rounded-full shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500/10 transition-all">
                        </div>

                        <button type="submit" class="bg-[#001D4E] hover:bg-blue-900 text-white px-6 py-2.5 rounded-full font-bold text-sm shadow-sm transition-all duration-200">
                            Search
                        </button>
                    </form>

                    
                    <div class="flex bg-slate-200/50 p-1 rounded-lg text-[10px] font-bold uppercase tracking-widest">
                        <button class="px-4 py-1.5 bg-white shadow-sm rounded-md text-[#1e3a8a]">
                            Table View
                        </button>
                        <button class="px-4 py-1.5 text-slate-500 hover:text-slate-700 transition-colors">
                            Grid View
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-[15px] border border-slate-200 shadow-[0_15px_40px_-15px_rgba(0,0,0,0.08)] overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50/50 border-b border-slate-100">
                    <tr class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">
                        <th class="px-10 py-6">Renter Name</th>
                        <th class="px-10 py-6">Utility Type</th>
                        <th class="px-10 py-6">Date of Transactions</th>
                        <th class="px-10 py-6">Total Bill</th>
                        <th class="px-10 py-6 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($bills as $bill)
                    <tr class="group hover:bg-slate-50/50 transition-colors">
                        <td class="px-10 py-6">
                            <p class="font-bold text-[#0f172a] text-base group-hover:text-blue-700">
                                {{ $bill->user?->first_name }} {{ $bill->user?->last_name ?? 'Unknown' }}
                            </p>
                            <p class="text-[11px] text-slate-400 font-medium">{{ $bill->user?->email ?? 'N/A' }}</p>
                        </td>
                        <td class="px-10 py-6 text-sm font-medium text-slate-600">{{ $bill->utility_type }}</td>
                        <td class="px-10 py-6 text-sm text-slate-500 font-medium">{{ $bill->reading_date?->format('M d, Y') }}</td>
                        <td class="px-10 py-6 text-sm font-bold text-[#1e3a8a]">₱{{ number_format($bill->total_bill, 2) }}</td>
                        <td class="px-10 py-6 text-center">
                            <span class="inline-flex items-center px-4 py-1.5 rounded-full
                                @if($bill->status === 'Paid') bg-blue-100 text-blue-600
                                @elseif($bill->status === 'Pending') bg-amber-100 text-amber-600
                                @elseif($bill->status === 'Overdue') bg-red-100 text-red-600
                                @endif
                                text-[10px] font-black uppercase tracking-wider">
                                <span class="w-1.5 h-1.5 rounded-full mr-2
                                    @if($bill->status === 'Paid') bg-blue-600
                                    @elseif($bill->status === 'Pending') bg-amber-600
                                    @elseif($bill->status === 'Overdue') bg-red-600
                                    @endif"></span>
                                {{ $bill->status }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-10 py-6 text-center text-slate-500">
                            No bills found
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="bg-slate-50/50 px-10 py-5 flex items-center justify-between border-t border-slate-100">
                <p class="text-xs font-bold text-slate-400 italic">
                    Showing 1-5 of 6 renters
                </p>
                
                <div class="flex items-center space-x-2">
                    <button class="p-1 text-slate-300 hover:text-slate-500"><i class="fas fa-chevron-left text-[10px]"></i></button>
                    <button class="w-8 h-8 flex items-center justify-center bg-blue-600 text-white rounded font-bold text-xs shadow-lg shadow-blue-200">
                        1
                    </button>
                    <button class="w-8 h-8 flex items-center justify-center bg-white text-slate-600 rounded font-bold text-xs border border-slate-200 hover:bg-slate-50">
                        2
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-layout>

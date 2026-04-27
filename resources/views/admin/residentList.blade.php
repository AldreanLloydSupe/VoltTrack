<x-layout title="Resident List | VoltTrack">
    <div class="max-w-7xl mx-auto p-8 bg-slate-50 min-h-screen">   
        <div class="mb-8">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1">
                Residents List >
            </p>
            <h2 class="text-5xl font-black text-[#0f172a] mt-1">
                Resident Directory
            </h2>
            <br>
            <p class="text-slate-500 max-w-2xl leading-relaxed">
                A consolidated fiscal record of all active tenants, units, and financial standing within the VoltTrack Utility ecosystem.
            </p>
        </div>

        <div class="flex items-center justify-between mb-6 gap-4">
            <div class="flex gap-3">
                <div class="bg-white border border-slate-200 rounded-lg px-4 py-2 flex items-center space-x-3 cursor-pointer shadow-sm">
                    <i class="fas fa-filter text-slate-400 text-xs"></i>
                    <span class="text-sm font-bold text-slate-700">
                        Status: All
                    </span>
                    <i class="fas fa-chevron-down text-slate-400 text-[10px]"></i>
                </div>
                <div class="bg-white border border-slate-200 rounded-lg px-4 py-2 flex items-center space-x-3 cursor-pointer shadow-sm">
                    <i class="fas fa-building text-slate-400 text-xs"></i>
                    <span class="text-sm font-bold text-slate-700">
                        Unit Type: All
                    </span>
                    <i class="fas fa-chevron-down text-slate-400 text-[10px]"></i>
                </div>
            </div>

            {{-- Search Bar --}}
            <div class="relative flex-1 max-w-md">
                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <input type="text" placeholder="Search..." 
                    class="w-full pl-12 pr-4 py-2.5 bg-white border border-slate-200 rounded-full shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20">
            </div>
        </div>

        <div class="bg-white rounded-[20px] border border-slate-200 shadow-[0_20px_50px_rgba(0,0,0,0.05)] overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50/50 border-b border-slate-100">
                    <tr class="text-[11px] font-black text-slate-400 uppercase tracking-widest">
                        <th class="px-8 py-5">Renter Name</th>
                        <th class="px-8 py-5">Account ID</th>
                        <th class="px-8 py-5">Unit Type</th>
                        <th class="px-8 py-5">Applied Date</th>
                        <th class="px-8 py-5">Last Pay</th>
                        <th class="px-8 py-5 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($residents as $resident)
                    <tr class="hover:bg-slate-50/80 transition-colors group">
                        <td class="px-8 py-6">
                            <p class="font-bold text-[#0f172a] text-base group-hover:text-blue-600 transition-colors">
                                {{ $resident->first_name }} {{ $resident->last_name }}
                            </p>
                            <p class="text-[12px] text-slate-400">{{ $resident->email }}</p>
                        </td>
                        <td class="px-8 py-6 text-sm font-medium text-slate-600">#{{ $resident->id }}</td>
                        <td class="px-8 py-6 text-sm font-bold text-slate-800">
                            {{ $resident->properties->first()?->unit_type ?? 'N/A' }}
                        </td>
                        <td class="px-8 py-6 text-sm text-slate-500">
                            {{ $resident->created_at?->format('M d, Y') ?? 'N/A' }}
                        </td>
                        <td class="px-8 py-6 text-sm text-slate-500">
                            {{ $resident->bills->where('status', 'Paid')->sortByDesc('paid_at')->first()?->paid_at?->format('M d, Y') ?? 'No payments' }}
                        </td>
                        <td class="px-8 py-6 text-right">
                            <a href="{{ route('admin.residentInfo', $resident->id) }}" class="text-[12px] font-black text-blue-600 uppercase hover:underline">View</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-8 py-6 text-center text-slate-500">
                            No residents found
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="bg-slate-50/50 px-8 py-4 flex items-center justify-between border-t border-slate-100">
                <p class="text-xs font-bold text-slate-400">
                    Showing 1-6 of 6 renters
                </p>
                <div class="flex space-x-1">
                    <button class="px-3 py-1 bg-blue-600 text-white rounded text-xs font-bold shadow-md shadow-blue-200">
                        1
                    </button>
                    <button class="px-3 py-1 bg-white text-slate-600 rounded text-xs font-bold border border-slate-200 hover:bg-slate-50">
                        2
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-layout>

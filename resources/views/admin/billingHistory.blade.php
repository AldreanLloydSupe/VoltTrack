<x-layout title="Admin Dashboard | VoltTrack">
    <div class="mx-auto min-h-screen max-w-7xl bg-slate-50 p-8">
        <div class="mb-8">
            <p class="mb-1 text-[10px] font-black uppercase tracking-[0.2em] text-blue-100">
                Billing History >
            </p>

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
                    <form action="{{ route('admin.billingHistory') }}" method="GET" class="flex items-center gap-3">
                        <div class="relative w-80">
                            <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                            <input
                                type="text"
                                name="search"
                                value="{{ request('search') }}"
                                placeholder="Search Renter Name..."
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
            <div>
                <table class="w-full border-collapse text-left">
                    <thead class="border-b border-slate-100 bg-slate-50/50">
                        <tr class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">
                            <th class="px-10 py-6">Renter Name</th>
                            <th class="px-10 py-6">Utility Type</th>
                            <th class="px-10 py-6">Date of Transactions</th>
                            <th class="px-10 py-6">Total Bill</th>
                            <th class="px-10 py-6 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($bills as $bill)
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
                                    @if($bill->status === 'Paid')
                                        <span class="inline-flex items-center rounded-full bg-blue-100 px-4 py-1.5 text-[10px] font-black uppercase tracking-wider text-blue-600">
                                            <span class="mr-2 h-1.5 w-1.5 rounded-full bg-blue-600"></span> Paid
                                        </span>
                                    @elseif($bill->status === 'Pending')
                                        <span class="inline-flex items-center rounded-full bg-amber-100 px-4 py-1.5 text-[10px] font-black uppercase tracking-wider text-amber-600">
                                            <span class="mr-2 h-1.5 w-1.5 rounded-full bg-amber-600"></span> Pending
                                        </span>
                                    @elseif($bill->status === 'Overdue')
                                        <span class="inline-flex items-center rounded-full bg-red-100 px-4 py-1.5 text-[10px] font-black uppercase tracking-wider text-red-600">
                                            <span class="mr-2 h-1.5 w-1.5 rounded-full bg-red-600"></span> Overdue
                                        </span>
                                    @else
                                        <span class="inline-flex items-center rounded-full bg-slate-100 px-4 py-1.5 text-[10px] font-black uppercase tracking-wider text-slate-600">
                                            <span class="mr-2 h-1.5 w-1.5 rounded-full bg-slate-500"></span> {{ $bill->status ?? 'N/A' }}
                                        </span>
                                    @endif
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
            </div>

            <div class="flex items-center justify-between border-t border-slate-100 bg-slate-50/50 px-10 py-5">
                <p class="text-xs font-bold italic text-slate-400">
                    Showing
                    <span class="text-slate-600">{{ number_format($bills->firstItem() ?? 0) }}-{{ number_format($bills->lastItem() ?? 0) }}</span>
                    of {{ number_format($bills->total()) }} bills
                </p>

                <x-pagination-links :paginator="$bills" />
            </div>
        </div>
    </div>
</x-layout>

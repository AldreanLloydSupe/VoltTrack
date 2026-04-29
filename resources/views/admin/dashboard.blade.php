<x-layout title="Admin Dashboard | VoltTrack">
    <main class="p-6 md:p-8 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto">
            <div class="mb-6">
                @if (session('success'))
                    <div class="mb-4 rounded-xl border border-emerald-300 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-700">
                        {{ session('success') }}
                    </div>
                @endif
                <h1 class="text-3xl md:text-4xl font-bold text-slate-900 mt-1">Admin Dashboard</h1>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-6">
                <div class="bg-white border border-slate-200 rounded-xl p-5">
                    <p class="text-xs text-slate-500 uppercase font-semibold">Total Boarding Houses</p>
                    <p class="mt-2 text-2xl font-bold text-slate-900">{{ number_format($totalBoardingHouses) }}</p>
                </div>
                <div class="bg-white border border-slate-200 rounded-xl p-5">
                    <p class="text-xs text-slate-500 uppercase font-semibold">Active Rentals</p>
                    <p class="mt-2 text-2xl font-bold text-slate-900">{{ number_format($activeRentals) }}</p>
                </div>
                <div class="bg-white border border-slate-200 rounded-xl p-5">
                    <p class="text-xs text-slate-500 uppercase font-semibold">Overdue Payments</p>
                    <p class="mt-2 text-2xl font-bold text-rose-600">{{ number_format($overduePayments) }}</p>
                </div>
                <div class="bg-white border border-slate-200 rounded-xl p-5">
                    <p class="text-xs text-slate-500 uppercase font-semibold">Collected This Month</p>
                    <p class="mt-2 text-2xl font-bold text-slate-900">PHP {{ number_format($collectedMonthly, 2) }}</p>
                </div>
            </div>

            <div class="flex flex-wrap gap-2 mb-6">
                <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-semibold">
                    Approved Residents: {{ number_format($approvedResidents) }}
                </span>
                <span class="px-3 py-1 bg-amber-100 text-amber-700 rounded-full text-xs font-semibold">
                    Pending Approvals: {{ number_format($pendingApprovals) }}
                </span>
                <span class="px-3 py-1 bg-slate-200 text-slate-700 rounded-full text-xs font-semibold">
                    Pending/Overdue Bills: {{ number_format($pendingBillsCount) }}
                </span>
            </div>

            <div class="bg-white border border-slate-200 rounded-xl overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-slate-900">Pending Transactions</h2>
                    <p class="text-xs text-slate-500">Showing {{ $pending_bills->count() }} of {{ number_format($pendingBillsCount) }}</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-slate-50 text-slate-600 uppercase text-xs">
                            <tr>
                                <th class="px-5 py-3 text-left font-extrabold">
                                    Resident
                                </th>
                                <th class="px-5 py-3 text-center font-extrabold">
                                    Property
                                </th>
                                <th class="px-5 py-3 text-center font-extrabold">
                                    Utility
                                </th>
                                <th class="px-5 py-3 text-center font-extrabold">
                                    Balance
                                </th>
                                <th class="px-5 py-3 text-center font-extrabold">
                                    Reading Date
                                </th>
                                <th class="px-5 py-3 text-center font-extrabold">
                                    Status
                                </th>
                                <th class="px-5 py-3 text-right font-extrabold">
                                    Action
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($pending_bills as $bill)
                                <tr>
                                    <td class="px-5 py-4">
                                        <p class="font-semibold text-slate-900">{{ $bill->user?->first_name }} {{ $bill->user?->last_name }}</p>
                                        <p class="text-xs text-slate-500">{{ $bill->user?->email ?? 'No email' }}</p>
                                    </td>
                                    <td class="px-5 py-4 text-center">{{ $bill->user?->property?->property_unit_id ?? 'N/A' }}</td>
                                    <td class="px-5 py-4 text-center">{{ $bill->utility_type }}</td>
                                    <td class="px-5 py-4 text-center font-semibold">PHP {{ number_format($bill->total_bill, 2) }}</td>
                                    <td class="px-5 py-4 text-center">{{ $bill->reading_date?->format('M d, Y') ?? 'N/A' }}</td>
                                    <td class="px-5 py-4 text-center">
                                        <span class="px-2 py-1 rounded text-xs font-semibold {{ $bill->status === 'Overdue' ? 'bg-rose-100 text-rose-700' : 'bg-amber-100 text-amber-700' }}">
                                            {{ $bill->status }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-4 text-right">
                                        @if($bill->user_id)
                                            <a href="{{ route('admin.residentInfo', $bill->user_id) }}" class="text-blue-600 font-semibold hover:underline">View Account</a>
                                        @else
                                            <span class="text-slate-400">N/A</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-5 py-8 text-center text-slate-500">
                                        No pending or overdue bills right now.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
</x-layout>

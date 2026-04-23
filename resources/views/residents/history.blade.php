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
                                <th class="py-3 pl-4">Paid At</th>
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
                                @endphp
                                <tr class="border-b border-slate-100 text-sm text-slate-700 last:border-b-0">
                                    <td class="py-4 pr-4 font-semibold text-slate-600">{{ $bill->payment_reference ?: 'No reference yet' }}</td>
                                    <td class="px-4 py-4">{{ $bill->utility_type }}</td>
                                    <td class="px-4 py-4">
                                        {{ \Illuminate\Support\Carbon::parse($bill->billing_period_start)->format('M d') }}
                                        -
                                        {{ \Illuminate\Support\Carbon::parse($bill->billing_period_end)->format('M d, Y') }}
                                    </td>
                                    <td class="px-4 py-4 font-bold text-slate-900">PHP {{ number_format($bill->total_bill, 2) }}</td>
                                    <td class="px-4 py-4">
                                        <span class="text-[11px] font-black uppercase tracking-[0.16em] {{ $statusClasses }}">
                                            {{ $bill->status }}
                                        </span>
                                    </td>
                                    <td class="py-4 pl-4 text-slate-500">
                                        {{ $bill->paid_at ? \Illuminate\Support\Carbon::parse($bill->paid_at)->format('M d, Y h:i A') : 'Not paid yet' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-12 text-center text-sm text-slate-400">
                                        No payment history yet. Bills will appear here after admin records them.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>
        </main>
    </div>
</x-layout>

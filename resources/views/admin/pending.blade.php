<x-layout title="Pending Residents | VoltTrack">
    <main class="p-10 bg-slate-50 min-h-screen">
        @if (session('success'))
            <div class="mb-4 rounded-xl border border-emerald-300 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-700">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="mb-4 rounded-xl border border-red-300 bg-red-50 px-4 py-3 text-sm font-semibold text-red-700">
                {{ session('error') }}
            </div>
        @endif

        <div class="flex justify-between items-end mb-4">
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                    Pending Residents >
                </p>
                <h2 class="text-5xl font-black text-[#ffffff] mt-1">
                    Pending Resident Confirmation
                </h2>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
            @php
                $stats = [
                    ['label' => 'Total Queue', 'value' => $pendingResidents->count(), 'color' => 'text-slate-900'],
                    ['label' => 'Male', 'value' => $pendingResidents->where('gender', 'Male')->count(), 'color' => 'text-slate-900'],
                    ['label' => 'Female', 'value' => $pendingResidents->where('gender', 'Female')->count(), 'color' => 'text-slate-900'],
                ];
            @endphp

            @foreach($stats as $stat)
                <div class="bg-white p-6 rounded-xl border border-slate-100 shadow-md transition-all hover:translate-y-[-2px]">
                    <p class="text-[11px] font-black text-slate-500 uppercase tracking-wider mb-2">
                        {{ $stat['label'] }}
                    </p>
                    <p class="text-4xl font-bold {{ $stat['color'] }}">
                        {{ $stat['value'] }}
                    </p>
                </div>
            @endforeach
        </div>

        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50 text-[11px] font-bold uppercase text-slate-400 tracking-widest">
                    <tr>
                        <th class="px-6 py-4">Resident</th>
                        <th class="px-6 py-4">Account ID</th>
                        <th class="px-6 py-4">Phone</th>
                        <th class="px-6 py-4">House Number</th>
                        <th class="px-6 py-4">Registered Date</th>
                        <th class="px-6 py-4 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($pendingResidents as $resident)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-5">
                                <p class="font-bold text-slate-800 text-base">
                                    {{ $resident->first_name }} {{ $resident->last_name }}
                                </p>
                                <p class="text-[11px] text-slate-400">{{ $resident->email }}</p>
                            </td>
                            <td class="px-6 py-5 text-sm font-medium text-slate-600">#{{ $resident->id }}</td>
                            <td class="px-6 py-5 text-sm font-medium text-slate-600">{{ $resident->phone_number }}</td>
                            <td class="px-6 py-5 text-sm font-medium text-slate-600">{{ $resident->house_number }}</td>
                            <td class="px-6 py-5 text-sm text-slate-500">{{ $resident->created_at?->format('M d, Y') ?? 'N/A' }}</td>
                            <td class="px-6 py-5">
                                <div class="flex items-center justify-end gap-2">
                                    <form action="{{ route('admin.pending.approve', $resident->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="rounded-lg bg-emerald-500 px-3 py-1.5 text-[11px] font-black uppercase tracking-wide text-white hover:bg-emerald-600">
                                            Approve
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.pending.reject', $resident->id) }}" method="POST" onsubmit="return confirm('Rejecting this resident will permanently delete their account. Continue?');">
                                        @csrf
                                        <button type="submit" class="rounded-lg bg-rose-500 px-3 py-1.5 text-[11px] font-black uppercase tracking-wide text-white hover:bg-rose-600">
                                            Reject
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-5 text-center text-slate-500">
                                No pending residents found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </main>
</x-layout>

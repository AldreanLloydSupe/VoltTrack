<x-layout title="Deleted Records | VoltTrack">
    <main class="min-h-screen bg-slate-50 p-6 md:p-8">
        <div class="mx-auto max-w-7xl">
            @if (session('success'))
                <div class="mb-6 rounded-xl border border-emerald-300 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-700">
                    {{ session('success') }}
                </div>
            @endif

            <div class="mb-8">
                <p class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-200">Activity Logs</p>
                <h1 class="mt-1 text-4xl font-black text-white">Deleted Records</h1>
                <p class="mt-2 max-w-2xl text-sm font-semibold text-slate-200">
                    Review deleted residents and properties, then restore records when they need to return to active lists.
                </p>
            </div>

            <section class="mb-8 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-100 px-6 py-5">
                    <p class="text-xs font-black uppercase tracking-[0.18em] text-slate-400">Residents</p>
                    <h2 class="mt-1 text-xl font-black text-slate-900">Deleted Resident Accounts</h2>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-left text-sm">
                        <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
                            <tr>
                                <th class="px-6 py-3 font-black">Resident</th>
                                <th class="px-6 py-3 font-black">Email</th>
                                <th class="px-6 py-3 font-black">Deleted At</th>
                                <th class="px-6 py-3 font-black">Auto Deletes At</th>
                                <th class="px-6 py-3 text-right font-black">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($deletedResidents as $resident)
                                <tr>
                                    <td class="px-6 py-4 font-bold text-slate-800">
                                        {{ $resident->first_name }} {{ $resident->last_name }}
                                    </td>
                                    <td class="px-6 py-4 text-slate-500">{{ $resident->email }}</td>
                                    <td class="px-6 py-4 text-slate-500">{{ $resident->deleted_at?->format('M d, Y g:i A') ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 text-slate-500">{{ $resident->deleted_at?->copy()->addDays(30)->format('M d, Y g:i A') ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 text-right">
                                        <form
                                            method="POST"
                                            action="{{ route('admin.deletedRecords.residents.restore', $resident->id) }}"
                                            data-confirm
                                            data-confirm-title="Restore Resident?"
                                            data-confirm-message="This will return {{ $resident->first_name }} {{ $resident->last_name }} to the resident list."
                                            data-confirm-confirm-label="Restore"
                                            data-confirm-variant="success"
                                        >
                                            @csrf
                                            <button type="submit" class="rounded-lg bg-emerald-500 px-4 py-2 text-xs font-black uppercase tracking-[0.14em] text-white transition-colors hover:bg-emerald-600">
                                                Restore
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-slate-500">No deleted residents.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($deletedResidents->hasPages())
                    <div class="border-t border-slate-100 bg-slate-50/50 px-6 py-4">
                        <x-pagination-links :paginator="$deletedResidents" />
                    </div>
                @endif
            </section>

            <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-100 px-6 py-5">
                    <p class="text-xs font-black uppercase tracking-[0.18em] text-slate-400">Properties</p>
                    <h2 class="mt-1 text-xl font-black text-slate-900">Deleted Properties</h2>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-left text-sm">
                        <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
                            <tr>
                                <th class="px-6 py-3 font-black">Property Unit</th>
                                <th class="px-6 py-3 font-black">Address</th>
                                <th class="px-6 py-3 font-black">Previous Resident</th>
                                <th class="px-6 py-3 font-black">Deleted At</th>
                                <th class="px-6 py-3 font-black">Auto Deletes At</th>
                                <th class="px-6 py-3 text-right font-black">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($deletedProperties as $property)
                                <tr>
                                    <td class="px-6 py-4 font-bold text-slate-800">{{ $property->property_unit_id }}</td>
                                    <td class="px-6 py-4 text-slate-500">{{ $property->physical_address }}</td>
                                    <td class="px-6 py-4 text-slate-500">
                                        {{ trim(($property->user?->first_name ?? '') . ' ' . ($property->user?->last_name ?? '')) ?: 'Unassigned' }}
                                    </td>
                                    <td class="px-6 py-4 text-slate-500">{{ $property->deleted_at?->format('M d, Y g:i A') ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 text-slate-500">{{ $property->deleted_at?->copy()->addDays(30)->format('M d, Y g:i A') ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 text-right">
                                        <form
                                            method="POST"
                                            action="{{ route('admin.deletedRecords.properties.restore', $property->id) }}"
                                            data-confirm
                                            data-confirm-title="Restore Property?"
                                            data-confirm-message="This will return property {{ $property->property_unit_id }} to the property list."
                                            data-confirm-confirm-label="Restore"
                                            data-confirm-variant="success"
                                        >
                                            @csrf
                                            <button type="submit" class="rounded-lg bg-emerald-500 px-4 py-2 text-xs font-black uppercase tracking-[0.14em] text-white transition-colors hover:bg-emerald-600">
                                                Restore
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-8 text-center text-slate-500">No deleted properties.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($deletedProperties->hasPages())
                    <div class="border-t border-slate-100 bg-slate-50/50 px-6 py-4">
                        <x-pagination-links :paginator="$deletedProperties" />
                    </div>
                @endif
            </section>
        </div>
    </main>
</x-layout>

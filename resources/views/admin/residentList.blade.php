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
                <div id="resident-view-switcher" class="flex rounded-lg bg-slate-200/50 p-1 text-[10px] font-bold uppercase tracking-widest">
                    <button type="button" data-view="table" class="resident-view-option rounded-md bg-white px-4 py-1.5 text-[#1e3a8a] shadow-sm transition-colors">
                        Table View
                    </button>
                    <button type="button" data-view="grid" class="resident-view-option rounded-md px-4 py-1.5 text-slate-500 transition-colors hover:text-slate-700">
                        Grid View
                    </button>
                </div>

                <div class="relative inline-block">
                    <select
                        id="unitTypeFilter"
                        class="appearance-none bg-white border border-slate-200 rounded-lg pl-10 pr-10 py-2 font-bold text-sm text-slate-700 shadow-sm cursor-pointer outline-none focus:ring-2 focus:ring-blue-500 transition-all"
                    >
                        <option value="All">Unit Type: All</option>
                        <option value="Residential">Residential</option>
                        <option value="Commercial">Commercial</option>
                    </select>

                    <i class="fas fa-building absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                    <i class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 text-[10px] pointer-events-none"></i>
                </div>
                
            </div>

            {{-- Search Bar --}}
            <form action="{{ route('admin.residentList') }}" method="GET" class="flex items-center gap-2 flex-1 max-w-md">
                <div class="relative flex-1">
                    <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input type="text" name="search" placeholder="Search renter name..." value="{{ request('search') }}"
                        class="w-full pl-12 pr-4 py-2.5 bg-white border border-slate-200 rounded-full shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition-all">
                </div>
                
                <button type="submit" 
                    class="bg-[#001D4E] hover:bg-blue-900 text-white px-6 py-2.5 rounded-full font-bold text-sm shadow-sm transition-colors duration-200 flex items-center">
                    Search
                </button>
            </form>
        </div>

        <div class="bg-white rounded-[20px] border border-slate-200 shadow-[0_20px_50px_rgba(0,0,0,0.05)] overflow-hidden">
            <div id="resident-table-view">
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
                        <tr class="resident-row hover:bg-slate-50/80 transition-colors group" data-unit-type="{{ $resident->property->unit_type ?? 'N/A' }}">
                            <td class="px-8 py-6">
                                <p class="font-bold text-[#0f172a] text-base group-hover:text-blue-600 transition-colors">
                                    {{ $resident->first_name }} {{ $resident->last_name }}
                                </p>
                                <p class="text-[12px] text-slate-400">{{ $resident->email }}</p>
                            </td>
                            <td class="px-8 py-6 text-sm font-medium text-slate-600">#{{ $resident->id }}</td>
                            <td class="px-8 py-6 text-sm font-bold text-slate-800">
                                {{ $resident->property->unit_type ?? 'N/A' }}
                            </td>
                            <td class="px-8 py-6 text-sm text-slate-500">
                                {{ $resident->created_at?->format('M d, Y') ?? 'N/A' }}
                            </td>
                            <td class="px-8 py-6 text-sm text-slate-500">
                                {{ $resident->bills->where('status', 'Paid')->sortByDesc('paid_at')->first()?->paid_at?->format('M d, Y') ?? 'No payments' }}
                            </td>
                            <td class="px-8 py-6 text-right">
                                <a href="{{ route('admin.residentInfo', $resident->id) }}" class="text-[12px] font-black text-blue-600 uppercase hover:underline">
                                    View
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr id="emptyResidentRow">
                            <td colspan="6" class="px-8 py-6 text-center text-slate-500">
                                No residents found
                            </td>
                        </tr>
                        @endforelse
                        @if($residents->isNotEmpty())
                            <tr id="noFilteredResidentsRow" class="hidden">
                                <td colspan="6" class="px-8 py-6 text-center text-slate-500">
                                    No residents match this unit type.
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            <div id="resident-grid-view" class="hidden grid-cols-1 gap-5 p-6 md:grid-cols-2 xl:grid-cols-3">
                @forelse($residents as $resident)
                    @php($lastPaid = $resident->bills->where('status', 'Paid')->sortByDesc('paid_at')->first())
                    <article class="resident-card rounded-2xl border border-slate-100 bg-slate-50/70 p-6 transition-all hover:-translate-y-0.5 hover:border-blue-100 hover:bg-white hover:shadow-xl hover:shadow-slate-200/60" data-unit-type="{{ $resident->property->unit_type ?? 'N/A' }}">
                        <div class="mb-5 flex items-start justify-between gap-4">
                            <div>
                                <p class="text-[10px] font-black uppercase tracking-[0.18em] text-slate-400">Resident</p>
                                <h3 class="mt-1 text-xl font-black tracking-tight text-[#0f172a]">
                                    {{ $resident->first_name }} {{ $resident->last_name }}
                                </h3>
                                <p class="mt-1 text-xs font-medium text-slate-400">{{ $resident->email }}</p>
                            </div>
                            <span class="rounded-full bg-blue-100 px-3 py-1.5 text-[10px] font-black uppercase tracking-wider text-blue-600">
                                #{{ $resident->id }}
                            </span>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div class="rounded-xl border border-slate-100 bg-white p-4">
                                <p class="text-[10px] font-black uppercase tracking-[0.16em] text-slate-400">Unit Type</p>
                                <p class="mt-2 text-sm font-black text-slate-700">{{ $resident->property->unit_type ?? 'N/A' }}</p>
                            </div>
                            <div class="rounded-xl border border-slate-100 bg-white p-4">
                                <p class="text-[10px] font-black uppercase tracking-[0.16em] text-slate-400">Applied</p>
                                <p class="mt-2 text-sm font-black text-slate-700">{{ $resident->created_at?->format('M d, Y') ?? 'N/A' }}</p>
                            </div>
                        </div>

                        <div class="mt-4 rounded-xl border border-blue-100 bg-blue-50/70 p-4">
                            <p class="text-[10px] font-black uppercase tracking-[0.16em] text-blue-600">Last Payment</p>
                            <p class="mt-1 text-lg font-black text-[#1e3a8a]">{{ $lastPaid?->paid_at?->format('M d, Y') ?? 'No payments' }}</p>
                        </div>

                        <div class="mt-5 flex justify-end border-t border-slate-100 pt-4">
                            <a href="{{ route('admin.residentInfo', $resident->id) }}" class="text-[12px] font-black uppercase text-blue-600 hover:underline">
                                View Account
                            </a>
                        </div>
                    </article>
                @empty
                    <div class="col-span-full px-8 py-6 text-center text-slate-500">
                        No residents found
                    </div>
                @endforelse

                @if($residents->isNotEmpty())
                    <div id="noFilteredResidentCards" class="col-span-full hidden px-8 py-6 text-center text-slate-500">
                        No residents match this unit type.
                    </div>
                @endif
            </div>

            <div class="bg-slate-50/50 px-8 py-4 flex items-center justify-between border-t border-slate-100">
                <p id="residentListCount" class="text-xs font-bold text-slate-400">
                    Showing {{ $residents->count() }} of {{ $residents->count() }} renters
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

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const unitTypeFilter = document.getElementById('unitTypeFilter');
            const rows = Array.from(document.querySelectorAll('.resident-row'));
            const cards = Array.from(document.querySelectorAll('.resident-card'));
            const noFilteredResidentsRow = document.getElementById('noFilteredResidentsRow');
            const noFilteredResidentCards = document.getElementById('noFilteredResidentCards');
            const residentListCount = document.getElementById('residentListCount');
            const switcher = document.getElementById('resident-view-switcher');
            const tableView = document.getElementById('resident-table-view');
            const gridView = document.getElementById('resident-grid-view');
            const totalRows = rows.length;
            const activeClasses = ['bg-white', 'text-[#1e3a8a]', 'shadow-sm'];
            const inactiveClasses = ['text-slate-500', 'hover:text-slate-700'];

            if (!unitTypeFilter) {
                return;
            }

            const applyUnitTypeFilter = () => {
                const selectedUnitType = unitTypeFilter.value;
                let visibleRows = 0;

                rows.forEach((row) => {
                    const matches = selectedUnitType === 'All' || row.dataset.unitType === selectedUnitType;
                    row.classList.toggle('hidden', !matches);

                    if (matches) {
                        visibleRows++;
                    }
                });

                cards.forEach((card) => {
                    const matches = selectedUnitType === 'All' || card.dataset.unitType === selectedUnitType;
                    card.classList.toggle('hidden', !matches);
                });

                noFilteredResidentsRow?.classList.toggle('hidden', visibleRows > 0);
                noFilteredResidentCards?.classList.toggle('hidden', visibleRows > 0);

                if (residentListCount) {
                    residentListCount.textContent = `Showing ${visibleRows} of ${totalRows} renters`;
                }
            };

            const setView = (view, selectedButton = null) => {
                tableView?.classList.toggle('hidden', view !== 'table');
                gridView?.classList.toggle('hidden', view !== 'grid');
                gridView?.classList.toggle('grid', view === 'grid');

                switcher?.querySelectorAll('.resident-view-option').forEach((button) => {
                    button.classList.remove(...activeClasses);
                    button.classList.add(...inactiveClasses);
                });

                const button = selectedButton || switcher?.querySelector(`[data-view="${view}"]`);

                if (button) {
                    button.classList.remove(...inactiveClasses);
                    button.classList.add(...activeClasses);
                }
            };

            switcher?.addEventListener('click', (event) => {
                const button = event.target.closest('.resident-view-option');

                if (!button) {
                    return;
                }

                setView(button.dataset.view, button);
            });

            unitTypeFilter.addEventListener('change', applyUnitTypeFilter);
            setView('table');
            applyUnitTypeFilter();
        });
    </script>
</x-layout>

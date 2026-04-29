<x-layout title="Property & Meter Registry | VoltTrack">
    <main class="p-10 bg-slate-50 min-h-screen font-sans">
        @if (session('success'))
            <div class="mb-6 rounded-xl border border-emerald-300 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-700">
                {{ session('success') }}
            </div>
        @endif

        {{-- Header Section --}}
        <div class="flex justify-between items-end mb-8">
            <div>
                <nav class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">
                    Properties & Meters <span class="mx-1">></span>
                </nav>
                <h1 class="text-4xl font-black text-[#0f172a] leading-tight">
                    Property & Meter Registry
                </h1>
            </div>
            
            <div class="flex items-center space-x-6">
                {{-- View Switcher --}}
                <div class="flex bg-slate-200/50 p-1 rounded-lg text-[11px] font-bold uppercase tracking-tight">
                    <button class="bg-white px-4 py-1.5 rounded shadow-sm text-blue-600 transition-all">Table View</button>
                    <button class="px-4 py-1.5 text-slate-400 hover:text-slate-600 transition-all">Grid View</button>
                </div>
                
                {{-- UPDATED: Primary Action as Link --}}
                <a href="{{ route('admin.createnew') }}" class="bg-[#1e3a8a] hover:bg-blue-900 text-white text-[11px] font-black px-6 py-3.5 rounded-lg shadow-lg shadow-blue-900/20 uppercase tracking-wider transition-all active:scale-95">
                    Add Property
                </a>
            </div>
        </div>

        {{-- Table Container --}}
        <div class="bg-white rounded-[24px] shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 p-8">
            
            {{-- Filter and Search Row --}}
            <div class="flex justify-between items-center mb-10">
                <div class="flex items-center space-x-6">
                    <div class="flex items-center gap-4">
                        <div id="unit-type-filter" class="flex items-center bg-slate-100/50 rounded-full p-1 border border-slate-200">
                            <span class="text-[9px] font-black text-slate-800 uppercase tracking-[0.15em] px-4">Type</span>
                            <button type="button"
                                data-unit-type="all"
                                class="unit-type-option {{ !request('unit_type') ? 'bg-[#1e3a8a] text-white shadow-md' : 'text-slate-400 hover:text-slate-600' }} px-5 py-1.5 rounded-full text-[10px] font-black uppercase tracking-wider transition-colors">
                                All
                            </button>
                            <button type="button"
                                data-unit-type="Residential"
                                class="unit-type-option {{ request('unit_type') === 'Residential' ? 'bg-[#1e3a8a] text-white shadow-md' : 'text-slate-400 hover:text-slate-600' }} px-5 py-1.5 rounded-full text-[10px] font-black uppercase tracking-wider transition-colors">
                                Residential
                            </button>
                            <button type="button"
                                data-unit-type="Commercial"
                                class="unit-type-option {{ request('unit_type') === 'Commercial' ? 'bg-[#1e3a8a] text-white shadow-md' : 'text-slate-400 hover:text-slate-600' }} px-5 py-1.5 rounded-full text-[10px] font-black uppercase tracking-wider transition-colors">
                                Commercial
                            </button>
                        </div>

                        <div class="flex items-center gap-2">
                            <label for="status" class="text-[9px] font-black text-slate-800 uppercase tracking-[0.15em]">Status:</label>
                            <select
                                id="status"
                                name="status"
                                class="rounded-full border border-slate-200 bg-slate-100/50 px-4 py-2 text-[10px] font-black uppercase tracking-wider text-slate-600 outline-none focus:border-blue-200"
                            >
                                <option value="">All</option>
                                <option value="Active" @selected(request('status') === 'Active')>Active</option>
                                <option value="Inactive" @selected(request('status') === 'Inactive')>Vacant</option>
                                <option value="Archived" @selected(request('status') === 'Archived')>Archived</option>
                            </select>
                        </div>
                    </div>
                    <span class="text-xs font-medium italic text-slate-400">
                        Showing <span id="visible-property-count">{{ number_format($properties->count()) }}</span> of {{ number_format($totalAssets) }} recorded assets
                    </span>
                </div>

                {{-- Search Bar --}}
                <div class="flex items-center gap-3">
                    <div class="relative group">
                        <span class="absolute inset-y-0 left-5 flex items-center text-blue-500 transition-colors group-focus-within:text-blue-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </span>
                        <input
                            id="property-search"
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Search resident..."
                            class="pl-12 pr-8 py-3.5 w-80 rounded-full bg-slate-50 border border-slate-100 focus:bg-white focus:border-blue-200 focus:outline-none focus:ring-4 focus:ring-blue-50 text-sm transition-all placeholder:text-slate-400"
                        >
                    </div>
                    <button type="button" id="property-search-button" class="rounded-full bg-[#1e3a8a] px-6 py-3 text-[11px] font-black uppercase tracking-wider text-white shadow-lg shadow-blue-900/20 transition-all hover:bg-blue-900 active:scale-95">
                        Search
                    </button>
                </div>
            </div>

            {{-- Registry Table --}}
            <div class="overflow-hidden">
                <table class="w-full text-left">
                    <thead class="text-[10px] font-black uppercase text-slate-400 tracking-[0.2em] border-b border-slate-50">
                        <tr>
                            <th class="px-6 py-5">Property ID</th>
                            <th class="px-6 py-5">Resident</th>
                            <th class="px-6 py-5">Unit Type</th>
                            <th class="px-6 py-5">Electricity Meter No.</th>
                            <th class="px-6 py-5">Water Meter No.</th>
                            <th class="px-6 py-5">Status</th>
                            <th class="px-6 py-5 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody id="property-table-body" class="divide-y divide-slate-50 text-[13px]">
                        @forelse($properties as $property)
                        @php
                            $electricMeter = $property->meters->firstWhere('utility_type', 'Electricity');
                            $waterMeter = $property->meters->firstWhere('utility_type', 'Water');
                        @endphp
                        <tr
                            class="property-row hover:bg-slate-50/80 transition-all group"
                            data-unit-type="{{ $property->unit_type ?? '' }}"
                            data-status="{{ $property->status ?? '' }}"
                            data-search="{{ strtolower(trim(($property->property_unit_id ?? "#{$property->id}") . ' ' . ($property->user?->first_name ?? '') . ' ' . ($property->user?->last_name ?? '') . ' ' . ($property->user?->email ?? '') . ' ' . ($property->unit_type ?? '') . ' ' . ($electricMeter?->hardware_meter_number ?? $electricMeter?->serial_number ?? '') . ' ' . ($waterMeter?->hardware_meter_number ?? $waterMeter?->serial_number ?? '') . ' ' . ($property->status ?? ''))) }}"
                        >
                            <td class="px-6 py-6 font-bold text-slate-400 group-hover:text-slate-600">{{ $property->property_unit_id ?? "#{$property->id}" }}</td>
                            <td class="px-6 py-6">
                                @if($property->user)
                                    <p class="font-black text-[#1e293b]">
                                        {{ $property->user->first_name }} {{ $property->user->last_name }}
                                    </p>
                                    <p class="text-[11px] text-slate-400">{{ $property->user->email }}</p>
                                @else
                                    <span class="text-slate-400 font-medium">Unassigned</span>
                                @endif
                            </td>
                            <td class="px-6 py-6 font-black text-[#1e293b]">{{ $property->unit_type ?? 'N/A' }}</td>
                            <td class="px-6 py-6 text-slate-500 font-medium">
                                {{ $electricMeter?->hardware_meter_number ?? $electricMeter?->serial_number ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-6 text-slate-500 font-medium">
                                {{ $waterMeter?->hardware_meter_number ?? $waterMeter?->serial_number ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-6">
                                @if($property->status === 'Active')
                                    <span class="bg-blue-50 text-blue-700 text-[10px] font-black px-4 py-1.5 rounded-full flex items-center w-fit border border-blue-100/50">
                                        <span class="w-1.5 h-1.5 bg-blue-500 rounded-full mr-2.5 animate-pulse"></span> ACTIVE
                                    </span>
                                @elseif($property->status === 'Inactive')
                                    <span class="bg-emerald-50 text-emerald-700 text-[10px] font-black px-4 py-1.5 rounded-full flex items-center w-fit border border-emerald-100/50">
                                        <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full mr-2.5"></span> VACANT
                                    </span>
                                @else
                                    <span class="bg-slate-100 text-slate-700 text-[10px] font-black px-4 py-1.5 rounded-full flex items-center w-fit border border-slate-200/50">
                                        {{ $property->status ?? 'N/A' }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-6 text-right">
                                <a href="{{ route('admin.propertyInfo', $property->id) }}" class="text-[11px] font-black text-blue-600 uppercase tracking-widest hover:text-blue-800 hover:underline transition-all">View</a>
                            </td>
                        </tr>
                        @empty
                        <tr id="empty-property-row">
                            <td colspan="7" class="px-6 py-6 text-center text-slate-500">
                                No properties found
                            </td>
                        </tr>
                        @endforelse
                        <tr id="no-filtered-properties-row" class="hidden">
                            <td colspan="7" class="px-6 py-6 text-center text-slate-500">
                                No properties found for the selected filters
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- Pagination Footer --}}
            <div class="mt-10 flex justify-between items-center text-[11px] font-bold text-slate-400 pt-8 border-t border-slate-50">
                <p>
                    Showing
                    <span class="text-slate-600">{{ number_format($properties->firstItem() ?? 0) }}-{{ number_format($properties->lastItem() ?? 0) }}</span>
                    of {{ number_format($properties->total()) }} assets
                </p>
                <div class="[&_nav]:inline [&_nav]:text-sm [&_nav]:font-semibold [&_nav_.relative.inline-flex.items-center.px-2.py-2.text-sm]:hidden">
                    {{ $properties->links() }}
                </div>
            </div>
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const filter = document.getElementById('unit-type-filter');
            const statusSelect = document.getElementById('status');
            const searchInput = document.getElementById('property-search');
            const searchButton = document.getElementById('property-search-button');
            const rows = Array.from(document.querySelectorAll('.property-row'));
            const emptyFilteredRow = document.getElementById('no-filtered-properties-row');
            const visibleCount = document.getElementById('visible-property-count');
            const activeClasses = ['bg-[#1e3a8a]', 'text-white', 'shadow-md'];
            const inactiveClasses = ['text-slate-400', 'hover:text-slate-600'];

            if (!filter || rows.length === 0) {
                return;
            }

            const setActiveButton = (selectedButton) => {
                filter.querySelectorAll('.unit-type-option').forEach((button) => {
                    button.classList.remove(...activeClasses);
                    button.classList.add(...inactiveClasses);
                });

                selectedButton.classList.remove(...inactiveClasses);
                selectedButton.classList.add(...activeClasses);
            };

            const applyFilters = () => {
                const activeUnitType = filter.querySelector('.unit-type-option.bg-\\[\\#1e3a8a\\]')?.dataset.unitType || 'all';
                const activeStatus = statusSelect?.value || '';
                const searchTerm = searchInput?.value.trim().toLowerCase() || '';
                let shownRows = 0;

                rows.forEach((row) => {
                    const matchesUnitType = activeUnitType === 'all' || row.dataset.unitType === activeUnitType;
                    const matchesStatus = activeStatus === '' || row.dataset.status === activeStatus;
                    const matchesSearch = searchTerm === '' || row.dataset.search.includes(searchTerm);
                    const shouldShow = matchesUnitType && matchesStatus && matchesSearch;
                    row.classList.toggle('hidden', !shouldShow);

                    if (shouldShow) {
                        shownRows += 1;
                    }
                });

                if (emptyFilteredRow) {
                    emptyFilteredRow.classList.toggle('hidden', shownRows > 0);
                }

                if (visibleCount) {
                    visibleCount.textContent = shownRows.toLocaleString();
                }
            };

            filter.addEventListener('click', (event) => {
                const button = event.target.closest('.unit-type-option');

                if (!button) {
                    return;
                }

                setActiveButton(button);
                applyFilters();
            });

            statusSelect?.addEventListener('change', applyFilters);
            searchInput?.addEventListener('input', applyFilters);
            searchButton?.addEventListener('click', applyFilters);

            const initialButton = filter.querySelector('.unit-type-option.bg-\\[\\#1e3a8a\\]') || filter.querySelector('[data-unit-type="all"]');
            setActiveButton(initialButton);
            applyFilters();
        });
    </script>
</x-layout>

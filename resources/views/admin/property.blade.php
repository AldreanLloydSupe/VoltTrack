<x-layout title="Property & Meter Registry | VoltTrack">
    @php
        $viewMode = request('view') === 'table' ? 'table' : 'grid';
        $isGridView = $viewMode === 'grid';
    @endphp

    <main class="min-h-screen bg-slate-50 p-10 font-sans">
        @if (session('success'))
            <div class="mb-6 rounded-xl border border-emerald-300 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-700">
                {{ session('success') }}
            </div>
        @endif

        <div class="mb-8 flex items-end justify-between">
            <div>
                <nav class="mb-1 text-[10px] font-bold uppercase tracking-widest text-slate-400">
                    Properties & Meters <span class="mx-1">></span>
                </nav>
                <h1 class="text-4xl font-black leading-tight text-[#ffffff]">
                    Property & Meter Registry
                </h1>
            </div>

            <div class="flex items-center gap-6">
                <div id="property-view-switcher" class="flex rounded-lg bg-slate-200/50 p-1 text-[11px] font-bold uppercase tracking-tight">
                    <button type="button" data-view="table" class="property-view-option {{ ! $isGridView ? 'bg-white text-blue-600 shadow-sm' : 'text-slate-400 hover:text-slate-600' }} rounded px-4 py-1.5 transition-all">
                        Table View
                    </button>
                    <button type="button" data-view="grid" class="property-view-option {{ $isGridView ? 'bg-white text-blue-600 shadow-sm' : 'text-slate-400 hover:text-slate-600' }} rounded px-4 py-1.5 transition-all">
                        Grid View
                    </button>
                </div>

                <a href="{{ route('admin.createnew') }}" data-instant-nav class="rounded-lg bg-[#1e3a8a] px-6 py-3.5 text-[11px] font-black uppercase tracking-wider text-white shadow-lg shadow-blue-900/20 transition-all hover:bg-blue-900 active:scale-95">
                    Add Property
                </a>
            </div>
        </div>

        <div class="rounded-[24px] border border-slate-100 bg-white p-8 shadow-[0_8px_30px_rgb(0,0,0,0.04)]">
            <div class="mb-10 flex items-center justify-between gap-6">
                <div class="flex items-center gap-6">
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
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </span>
                        <input
                            id="property-search"
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Search resident..."
                            class="w-80 rounded-full border border-slate-100 bg-slate-50 py-3.5 pl-12 pr-8 text-sm transition-all placeholder:text-slate-400 focus:border-blue-200 focus:bg-white focus:outline-none focus:ring-4 focus:ring-blue-50"
                        >
                    </div>
                    <button type="button" id="property-search-button" class="rounded-full bg-[#1e3a8a] px-6 py-3 text-[11px] font-black uppercase tracking-wider text-white shadow-lg shadow-blue-900/20 transition-all hover:bg-blue-900 active:scale-95">
                        Search
                    </button>
                </div>
            </div>

            <div id="grid-view" class="{{ $isGridView ? 'grid' : 'hidden' }} grid-cols-1 gap-5 md:grid-cols-2 xl:grid-cols-3">
                @forelse($properties as $property)
                    @php
                        $electricMeter = $property->meters->firstWhere('utility_type', 'Electricity');
                        $waterMeter = $property->meters->firstWhere('utility_type', 'Water');
                    @endphp
                    <article
                        class="property-row rounded-2xl border border-slate-100 bg-slate-50/70 p-6 transition-all hover:-translate-y-0.5 hover:border-blue-100 hover:bg-white hover:shadow-xl hover:shadow-slate-200/60"
                        data-unit-type="{{ $property->unit_type ?? '' }}"
                        data-status="{{ $property->status ?? '' }}"
                        data-search="{{ strtolower(trim(($property->property_unit_id ?? "#{$property->id}") . ' ' . ($property->user?->first_name ?? '') . ' ' . ($property->user?->last_name ?? '') . ' ' . ($property->user?->email ?? '') . ' ' . ($property->unit_type ?? '') . ' ' . ($electricMeter?->hardware_meter_number ?? $electricMeter?->serial_number ?? '') . ' ' . ($waterMeter?->hardware_meter_number ?? $waterMeter?->serial_number ?? '') . ' ' . ($property->status ?? ''))) }}"
                    >
                        <div class="mb-5 flex items-start justify-between gap-4">
                            <div>
                                <p class="text-[10px] font-black uppercase tracking-[0.18em] text-slate-400">Property ID</p>
                                <h2 class="mt-1 text-2xl font-black tracking-tight text-[#0f172a]">{{ $property->property_unit_id ?? "#{$property->id}" }}</h2>
                                <p class="mt-1 text-xs font-bold uppercase tracking-wider text-slate-400">{{ $property->unit_type ?? 'N/A' }}</p>
                            </div>

                            @if($property->status === 'Active')
                                <span class="inline-flex items-center rounded-full border border-blue-100/50 bg-blue-50 px-3 py-1.5 text-[10px] font-black text-blue-700">
                                    <span class="mr-2 h-1.5 w-1.5 rounded-full bg-blue-500"></span> ACTIVE
                                </span>
                            @elseif($property->status === 'Inactive')
                                <span class="inline-flex items-center rounded-full border border-emerald-100/50 bg-emerald-50 px-3 py-1.5 text-[10px] font-black text-emerald-700">
                                    <span class="mr-2 h-1.5 w-1.5 rounded-full bg-emerald-500"></span> VACANT
                                </span>
                            @else
                                <span class="inline-flex items-center rounded-full border border-slate-200/50 bg-slate-100 px-3 py-1.5 text-[10px] font-black text-slate-700">
                                    {{ $property->status ?? 'N/A' }}
                                </span>
                            @endif
                        </div>

                        <div class="mb-5 min-h-16 rounded-xl border border-slate-100 bg-white p-4">
                            <p class="text-[10px] font-black uppercase tracking-[0.18em] text-slate-400">Resident</p>
                            @if($property->user)
                                <p class="mt-1 font-black text-[#1e293b]">{{ $property->user->first_name }} {{ $property->user->last_name }}</p>
                                <p class="text-xs text-slate-400">{{ $property->user->email }}</p>
                            @else
                                <p class="mt-1 font-bold text-slate-400">Unassigned</p>
                            @endif
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div class="rounded-xl border border-amber-100 bg-amber-50/60 p-4">
                                <p class="text-[10px] font-black uppercase tracking-[0.16em] text-amber-600">Electric</p>
                                <p class="mt-2 truncate text-sm font-black text-slate-700" title="{{ $electricMeter?->hardware_meter_number ?? $electricMeter?->serial_number ?? 'N/A' }}">
                                    {{ $electricMeter?->hardware_meter_number ?? $electricMeter?->serial_number ?? 'N/A' }}
                                </p>
                            </div>
                            <div class="rounded-xl border border-blue-100 bg-blue-50/60 p-4">
                                <p class="text-[10px] font-black uppercase tracking-[0.16em] text-blue-600">Water</p>
                                <p class="mt-2 truncate text-sm font-black text-slate-700" title="{{ $waterMeter?->hardware_meter_number ?? $waterMeter?->serial_number ?? 'N/A' }}">
                                    {{ $waterMeter?->hardware_meter_number ?? $waterMeter?->serial_number ?? 'N/A' }}
                                </p>
                            </div>
                        </div>

                        <a href="{{ route('admin.propertyInfo', $property->id) }}" data-instant-nav class="mt-5 inline-flex w-full items-center justify-center rounded-xl bg-[#1e3a8a] px-4 py-3 text-[11px] font-black uppercase tracking-widest text-white shadow-lg shadow-blue-900/15 transition-all hover:bg-blue-900 active:scale-95">
                            View Details
                        </a>
                    </article>
                @empty
                    <div class="col-span-full rounded-2xl border border-dashed border-slate-200 bg-slate-50 px-6 py-12 text-center text-slate-500">
                        No properties found
                    </div>
                @endforelse
                <div id="no-filtered-properties-card" class="col-span-full hidden rounded-2xl border border-dashed border-slate-200 bg-slate-50 px-6 py-12 text-center text-slate-500">
                    No properties found for the selected filters
                </div>
            </div>

            <div id="table-view" class="{{ $isGridView ? 'hidden' : '' }} overflow-hidden">
                <table class="w-full text-left">
                    <thead class="border-b border-slate-50 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">
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
                                class="property-row group transition-all hover:bg-slate-50/80"
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
                                        <span class="font-medium text-slate-400">Unassigned</span>
                                    @endif
                                </td>
                                <td class="px-6 py-6 font-black text-[#1e293b]">{{ $property->unit_type ?? 'N/A' }}</td>
                                <td class="px-6 py-6 font-medium text-slate-500">
                                    {{ $electricMeter?->hardware_meter_number ?? $electricMeter?->serial_number ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-6 font-medium text-slate-500">
                                    {{ $waterMeter?->hardware_meter_number ?? $waterMeter?->serial_number ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-6">
                                    @if($property->status === 'Active')
                                        <span class="flex w-fit items-center rounded-full border border-blue-100/50 bg-blue-50 px-4 py-1.5 text-[10px] font-black text-blue-700">
                                            <span class="mr-2.5 h-1.5 w-1.5 rounded-full bg-blue-500"></span> ACTIVE
                                        </span>
                                    @elseif($property->status === 'Inactive')
                                        <span class="flex w-fit items-center rounded-full border border-emerald-100/50 bg-emerald-50 px-4 py-1.5 text-[10px] font-black text-emerald-700">
                                            <span class="mr-2.5 h-1.5 w-1.5 rounded-full bg-emerald-500"></span> VACANT
                                        </span>
                                    @else
                                        <span class="flex w-fit items-center rounded-full border border-slate-200/50 bg-slate-100 px-4 py-1.5 text-[10px] font-black text-slate-700">
                                            {{ $property->status ?? 'N/A' }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-6 text-right">
                                    <a href="{{ route('admin.propertyInfo', $property->id) }}" data-instant-nav class="text-[11px] font-black uppercase tracking-widest text-blue-600 transition-all hover:text-blue-800 hover:underline">View</a>
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

            <div class="mt-10 flex items-center justify-between border-t border-slate-50 pt-8 text-[11px] font-bold text-slate-400">
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
        (() => {
        const initPropertyRegistryControls = () => {
            const viewSwitcher = document.getElementById('property-view-switcher');
            const viewSections = {
                table: document.getElementById('table-view'),
                grid: document.getElementById('grid-view'),
            };
            const filter = document.getElementById('unit-type-filter');
            const statusSelect = document.getElementById('status');
            const searchInput = document.getElementById('property-search');
            const searchButton = document.getElementById('property-search-button');
            const emptyFilteredRows = Array.from(document.querySelectorAll('#no-filtered-properties-row, #no-filtered-properties-card'));
            const visibleCount = document.getElementById('visible-property-count');
            const activeClasses = ['bg-[#1e3a8a]', 'text-white', 'shadow-md'];
            const inactiveClasses = ['text-slate-400', 'hover:text-slate-600'];
            const activeViewClasses = ['bg-white', 'text-blue-600', 'shadow-sm'];
            const inactiveViewClasses = ['text-slate-400', 'hover:text-slate-600'];
            const findActiveUnitTypeButton = () => {
                return Array.from(filter?.querySelectorAll('.unit-type-option') || [])
                    .find((button) => button.classList.contains('bg-[#1e3a8a]'));
            };
            let activeUnitType = findActiveUnitTypeButton()?.dataset.unitType || 'all';

            if (!filter || filter.dataset.bound === 'true') {
                return;
            }

            filter.dataset.bound = 'true';

            const getActiveView = () => {
                return viewSections.grid && !viewSections.grid.classList.contains('hidden') ? 'grid' : 'table';
            };

            const getActiveRows = () => {
                const activeSection = viewSections[getActiveView()];

                return activeSection ? Array.from(activeSection.querySelectorAll('.property-row')) : [];
            };

            const setActiveButton = (selectedButton) => {
                activeUnitType = selectedButton?.dataset.unitType || 'all';

                filter.querySelectorAll('.unit-type-option').forEach((button) => {
                    button.classList.remove(...activeClasses);
                    button.classList.add(...inactiveClasses);
                });

                selectedButton?.classList.remove(...inactiveClasses);
                selectedButton?.classList.add(...activeClasses);
            };

            const setActiveViewButton = (selectedButton) => {
                viewSwitcher?.querySelectorAll('.property-view-option').forEach((button) => {
                    button.classList.remove(...activeViewClasses);
                    button.classList.add(...inactiveViewClasses);
                });

                selectedButton?.classList.remove(...inactiveViewClasses);
                selectedButton?.classList.add(...activeViewClasses);
            };

            const applyFilters = () => {
                const activeStatus = statusSelect?.value || '';
                const searchTerm = searchInput?.value.trim().toLowerCase() || '';
                const rows = getActiveRows();
                let shownRows = 0;

                document.querySelectorAll('.property-row').forEach((row) => {
                    row.classList.add('hidden');
                });

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

                emptyFilteredRows.forEach((emptyFilteredRow) => {
                    emptyFilteredRow.classList.toggle('hidden', shownRows > 0);
                });

                if (visibleCount) {
                    visibleCount.textContent = shownRows.toLocaleString();
                }
            };

            viewSwitcher?.addEventListener('click', (event) => {
                const button = event.target.closest('.property-view-option');

                if (!button) {
                    return;
                }

                Object.entries(viewSections).forEach(([view, section]) => {
                    if (!section) {
                        return;
                    }

                    const isSelected = view === button.dataset.view;
                    section.classList.toggle('hidden', !isSelected);
                    section.classList.toggle('grid', view === 'grid' && isSelected);
                });

                setActiveViewButton(button);
                applyFilters();
            });

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

            const initialButton = findActiveUnitTypeButton() || filter.querySelector('[data-unit-type="all"]');
            const initialViewButton = viewSwitcher?.querySelector('.property-view-option.bg-white') || viewSwitcher?.querySelector('[data-view="table"]');

            if (initialViewButton) {
                setActiveViewButton(initialViewButton);
            }

            setActiveButton(initialButton);
            applyFilters();
        };

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initPropertyRegistryControls);
        } else {
            initPropertyRegistryControls();
        }
        })();
    </script>
</x-layout>

<x-layout title="Create New Property | VoltTrack">
    <main class="w-full min-h-screen bg-slate-50 p-8 font-sans">
        <div class="max-w-6xl mx-auto">
            @if ($errors->any())
                <div class="mb-6 rounded-xl border border-red-300 bg-red-50 px-4 py-3 text-sm font-semibold text-red-700">
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.createnew.store') }}" method="POST" class="space-y-6">
                @csrf

                <div class="flex justify-between items-start mb-2">
                    <div>
                        <nav class="text-[11px] font-bold text-blue-100 uppercase tracking-[0.2em] mb-1">
                            PROPERTIES & METERS <span class="mx-1 text-blue-100/70">></span> <span class="text-white">
                                CREATE NEW PROPERTY
                            </span>
                        </nav>
                        <h1 class="text-5xl font-black text-white tracking-tight">Create New Property</h1>
                    </div>

                    <div class="flex items-center space-x-4">
                        <a href="{{ route('admin.property') }}" class="text-[12px] font-black text-slate-400 uppercase tracking-widest hover:text-slate-600 transition-all">
                            Cancel
                        </a>
                        <button type="submit" class="bg-[#1e3a8a] hover:bg-blue-900 text-white text-[12px] font-black px-8 py-4 rounded-2xl shadow-xl uppercase tracking-widest transition-all active:scale-95">
                            Create Property
                        </button>
                    </div>
                </div>

                <section class="bg-white p-8 rounded-[32px] border border-slate-200 shadow-sm">
                    <div class="flex items-center space-x-3 mb-6">
                        <div class="bg-blue-50 p-2 rounded-xl text-blue-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        </div>
                        <h2 class="text-2xl font-bold tracking-tight text-slate-700">Property Details</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-6">
                        <div>
                            <label class="block text-[11px] font-black text-slate-400 uppercase mb-2 tracking-widest">Property Unit ID</label>
                            <input name="property_unit_id" value="{{ old('property_unit_id') }}" type="text" placeholder="VT-8829-A" class="w-full bg-slate-50 border border-slate-100 rounded-xl px-6 py-4 text-lg font-bold text-slate-700 focus:ring-2 focus:ring-blue-100 outline-none" required>
                        </div>

                        <div>
                            <label class="block text-[11px] font-black text-slate-400 uppercase mb-2 tracking-widest">Assign Resident (Approved)</label>
                            <select name="user_id" class="w-full bg-slate-50 border border-slate-100 rounded-xl px-6 py-4 text-base font-bold text-slate-700 appearance-none outline-none">
                                <option value="">Unassigned</option>
                                @foreach($approvedResidents as $resident)
                                    <option value="{{ $resident->id }}" @selected((string) old('user_id') === (string) $resident->id)>
                                        {{ $resident->first_name }} {{ $resident->last_name }} (#{{ $resident->id }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-[11px] font-black text-slate-400 uppercase mb-2 tracking-widest">Cluster Housing</label>
                            <input name="cluster_housing" value="{{ old('cluster_housing') }}" type="text" placeholder="Multi-Unit Prefab Housing" class="w-full bg-slate-50 border border-slate-100 rounded-xl px-6 py-4 text-lg font-bold text-slate-700 outline-none">
                        </div>

                        <div>
                            <label class="block text-[11px] font-black text-slate-400 uppercase mb-2 tracking-widest">Unit Type</label>
                            <select name="unit_type" class="w-full bg-slate-50 border border-slate-100 rounded-xl px-6 py-4 text-lg font-bold text-slate-700 appearance-none outline-none" required>
                                <option value="Residential" @selected(old('unit_type', 'Residential') === 'Residential')>Residential</option>
                                <option value="Commercial" @selected(old('unit_type') === 'Commercial')>Commercial</option>
                            </select>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-[11px] font-black text-slate-400 uppercase mb-2 tracking-widest">Physical Address</label>
                            <input name="physical_address" value="{{ old('physical_address') }}" type="text" placeholder="Vizcaya Village, Tagum City" class="w-full bg-slate-50 border border-slate-100 rounded-xl px-6 py-4 text-lg font-bold text-slate-700 outline-none" required>
                        </div>

                        <div>
                            <label class="block text-[11px] font-black text-slate-400 uppercase mb-2 tracking-widest">Lease Commencement Date</label>
                            <input name="lease_commencement_date" value="{{ old('lease_commencement_date') }}" type="date" class="w-full bg-slate-50 border border-slate-100 rounded-xl px-6 py-4 text-lg font-bold text-slate-700 outline-none">
                        </div>

                        <div>
                            <label class="block text-[11px] font-black text-slate-400 uppercase mb-2 tracking-widest">Property Status</label>
                            <select name="status" class="w-full bg-slate-50 border border-slate-100 rounded-xl px-6 py-4 text-lg font-bold text-slate-700 appearance-none outline-none" required>
                                <option value="Active" @selected(old('status', 'Active') === 'Active')>Active</option>
                                <option value="Inactive" @selected(old('status') === 'Inactive')>Inactive</option>
                                <option value="Archived" @selected(old('status') === 'Archived')>Archived</option>
                            </select>
                        </div>
                    </div>
                </section>

                <section class="bg-white p-8 rounded-[32px] border border-slate-200 shadow-sm">
                    <div class="flex items-center space-x-3 mb-6">
                        <div class="bg-blue-50 p-2 rounded-xl text-blue-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        </div>
                        <h2 class="text-2xl font-bold tracking-tight text-slate-700">Meter Initialization (Optional)</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                        <div class="space-y-4">
                            <p class="text-xs font-black uppercase tracking-[0.2em] text-[#f59e0b]">Electricity Meter</p>
                            <input name="electric_serial_number" value="{{ old('electric_serial_number') }}" type="text" placeholder="Serial Number (required if adding meter)" class="w-full bg-slate-100 border-none rounded-xl px-6 py-4 text-base font-bold text-slate-700">
                            <div class="relative">
                                <input name="electric_initial_reading" value="{{ old('electric_initial_reading', 0) }}" type="number" step="0.01" min="0" class="w-full bg-blue-50/50 border-none rounded-xl px-6 py-4 text-xl font-black text-blue-700">
                                <span class="absolute right-6 top-1/2 -translate-y-1/2 text-xs font-bold text-slate-400 uppercase">kWh</span>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <p class="text-xs font-black uppercase tracking-[0.2em] text-blue-500">Water Meter</p>
                            <input name="water_serial_number" value="{{ old('water_serial_number') }}" type="text" placeholder="Serial Number (required if adding meter)" class="w-full bg-slate-100 border-none rounded-xl px-6 py-4 text-base font-bold text-slate-700">
                            <div class="relative">
                                <input name="water_initial_reading" value="{{ old('water_initial_reading', 0) }}" type="number" step="0.01" min="0" class="w-full bg-blue-50/50 border-none rounded-xl px-6 py-4 text-xl font-black text-blue-700">
                                <span class="absolute right-6 top-1/2 -translate-y-1/2 text-xs font-bold text-slate-400 uppercase">m3</span>
                            </div>
                        </div>
                    </div>
                </section>
            </form>
        </div>
    </main>
</x-layout>

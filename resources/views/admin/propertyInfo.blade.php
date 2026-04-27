<x-layout title="Property & Meter Registry | VoltTrack">
    <div class="max-w-6xl mx-auto py-10 px-6">
        @if (session('success'))
            <div class="mb-4 rounded-xl border border-emerald-300 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-700">
                {{ session('success') }}
            </div>
        @endif
        <nav class="flex text-[10px] font-black uppercase tracking-widest text-slate-400 mb-4 gap-2">
            <span>Properties & Meters</span>
            <i class="fas fa-chevron-right text-[8px]"></i>
            <span class="text-blue-600">Property Details</span>
        </nav>

        <div class="flex justify-between items-end mb-10">
            <h1 class="text-5xl font-extrabold text-[#001D4E] tracking-tight">Property Details</h1>
            
            <div class="flex gap-3">
                <form method="POST" action="{{ route('admin.property.destroy', $property->id) }}" onsubmit="return confirm('Delete this property record? This action cannot be undone.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-6 py-2.5 bg-rose-500 text-white font-black rounded-xl text-xs uppercase tracking-wider shadow-lg shadow-rose-200 hover:bg-rose-600 transition-all">
                        Delete Property
                    </button>
                </form>
                <button class="px-6 py-2.5 bg-[#001D4E] text-white font-black rounded-xl text-xs uppercase tracking-wider shadow-lg shadow-blue-900/20 hover:scale-[1.02] transition-all">
                    Update Property
                </button>
            </div>
        </div>

        <div class="space-y-8">
            
            <div class="bg-white p-10 rounded-[40px] border border-slate-50 shadow-2xl shadow-slate-200/60">
                <div class="flex items-center gap-4 mb-12">
                    <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center text-blue-600">
                        <i class="fas fa-building text-lg"></i>
                    </div>
                    <h3 class="text-lg font-black text-[#001D4E] uppercase tracking-wider">Property Specification</h3>
                </div>

                <div class="grid grid-cols-3 gap-y-10">
                    <div>
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1">Property ID</label>
                        <div class="flex items-center gap-2">
                            <span class="font-black text-[#001D4E] text-lg">#{{ $property->id }}</span>
                            <i class="fas fa-check-circle text-slate-300 text-xs"></i>
                        </div>
                    </div>
                    <div>
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1">Cluster Housing</label>
                        <span class="font-black text-[#001D4E] text-lg">{{ $property->cluster_housing ?? 'N/A' }}</span>
                    </div>
                    <div>
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1">Resident</label>
                        <span class="font-black text-[#001D4E] text-lg">
                            {{ $property->user?->first_name }} {{ $property->user?->last_name ?? 'Unassigned' }}
                        </span>
                    </div>

                    <div>
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1">Physical Address</label>
                        <span class="font-black text-[#001D4E] text-lg">{{ $property->physical_address ?? 'N/A' }}</span>
                    </div>
                    <div>
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1">Unit Type</label>
                        <span class="font-black text-[#001D4E] text-lg">{{ $property->unit_type ?? 'N/A' }}</span>
                    </div>
                    <div>
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1">Date Rented:</label>
                        <span class="font-black text-[#001D4E] text-lg">{{ $property->lease_commencement_date?->format('M d, Y') ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>

            <div class="bg-white p-10 rounded-[40px] border border-slate-50 shadow-2xl shadow-slate-200/60">
                <div class="flex items-center gap-4 mb-8">
                    <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center text-blue-600">
                        <i class="fas fa-sliders-h text-lg"></i>
                    </div>
                    <h3 class="text-lg font-black text-[#001D4E] uppercase tracking-wider">Assigned Metering</h3>
                </div>

                <div class="grid grid-cols-2 gap-6">
                    @php
                        $electricMeter = $property->meters->firstWhere('utility_type', 'Electricity');
                        $waterMeter = $property->meters->firstWhere('utility_type', 'Water');
                    @endphp

                    @if($electricMeter)
                    <div class="bg-slate-50 border-l-4 border-blue-600 p-6 rounded-2xl flex items-center justify-between">
                        <div>
                            <div class="flex items-center gap-2 mb-4">
                                <i class="fas fa-bolt text-[#001D4E] text-xs"></i>
                                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Electricity Meter No.</span>
                            </div>
                            <h4 class="text-2xl font-black text-[#001D4E] mb-1 tracking-tight">{{ $electricMeter->hardware_meter_number }}</h4>
                            <p class="text-[10px] font-bold text-slate-400">Serial: {{ $electricMeter->serial_number }}</p>
                        </div>
                    </div>
                    @endif

                    @if($waterMeter)
                    <div class="bg-slate-50 border-l-4 border-blue-600 p-6 rounded-2xl flex items-center justify-between">
                        <div>
                            <div class="flex items-center gap-2 mb-4">
                                <i class="fas fa-tint text-blue-500 text-xs"></i>
                                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Water Meter No.</span>
                            </div>
                            <h4 class="text-2xl font-black text-[#001D4E] mb-1 tracking-tight">{{ $waterMeter->hardware_meter_number }}</h4>
                            <p class="text-[10px] font-bold text-slate-400">Serial: {{ $waterMeter->serial_number }}</p>
                        </div>
                    </div>
                    @endif

                    @if(!$electricMeter && !$waterMeter)
                    <div class="col-span-2 p-6 bg-slate-50 rounded-2xl text-center text-slate-500">
                        No meters assigned to this property yet.
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-layout>

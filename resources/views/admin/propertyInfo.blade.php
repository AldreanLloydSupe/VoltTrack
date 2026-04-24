<x-layout title="Property & Meter Registry | VoltTrack">
    <div class="max-w-6xl mx-auto py-10 px-6">
        <nav class="flex text-[10px] font-black uppercase tracking-widest text-slate-400 mb-4 gap-2">
            <span>Properties & Meters</span>
            <span>&gt;</span>
            <span class="text-[#001D4E]">Property Details</span>
        </nav>

        <div class="flex justify-between items-center mb-8">
            <h1 class="text-4xl font-black text-[#001D4E] tracking-tight">Property Details</h1>
            
            <div class="flex gap-3">
                <button class="px-6 py-2.5 bg-rose-500 text-white font-black rounded-xl text-xs uppercase tracking-wider shadow-lg shadow-rose-200 hover:bg-rose-600 transition-all">
                    Delete Account
                </button>
                <button class="px-6 py-2.5 bg-[#001D4E] text-white font-black rounded-xl text-xs uppercase tracking-wider shadow-lg shadow-blue-900/20 hover:scale-[1.02] transition-all">
                    Update Property
                </button>
            </div>
        </div>

        <div class="space-y-6">
            
            <div class="bg-white p-8 rounded-3xl border border-slate-200 shadow-sm">
                <div class="flex items-center gap-3 mb-10">
                    <div class="text-blue-600"><i class="fas fa-building text-xl"></i></div>
                    <h3 class="text-sm font-black text-[#001D4E] uppercase tracking-widest">Property Specification</h3>
                </div>

                <div class="grid grid-cols-3 gap-y-10">
                    <div>
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1">Property ID</label>
                        <div class="flex items-center gap-2">
                            <span class="font-black text-[#001D4E] text-lg">11111</span>
                            <i class="fas fa-check-circle text-slate-300 text-xs"></i>
                        </div>
                    </div>
                    <div>
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1">Cluster Housing</label>
                        <span class="font-black text-[#001D4E] text-lg">Multi-Unit Prefab Housing</span>
                    </div>
                    <div>
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1">Resident</label>
                        <span class="font-black text-[#001D4E] text-lg">Mami Jupeta</span>
                    </div>

                    <div>
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1">Physical Address</label>
                        <span class="font-black text-[#001D4E] text-lg">Visayan Village, Tagum City</span>
                    </div>
                    <div>
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1">Unit Type</label>
                        <span class="font-black text-[#001D4E] text-lg">Residential</span>
                    </div>
                    <div>
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1">Date Rented:</label>
                        <span class="font-black text-[#001D4E] text-lg">Apr 01, 2026</span>
                    </div>
                </div>
            </div>

            <div class="bg-white p-8 rounded-3xl border border-slate-200 shadow-sm">
                <div class="flex items-center gap-3 mb-8">
                    <div class="text-blue-600"><i class="fas fa-sliders-h text-xl"></i></div>
                    <h3 class="text-sm font-black text-[#001D4E] uppercase tracking-widest">Assigned Metering</h3>
                </div>

                <div class="grid grid-cols-2 gap-6">
                    <div class="bg-slate-50 border-l-4 border-blue-600 p-6 rounded-2xl flex items-center justify-between">
                        <div>
                            <div class="flex items-center gap-2 mb-4">
                                <i class="fas fa-bolt text-[#001D4E] text-xs"></i>
                                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Electricity Meter No.</span>
                            </div>
                            <h4 class="text-2xl font-black text-[#001D4E] mb-1 tracking-tight">E-2023-X9921</h4>
                            <p class="text-[10px] font-bold text-slate-400">Added: 12 Oct 2023</p>
                        </div>
                    </div>

                    <div class="bg-slate-50 border-l-4 border-blue-600 p-6 rounded-2xl flex items-center justify-between">
                        <div>
                            <div class="flex items-center gap-2 mb-4">
                                <i class="fas fa-tint text-blue-500 text-xs"></i>
                                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Water Meter No.</span>
                            </div>
                            <h4 class="text-2xl font-black text-[#001D4E] mb-1 tracking-tight">W-881-AQ-21</h4>
                            <p class="text-[10px] font-bold text-slate-400">Added: 04 Nov 2023</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-layout>
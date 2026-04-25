<x-layout title="Property & Meter Registry | VoltTrack">
    <div class="max-w-7xl mx-auto p-12 bg-[#F8FAFC] min-h-screen font-sans">
        
        <nav class="flex items-center text-[11px] font-black uppercase tracking-[0.2em] text-slate-400 mb-4 gap-3">
            <span>Properties & Meters</span>
            <i class="fas fa-chevron-right text-[8px]"></i>
            <span class="text-blue-600">Property Details</span>
        </nav>

        <div class="flex justify-between items-end mb-10">
            <h1 class="text-5xl font-extrabold text-[#001D4E] tracking-tight">Property Details</h1>
            
            <div class="flex gap-4">
                <button class="px-8 py-3 bg-white border border-rose-100 text-rose-500 font-black rounded-2xl text-[11px] uppercase tracking-widest shadow-xl shadow-rose-100/50 hover:bg-rose-500 hover:text-white transition-all duration-300">
                    Delete Account
                </button>
                
                <a href="{{ route('admin.updateproperty') }}" class="px-8 py-3 bg-[#001D4E] text-white font-black rounded-2xl text-[11px] uppercase tracking-widest shadow-xl shadow-blue-900/20 hover:scale-[1.02] active:scale-95 transition-all duration-300 flex items-center">
                    Update Property
                </a>
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

                <div class="grid grid-cols-3 gap-x-12 gap-y-12">
                    <div class="space-y-2">
                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-[0.15em] block">Property ID</label>
                        <div class="flex items-center gap-3">
                            <span class="font-bold text-[#001D4E] text-2xl tracking-tight">11111</span>
                            <i class="fas fa-check-circle text-blue-500 text-sm"></i>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-[0.15em] block">Cluster Housing</label>
                        <span class="font-bold text-[#001D4E] text-2xl tracking-tight">Multi-Unit Prefab</span>
                    </div>
                    <div class="space-y-2">
                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-[0.15em] block">Resident</label>
                        <span class="font-bold text-[#001D4E] text-2xl tracking-tight">Mami Jupeta</span>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-[0.15em] block">Physical Address</label>
                        <span class="font-bold text-[#001D4E] text-2xl tracking-tight">Visayan Village, Tagum</span>
                    </div>
                    <div class="space-y-2">
                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-[0.15em] block">Unit Type</label>
                        <span class="font-bold text-[#001D4E] text-2xl tracking-tight">Residential</span>
                    </div>
                    <div class="space-y-2">
                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-[0.15em] block">Date Rented</label>
                        <span class="font-bold text-[#001D4E] text-2xl tracking-tight">Apr 01, 2026</span>
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

                <div class="grid grid-cols-2 gap-8">
                    <div class="bg-[#F8FAFC] border-l-[6px] border-blue-600 p-8 rounded-[32px] group hover:bg-white hover:shadow-xl transition-all duration-300">
                        <div class="flex justify-between items-start">
                            <div>
                                <div class="flex items-center gap-3 mb-6">
                                    <div class="w-8 h-8 bg-white rounded-lg flex items-center justify-center shadow-sm">
                                        <i class="fas fa-bolt text-[#001D4E] text-xs"></i>
                                    </div>
                                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Electricity Meter No.</span>
                                </div>
                                <h4 class="text-3xl font-black text-[#001D4E] mb-2 tracking-tighter">E-2023-X9921</h4>
                                <p class="text-[11px] font-bold text-slate-400">Added on <span class="text-slate-600">12 Oct 2023</span></p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-[#F8FAFC] border-l-[6px] border-blue-400 p-8 rounded-[32px] group hover:bg-white hover:shadow-xl transition-all duration-300">
                        <div class="flex justify-between items-start">
                            <div>
                                <div class="flex items-center gap-3 mb-6">
                                    <div class="w-8 h-8 bg-white rounded-lg flex items-center justify-center shadow-sm">
                                        <i class="fas fa-tint text-blue-500 text-xs"></i>
                                    </div>
                                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Water Meter No.</span>
                                </div>
                                <h4 class="text-3xl font-black text-[#001D4E] mb-2 tracking-tighter">W-881-AQ-21</h4>
                                <p class="text-[11px] font-bold text-slate-400">Added on <span class="text-slate-600">04 Nov 2023</span></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout>
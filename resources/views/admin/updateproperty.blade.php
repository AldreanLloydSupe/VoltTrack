<x-layout title="Update Property | VoltTrack">
    <div class="w-full bg-[#F3F4F6] min-h-screen font-sans">
        
        <div class="flex justify-between items-start pt-12 px-12 mb-10">
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">
                    Properties & Meters <span class="mx-1 text-slate-300">></span> Property Details <span class="mx-1 text-slate-300">></span> <span class="text-blue-700">Update Property</span>
                </p>
                <h1 class="text-5xl font-extrabold text-[#001D4E] tracking-tight">Update Property</h1>
                <p class="text-sm font-medium text-slate-500 mt-1 max-w-2xl">
                    Create a new residential and assigning meters and residents now ensures immediate billing cycles.
                </p>
            </div>
            <div class="flex items-center gap-6">
                <button type="button" class="text-[11px] font-bold text-slate-400 uppercase tracking-widest hover:text-slate-600 transition-all">
                    Cancel
                </button>
                <button type="submit" form="update-form" class="bg-[#1e3a8a] hover:bg-blue-900 text-white text-[11px] font-bold px-9 py-3.5 rounded-xl shadow-lg uppercase tracking-widest transition-all active:scale-95 text-center">
                    Update Changes
                </button>
            </div>
        </div>

        <form id="update-form" action="#" method="POST" class="px-12 pb-12 space-y-8">
            
            <section class="bg-white p-9 rounded-2xl border border-slate-100 shadow-sm">
                <div class="flex items-center space-x-2 mb-8">
                    <div class="text-blue-600">
                        <i class="fas fa-building text-lg"></i>
                    </div>
                    <h2 class="text-lg font-bold tracking-tight text-[#111827]">Property Details</h2>
                </div>

                <div class="grid grid-cols-2 gap-x-10 gap-y-6 mb-6">
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase mb-2 tracking-widest">Property Unit ID</label>
                        <input type="text" value="VT-8820-A" class="w-full bg-[#F3F4F6] border border-slate-100 rounded-xl px-5 py-3.5 text-sm font-medium text-slate-400 focus:outline-none cursor-not-allowed" readonly>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase mb-2 tracking-widest">Cluster Housing</label>
                        <div class="relative">
                            <select class="w-full bg-[#F3F4F6] border border-slate-100 rounded-xl px-5 py-3.5 text-sm font-medium text-[#111827] appearance-none outline-none focus:ring-1 focus:ring-blue-100">
                                <option>Multi-Unit Prefab Housing</option>
                            </select>
                            <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">
                                <i class="fas fa-chevron-down text-sm"></i>
                            </div>
                        </div>
                    </div>

                    <div class="col-span-2">
                        <label class="block text-[10px] font-bold text-slate-400 uppercase mb-2 tracking-widest">Physical Address</label>
                        <input type="text" value="Visayan Village, Tagum City" class="w-full bg-[#F3F4F6] border border-slate-100 rounded-xl px-5 py-3.5 text-sm font-medium text-[#111827] outline-none focus:ring-1 focus:ring-blue-100">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-x-10">
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase mb-2 tracking-widest">Unit Type</label>
                        <div class="flex p-1 bg-[#F3F4F6] rounded-xl w-fit gap-1">
                            <button type="button" class="px-10 py-3 bg-[#1D4ED8] text-white text-[11px] font-bold rounded-xl shadow-md uppercase tracking-wider">Residential</button>
                            <button type="button" class="px-10 py-3 text-slate-400 text-[11px] font-bold rounded-xl hover:text-slate-600 transition-colors uppercase tracking-wider">Commercial</button>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase mb-2 tracking-widest">Lease Commencement</label>
                        <input type="date" class="w-full bg-[#F3F4F6] border border-slate-100 rounded-xl px-5 py-3.5 text-sm font-medium text-[#111827] outline-none focus:ring-1 focus:ring-blue-100">
                    </div>
                </div>
            </section>

            <section class="bg-white p-9 rounded-2xl border border-slate-100 shadow-sm">
                <div class="flex items-center space-x-2 mb-8">
                    <div class="text-blue-600">
                        <i class="fas fa-tachometer-alt text-lg"></i>
                    </div>
                    <h2 class="text-lg font-bold tracking-tight text-[#111827]">Meter Initialization</h2>
                </div>

                <div class="grid grid-cols-2 gap-x-12">
                    <div>
                        <div class="flex items-center space-x-2.5 text-[#FBBF24] mb-3">
                            <i class="fas fa-bolt text-sm"></i>
                            <span class="text-[10px] font-black uppercase tracking-widest text-[#111827]">Electricity Meter</span>
                        </div>
                        <label class="block text-[9px] font-bold text-slate-400 uppercase mb-2 tracking-widest">Serial Number</label>
                        <input type="text" placeholder="e.g. ELEC-992-00" class="w-full bg-[#E5E7EB] border border-slate-100 rounded-xl px-5 py-3.5 text-sm font-medium text-[#111827] placeholder-slate-400 outline-none focus:ring-1 focus:ring-blue-100">
                    </div>

                    <div>
                        <div class="flex items-center space-x-2.5 text-blue-500 mb-3">
                            <i class="fas fa-tint text-sm"></i>
                            <span class="text-[10px] font-black uppercase tracking-widest text-[#111827]">Water Meter</span>
                        </div>
                        <label class="block text-[9px] font-bold text-slate-400 uppercase mb-2 tracking-widest">Serial Number</label>
                        <input type="text" placeholder="e.g. WATR-441-00" class="w-full bg-[#E5E7EB] border border-slate-100 rounded-xl px-5 py-3.5 text-sm font-medium text-[#111827] placeholder-slate-400 outline-none focus:ring-1 focus:ring-blue-100">
                    </div>
                </div>
            </section>
        </form>
    </div>
</x-layout>
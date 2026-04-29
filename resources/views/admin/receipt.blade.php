<x-layout title="Receipt | VoltTrack">
    <div class="w-full min-h-screen bg-[#334155] flex items-center justify-center font-sans p-6">
        
        <div class="w-full max-w-md bg-white rounded-3xl overflow-hidden shadow-2xl">
            

            <div class="bg-[#F8FAFC] px-10 pt-12 pb-10 flex flex-col items-center">
                
                <h2 class="text-4xl font-black text-[#001D4E] tracking-tighter mb-1">VoltTrack</h2>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-10">Tagum City</p>

                <div class="text-center mb-12">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Total Amount Payable</p>
                    <div class="flex items-start justify-center text-[#001D4E]">
                        <span class="text-3xl font-black mt-1">₱</span>
                        <h1 class="text-7xl font-black tracking-tighter">--</h1>
                    </div>
                </div>

                <div class="w-full bg-white rounded-2xl p-8 shadow-sm border border-slate-100 mb-10">
                    
                    <div class="flex justify-between items-center mb-4 pb-4 border-b border-slate-900/10">
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Units Used</span>
                        <span class="text-sm font-bold text-slate-600">--</span>
                    </div>

                    <div class="space-y-4">
                        <div class="flex justify-between items-center text-xs">
                            <span class="font-bold text-slate-400 uppercase tracking-widest text-[9px]">Base Bill</span>
                            <div class="flex items-center gap-1">
                                <span class="font-bold text-slate-600">₱</span>
                                <span class="font-bold text-slate-600">--</span>
                            </div>
                        </div>
                        <div class="flex justify-between items-center text-xs">
                            <span class="font-bold text-slate-400 uppercase tracking-widest text-[9px]">Penalty</span>
                            <div class="flex items-center gap-1">
                                <span class="font-bold text-slate-600">₱</span>
                                <span class="font-bold text-slate-600">--</span>
                            </div>
                        </div>
                         <div class="flex justify-between items-center text-xs">
                            <span class="font-bold text-slate-400 uppercase tracking-widest text-[9px]">Subtotal</span>
                            <div class="flex items-center gap-1">
                                <span class="font-bold text-slate-600">₱</span>
                                <span class="font-bold text-slate-600">--</span>
                            </div>
                        </div>
                        <div class="flex justify-between items-center text-xs">
                            <span class="font-bold text-slate-400 uppercase tracking-widest text-[9px]">VAT (12%)</span>
                            <div class="flex items-center gap-1">
                                <span class="font-bold text-slate-600">₱</span>
                                <span class="font-bold text-slate-600">--</span>
                            </div>
                        </div>
                    </div>
                </div>

                <button class="w-full bg-[#1e3a8a] hover:bg-blue-900 text-white text-[11px] font-black py-4 rounded-xl shadow-lg uppercase tracking-[0.2em] transition-all active:scale-95">
                    Done
                </button>
            </div>
        </div>
    </div>
</x-layout>
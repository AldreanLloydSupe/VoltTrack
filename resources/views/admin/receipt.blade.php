<x-layout title="Receipt | VoltTrack">
    <div class="w-full min-h-screen bg-[#334155] flex items-center justify-center font-sans p-6">
        
        <div class="w-full max-w-md bg-white rounded-3xl overflow-hidden shadow-2xl">
            
            <div class="px-6 py-4 flex items-center justify-between border-b border-slate-50">
                <button class="text-slate-400 hover:text-slate-600">
                    <i class="fas fa-times text-lg"></i>
                </button>
                <span class="text-xs font-black text-[#001D4E] uppercase tracking-[0.2em]">Receipt</span>
                <div class="w-5"></div> </div>

            <div class="bg-[#F8FAFC] px-10 pt-12 pb-10 flex flex-col items-center">
                
                <h2 class="text-2xl font-black text-[#001D4E] tracking-tight mb-1">VolTrack</h2>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-10">Tagum City</p>

                <div class="text-center mb-12">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Total Amount Paid</p>
                    <div class="flex items-start justify-center text-[#001D4E]">
                        <span class="text-3xl font-black mt-1">₱</span>
                        <h1 class="text-7xl font-black tracking-tighter">24.50</h1>
                    </div>
                </div>

                <div class="w-full bg-white rounded-2xl p-8 shadow-sm border border-slate-100 mb-10">
                    
                    <div class="flex justify-between items-center mb-4 pb-4 border-b border-slate-900/10">
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">KWH</span>
                        <span class="text-sm font-bold text-slate-600">13.00</span>
                    </div>

                    <div class="space-y-4 mb-4">
                        <div class="flex justify-between items-center text-xs">
                            <span class="font-bold text-slate-400 uppercase tracking-widest text-[9px]">Subtotal</span>
                            <span class="font-bold text-slate-600">22.50</span>
                        </div>
                        <div class="flex justify-between items-center text-xs">
                            <span class="font-bold text-slate-400 uppercase tracking-widest text-[9px]">Tax (8.5%)</span>
                            <span class="font-bold text-slate-600">2.00</span>
                        </div>
                        <div class="flex justify-between items-center text-xs">
                            <span class="font-bold text-slate-400 uppercase tracking-widest text-[9px]">Total</span>
                            <span class="font-bold text-slate-600">24.50</span>
                        </div>
                    </div>

                    <div class="pt-6 border-t border-slate-900/10">
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Date</p>
                        <p class="text-xs font-bold text-slate-600">OCT 24, 2023</p>
                    </div>
                </div>

                <button class="w-full bg-[#1e3a8a] hover:bg-blue-900 text-white text-[11px] font-black py-4 rounded-xl shadow-lg uppercase tracking-[0.2em] transition-all active:scale-95">
                    Done
                </button>
            </div>
        </div>
    </div>
</x-layout>
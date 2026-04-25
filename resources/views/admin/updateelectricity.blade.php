<x-layout title="Update Electricity Bill | VoltTrack">
    <div class="max-w-7xl mx-auto p-12 bg-[#F8FAFC] min-h-screen font-sans">
        
        <div class="flex justify-between items-center mb-10">
            <div>
                <p class="text-[11px] font-black text-slate-400 uppercase tracking-widest mb-2">
                    Resident List > Mami Jupeta > <span class="text-blue-600">Update Electricity Bill</span>
                </p>
                <h1 class="text-5xl font-bold text-[#001D4E] tracking-tight">
                    Update Electricity Bill
                </h1>
            </div>
            <div class="flex gap-4 self-start">
                <button class="px-10 py-3 bg-[#E2E8F0] text-slate-700 rounded-xl font-bold text-sm hover:bg-slate-300 transition-all">
                    Cancel
                </button>
                <button class="px-10 py-3 bg-[#001D4E] text-white rounded-xl font-bold text-sm shadow-lg hover:bg-slate-900 transition-all">
                    Update Bill
                </button>
            </div>
        </div>

        <div class="grid grid-cols-12 gap-10 items-start">
            
            <div class="col-span-8 space-y-8">
                
                <div class="bg-white p-10 rounded-[40px] shadow-2xl shadow-slate-200/60 border border-slate-50">
                    <div class="flex items-center gap-4 mb-8">
                        <div class="w-10 h-10 bg-slate-50 rounded-xl flex items-center justify-center text-slate-400">
                            <i class="fas fa-bolt text-lg"></i>
                        </div>
                        <h3 class="text-lg font-black text-[#001D4E] uppercase tracking-wider">Overall Bill Usage</h3>
                    </div>
                    <div class="grid grid-cols-2 gap-10">
                        <div class="space-y-3">
                            <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest">Overall Total Bill</label>
                            <div class="relative flex items-center">
                                <span class="absolute left-6 text-slate-400 font-bold">₱</span>
                                <input type="text" value="604.34" class="w-full bg-[#F8FAFC] border border-slate-100 p-5 pl-12 rounded-2xl text-lg text-slate-600 font-bold focus:ring-2 focus:ring-blue-500/20 outline-none">
                            </div>
                        </div>
                        <div class="space-y-3">
                            <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest">Overall Total KWH Used</label>
                            <div class="relative flex items-center">
                                <input type="text" value="603" class="w-full bg-[#F8FAFC] border border-slate-100 p-5 rounded-2xl text-lg text-slate-600 font-bold focus:ring-2 focus:ring-blue-500/20 outline-none">
                                <span class="absolute right-6 text-xs font-black text-slate-300 uppercase">KWH</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-10 rounded-[40px] shadow-2xl shadow-slate-200/60 border border-slate-50">
                    <div class="flex items-center gap-4 mb-8">
                        <div class="w-10 h-10 bg-slate-50 rounded-xl flex items-center justify-center text-slate-400">
                            <i class="fas fa-calendar-alt text-lg"></i>
                        </div>
                        <h3 class="text-lg font-black text-[#001D4E] uppercase tracking-wider">Reading Data</h3>
                    </div>
                    <div class="grid grid-cols-2 gap-x-10 gap-y-8">
                        <div class="space-y-3">
                            <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest">Previous Reading</label>
                            <div class="relative flex items-center">
                                <input type="text" value="1240" class="w-full bg-[#F8FAFC] border border-slate-100 p-5 rounded-2xl text-lg text-slate-600 font-bold outline-none">
                                <span class="absolute right-6 text-xs font-black text-blue-600 uppercase">KWH</span>
                            </div>
                        </div>
                        <div class="space-y-3">
                            <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest">Current Reading</label>
                            <div class="relative flex items-center">
                                <input type="text" value="0000" class="w-full bg-[#F8FAFC] border border-slate-100 p-5 rounded-2xl text-lg text-slate-600 font-bold outline-none">
                                <span class="absolute right-6 text-xs font-black text-blue-600 uppercase">KWH</span>
                            </div>
                        </div>
                        <div class="space-y-3">
                            <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest">Reading Date</label>
                            <input type="text" placeholder="mm/dd/yyyy" class="w-full bg-[#F8FAFC] border border-slate-100 p-5 rounded-2xl text-lg text-slate-400 font-bold outline-none">
                        </div>
                        <div class="space-y-3">
                            <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest">Billing Period</label>
                            <input type="text" placeholder="mm/dd/yyyy" class="w-full bg-[#F8FAFC] border border-slate-100 p-5 rounded-2xl text-lg text-slate-400 font-bold outline-none">
                        </div>
                    </div>
                </div>

                <div class="bg-white p-8 rounded-[35px] shadow-2xl shadow-slate-200/60 border border-slate-50 flex items-center justify-between">
                    <div class="flex items-center gap-10">
                        <div class="flex items-center gap-4">
                            <i class="fas fa-th-large text-slate-400"></i>
                            <h3 class="text-lg font-black text-[#001D4E] uppercase tracking-wider">Status</h3>
                        </div>
                        <div class="flex gap-6">
                            <label class="flex items-center gap-3 cursor-pointer group">
                                <input type="radio" name="st" class="w-5 h-5 accent-black" checked>
                                <span class="bg-[#FFD700] text-black text-[10px] font-black px-6 py-2 rounded-full uppercase">Pending</span>
                            </label>
                            <label class="flex items-center gap-3 cursor-pointer group">
                                <input type="radio" name="st" class="w-5 h-5 accent-black">
                                <span class="bg-red-100 text-red-500 text-[10px] font-black px-6 py-2 rounded-full uppercase">Overdue</span>
                            </label>
                            <label class="flex items-center gap-3 cursor-pointer group">
                                <input type="radio" name="st" class="w-5 h-5 accent-black">
                                <span class="bg-blue-100 text-blue-500 text-[10px] font-black px-6 py-2 rounded-full uppercase">Paid</span>
                            </label>
                        </div>
                    </div>
                    <button class="border-2 border-blue-600 text-blue-600 px-8 py-3 rounded-2xl text-[11px] font-black uppercase hover:bg-blue-50 transition-all">
                        Mark Done
                    </button>
                </div>
            </div>

            <div class="col-span-4 h-full">
                <div class="bg-[#001D4E] rounded-[50px] p-10 text-white shadow-2xl sticky top-12">
                    
                    <div class="bg-white/5 backdrop-blur-xl rounded-[32px] p-8 mb-12 border border-white/10">
                        <p class="text-[10px] font-black uppercase tracking-widest text-white/30 mb-6">Meter Spec</p>
                        <div class="grid grid-cols-2 gap-y-8">
                            <div>
                                <p class="text-[10px] font-bold text-white/20 uppercase mb-1">Meter No:</p>
                                <p class="text-xl font-bold tracking-wide">502932</p>
                            </div>
                            <div></div> <div>
                                <p class="text-[10px] font-bold text-white/20 uppercase mb-1">Resident:</p>
                                <p class="text-xl font-bold">Mami Jupeta</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-white/20 uppercase mb-1">Unit Type:</p>
                                <p class="text-xl font-bold">Residential</p>
                            </div>
                        </div>
                    </div>

                    <div class="mb-10">
                        <p class="text-[10px] font-black uppercase tracking-widest text-white/30 mb-2">Computation</p>
                        <h2 class="text-8xl font-bold tracking-tighter mb-10">₱0.00</h2>
                        
                        <div class="space-y-6 text-sm font-medium">
                            <div class="flex justify-between items-center border-b border-white/5 pb-4">
                                <span class="text-white/50">Consumption</span>
                                <span>0.00 KWH</span>
                            </div>
                            <div class="flex justify-between items-center border-b border-white/5 pb-4">
                                <span class="text-white/50">Price per KWH</span>
                                <span>₱12.50</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-white/50">Service Fee</span>
                                <span>₱0.00</span>
                            </div>
                        </div>
                    </div>

                    <button class="w-full bg-[#007BFF] hover:bg-blue-400 py-5 rounded-[24px] text-sm font-black uppercase tracking-widest shadow-xl transition-all active:scale-95">
                        Calculate Bill
                    </button>
                </div>
            </div>

        </div>
    </div>
</x-layout>
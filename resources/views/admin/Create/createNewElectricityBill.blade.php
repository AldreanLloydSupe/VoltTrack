<x-layout title="Create Electricity Bill | VoltTrack">
    <div class="max-w-7xl mx-auto p-8 bg-slate-50 min-h-screen">
        <div class="flex justify-between items-start mb-8">
            <div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">
                    Resident List > Mami Jupeta > <span class="text-blue-600">
                        Create New Electricity Bill
                    </span>
                </p>
                <h1 class="text-5xl font-bold text-[#001D4E] tracking-tight">
                    Create New Electricity Bill
                </h1>
            </div>
            <div class="flex gap-4">
                <button class="px-8 py-2.5 bg-slate-200 text-slate-700 rounded-lg font-bold hover:bg-slate-300 transition-all">
                    <a href="{{route('admin.residentInfo')}}">Cancel</a>
                </button>
                <button class="px-8 py-2.5 bg-[#001D4E] text-white rounded-lg font-bold shadow-lg hover:bg-black transition-all">
                    Create Bill
                </button>
            </div>
        </div>

        <div class="grid grid-cols-12 gap-8">
            <div class="col-span-8 space-y-6">
                
                <div class="bg-white p-8 rounded-2xl border border-slate-200 shadow-sm">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 bg-slate-50 rounded-lg flex items-center justify-center text-slate-400">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <h3 class="text-lg font-black text-[#001D4E] uppercase tracking-wider">
                            Overall Bill Usage
                        </h3>
                    </div>
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-2">
                                Overall Total Bill
                            </label>
                            
                            <div class="relative flex items-center">
                                <input 
                                    type="text" 
                                    class="w-full bg-slate-50 border border-slate-300 p-4 pl-10 rounded-xl text-slate-600 font-bold focus:outline-none focus:ring-2 focus:ring-blue-100 transition-all" 
                                    placeholder="0.00"
                                >
                                <span class="absolute left-4 text-sm font-black text-slate-400 pointer-events-none tracking-widest">
                                    ₱
                                </span>
                            </div>
                        </div>

                        <div>
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-2">
                                Overall Total KWH Used
                            </label>
                            
                            <div class="relative flex items-center">
                                <input 
                                    type="text" 
                                    class="w-full bg-slate-50 border border-slate-300 p-4 pr-16 rounded-xl text-slate-600 font-bold focus:outline-none focus:ring-2 focus:ring-blue-100 transition-all" 
                                    placeholder="0.00"
                                >
                                <span class="absolute right-4 text-sm font-black text-slate-400 pointer-events-none uppercase tracking-widest">
                                    KWH
                                </span>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="bg-white p-8 rounded-2xl border border-slate-200 shadow-sm">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 bg-slate-50 rounded-lg flex items-center justify-center text-slate-400">
                            <i class="fas fa-clipboard-list"></i>
                        </div>
                        <h3 class="text-lg font-black text-[#001D4E] uppercase tracking-wider">
                            Reading Data
                        </h3>
                    </div>
                    <div class="grid grid-cols-2 gap-x-6 gap-y-8">
                        <div>
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-2">
                                Previous Reading
                            </label>
                            
                            <div class="relative flex items-center">
                                <input 
                                    type="text" 
                                    class="w-full bg-slate-50 border border-slate-300 p-4 pr-16 rounded-xl text-slate-600 font-bold focus:outline-none focus:ring-2 focus:ring-blue-100 transition-all" 
                                    placeholder="0.00"
                                >
                                <span class="absolute right-4 text-sm font-black text-slate-400 pointer-events-none uppercase tracking-widest">
                                    KWH
                                </span>
                            </div>
                        </div>

                        <div>
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-2">
                                Current Reading
                            </label>
                            
                            <div class="relative flex items-center">
                                <input 
                                    type="text" 
                                    class="w-full bg-slate-50 border border-slate-300 p-4 pr-16 rounded-xl text-slate-600 font-bold focus:outline-none focus:ring-2 focus:ring-blue-100 transition-all" 
                                    placeholder="0.00"
                                >
                                <span class="absolute right-4 text-sm font-black text-slate-400 pointer-events-none uppercase tracking-widest">
                                    KWH
                                </span>
                            </div>
                        </div>
                        
                        <div>
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-2">
                                Reading Date
                            </label>
                            <input type="date" class="w-full bg-slate-50 border border-slate-200 p-4 rounded-xl text-slate-500 font-bold">
                        </div>
                        <div>
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-2">
                                Billing Period
                            </label>
                            <input type="date" class="w-full bg-slate-50 border border-slate-200 p-4 rounded-xl text-slate-500 font-bold">
                        </div>
                    </div>
                </div>

                <div class="bg-white p-8 rounded-2xl border border-slate-200 shadow-sm">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 bg-slate-50 rounded-lg flex items-center justify-center text-slate-400">
                            <i class="fas fa-tasks"></i>
                        </div>
                        <h3 class="text-lg font-black text-[#001D4E] uppercase tracking-wider">
                            Status
                        </h3>
                    </div>
                    <div class="flex gap-8">
                        <label class="flex items-center cursor-pointer group">
                            <input type="radio" name="status" class="hidden peer">
                            <div class="w-5 h-5 border-3 border-slate-400 rounded-full mr-3 flex items-center justify-center peer-checked:bg-black">
                                <div class="w-2.5 h-2.5 bg-black rounded-full scale-0 peer-checked:scale-100 transition-transform"></div>
                            </div>
                            <span class="bg-amber-400 text-white text-[10px] font-black px-4 py-1 rounded-full uppercase">
                                Pending
                            </span>
                        </label>

                        <label class="flex items-center cursor-pointer group">
                            <input type="radio" name="status" class="hidden peer">
                            <div class="w-5 h-5 border-3 border-slate-400 rounded-full mr-3 flex items-center justify-center peer-checked:bg-black">
                                <div class="w-2.5 h-2.5 bg-black rounded-full scale-0 peer-checked:scale-100 transition-transform"></div>
                            </div>
                            <span class="bg-rose-100 text-rose-500 text-[10px] font-black px-4 py-1 rounded-full uppercase">
                                Overdue
                            </span>
                        </label>

                        <label class="flex items-center cursor-pointer group">
                            <input type="radio" name="status" class="hidden peer">
                            <div class="w-5 h-5 border-3 border-slate-400 rounded-full mr-3 flex items-center justify-center peer-checked:bg-black">
                                <div class="w-2.5 h-2.5 bg-black rounded-full scale-0 peer-checked:scale-100 transition-transform"></div>
                            </div>
                            <span class="bg-blue-100 text-blue-500 text-[10px] font-black px-4 py-1 rounded-full uppercase">
                                Paid
                            </span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="col-span-4">
                <div class="bg-[#1e3a8a] rounded-3xl p-8 text-white shadow-2xl sticky top-8">
                    <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 mb-10 border border-white/10">
                        <p class="text-[10px] font-black uppercase tracking-widest text-white/50 mb-4">
                            Meter Spec
                        </p>
                        <div class="grid grid-cols-2 gap-y-4">
                            <div>
                                <p class="text-[9px] font-bold text-white/40 uppercase">
                                    Meter No:
                                </p>
                                <p class="font-bold">
                                    320932
                                </p>
                            </div>
                            <div></div>
                            <div>
                                <p class="text-[9px] font-bold text-white/40 uppercase">Resident:</p>
                                <p class="font-bold">
                                    Mami Jupeta
                                </p>
                            </div>
                            <div>
                                <p class="text-[9px] font-bold text-white/40 uppercase">Unit Type:</p>
                                <p class="font-bold">
                                    Residential
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="mb-12">
                        <p class="text-[10px] font-black uppercase tracking-widest text-white/50 mb-2">Computation</p>
                        <div class="flex items-baseline gap-2 mb-8">
                            <span class="text-2xl font-bold opacity-60">₱</span>
                            <h2 class="text-7xl font-bold tracking-tighter">0.00</h2>
                        </div>
                        
                        <div class="space-y-4 text-sm font-medium">
                            <div class="flex justify-between border-b border-white/10 pb-4">
                                <span class="opacity-60">Consumption</span>
                                <span>0.00 KWH</span>
                            </div>
                            <div class="flex justify-between border-b border-white/10 pb-4">
                                <span class="opacity-60">Price per KWH</span>
                                <span>₱ 12.50</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="opacity-60">Service Fee</span>
                                <span>₱ 0.00</span>
                            </div>
                        </div>
                    </div>

                    <button class="w-full bg-blue-500 hover:bg-blue-400 py-4 rounded-xl font-black uppercase tracking-widest shadow-lg transition-all">
                        Calculate Bill
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-layout>
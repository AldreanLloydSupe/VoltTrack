<x-layout title="Updating Resident Account | VoltTrack">
    <div class="max-w-5xl mx-auto py-10">
        <nav class="flex text-[10px] font-black uppercase tracking-widest text-slate-400 mb-4 gap-2">
            <span>Resident List</span>
            <span>&gt;</span>
            <span>Mami Jupeta</span>
            <span>&gt;</span>
            <span class="text-[#001D4E]">Update Account</span>
        </nav>

        <div class="bg-white p-10 rounded-3xl border border-slate-200 shadow-sm relative overflow-hidden">
            
            <div class="flex justify-between items-start mb-10">
                <div>
                    <span class="text-[10px] font-black text-blue-500 uppercase tracking-[0.2em] block mb-1">Profile</span>
                    <h1 class="text-3xl font-black text-[#001D4E] tracking-tight">Update Resident Account</h1>
                </div>
                
                <div class="flex gap-3">
                    <a href="{{route('admin.residentInfo')}}"><button type="button" class="px-6 py-2.5 bg-slate-100 text-slate-600 font-black rounded-xl text-sm hover:bg-slate-200 transition-colors">
                        Cancel
                    </button></a>
                    <button type="submit" class="px-6 py-2.5 bg-[#001D4E] text-white font-black rounded-xl text-sm shadow-lg shadow-blue-900/20 hover:scale-[1.02] active:scale-95 transition-all">
                        Apply Changes
                    </button>
                </div>
            </div>

            <form class="grid grid-cols-2 gap-x-8 gap-y-8">
                
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block ml-1">
                        First Name
                    </label>
                    {{-- Current Value sa details mo appear dapat inig mag backend --}}
                    <input type="text" 
                    value="Mami" 
                        class="w-full bg-slate-50 border border-slate-200 p-4 rounded-2xl text-slate-700 font-bold outline-none ring-1 ring-slate-100 focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition-all">
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block ml-1">
                        Last Name
                    </label>
                    {{-- Current Value sa details mo appear dapat inig mag backend --}}
                    <input type="text" 
                    value="Jupeta" 
                        class="w-full bg-slate-50 border border-slate-200 p-4 rounded-2xl text-slate-700 font-bold outline-none ring-1 ring-slate-100 focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition-all">
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block ml-1">
                        Phone Number
                    </label>
                    {{-- Current Value sa details mo appear dapat inig mag backend --}}
                    <input type="text" 
                    value="+63 921 555 0123" 
                        class="w-full bg-slate-50 border border-slate-200 p-4 rounded-2xl text-slate-700 font-bold outline-none ring-1 ring-slate-100 focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition-all">
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block ml-1">
                        Unit ID
                    </label>
                    {{-- Current Value sa details mo appear dapat inig mag backend --}}
                    <input type="text" 
                    value="B4-L12-WEST" 
                        class="w-full bg-slate-50 border border-slate-200 p-4 rounded-2xl text-slate-700 font-bold outline-none ring-1 ring-slate-100 focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition-all">
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block ml-1">
                        Email Address
                    </label>
                    {{-- Current Value sa details mo appear dapat inig mag backend --}}
                    <input type="email" 
                    value="r.dalisay@cidg.gov.ph" 
                        class="w-full bg-slate-50 border border-slate-200 p-4 rounded-2xl text-slate-700 font-bold outline-none ring-1 ring-slate-100 focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition-all">
                </div>

                <div class="space-y-2">
                    {{-- Current Value sa details mo appear dapat inig mag backend --}}
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block ml-1">Gender</label>
                    <div class="flex gap-8 py-3">
                        <label class="flex items-center cursor-pointer group">
                            <input type="radio" name="gender" class="hidden peer" checked>
                            <div class="w-6 h-6 border-3 border-slate-400 rounded-full mr-3 flex items-center justify-center bg-white peer-checked:bg-black transition-all">
                                <div class="w-3 h-3 bg-black rounded-full scale-0 peer-checked:scale-100 transition-transform"></div>
                            </div>
                            <span class="text-sm font-bold text-slate-600">Male</span>
                        </label>

                        <label class="flex items-center cursor-pointer group">
                            <input type="radio" name="gender" class="hidden peer">
                            <div class="w-6 h-6 border-3 border-slate-400 rounded-full mr-3 flex items-center justify-center bg-white peer-checked:bg-black transition-all">
                                <div class="w-3 h-3 bg-black rounded-full scale-0 peer-checked:scale-100 transition-transform"></div>
                            </div>
                            <span class="text-sm font-bold text-slate-600">Female</span>
                        </label>
                    </div>
                </div>

            </form>
        </div>
    </div>
</x-layout>
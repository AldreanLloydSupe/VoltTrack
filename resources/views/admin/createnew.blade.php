<x-layout title="Create New Property | VoltTrack">
    <main class="w-full h-screen bg-slate-50 p-8 flex flex-col overflow-hidden font-sans">
        
        {{-- Header Section --}}
        <div class="flex justify-between items-start mb-6 flex-none">
            <div>
                <nav class="text-[11px] font-bold text-slate-400 uppercase tracking-[0.2em] mb-1">
                    PROPERTIES & METERS <span class="mx-1 text-slate-300">></span> <span class="text-blue-700">CREATE NEW PROPERTY</span>
                </nav>
                <h1 class="text-5xl font-black text-[#0f172a] tracking-tight">Create New Property</h1>
            </div>
            
            <div class="flex items-center space-x-6">
                <button class="text-[12px] font-black text-slate-400 uppercase tracking-widest hover:text-slate-600 transition-all">
                    Cancel
                </button>
                <button class="bg-[#1e3a8a] hover:bg-blue-900 text-white text-[12px] font-black px-8 py-4 rounded-2xl shadow-xl uppercase tracking-widest transition-all active:scale-95">
                    Create Property
                </button>
            </div>
        </div>

        {{-- Form Area --}}
        <form action="#" method="POST" class="flex flex-col flex-grow min-h-0 space-y-6">
            
            {{-- Section 1: Property Details --}}
            <section class="bg-white p-8 rounded-[32px] border border-slate-200 shadow-sm flex flex-col justify-center flex-1">
                <div class="flex items-center space-x-3 mb-6">
                    <div class="bg-blue-50 p-2 rounded-xl text-blue-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    </div>
                    <h2 class="text-2xl font-bold tracking-tight text-slate-700">Property Details</h2>
                </div>

                <div class="grid grid-cols-2 gap-x-12 gap-y-6">
                    <div>
                        <label class="block text-[11px] font-black text-slate-400 uppercase mb-2 tracking-widest">Property List ID</label>
                        <input type="text" placeholder="VT-8829-A" class="w-full bg-slate-50 border border-slate-100 rounded-xl px-6 py-4 text-lg font-bold text-slate-700 focus:ring-2 focus:ring-blue-100 outline-none">
                    </div>
                    <div>
                        <label class="block text-[11px] font-black text-slate-400 uppercase mb-2 tracking-widest">Cluster Heading</label>
                        <select class="w-full bg-slate-50 border border-slate-100 rounded-xl px-6 py-4 text-lg font-bold text-slate-700 appearance-none outline-none">
                            <option>Multi-Unit Prefab Housing</option>
                        </select>
                    </div>
                    <div class="col-span-2">
                        <label class="block text-[11px] font-black text-slate-400 uppercase mb-2 tracking-widest">Physical Address</label>
                        <input type="text" placeholder="Vizcaya Village, Tagum City" class="w-full bg-slate-50 border border-slate-100 rounded-xl px-6 py-4 text-lg font-bold text-slate-700 outline-none">
                    </div>
                </div>
            </section>

            {{-- Section 2: Meter Initialization --}}
            <section class="bg-white p-8 rounded-[32px] border border-slate-200 shadow-sm flex flex-col justify-center flex-1">
                <div class="flex items-center space-x-3 mb-6">
                    <div class="bg-blue-50 p-2 rounded-xl text-blue-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                    <h2 class="text-2xl font-bold tracking-tight text-slate-700">Meter Initialization</h2>
                </div>

                <div class="grid grid-cols-2 gap-16">
                    {{-- Electricity --}}
                    <div class="space-y-4">
                        <div class="flex items-center space-x-2 text-[#f59e0b]">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z"></path></svg>
                            <span class="text-xs font-black uppercase tracking-[0.2em]">Electricity Meter</span>
                        </div>
                        <input type="text" placeholder="ELEC-992-00" class="w-full bg-slate-100 border-none rounded-xl px-6 py-4 text-lg font-bold text-slate-700">
                        <div class="relative">
                            <input type="text" value="0.00" class="w-full bg-blue-50/50 border-none rounded-xl px-6 py-4 text-2xl font-black text-blue-700">
                            <span class="absolute right-6 top-1/2 -translate-y-1/2 text-xs font-bold text-slate-400 uppercase">kWh</span>
                        </div>
                    </div>

                    {{-- Water --}}
                    <div class="space-y-4">
                        <div class="flex items-center space-x-2 text-blue-500">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a1 1 0 011 1v1.323l.395.17c.712.305 1.258.91 1.482 1.637.245.797.1 1.637-.41 2.302l-.1.127a1 1 0 01-1.564-1.246l.1-.127c.18-.233.23-.526.143-.807a.78.78 0 00-.51-.563l-.536-.23V17a1 1 0 11-2 0V5.286l-.536.23a.78.78 0 00-.51.563c-.087.281-.037.574.143.807l.1.127a1 1 0 01-1.564 1.246l-.1-.127c-.51-.665-.655-1.505-.41-2.302.224-.727.77-1.332 1.482-1.637l.395-.17V3a1 1 0 011-1z"></path></svg>
                            <span class="text-xs font-black uppercase tracking-[0.2em]">Water Meter</span>
                        </div>
                        <input type="text" placeholder="WATR-441-00" class="w-full bg-slate-100 border-none rounded-xl px-6 py-4 text-lg font-bold text-slate-700">
                        <div class="relative">
                            <input type="text" value="0.00" class="w-full bg-blue-50/50 border-none rounded-xl px-6 py-4 text-2xl font-black text-blue-700">
                            <span class="absolute right-6 top-1/2 -translate-y-1/2 text-xs font-bold text-slate-400 uppercase">m³</span>
                        </div>
                    </div>
                </div>
            </section>
        </form>
    </main>
</x-layout>
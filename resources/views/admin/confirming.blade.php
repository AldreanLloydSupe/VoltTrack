<x-layout title="Confirming Utility Assignment | VoltTrack">
    <main class="w-full min-h-screen bg-slate-50 p-8 flex flex-col">
        
        {{-- Breadcrumbs & Date Row --}}
        <div class="flex justify-between items-start mb-10 w-full">
            <div>
                <nav class="text-[10px] font-bold uppercase tracking-[0.15em] text-slate-400 mb-2">
                    DASHBOARD <span class="mx-1 text-slate-300">></span> PENDING RESIDENTS <span class="mx-1 text-slate-300">></span> <span class="text-blue-700">CONFIRMING UTILITY ASSIGNMENT</span>
                </nav>
                <h1 class="text-[44px] font-black text-[#0f172a] leading-tight">
                    Confirming Utility Assignment
                </h1>
            </div>
            <div class="text-right">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Current Date</p>
                <p class="text-xl font-semibold text-slate-600 italic">21 April, 2026</p>
            </div>
        </div>

        {{-- Client Summary Cards --}}
        <div class="flex space-x-3 mb-10">
            <div class="bg-white border border-slate-100 rounded-xl px-6 py-4 shadow-sm min-w-[250px]">
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Pending Client</p>
                <p class="text-lg font-bold text-slate-700">{{ $assignment->user?->first_name }} {{ $assignment->user?->last_name }}</p>
            </div>
            <div class="bg-white border border-slate-100 rounded-xl px-6 py-4 shadow-sm min-w-[250px]">
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Account Status</p>
                <p class="text-lg font-bold
                    @if($assignment->status === 'Pending') text-amber-500
                    @elseif($assignment->status === 'Confirmed') text-green-500
                    @else text-red-500
                    @endif">
                    @if($assignment->status === 'Pending') Awaiting Technical Validation
                    @elseif($assignment->status === 'Confirmed') Confirmed
                    @else Rejected
                    @endif
                </p>
            </div>
        </div>

        <div class="grid grid-cols-12 gap-10 w-full flex-grow">
            
            {{-- Left Column: Form Sections --}}
            <div class="col-span-8 space-y-8">
                
                {{-- Property Registry Card --}}
                <section class="bg-white p-10 rounded-[24px] border border-slate-200 shadow-[0_10px_25px_-5px_rgba(0,0,0,0.04)] w-full">
                    <div class="flex items-center space-x-3 mb-8">
                        <svg class="w-6 h-6 text-slate-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                        </svg>
                        <h2 class="text-2xl font-bold tracking-tight text-slate-700">Property Registry Identification</h2>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-8">
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase mb-3 tracking-widest">Property ID</label>
                            <div class="relative">
                                <input type="text" value="{{ $assignment->property_id }}" disabled class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-5 py-4 font-bold text-slate-600">
                                <div class="absolute right-5 top-1/2 -translate-y-1/2 w-6 h-6 rounded-full border border-slate-300 flex items-center justify-center">
                                    <span class="text-[10px] text-slate-400">✔</span>
                                </div>
                            </div>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase mb-3 tracking-widest">Unit Classification</label>
                            <div class="relative">
                                <input type="text" value="{{ $assignment->property?->unit_type ?? 'N/A' }}" disabled class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-5 py-4 font-bold text-slate-600">
                                <div class="absolute right-5 top-1/2 -translate-y-1/2 w-6 h-6 rounded-full border border-slate-300 flex items-center justify-center">
                                    <span class="text-[10px] text-slate-400">✔</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                {{-- Utility Grids --}}
                <div class="grid grid-cols-2 gap-8">
                    @php
                        $electricMeter = $assignment->property?->meters->firstWhere('utility_type', 'Electricity');
                        $waterMeter = $assignment->property?->meters->firstWhere('utility_type', 'Water');
                    @endphp

                    {{-- Electricity --}}
                    @if($electricMeter)
                    <div class="bg-white p-8 rounded-[24px] border border-slate-200 shadow-sm">
                        <div class="flex items-center space-x-3 mb-6">
                            <div class="bg-blue-100 p-2.5 rounded-xl text-blue-600">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M11 3a1 1 0 10-2 0v1a1 1 0 102 0V3zM15.657 5.757a1 1 0 00-1.414-1.414l-.707.707a1 1 0 001.414 1.414l.707-.707zM18 10a1 1 0 01-1 1h-1a1 1 0 110-2h1a1 1 0 011 1zM5.05 6.464A1 1 0 106.464 5.05l-.707-.707a1 1 0 00-1.414 1.414l.707.707zM5 10a1 1 0 01-1 1H3a1 1 0 110-2h1a1 1 0 011 1zM8 16v-1h4v1a2 2 0 11-4 0zM12 14H8a4 4 0 014-4h.01"></path></svg>
                            </div>
                            <span class="text-[11px] font-black text-slate-400 uppercase tracking-widest">Electricity</span>
                        </div>
                        <input type="text" value="{{ $electricMeter->hardware_meter_number }}" disabled class="w-full bg-slate-50 border border-slate-100 rounded-xl px-4 py-3 text-sm font-medium text-slate-400 mb-4">
                        <input type="text" placeholder="000.00 kWh" class="w-full border border-slate-200 rounded-xl px-4 py-4 text-base placeholder:italic placeholder:text-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-100">
                    </div>
                    @endif

                    {{-- Water Supply --}}
                    @if($waterMeter)
                    <div class="bg-white p-8 rounded-[24px] border border-slate-200 shadow-sm">
                        <div class="flex items-center space-x-3 mb-6">
                            <div class="bg-blue-100 p-2.5 rounded-xl text-blue-600">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a1 1 0 011 1v1.323l.395.17c.712.305 1.258.91 1.482 1.637.245.797.1 1.637-.41 2.302l-.1.127a1 1 0 01-1.564-1.246l.1-.127c.18-.233.23-.526.143-.807a.78.78 0 00-.51-.563l-.536-.23V17a1 1 0 11-2 0V5.286l-.536.23a.78.78 0 00-.51.563c-.087.281-.037.574.143.807l.1.127a1 1 0 01-1.564 1.246l-.1-.127c-.51-.665-.655-1.505-.41-2.302.224-.727.77-1.332 1.482-1.637l.395-.17V3a1 1 0 011-1z"></path></svg>
                            </div>
                            <span class="text-[11px] font-black text-slate-400 uppercase tracking-widest">Water Supply</span>
                        </div>
                        <input type="text" value="{{ $waterMeter->hardware_meter_number }}" disabled class="w-full bg-slate-50 border border-slate-100 rounded-xl px-4 py-3 text-sm font-medium text-slate-400 mb-4">
                        <input type="text" placeholder="000.00 m³" class="w-full border border-slate-200 rounded-xl px-4 py-4 text-base placeholder:italic placeholder:text-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-100">
                    </div>
                    @endif

                    @if(!$electricMeter && !$waterMeter)
                    <div class="col-span-2 bg-slate-50 p-8 rounded-[24px] text-center text-slate-500">
                        No meters assigned to this property
                    </div>
                    @endif
                </div>
            </div>

            {{-- Right Column: Actions --}}
            <div class="col-span-4 flex flex-col">
                {{-- BIG Initial Deposit Panel --}}
                <div class="bg-gradient-to-b from-slate-200/60 to-slate-300/40 p-10 rounded-[32px] shadow-inner border border-white/50 backdrop-blur-sm mb-8">
                    <div class="flex items-center space-x-3 mb-8 text-slate-600">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"></path>
                            <path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="text-lg font-black uppercase tracking-widest">Initial Deposit</span>
                    </div>
                    
                    <div class="bg-white rounded-[24px] p-8 flex items-center shadow-2xl ring-1 ring-slate-200">
                        <span class="text-slate-300 font-light text-5xl mr-4">₱</span>
                        <input type="text"
                               value="{{ number_format($assignment->initial_deposit, 2) }}"
                               class="w-full text-5xl font-black text-slate-800 outline-none bg-transparent tracking-tighter">
                    </div>
                    
                    <p class="mt-6 text-[11px] text-slate-500 font-bold uppercase tracking-widest text-center italic">
                        Required Security Balance for Activation
                    </p>
                </div>

                {{-- Action Buttons --}}
                <div class="space-y-4">
                    <button class="w-full bg-[#0f172a] hover:bg-black text-white py-6 rounded-[20px] font-bold uppercase tracking-[0.15em] text-[13px] flex items-center justify-center space-x-4 shadow-xl transition-all active:scale-95">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 00-2 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        <span>Confirm & Commit Assignment</span>
                    </button>

                    <button class="w-full bg-[#ef4444] hover:bg-red-600 text-white py-6 rounded-[20px] font-bold uppercase tracking-[0.15em] text-[13px] flex items-center justify-center space-x-4 shadow-lg shadow-red-200 transition-all active:scale-95">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span>Cancel Client</span>
                    </button>

                    <button onclick="window.history.back()" class="w-full bg-[#e2e8f0] hover:bg-slate-300 text-slate-600 py-6 rounded-[20px] font-bold uppercase tracking-[0.15em] text-[13px] flex items-center justify-center space-x-4 transition-all active:scale-95">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        <span>Back</span>
                    </button>
                </div>
            </div>
        </div>
    </main>
</x-layout>
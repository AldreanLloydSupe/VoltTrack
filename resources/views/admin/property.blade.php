<x-layout title="Property & Meter Registry | VoltTrack">
    <main class="p-10 bg-slate-50 min-h-screen font-sans">
        {{-- Header Section --}}
        <div class="flex justify-between items-end mb-8">
            <div>
                <nav class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">
                    Properties & Meters <span class="mx-1">></span>
                </nav>
                <h1 class="text-4xl font-black text-[#0f172a] leading-tight">
                    Property & Meter Registry
                </h1>
            </div>
            
            <div class="flex items-center space-x-6">
                {{-- View Switcher --}}
                <div class="flex bg-slate-200/50 p-1 rounded-lg text-[11px] font-bold uppercase tracking-tight">
                    <button class="bg-white px-4 py-1.5 rounded shadow-sm text-blue-600 transition-all">Table View</button>
                    <button class="px-4 py-1.5 text-slate-400 hover:text-slate-600 transition-all">Grid View</button>
                </div>
                
                {{-- UPDATED: Primary Action as Link --}}
                <a href="{{ route('admin.createnew') }}" class="bg-[#1e3a8a] hover:bg-blue-900 text-white text-[11px] font-black px-6 py-3.5 rounded-lg shadow-lg shadow-blue-900/20 uppercase tracking-wider transition-all active:scale-95">
                    Add Property
                </a>
            </div>
        </div>

        {{-- Table Container --}}
        <div class="bg-white rounded-[24px] shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 p-8">
            
            {{-- Filter and Search Row --}}
            <div class="flex justify-between items-center mb-10">
                <div class="flex items-center space-x-6">
                    <div class="flex items-center bg-slate-100/50 rounded-full p-1 border border-slate-100">
                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-[0.15em] px-4">Type</span>
                        <button class="bg-[#1e3a8a] text-white px-5 py-1.5 rounded-full text-[10px] font-black uppercase tracking-wider shadow-md">All</button>
                        <button class="text-slate-400 px-5 py-1.5 text-[10px] font-black uppercase tracking-wider hover:text-slate-600 transition-colors">Residential</button>
                        <button class="text-slate-400 px-5 py-1.5 text-[10px] font-black uppercase tracking-wider hover:text-slate-600 transition-colors">Commercial</button>
                    </div>
                    <span class="text-xs font-medium italic text-slate-400">Showing 122 recorded assets</span>
                </div>

                {{-- Search Bar --}}
                <div class="relative group">
                    <span class="absolute inset-y-0 left-5 flex items-center text-blue-500 transition-colors group-focus-within:text-blue-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </span>
                    <input type="text" placeholder="Search registry..." class="pl-12 pr-8 py-3.5 w-80 rounded-full bg-slate-50 border border-slate-100 focus:bg-white focus:border-blue-200 focus:outline-none focus:ring-4 focus:ring-blue-50 text-sm transition-all placeholder:text-slate-300">
                </div>
            </div>

            {{-- Registry Table --}}
            <div class="overflow-hidden">
                <table class="w-full text-left">
                    <thead class="text-[10px] font-black uppercase text-slate-400 tracking-[0.2em] border-b border-slate-50">
                        <tr>
                            <th class="px-6 py-5">Property ID</th>
                            <th class="px-6 py-5">Unit Type</th>
                            <th class="px-6 py-5">Electricity Meter No.</th>
                            <th class="px-6 py-5">Water Meter No.</th>
                            <th class="px-6 py-5">Status</th>
                            <th class="px-6 py-5 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 text-[13px]">
                        @php
                            $properties = [
                                ['id' => '11111', 'type' => 'Residential', 'elec' => 'E-2023-X9821', 'water' => 'W-881-A2-21', 'status' => 'ACTIVE', 'color' => 'blue'],
                                ['id' => '11112', 'type' => 'Residential', 'elec' => 'E-2023-X9821', 'water' => 'W-881-A2-21', 'status' => 'ACTIVE', 'color' => 'blue'],
                                ['id' => '11113', 'type' => 'Residential', 'elec' => 'E-2023-X9821', 'water' => 'W-881-A2-21', 'status' => 'ACTIVE', 'color' => 'blue'],
                                ['id' => '11114', 'type' => 'Commercial', 'elec' => 'E-2023-X9821', 'water' => 'W-881-A2-21', 'status' => 'ACTIVE', 'color' => 'blue'],
                                ['id' => '11114', 'type' => 'Residential', 'elec' => 'E-2023-X9821', 'water' => 'W-881-A2-21', 'status' => 'ACTIVE', 'color' => 'blue'],
                                ['id' => '11115', 'type' => 'Residential', 'elec' => 'E-2023-X9821', 'water' => 'W-881-A2-21', 'status' => 'VACANT', 'color' => 'green'],
                            ];
                        @endphp

                        @foreach($properties as $property)
                        <tr class="hover:bg-slate-50/80 transition-all group">
                            <td class="px-6 py-6 font-bold text-slate-400 group-hover:text-slate-600">{{ $property['id'] }}</td>
                            <td class="px-6 py-6 font-black text-[#1e293b]">{{ $property['type'] }}</td>
                            <td class="px-6 py-6 text-slate-500 font-medium">{{ $property['elec'] }}</td>
                            <td class="px-6 py-6 text-slate-500 font-medium">{{ $property['water'] }}</td>
                            <td class="px-6 py-6">
                                @if($property['color'] == 'blue')
                                    <span class="bg-blue-50 text-blue-700 text-[10px] font-black px-4 py-1.5 rounded-full flex items-center w-fit border border-blue-100/50">
                                        <span class="w-1.5 h-1.5 bg-blue-500 rounded-full mr-2.5 animate-pulse"></span> ACTIVE
                                    </span>
                                @else
                                    <span class="bg-emerald-50 text-emerald-700 text-[10px] font-black px-4 py-1.5 rounded-full flex items-center w-fit border border-emerald-100/50">
                                        <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full mr-2.5"></span> VACANT
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-6 text-right">
                                <a href="{{route('admin.propertyInfo')}}" class="text-[11px] font-black text-blue-600 uppercase tracking-widest hover:text-blue-800 hover:underline transition-all">View</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination Footer --}}
            <div class="mt-10 flex justify-between items-center text-[11px] font-bold text-slate-400 pt-8 border-t border-slate-50">
                <p>Showing <span class="text-slate-600">1-6</span> of 122 assets</p>
                <div class="flex items-center space-x-2">
                    <button class="w-9 h-9 flex items-center justify-center bg-[#1e3a8a] text-white rounded-xl shadow-lg shadow-blue-900/20">1</button>
                    <button class="w-9 h-9 flex items-center justify-center bg-white border border-slate-200 rounded-xl text-slate-500 hover:border-slate-300 hover:text-slate-700 transition-all">2</button>
                    <button class="w-9 h-9 flex items-center justify-center bg-white border border-slate-200 rounded-xl text-slate-500 hover:border-slate-300 hover:text-slate-700 transition-all">3</button>
                    <span class="px-2 text-slate-300 italic">...</span>
                    <button class="w-9 h-9 flex items-center justify-center bg-white border border-slate-200 rounded-xl text-slate-500 hover:border-slate-300 hover:text-slate-700 transition-all">21</button>
                    <button class="pl-3 hover:translate-x-1 transition-transform">
                        <svg class="w-5 h-5 text-slate-300" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </main>
</x-layout>
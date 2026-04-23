<x-layout title="Pending Residents | VoltTrack">
    <main class="p-10 bg-slate-50 min-h-screen">
        {{-- Header Section --}}
        <div class="flex justify-between items-end mb-4">
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                    Pending Residents > 
                </p>
                <h2 class="text-5xl font-black text-[#0f172a] mt-1">
                    Pending Resident Confirmation
                </h2>
            </div>
            
            <div class="flex items-center space-x-4">
                <div class="flex bg-slate-200 p-1 rounded-lg text-[12px] font-bold uppercase">
                    <button class="bg-white px-3 py-1 rounded shadow-sm text-blue-600">Table View</button>
                    <button class="px-3 py-1 text-slate-500">Grid View</button>
                </div>
            </div>
        </div>

        {{-- Search Bar Row --}}
        <div class="flex justify-end mb-8">
            <div class="relative">
                <span class="absolute inset-y-0 left-3 flex items-center text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </span>
                <input type="text" placeholder="Search Transaction..." class="pl-10 pr-4 py-2 w-72 rounded-full border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm shadow-sm bg-white">
            </div>
        </div>
        
        {{-- Stats Panels --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-10">
            @php
                $stats = [
                    ['label' => 'Total Queue', 'value' => '6', 'color' => 'text-slate-900'],
                    ['label' => 'Pending', 'value' => '1', 'color' => 'text-slate-900'],
                    ['label' => 'Total Deposits', 'value' => 'P10,000', 'color' => 'text-slate-900'],
                    ['label' => 'Collected Monthly', 'value' => 'P5,000', 'color' => 'text-slate-900'],
                ];
            @endphp

            @foreach($stats as $stat)
                <div class="bg-white p-6 rounded-xl border border-slate-100 shadow-md transition-all hover:translate-y-[-2px]">
                    <p class="text-[11px] font-black text-slate-500 uppercase tracking-wider mb-2">
                        {{ $stat['label'] }}
                    </p>
                    <p class="text-4xl font-bold {{ $stat['color'] }}">
                        {{ $stat['value'] }}
                    </p>
                </div>
            @endforeach
        </div>

        {{-- Filter Dropdowns --}}
        <div class="flex space-x-4 mb-6">
            <select class="bg-white border border-slate-200 text-[12px] font-bold px-4 py-2 rounded shadow-sm text-slate-600 focus:outline-none">
                <option>Status: All</option>
            </select>
            <select class="bg-white border border-slate-200 text-[12px] font-bold px-4 py-2 rounded shadow-sm text-slate-600 focus:outline-none">
                <option>Unit Type: All</option>
            </select>
        </div>

        {{-- Table --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50 text-[11px] font-bold uppercase text-slate-400 tracking-widest">
                    <tr>
                        <th class="px-6 py-4">Renter Name</th>
                        <th class="px-6 py-4">Property ID</th>
                        <th class="px-6 py-4">Unit Type</th>
                        <th class="px-6 py-4">Approve Date</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    {{-- Row 1: Pending --}}
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-6 py-5">
                            <p class="font-bold text-slate-800 text-base">Ricardo Bailoop</p>
                            <p class="text-[11px] text-slate-400">paikow@gmail.com</p>
                        </td>
                        <td class="px-6 py-5 text-sm font-medium text-slate-600 uppercase">TT11</td>
                        <td class="px-6 py-5 text-sm font-black text-slate-800">Residential</td>
                        <td class="px-6 py-5 text-sm text-slate-500">Apr 01, 2026</td>
                        <td class="px-6 py-5">
                            <span class="bg-orange-400 text-white text-[10px] font-black px-2 py-1 rounded shadow-sm uppercase">Pending</span>
                        </td>
                        <td class="px-6 py-5 text-right">
                            <a href="{{ route('admin.confirming')}}" class="text-[12px] font-black text-blue-600 uppercase hover:underline">Review</a>
                        </td>
                    </tr>

                    {{-- Row 2: Failed --}}
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-6 py-5">
                            <p class="font-bold text-slate-800 text-base">Elias Dimaculangan</p>
                            <p class="text-[11px] text-slate-400">elias77@gmail.com</p>
                        </td>
                        <td class="px-6 py-5 text-sm font-medium text-slate-600 uppercase">TT12</td>
                        <td class="px-6 py-5 text-sm font-black text-slate-800">Residential</td>
                        <td class="px-6 py-5 text-sm text-slate-500">Apr 15, 2026</td>
                        <td class="px-6 py-5">
                            <span class="bg-red-500 text-white text-[10px] font-black px-2 py-1 rounded shadow-sm uppercase">Failed</span>
                        </td>
                        <td class="px-6 py-5 text-right">
                            <a href="{{ route('admin.confirming')}}" class="text-[12px] font-black text-blue-600 uppercase hover:underline">Review</a>
                        </td>
                    </tr>
                </tbody>
            </table>

            {{-- Pagination Footer --}}
            <div class="p-6 bg-slate-50/30 flex justify-between items-center text-[10px] font-bold text-slate-400">
                <p>Showing 1-2 of 6 renters</p>
                <div class="flex space-x-2">
                    <button class="w-6 h-6 flex items-center justify-center bg-blue-600 text-white rounded">1</button>
                    <button class="w-6 h-6 flex items-center justify-center bg-white border border-slate-200 rounded">2</button>
                    <button class="w-6 h-6 flex items-center justify-center bg-white border border-slate-200 rounded">3</button>
                </div>
            </div>
        </div>
    </main>
</x-layout>
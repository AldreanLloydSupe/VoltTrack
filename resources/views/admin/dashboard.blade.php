<x-layout title="Admin Dashboard | VoltTrack">
    <header class="bg-[#1e3a8a] text-white py-4 px-8 flex items-center justify-between">
        <div class="flex items-center space-x-12">
            <h1 class="text-2xl font-bold italic">VoltTrack</h1>
            <nav class="flex space-x-8 text-sm font-medium">
                <a href="#" class="border-b-2 border-white pb-1">Dashboard</a>
                <div class="group relative cursor-pointer">
                    <span>Residents <i class="fas fa-chevron-down text-[10px]"></i></span>
                </div>
                <div class="group relative cursor-pointer">
                    <span>Utilities <i class="fas fa-chevron-down text-[10px]"></i></span>
                </div>
            </nav>
        </div>
        <div class="flex items-center space-x-3">
            <img src="https://ui-avatars.com/api/?name=Admin&background=fff&color=1e3a8a" class="w-8 h-8 rounded-full border-2 border-white" alt="Profile">
        </div>
    </header>

    <main class="p-10 bg-slate-50 min-h-screen">
        <div class="flex justify-between items-end mb-8">
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Dashboard ></p>
                <h2 class="text-4xl font-black text-[#0f172a] mt-1">Dashboard</h2>
            </div>
            
            <div class="flex items-center space-x-4">
                <div class="relative">
                    <span class="absolute inset-y-0 left-3 flex items-center text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </span>
                    <input type="text" placeholder="Search Transaction..." class="pl-10 pr-4 py-2 w-72 rounded-full border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                </div>
                <div class="flex bg-slate-200 p-1 rounded-lg text-[10px] font-bold uppercase">
                    <button class="bg-white px-3 py-1 rounded shadow-sm text-blue-600">Table View</button>
                    <button class="px-3 py-1 text-slate-500">Grid View</button>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
            @php
                $stats = [
                    ['label' => 'Total Boarding House', 'value' => '4', 'color' => 'text-slate-800'],
                    ['label' => 'Active Rentals', 'value' => '3', 'color' => 'text-slate-800'],
                    ['label' => 'Overdue Payments', 'value' => '1', 'color' => 'text-red-600'],
                    ['label' => 'Collected Monthly', 'value' => 'P5,000', 'color' => 'text-slate-800'],
                ];
            @endphp

            @foreach($stats as $stat)
                <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
                    <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider mb-2">{{ $stat['label'] }}</p>
                    <p class="text-3xl font-bold {{ $stat['color'] }}">{{ $stat['value'] }}</p>
                </div>
            @endforeach
        </div>

        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-slate-50">
                <h3 class="font-black text-slate-700 uppercase tracking-tight text-sm">Pending Transactions:</h3>
            </div>
            
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50 text-[10px] font-bold uppercase text-slate-400 tracking-widest">
                    <tr>
                        <th class="px-6 py-4">Renter Name</th>
                        <th class="px-6 py-4">Property ID</th>
                        <th class="px-6 py-4">Current Balance</th>
                        <th class="px-6 py-4 text-center">Last Payment Date</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-6 py-5">
                            <p class="font-bold text-slate-800 text-sm">Jorge Pakaw</p>
                            <p class="text-[10px] text-slate-400">jorgepakaw@gmail.com</p>
                        </td>
                        <td class="px-6 py-5 text-sm font-medium text-slate-600">11115</td>
                        <td class="px-6 py-5 text-sm font-bold text-slate-800">P3,200.00</td>
                        <td class="px-6 py-5 text-center text-xs text-slate-500">Apr 25, 2026</td>
                        <td class="px-6 py-5 text-center">
                            <span class="bg-blue-100 text-blue-600 text-[9px] font-black px-2 py-1 rounded uppercase">Pending</span>
                        </td>
                        <td class="px-6 py-5 text-right">
                            <a href="#" class="text-[10px] font-black text-blue-600 uppercase hover:underline">View Account</a>
                        </td>
                    </tr>
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-6 py-5">
                            <p class="font-bold text-slate-800 text-sm">John Doe</p>
                            <p class="text-[10px] text-slate-400">johndoe@gmail.com</p>
                        </td>
                        <td class="px-6 py-5 text-sm font-medium text-slate-600">11116</td>
                        <td class="px-6 py-5 text-sm font-bold text-red-500">P3,700.00</td>
                        <td class="px-6 py-5 text-center text-xs text-slate-500">Apr 15, 2026</td>
                        <td class="px-6 py-5 text-center">
                            <span class="bg-red-100 text-red-600 text-[9px] font-black px-2 py-1 rounded uppercase">Overdue</span>
                        </td>
                        <td class="px-6 py-5 text-right">
                            <a href="#" class="text-[10px] font-black text-blue-600 uppercase hover:underline">View Account</a>
                        </td>
                    </tr>
                </tbody>
            </table>

            <div class="p-6 bg-slate-50/30 flex justify-between items-center text-[10px] font-bold text-slate-400">
                <p>Showing 1-4 of 4 renters</p>
                <div class="flex space-x-2">
                    <button class="w-6 h-6 flex items-center justify-center bg-blue-600 text-white rounded">1</button>
                    <button class="w-6 h-6 flex items-center justify-center bg-white border border-slate-200 rounded">2</button>
                </div>
            </div>
        </div>
    </main>
</x-layout>
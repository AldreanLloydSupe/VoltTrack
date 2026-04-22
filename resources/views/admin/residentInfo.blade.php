<x-layout title="Mami Jupeta | VoltTrack">
<div class="max-w-7xl mx-auto p-8 bg-slate-50 min-h-screen">
    <div class="mb-2">
        <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">
            Residents List > <span class="text-blue-600">Mami Jupeta</span>
        </p>
    </div>

    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-5xl font-bold text-[#1e3a8a] tracking-tight">Mami Jupeta</h1>
            <p class="text-slate-500 font-medium mt-2">Account ID: #10002 • House No. 2</p>
        </div>
        
        <div class="flex space-x-4">
            <button class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg font-bold text-sm shadow-md transition-all">
                UPDATE ACCOUNT
            </button>
            <button class="bg-rose-500 hover:bg-rose-600 text-white px-6 py-2 rounded-lg font-bold text-sm shadow-md transition-all">
                DELETE ACCOUNT
            </button>
            <button class="bg-[#0f172a] hover:bg-black text-white px-6 py-2 rounded-lg font-bold text-sm shadow-md flex items-center">
                <i class="fas fa-plus-circle mr-2"></i> CREATE NEW BILL
            </button>
        </div>
    </div>

    <div class="grid grid-cols-3 gap-8 mb-10">
        <div class="bg-white p-8 rounded-2xl shadow-[0_15px_30px_-10px_rgba(0,0,0,0.05)] border border-slate-100 relative overflow-hidden">
            <p class="text-[11px] font-black text-slate-400 uppercase tracking-widest mb-4">Current Balance</p>
            <h2 class="text-4xl font-bold text-[#1e3a8a]">₱428.50</h2>
            <div class="mt-6 inline-flex items-center px-3 py-1.5 bg-blue-50 text-blue-600 rounded-lg text-xs font-bold border border-blue-100">
                <i class="far fa-clock mr-2"></i> Due in 4 days
            </div>
            <i class="fas fa-wallet absolute top-8 right-8 text-slate-200 text-2xl"></i>
        </div>

        <div class="bg-white p-8 rounded-2xl shadow-[0_15px_30px_-10px_rgba(0,0,0,0.05)] border border-slate-100">
            <p class="text-[11px] font-black text-slate-400 uppercase tracking-widest mb-4">Last Amount Paid</p>
            <h2 class="text-4xl font-bold text-[#1e3a8a]">₱143.00</h2>
            <p class="mt-6 text-xs font-bold text-slate-400">Mar 28, 2026</p>
        </div>

        <div class="bg-white p-8 rounded-2xl shadow-[0_15px_30px_-10px_rgba(0,0,0,0.05)] border border-slate-100">
            <p class="text-[11px] font-black text-slate-400 uppercase tracking-widest mb-4">No. of Days Rented</p>
            <h2 class="text-4xl font-bold text-[#1e3a8a]">2 Days</h2>
            <p class="mt-6 text-xs font-bold text-slate-400">Mar 28, 2026</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-xl overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex justify-between items-center">
            <h3 class="text-xl font-bold text-[#1e3a8a]">Recent Transactions</h3>
            <div class="flex space-x-3">
                <div class="px-4 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-bold text-slate-500 cursor-pointer flex items-center">
                    <i class="fas fa-filter mr-2"></i> All Utilities <i class="fas fa-chevron-down ml-2 opacity-50"></i>
                </div>
                <div class="px-4 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-bold text-slate-500 cursor-pointer flex items-center">
                    <i class="far fa-calendar mr-2"></i> Last 6 Months <i class="fas fa-chevron-down ml-2 opacity-50"></i>
                </div>
            </div>
        </div>

        <table class="w-full text-left">
            <thead class="bg-slate-50/50 text-[10px] font-black text-slate-400 uppercase tracking-widest">
                <tr>
                    <th class="px-8 py-5">Billing Date</th>
                    <th class="px-8 py-5">Utility Type</th>
                    <th class="px-8 py-5">Usage</th>
                    <th class="px-8 py-5">Amount</th>
                    <th class="px-8 py-5 text-center">Status</th>
                    <th class="px-8 py-5 text-center">Done</th>
                    <th class="px-8 py-5 text-right">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <tr class="hover:bg-slate-50/50 transition-colors">
                    <td class="px-8 py-6 text-sm font-bold text-slate-700">Apr 01, 2026</td>
                    <td class="px-8 py-6 text-sm font-bold text-slate-800">Electricity</td>
                    <td class="px-8 py-6">
                        <p class="text-sm font-bold text-slate-800">245 kWh</p>
                        <p class="text-[10px] text-slate-400 italic font-medium">Avg: 230 kWh</p>
                    </td>
                    <td class="px-8 py-6 text-sm font-bold text-[#1e3a8a]">₱285.50</td>
                    <td class="px-8 py-6 text-center">
                        <span class="bg-amber-400 text-white text-[10px] font-black px-3 py-1 rounded-full uppercase">Pending</span>
                    </td>
                    <td class="px-8 py-6 text-center text-slate-400">-</td>
                    <td class="px-8 py-6 text-right">
                        <a href="#" class="text-[11px] font-black text-blue-600 uppercase hover:underline">Update</a>
                    </td>
                </tr>
                <tr class="hover:bg-slate-50/50 transition-colors">
                    <td class="px-8 py-6 text-sm font-bold text-slate-700">Mar 28, 2026</td>
                    <td class="px-8 py-6 text-sm font-bold text-slate-800">Water</td>
                    <td class="px-8 py-6">
                        <p class="text-sm font-bold text-slate-800">18 m³</p>
                        <p class="text-[10px] text-slate-400 italic font-medium">Avg: 19.2 m³</p>
                    </td>
                    <td class="px-8 py-6 text-sm font-bold text-[#1e3a8a]">₱143.00</td>
                    <td class="px-8 py-6 text-center">
                        <span class="bg-blue-100 text-blue-600 text-[10px] font-black px-3 py-1 rounded-full uppercase">Paid</span>
                    </td>
                    {{-- kailangan image check --}}
                    <td class="px-8 py-6 text-center">
                        <i class="fas fa-check text-emerald-400"></i>
                    </td>
                    <td class="px-8 py-6 text-right">
                        <a href="#" class="text-[11px] font-black text-blue-600 uppercase hover:underline">Update</a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
</x-layout>
<x-layout title="Create Electricity Bills | VoltTrack">
    <div class="max-w-4xl mx-auto p-8 bg-slate-50 min-h-screen">
        <div class="mb-8">
            <p class="text-xs font-bold text-slate-200 uppercase tracking-wider">
                Billing > Electricity
            </p>
            <h1 class="text-3xl font-black text-[#F8FAFC] mt-2">
                Create Electricity Bill
            </h1>
            <p class="text-sm text-slate-200 mt-2">
                Create or update an Electricity bill for one selected resident.
            </p>
        </div>

        @if(session('error'))
            <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-red-700 text-sm">
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-red-700 text-sm">
                <ul class="list-disc pl-5 space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.Create.storeNewElectricityBill') }}" class="bg-white border border-slate-200 rounded-2xl p-6 space-y-6 shadow-sm">
            @csrf

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">
                    Resident
                </label>
                <select name="resident_id" class="w-full rounded-lg border border-slate-300 px-3 py-2" required>
                    <option value="">
                        Select resident
                    </option>
                    @foreach($residents as $resident)
                        <option value="{{ $resident->id }}" @selected((string) old('resident_id', $selectedResidentId) === (string) $resident->id)>
                            {{ $resident->first_name }} {{ $resident->last_name }} ({{ $resident->email }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Previous Reading (kWh)
                    </label>
                    <input type="number" step="0.01" min="0" name="previous_reading" value="{{ old('previous_reading') }}" class="w-full rounded-lg border border-slate-300 px-3 py-2" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Current Reading (kWh)
                    </label>
                    <input type="number" step="0.01" min="0" name="current_reading" value="{{ old('current_reading') }}" class="w-full rounded-lg border border-slate-300 px-3 py-2" required>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Reading Date
                    </label>
                    <input type="date" name="reading_date" value="{{ old('reading_date', now()->toDateString()) }}" class="w-full rounded-lg border border-slate-300 px-3 py-2" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Billing Start
                    </label>
                    <input type="date" name="billing_period_start" value="{{ old('billing_period_start', now()->startOfMonth()->toDateString()) }}" class="w-full rounded-lg border border-slate-300 px-3 py-2" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Billing End
                    </label>
                    <input type="date" name="billing_period_end" value="{{ old('billing_period_end', now()->endOfMonth()->toDateString()) }}" class="w-full rounded-lg border border-slate-300 px-3 py-2" required>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Price Per Unit
                    </label>
                    <input type="number" step="0.01" min="0" name="price_per_unit" value="{{ old('price_per_unit', $defaultPricePerUnit ?? 0) }}" class="w-full rounded-lg border border-slate-300 px-3 py-2" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Service Fee
                    </label>
                    <input type="number" step="0.01" min="0" name="service_fee" value="{{ old('service_fee', $defaultServiceFee ?? 100) }}" class="w-full rounded-lg border border-slate-300 px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Status
                    </label>
                    <select name="status" class="w-full rounded-lg border border-slate-300 px-3 py-2" required>
                        @foreach(['Pending', 'Overdue', 'Paid'] as $status)
                            <option value="{{ $status }}" @selected(old('status', 'Pending') === $status)>{{ $status }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="flex items-center gap-3 pt-2">
                <a href="{{ $selectedResidentId ? route('admin.residentInfo', $selectedResidentId) : route('admin.billingHistory') }}" class="px-5 py-2.5 rounded-lg bg-slate-200 text-slate-700 font-semibold">
                    Cancel
                </a>
                <button type="submit" class="px-5 py-2.5 rounded-lg bg-[#001D4E] text-white font-semibold">
                    Create Electricity Bill
                </button>
            </div>
        </form>
    </div>
</x-layout>

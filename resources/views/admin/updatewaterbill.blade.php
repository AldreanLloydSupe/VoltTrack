{{-- Renders the Updatewaterbill view for VoltTrack. --}}
<x-layout title="Update Water Bill | VoltTrack">
    <div class="max-w-5xl mx-auto p-8 bg-slate-50 min-h-screen">
        <div class="mb-8 flex items-start justify-between gap-4">
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">
                    <a href="{{ route('admin.residentList') }}" data-instant-nav class="hover:text-slate-300">Resident List</a>
                    > <a href="{{ route('admin.residentInfo', $resident->id) }}" data-instant-nav class="hover:text-slate-300">{{ $resident->first_name }} {{ $resident->last_name }}</a>
                    > <span class="text-blue-600">Update Water Bill</span>
                </p>
                <h1 class="text-3xl font-black text-[#F8FAFC] mt-2">Update Water Bill</h1>
                <p class="text-sm text-slate-400 mt-2">Edit readings, charges, and status for this water transaction.</p>
            </div>
        </div>

        {{-- Conditional message/block --}}
        @if($errors->any())
            <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-red-700 text-sm">
                <ul class="list-disc pl-5 space-y-1">
                    {{-- List rendering --}}
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Form --}}
            <form
                method="POST"
                action="{{ route('admin.bills.update', $bill->id) }}"
                data-instant-form
                data-confirm
                data-confirm-title="Update Water Bill?"
                data-confirm-message="This will save the updated readings, charges, and status for this water bill."
                data-confirm-confirm-label="Update Bill"
                data-confirm-when-field="status"
                data-confirm-when-value="Paid"
                data-confirm-when-title="Mark Water Bill Paid?"
                data-confirm-when-message="This will update the bill and set its status to Paid."
                data-confirm-when-label="Mark Paid"
                data-confirm-when-variant="success"
                class="lg:col-span-2 bg-white border border-slate-200 rounded-2xl p-6 space-y-6 shadow-sm"
            >
                @csrf
                @method('PATCH')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Previous Reading (m3)</label>
                        <input type="number" step="0.01" min="0" name="previous_reading" value="{{ old('previous_reading', $bill->previous_reading) }}" class="w-full rounded-lg border border-slate-300 px-3 py-2" required>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Current Reading (m3)</label>
                        <input type="number" step="0.01" min="0" name="current_reading" value="{{ old('current_reading', $bill->current_reading) }}" class="w-full rounded-lg border border-slate-300 px-3 py-2" required>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Reading Date</label>
                        <input type="date" name="reading_date" value="{{ old('reading_date', $bill->reading_date?->toDateString()) }}" class="w-full rounded-lg border border-slate-300 px-3 py-2" required>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Billing Start</label>
                        <input type="date" name="billing_period_start" value="{{ old('billing_period_start', $bill->billing_period_start?->toDateString()) }}" class="w-full rounded-lg border border-slate-300 px-3 py-2" required>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Billing End</label>
                        <input type="date" name="billing_period_end" value="{{ old('billing_period_end', $bill->billing_period_end?->toDateString()) }}" class="w-full rounded-lg border border-slate-300 px-3 py-2" required>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Price Per m3</label>
                        <input type="number" step="0.01" min="0" name="price_per_unit" value="{{ old('price_per_unit', $bill->price_per_unit) }}" class="w-full rounded-lg border border-slate-300 px-3 py-2" required>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Service Fee</label>
                        <input type="number" step="0.01" min="0" name="service_fee" value="{{ old('service_fee', $bill->service_fee) }}" class="w-full rounded-lg border border-slate-300 px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Status</label>
                        <select name="status" class="w-full rounded-lg border border-slate-300 px-3 py-2" required>
                            {{-- List rendering --}}
                            @foreach(['Pending', 'Overdue', 'Paid'] as $status)
                                <option value="{{ $status }}" @selected(old('status', $bill->status) === $status)>{{ $status }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <label class="inline-flex items-center gap-3 text-sm font-semibold text-slate-700">
                    <input type="checkbox" name="is_done" value="1" class="rounded border-slate-300" @checked(old('is_done', $bill->is_done))>
                    Mark done
                </label>

                <div class="flex items-center gap-3 pt-2">
                    <a href="{{ route('admin.residentInfo', $resident->id) }}" data-instant-nav class="px-5 py-2.5 rounded-lg bg-slate-200 text-slate-700 font-semibold">Cancel</a>
                    <button type="submit" class="px-5 py-2.5 rounded-lg bg-[#001D4E] text-white font-semibold">Update Bill</button>
                </div>
            </form>

            <aside class="bg-[#001D4E] rounded-2xl p-6 text-white shadow-xl h-fit">
                <p class="text-xs font-black uppercase tracking-widest text-white/50 mb-4">Meter Spec</p>
                <div class="space-y-4 text-sm">
                    <div>
                        <p class="text-white/50">Meter No</p>
                        <p class="font-bold">{{ $bill->meter_no ?? $meter?->hardware_meter_number ?? $meter?->serial_number ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-white/50">Resident</p>
                        <p class="font-bold">{{ $resident->first_name }} {{ $resident->last_name }}</p>
                    </div>
                    <div>
                        <p class="text-white/50">Current Total</p>
                        <p class="text-3xl font-black">&#8369;{{ number_format($bill->amount_payable, 2) }}</p>
                    </div>
                    <div class="border-t border-white/10 pt-4">
                        <p class="text-white/50">Consumption</p>
                        <p class="font-bold">{{ number_format((float) $bill->consumption, 2) }} m3</p>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</x-layout>
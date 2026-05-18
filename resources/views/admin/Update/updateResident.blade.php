{{-- Renders the Update Resident view for VoltTrack. --}}
<x-layout title="Updating Resident Account | VoltTrack">
    <div class="max-w-5xl mx-auto py-10">
        {{-- Conditional message/block --}}
        @if ($errors->any())
            <div class="mb-6 rounded-xl border border-red-300 bg-red-50 px-4 py-3 text-sm font-semibold text-red-700">
                <ul class="list-disc pl-5 space-y-1">
                    {{-- List rendering --}}
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <nav class="flex text-[10px] font-black uppercase tracking-widest text-slate-400 mb-4 gap-2">
            <a href="{{ route('admin.residentList') }}" data-instant-nav class="hover:text-slate-300 transition-all">
                Resident List
            </a>
            <span>&gt;</span>
            <a href="{{ route('admin.residentInfo', $resident->id) }}" data-instant-nav class="hover:text-slate-300 transition-all">
                {{ $resident->first_name }} {{ $resident->last_name }}
            </a>
            <span>&gt;</span>
            <span class="text-slate-500">
                Update Account
            </span>
        </nav>

        <div class="bg-white p-10 rounded-3xl border border-slate-200 shadow-sm relative overflow-hidden">
            {{-- Form --}}
            <form
                action="{{ route('admin.resident.update', $resident->id) }}"
                method="POST"
                data-instant-form
                data-confirm
                data-confirm-title="Update Resident Account?"
                data-confirm-message="This will save the changes to {{ $resident->first_name }} {{ $resident->last_name }}'s resident profile and record the update in the audit log."
                data-confirm-confirm-label="Apply Changes"
            >
                @csrf
                @method('PATCH')

                <div class="flex justify-between items-start mb-10">
                    <div>
                        <span class="text-[10px] font-black text-blue-500 uppercase tracking-[0.2em] block mb-1">
                            Profile
                        </span>
                        <h1 class="text-3xl font-black text-[#001D4E] tracking-tight">
                            Update Resident Account
                        </h1>
                    </div>

                    <div class="flex gap-3">
                        <a href="{{ route('admin.residentInfo', $resident->id) }}" data-instant-nav class="px-6 py-2.5 bg-slate-100 text-slate-600 font-black rounded-xl text-sm hover:bg-slate-200 transition-colors">
                            Cancel
                        </a>
                        <button type="submit" class="px-6 py-2.5 bg-[#001D4E] text-white font-black rounded-xl text-sm shadow-lg shadow-blue-900/20 hover:scale-[1.02] active:scale-95 transition-all">
                            Apply Changes
                        </button>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-x-8 gap-y-8">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block ml-1">
                            First Name
                        </label>
                        <input
                            type="text"
                            name="first_name"
                            value="{{ old('first_name', $resident->first_name) }}"
                            class="w-full bg-slate-50 border border-slate-200 p-4 rounded-2xl text-slate-700 font-bold outline-none ring-1 ring-slate-100 focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition-all"
                            required
                        >
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block ml-1">
                            Last Name
                        </label>
                        <input
                            type="text"
                            name="last_name"
                            value="{{ old('last_name', $resident->last_name) }}"
                            class="w-full bg-slate-50 border border-slate-200 p-4 rounded-2xl text-slate-700 font-bold outline-none ring-1 ring-slate-100 focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition-all"
                            required
                        >
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block ml-1">
                            Phone Number
                        </label>
                        <div class="flex overflow-hidden rounded-2xl border border-slate-200 bg-slate-50 text-slate-700 font-bold ring-1 ring-slate-100 transition-all focus-within:border-blue-400 focus-within:ring-2 focus-within:ring-blue-400">
                            <span class="flex items-center border-r border-slate-200 bg-slate-100 px-4 text-slate-500">+63</span>
                            <input
                                type="text"
                                name="phone_number"
                                value="{{ old('phone_number', preg_replace('/^\+63/', '', $resident->phone_number)) }}"
                                placeholder="9123456789"
                                inputmode="numeric"
                                pattern="[0-9]{10}"
                                maxlength="10"
                                autocomplete="tel-national"
                                oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10)"
                                class="min-w-0 flex-1 bg-transparent p-4 outline-none"
                                required
                            >
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block ml-1">
                            Email Address
                        </label>
                        <input
                            type="email"
                            name="email"
                            value="{{ old('email', $resident->email) }}"
                            class="w-full bg-slate-50 border border-slate-200 p-4 rounded-2xl text-slate-700 font-bold outline-none ring-1 ring-slate-100 focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition-all"
                            required
                        >
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block ml-1">
                            Gender
                        </label>
                        <div class="flex gap-8 py-3">
                            <label class="flex items-center cursor-pointer group">
                                <input type="radio" name="gender" value="Male" class="sr-only peer" @checked(old('gender', $resident->gender) === 'Male')>
                                <div class="mr-3 flex h-6 w-6 items-center justify-center rounded-full border-2 border-slate-400 bg-white transition-all after:h-3 after:w-3 after:scale-0 after:rounded-full after:bg-[#001D4E] after:transition-transform peer-checked:border-[#001D4E] peer-checked:after:scale-100">
                                </div>
                                <span class="text-sm font-bold text-slate-600">
                                    Male
                                </span>
                            </label>

                            <label class="flex items-center cursor-pointer group">
                                <input type="radio" name="gender" value="Female" class="sr-only peer" @checked(old('gender', $resident->gender) === 'Female')>
                                <div class="mr-3 flex h-6 w-6 items-center justify-center rounded-full border-2 border-slate-400 bg-white transition-all after:h-3 after:w-3 after:scale-0 after:rounded-full after:bg-[#001D4E] after:transition-transform peer-checked:border-[#001D4E] peer-checked:after:scale-100">
                                </div>
                                <span class="text-sm font-bold text-slate-600">Female</span>
                            </label>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-layout>
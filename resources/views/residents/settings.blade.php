{{-- Renders the Settings view for VoltTrack. --}}
<x-layout title="Settings | VoltTrack" :show-admin-nav="false">
    <div class="min-h-screen bg-[#eef2f7]">
        {{-- Page header --}}
        <header class="border-b border-[#2f8cff]/40 bg-[#1b2d73] px-6 py-3 text-white shadow-[0_6px_30px_rgba(10,28,74,0.2)]">
            <div class="mx-auto flex max-w-7xl items-center justify-between">
                <div>
                    <h1 class="text-4xl font-black tracking-tight">VoltTrack</h1>
                    <p class="mt-1 text-xs uppercase tracking-[0.24em] text-blue-100">Resident Settings</p>
                </div>

                <div class="flex items-center gap-4">
                    <a href="{{ route('resident.dashboard') }}" class="rounded-full border border-white/20 px-4 py-2 text-xs font-bold uppercase tracking-[0.2em] text-white transition-all hover:bg-white/10">
                        Back
                    </a>
                    {{-- Form --}}
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="rounded-full border border-white/20 px-4 py-2 text-xs font-bold uppercase tracking-[0.2em] text-white transition-all hover:bg-white/10">
                            Log Out
                        </button>
                    </form>
                </div>
            </div>
        </header>

        {{-- Main page content --}}
        <main class="mx-auto max-w-3xl px-6 py-8">
            {{-- Conditional message/block --}}
            @if (session('success'))
                <div class="mb-5 rounded-xl border border-emerald-300 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-700">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Conditional message/block --}}
            @if ($errors->any())
                <div class="mb-5 rounded-xl border border-red-300 bg-red-50 px-4 py-3 text-sm font-semibold text-red-700">
                    Please review the form and fix any errors.
                </div>
            @endif

            {{-- Content section --}}
            <section class="rounded-[28px] border border-slate-200 bg-white p-6 shadow-[0_20px_70px_rgba(15,23,42,0.08)]">
                <div class="mb-6">
                    <h2 class="text-lg font-black uppercase tracking-[0.2em] text-slate-700">Profile Settings</h2>
                    <p class="mt-1 text-sm text-slate-400">Update your resident account information.</p>
                </div>

                {{-- Update form --}}
                <form method="POST" action="{{ route('resident.settings.update') }}" class="space-y-5">
                    @csrf
                    @method('PATCH')

                    <div class="grid gap-5 sm:grid-cols-2">
                        <div>
                            <label for="first_name" class="mb-2 block text-sm font-semibold text-slate-700">First Name</label>
                            <input id="first_name" type="text" name="first_name" value="{{ old('first_name', $user->first_name) }}" class="w-full rounded-lg border border-slate-300 px-3 py-2" required>
                            @error('first_name')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="last_name" class="mb-2 block text-sm font-semibold text-slate-700">Last Name</label>
                            <input id="last_name" type="text" name="last_name" value="{{ old('last_name', $user->last_name) }}" class="w-full rounded-lg border border-slate-300 px-3 py-2" required>
                            @error('last_name')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid gap-5 sm:grid-cols-2">
                        <div>
                            <label for="phone_number" class="mb-2 block text-sm font-semibold text-slate-700">Phone Number</label>
                            <input id="phone_number" type="text" name="phone_number" value="{{ old('phone_number', $user->phone_number) }}" class="w-full rounded-lg border border-slate-300 px-3 py-2" required>
                            @error('phone_number')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="email" class="mb-2 block text-sm font-semibold text-slate-700">Email</label>
                            <input id="email" type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full rounded-lg border border-slate-300 px-3 py-2" required>
                            @error('email')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="gender" class="mb-2 block text-sm font-semibold text-slate-700">Gender</label>
                        <select id="gender" name="gender" class="w-full rounded-lg border border-slate-300 px-3 py-2" required>
                            <option value="Male" @selected(old('gender', $user->gender) === 'Male')>Male</option>
                            <option value="Female" @selected(old('gender', $user->gender) === 'Female')>Female</option>
                        </select>
                        @error('gender')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="rounded-lg bg-[#001D4E] px-5 py-2.5 text-sm font-semibold text-white hover:bg-[#163571]">
                            Save Changes
                        </button>
                    </div>
                </form>
            </section>
        </main>
    </div>
</x-layout>
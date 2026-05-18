{{-- Renders the Contact Admin view for VoltTrack. --}}
<x-layout title="Contact Admin | VoltTrack" :show-admin-nav="false">
    <div class="min-h-screen bg-[#eef2f7]">
        {{-- Page header --}}
        <header class="border-b border-[#2f8cff]/40 bg-[#1b2d73] px-6 py-3 text-white shadow-[0_6px_30px_rgba(10,28,74,0.2)]">
            <div class="mx-auto flex max-w-7xl items-center justify-between">
                <div>
                    <h1 class="text-4xl font-black tracking-tight">VoltTrack</h1>
                    <p class="mt-1 text-xs uppercase tracking-[0.24em] text-blue-100">Contact Admin</p>
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
                    <h2 class="text-lg font-black uppercase tracking-[0.2em] text-slate-700">Send Message</h2>
                    <p class="mt-1 text-sm text-slate-400">Use this form to contact the admin team.</p>
                </div>

                {{-- Form --}}
                <form method="POST" action="{{ route('resident.contactAdmin.send') }}" class="space-y-5">
                    @csrf

                    <div class="grid gap-5 sm:grid-cols-2">
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-slate-700">Your Name</label>
                            <input type="text" value="{{ $user->first_name }} {{ $user->last_name }}" class="w-full rounded-lg border border-slate-300 bg-slate-100 px-3 py-2 text-slate-600" disabled>
                        </div>

                        <div>
                            <label class="mb-2 block text-sm font-semibold text-slate-700">Your Email</label>
                            <input type="text" value="{{ $user->email }}" class="w-full rounded-lg border border-slate-300 bg-slate-100 px-3 py-2 text-slate-600" disabled>
                        </div>
                    </div>

                    <div>
                        <label for="subject" class="mb-2 block text-sm font-semibold text-slate-700">Subject</label>
                        <input id="subject" type="text" name="subject" value="{{ old('subject') }}" class="w-full rounded-lg border border-slate-300 px-3 py-2" maxlength="255" required>
                        @error('subject')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="message" class="mb-2 block text-sm font-semibold text-slate-700">Message</label>
                        <textarea id="message" name="message" rows="8" class="w-full rounded-lg border border-slate-300 px-3 py-2" maxlength="2000" required>{{ old('message') }}</textarea>
                        @error('message')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="rounded-lg bg-[#001D4E] px-5 py-2.5 text-sm font-semibold text-white hover:bg-[#163571]">
                            Send Message
                        </button>
                    </div>
                </form>
            </section>
        </main>
    </div>
</x-layout>
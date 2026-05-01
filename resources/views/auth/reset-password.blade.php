<x-layout-login title="Reset Password | VoltTrack">
    <div class="relative min-h-screen overflow-x-hidden bg-[#0f172a] font-sans text-white">
        <div class="absolute inset-0 bg-[linear-gradient(135deg,#1e3a8a_0%,#1e293b_48%,#0f172a_100%)]"></div>
        <div class="absolute inset-0 opacity-25 [background-image:linear-gradient(rgba(255,255,255,.08)_1px,transparent_1px),linear-gradient(90deg,rgba(255,255,255,.08)_1px,transparent_1px)] [background-size:42px_42px]"></div>

        <header class="relative z-10 mx-auto flex w-full max-w-7xl items-center px-5 py-5 sm:px-8 sm:py-7 lg:px-12">
            <a href="{{ route('login') }}" class="text-3xl font-black tracking-tight text-white sm:text-4xl lg:text-[44px]">VoltTrack</a>
        </header>

        <main class="relative z-10 mx-auto grid min-h-[calc(100vh-86px)] w-full max-w-7xl items-center px-5 pb-8 sm:px-8 sm:pb-12 lg:min-h-[calc(100vh-108px)] lg:px-12">
            <section class="mx-auto w-full max-w-md rounded-[24px] border-4 border-slate-950 bg-white px-5 py-7 text-slate-950 shadow-[12px_12px_0_rgba(15,23,42,0.7)] sm:max-w-lg sm:rounded-[28px] sm:px-8 sm:py-10">
                <h2 class="text-2xl font-bold tracking-tight sm:text-3xl">Reset Password</h2>
                <p class="mt-2 text-sm font-medium text-slate-500">Choose a new password for your resident account.</p>

                @if ($errors->any())
                    <div class="mt-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-medium text-red-700">
                        Please check your input and try again.
                    </div>
                @endif

                <form action="{{ route('password.update') }}" method="POST" class="mt-6 space-y-5">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">

                    <div>
                        <label class="mb-2 ml-1 block text-xs font-bold uppercase tracking-wider text-slate-600">Email Address</label>
                        <input
                            type="email"
                            name="email"
                            value="{{ old('email', $email) }}"
                            class="w-full rounded-xl border-2 border-slate-200 bg-slate-50 px-4 py-3 text-base text-[#001D4E] outline-none transition-all hover:border-slate-300 focus:border-[#3b82f6] focus:bg-white"
                            required
                        >
                    </div>

                    <div>
                        <label class="mb-2 ml-1 block text-xs font-bold uppercase tracking-wider text-slate-600">New Password</label>
                        <input
                            type="password"
                            name="password"
                            class="w-full rounded-xl border-2 border-slate-200 bg-slate-50 px-4 py-3 text-base text-[#001D4E] outline-none transition-all hover:border-slate-300 focus:border-[#3b82f6] focus:bg-white"
                            required
                        >
                    </div>

                    <div>
                        <label class="mb-2 ml-1 block text-xs font-bold uppercase tracking-wider text-slate-600">Confirm Password</label>
                        <input
                            type="password"
                            name="password_confirmation"
                            class="w-full rounded-xl border-2 border-slate-200 bg-slate-50 px-4 py-3 text-base text-[#001D4E] outline-none transition-all hover:border-slate-300 focus:border-[#3b82f6] focus:bg-white"
                            required
                        >
                    </div>

                    <button type="submit" class="w-full rounded-2xl border-2 border-[#1d4ed8] bg-[#3b82f6] py-3.5 text-sm font-bold tracking-wide text-white transition-all hover:bg-[#2563eb]">
                        Reset Password
                    </button>
                </form>
            </section>
        </main>
    </div>
</x-layout-login>


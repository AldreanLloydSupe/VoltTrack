{{-- Renders the Login view for VoltTrack. --}}
<x-layout-login title="VoltTrack Login">
    <div class="relative min-h-screen overflow-x-hidden bg-[#0f172a] font-sans text-white">
        <div class="absolute inset-0 bg-[linear-gradient(135deg,#1e3a8a_0%,#1e293b_48%,#0f172a_100%)]"></div>
        <div class="absolute inset-0 opacity-25 [background-image:linear-gradient(rgba(255,255,255,.08)_1px,transparent_1px),linear-gradient(90deg,rgba(255,255,255,.08)_1px,transparent_1px)] [background-size:42px_42px]"></div>
        <div class="absolute -left-20 top-36 h-72 w-72 rotate-12 rounded-[36px] bg-blue-500/20"></div>
        <div class="absolute bottom-10 right-[-120px] h-96 w-96 -rotate-12 rounded-[44px] bg-sky-400/10"></div>

        {{-- Page header --}}
        <header class="relative z-10 mx-auto flex w-full max-w-7xl items-center px-5 py-5 sm:px-8 sm:py-7 lg:px-12">
            <a href="{{ route('login') }}" class="text-3xl font-black tracking-tight text-white sm:text-4xl lg:text-[44px]">
                <span>VoltTrack</span>
            </a>
        </header>

        {{-- Main page content --}}
        <main class="relative z-10 mx-auto grid min-h-[calc(100vh-86px)] w-full max-w-7xl items-center gap-8 px-5 pb-8 sm:px-8 sm:pb-12 lg:min-h-[calc(100vh-108px)] lg:grid-cols-[minmax(0,1fr)_minmax(440px,520px)] lg:gap-12 lg:px-12">
            {{-- Content section --}}
            <section class="mx-auto max-w-2xl pt-2 text-center sm:pt-4 lg:mx-0 lg:pt-0 lg:text-left">
                <p class="mb-4 inline-flex max-w-full items-center gap-2 rounded-full border border-white/20 bg-[#1e3a8a] px-4 py-2 text-xs font-semibold text-slate-100 shadow-[6px_6px_0_rgba(15,23,42,0.35)] sm:mb-5 sm:text-sm">
                    <i class="fa-solid fa-chart-line text-[#93c5fd]"></i>
                    <span>Utility billing made easier</span>
                </p>
                <h1 class="text-3xl font-semibold leading-tight tracking-tight text-white min-[380px]:text-4xl sm:text-5xl lg:text-6xl">
                    Manage your utility bills from anywhere.
                </h1>
                <p class="mx-auto mt-4 max-w-xl text-base font-medium leading-relaxed text-slate-200 sm:mt-6 sm:text-xl lg:mx-0">
                    Sign in to view electricity, water, and payment records with VoltTrack.
                </p>

                <div class="mx-auto mt-8 grid max-w-md grid-cols-2 gap-3 lg:mx-0">
                    <div class="flex min-h-28 flex-col justify-between rounded-2xl border border-white/15 bg-white/10 p-4 text-left shadow-[8px_8px_0_rgba(15,23,42,0.25)]">
                        <i class="fa-solid fa-bolt mb-3 text-lg text-[#93c5fd]"></i>
                        <p class="text-sm font-bold">Electricity</p>
                    </div>
                    <div class="flex min-h-28 flex-col justify-between rounded-2xl border border-white/15 bg-white/10 p-4 text-left shadow-[8px_8px_0_rgba(15,23,42,0.25)]">
                        <i class="fa-solid fa-droplet mb-3 text-lg text-[#93c5fd]"></i>
                        <p class="text-sm font-bold">Water</p>
                    </div>
                </div>
            </section>

            {{-- Content section --}}
            <section class="mx-auto w-full max-w-md rounded-[24px] border-4 border-slate-950 bg-white px-5 py-7 text-slate-950 shadow-[12px_12px_0_rgba(15,23,42,0.7)] sm:max-w-lg sm:rounded-[28px] sm:px-8 sm:py-10 lg:max-w-none lg:px-10">
                <div class="mb-6 sm:mb-7">
                    <h2 class="text-2xl font-bold tracking-tight sm:text-3xl">
                        Sign in to VoltTrack
                    </h2>
                    <p class="mt-2 text-sm font-medium text-slate-500">
                        Welcome back. Please enter your account details.
                    </p>
                </div>

                {{-- Conditional message/block --}}
                @if (session('success'))
                    <div class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700">
                        {{ session('success') }}
                    </div>
                @endif

                {{-- Conditional message/block --}}
                @if ($errors->any())
                    <div class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-medium text-red-700">
                        Please check your email and password, then try again.
                    </div>
                @endif

                {{-- Login Data --}}
                <form action="{{ route('login.store') }}" method="POST" class="space-y-4 sm:space-y-5">
                    @csrf

                    <div>
                        <label class="mb-2 ml-1 block text-xs font-bold uppercase tracking-wider text-slate-600">
                            Email Address
                        </label>
                        <input
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            class="w-full rounded-xl border-2 border-slate-200 bg-slate-50 px-4 py-3 text-base text-[#001D4E] outline-none transition-all hover:border-slate-300 focus:border-[#3b82f6] focus:bg-white focus:shadow-[4px_4px_0_rgba(59,130,246,0.18)]"
                            required
                        >
                        @error('email')
                            <p class="mt-1 ml-1 text-xs font-medium text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div x-data="{ show: false }">
                        <label class="mb-2 ml-1 block text-xs font-bold uppercase tracking-wider text-slate-600">
                            Password
                        </label>

                        <div class="relative flex items-center">
                            <input
                                :type="show ? 'text' : 'password'"
                                name="password"
                                class="w-full rounded-xl border-2 border-slate-200 bg-slate-50 px-4 py-3 pr-12 text-base font-semibold text-[#001D4E] outline-none transition-all hover:border-slate-300 focus:border-[#3b82f6] focus:bg-white focus:shadow-[4px_4px_0_rgba(59,130,246,0.18)]"
                                required
                            >

                            <button
                                type="button"
                                @click="show = !show"
                                class="absolute right-3 grid h-9 w-9 place-items-center rounded-full text-slate-500 transition-colors hover:bg-slate-200 hover:text-blue-600 focus:outline-none"
                                aria-label="Show or hide password"
                            >
                                <i class="fas" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                            </button>
                        </div>

                        @error('password')
                            <p class="mt-1 ml-1 text-xs font-medium text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex flex-col gap-3 px-1 text-xs font-bold text-slate-600 min-[420px]:flex-row min-[420px]:items-center min-[420px]:justify-between min-[420px]:gap-4">
                        <label class="flex cursor-pointer items-center">
                            <input type="checkbox" name="remember" class="mr-2 rounded border-slate-300 text-blue-600 focus:ring-blue-500" {{ old('remember') ? 'checked' : '' }}>
                            REMEMBER ME
                        </label>
                        <a href="{{ route('password.request') }}" class="text-blue-600 transition-colors hover:text-blue-800 hover:underline">
                            Forgot Password?
                        </a>
                    </div>

                    <button type="submit" class="mt-3 w-full rounded-2xl border-2 border-[#1d4ed8] bg-[#3b82f6] py-3.5 text-sm font-bold tracking-wide text-white transition-all hover:-translate-y-0.5 hover:bg-[#2563eb] active:translate-y-0 sm:py-4">
                        Login
                    </button>
                </form>

                <p class="mt-7 text-center text-sm font-medium text-slate-700">
                    New to VoltTrack?
                    <a href="{{ url('/register') }}" class="font-bold text-blue-600 hover:text-blue-800 hover:underline">
                        Create Account
                    </a>
                </p>
            </section>
        </main>
    </div>
</x-layout-login>
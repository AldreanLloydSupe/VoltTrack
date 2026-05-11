<x-layout-login title="Create Account | VoltTrack">
    <div class="relative min-h-screen overflow-x-hidden bg-[#0f172a] font-sans text-white">
        <div class="absolute inset-0 bg-[linear-gradient(135deg,#1e3a8a_0%,#1e293b_48%,#0f172a_100%)]"></div>
        <div class="absolute inset-0 opacity-25 [background-image:linear-gradient(rgba(255,255,255,.08)_1px,transparent_1px),linear-gradient(90deg,rgba(255,255,255,.08)_1px,transparent_1px)] [background-size:42px_42px]"></div>
        <div class="absolute -left-20 top-36 h-72 w-72 rotate-12 rounded-[36px] bg-blue-500/20"></div>
        <div class="absolute bottom-10 right-[-120px] h-96 w-96 -rotate-12 rounded-[44px] bg-sky-400/10"></div>

        <header class="relative z-10 mx-auto flex w-full max-w-7xl items-center px-5 py-5 sm:px-8 sm:py-7 lg:px-12">
            <a href="{{ route('login') }}" class="text-3xl font-black tracking-tight text-white sm:text-4xl lg:text-[44px]">
                <span>VoltTrack</span>
            </a>
        </header>

        <main class="relative z-10 mx-auto grid min-h-[calc(100vh-86px)] w-full max-w-7xl items-center gap-8 px-5 pb-8 sm:px-8 sm:pb-12 lg:min-h-[calc(100vh-108px)] lg:grid-cols-[minmax(0,0.8fr)_minmax(560px,680px)] lg:gap-12 lg:px-12">
            <section class="mx-auto max-w-2xl pt-2 text-center sm:pt-4 lg:mx-0 lg:pt-0 lg:text-left">
                <p class="mb-4 inline-flex max-w-full items-center gap-2 rounded-full border border-white/20 bg-[#1e3a8a] px-4 py-2 text-xs font-semibold text-slate-100 shadow-[6px_6px_0_rgba(15,23,42,0.35)] sm:mb-5 sm:text-sm">
                    <i class="fa-solid fa-user-plus text-[#93c5fd]"></i>
                    <span>Create your resident account</span>
                </p>
                <h1 class="text-3xl font-semibold leading-tight tracking-tight text-white min-[380px]:text-4xl sm:text-5xl lg:text-6xl">
                    Start tracking your bills today.
                </h1>
                <p class="mx-auto mt-4 max-w-xl text-base font-medium leading-relaxed text-slate-200 sm:mt-6 sm:text-xl lg:mx-0">
                    Register to manage electricity, water, and payment records in one clean dashboard.
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

            <section class="mx-auto w-full max-w-xl rounded-[24px] border-4 border-slate-950 bg-white px-5 py-7 text-slate-950 shadow-[12px_12px_0_rgba(15,23,42,0.7)] sm:max-w-2xl sm:rounded-[28px] sm:px-8 sm:py-9 lg:max-w-none lg:px-10">
                <div class="mb-6">
                    <h2 class="text-2xl font-bold tracking-tight sm:text-3xl">
                        Create your account
                    </h2>
                    <p class="mt-2 text-sm font-medium text-slate-500">
                        Fill in your resident details to get started.
                    </p>
                </div>

                @if ($errors->any())
                    <div class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-medium text-red-700">
                        Please fix the highlighted fields and try again.
                    </div>
                @endif

                <form action="{{ route('register.store') }}" method="POST" class="space-y-4">
                    @csrf

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label class="mb-1.5 ml-1 block text-xs font-bold uppercase tracking-wider text-slate-600">First Name</label>
                            <input type="text" name="first_name" value="{{ old('first_name') }}" placeholder="Enter your first name" class="w-full rounded-xl border-2 border-slate-200 bg-slate-50 px-4 py-3 text-base text-[#001D4E] outline-none transition-all hover:border-slate-300 focus:border-[#3b82f6] focus:bg-white focus:shadow-[4px_4px_0_rgba(59,130,246,0.18)]">
                            @error('first_name')
                                <p class="mt-1 ml-1 text-xs font-medium text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="mb-1.5 ml-1 block text-xs font-bold uppercase tracking-wider text-slate-600">Last Name</label>
                            <input type="text" name="last_name" value="{{ old('last_name') }}" placeholder="Enter your last name" class="w-full rounded-xl border-2 border-slate-200 bg-slate-50 px-4 py-3 text-base text-[#001D4E] outline-none transition-all hover:border-slate-300 focus:border-[#3b82f6] focus:bg-white focus:shadow-[4px_4px_0_rgba(59,130,246,0.18)]">
                            @error('last_name')
                                <p class="mt-1 ml-1 text-xs font-medium text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label class="mb-1.5 ml-1 block text-xs font-bold uppercase tracking-wider text-slate-600">Phone Number</label>
                        <div class="flex overflow-hidden rounded-xl border-2 border-slate-200 bg-slate-50 text-base text-[#001D4E] transition-all hover:border-slate-300 focus-within:border-[#3b82f6] focus-within:bg-white focus-within:shadow-[4px_4px_0_rgba(59,130,246,0.18)]">
                            <span class="flex items-center border-r-2 border-slate-200 bg-slate-100 px-4 font-bold text-slate-600">+63</span>
                            <input
                                type="text"
                                name="phone"
                                value="{{ old('phone') }}"
                                placeholder="9123456789"
                                inputmode="numeric"
                                pattern="[0-9]{10}"
                                maxlength="10"
                                autocomplete="tel-national"
                                oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10)"
                                class="min-w-0 flex-1 bg-transparent px-4 py-3 outline-none"
                            >
                        </div>
                        @error('phone')
                            <p class="mt-1 ml-1 text-xs font-medium text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-1.5 ml-1 block text-xs font-bold uppercase tracking-wider text-slate-600">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="Enter your email" class="w-full rounded-xl border-2 border-slate-200 bg-slate-50 px-4 py-3 text-base text-[#001D4E] outline-none transition-all hover:border-slate-300 focus:border-[#3b82f6] focus:bg-white focus:shadow-[4px_4px_0_rgba(59,130,246,0.18)]">
                        @error('email')
                            <p class="mt-1 ml-1 text-xs font-medium text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <span class="mb-2 ml-1 block text-xs font-bold uppercase tracking-wider text-slate-600">
                            Gender
                        </span>
                        <div class="grid grid-cols-2 gap-3">
                            <label class="flex cursor-pointer items-center rounded-xl border-2 border-slate-200 bg-slate-50 px-4 py-3 text-sm font-bold text-slate-700 transition hover:border-slate-300">
                                <input type="radio" name="gender" value="male" class="mr-2 accent-blue-500" {{ old('gender') === 'male' ? 'checked' : '' }}>
                                Male
                            </label>
                            <label class="flex cursor-pointer items-center rounded-xl border-2 border-slate-200 bg-slate-50 px-4 py-3 text-sm font-bold text-slate-700 transition hover:border-slate-300">
                                <input type="radio" name="gender" value="female" class="mr-2 accent-blue-500" {{ old('gender') === 'female' ? 'checked' : '' }}>
                                Female
                            </label>
                        </div>
                        @error('gender')
                            <p class="mt-1 ml-1 text-xs font-medium text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div x-data="{ showPass: false }">
                            <label class="mb-1.5 ml-1 block text-xs font-bold uppercase tracking-wider text-slate-600">
                                Password
                            </label>
                            <div class="relative flex items-center">
                                <input
                                    :type="showPass ? 'text' : 'password'"
                                    name="password"
                                    placeholder="Enter your password"
                                    class="w-full rounded-xl border-2 border-slate-200 bg-slate-50 px-4 py-3 pr-12 text-base font-semibold text-[#001D4E] outline-none transition-all hover:border-slate-300 focus:border-[#3b82f6] focus:bg-white focus:shadow-[4px_4px_0_rgba(59,130,246,0.18)]"
                                >
                                <button
                                    type="button"
                                    @click="showPass = !showPass"
                                    class="absolute right-3 grid h-9 w-9 place-items-center rounded-full text-slate-500 transition-colors hover:bg-slate-200 hover:text-blue-600 focus:outline-none"
                                    aria-label="Show or hide password"
                                >
                                    <i class="fas" :class="showPass ? 'fa-eye-slash' : 'fa-eye'"></i>
                                </button>
                            </div>
                            @error('password')
                                <p class="mt-1 ml-1 text-xs font-medium text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div x-data="{ showConfirm: false }">
                            <label class="mb-1.5 ml-1 block text-xs font-bold uppercase tracking-wider text-slate-600">
                                Confirm Password
                            </label>
                            <div class="relative flex items-center">
                                <input
                                    :type="showConfirm ? 'text' : 'password'"
                                    name="password_confirmation"
                                    placeholder="Confirm Password"
                                    class="w-full rounded-xl border-2 border-slate-200 bg-slate-50 px-4 py-3 pr-12 text-base font-semibold text-[#001D4E] outline-none transition-all hover:border-slate-300 focus:border-[#3b82f6] focus:bg-white focus:shadow-[4px_4px_0_rgba(59,130,246,0.18)]"
                                >
                                <button
                                    type="button"
                                    @click="showConfirm = !showConfirm"
                                    class="absolute right-3 grid h-9 w-9 place-items-center rounded-full text-slate-500 transition-colors hover:bg-slate-200 hover:text-blue-600 focus:outline-none"
                                    aria-label="Show or hide password confirmation"
                                >
                                    <i class="fas" :class="showConfirm ? 'fa-eye-slash' : 'fa-eye'"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    @error('role')
                        <p class="ml-1 text-xs font-medium text-red-600">{{ $message }}</p>
                    @enderror

                    <div class="flex items-start gap-3 rounded-xl border-2 border-slate-200 bg-slate-50 px-4 py-3">
                        <input
                            type="checkbox"
                            name="agree_terms"
                            value="1"
                            required
                            class="mt-0.5 rounded border-slate-300 text-blue-600 focus:ring-blue-500"
                            {{ old('agree_terms') ? 'checked' : '' }}
                        >
                        <p class="text-xs font-medium leading-relaxed text-slate-600">
                            I agree to the
                            <a href="#" class="font-bold text-blue-600 hover:text-blue-800 hover:underline">
                                Terms of Service
                            </a> and
                            <a href="#" class="font-bold text-blue-600 hover:text-blue-800 hover:underline">
                                Privacy Policy
                            </a>.
                        </p>
                    </div>
                    @error('agree_terms')
                        <p class="mt-1 ml-1 text-xs font-medium text-red-600">{{ $message }}</p>
                    @enderror

                    <button type="submit" class="w-full rounded-2xl border-2 border-[#1d4ed8] bg-[#3b82f6] py-3.5 text-sm font-bold tracking-wide text-white transition-all hover:-translate-y-0.5 hover:bg-[#2563eb] active:translate-y-0 sm:py-4">
                        Create Account
                    </button>

                    <p class="text-center text-sm font-medium text-slate-700">
                        Already have an account?
                        <a href="{{ route('login') }}" class="font-bold text-blue-600 hover:text-blue-800 hover:underline">
                            Sign In
                        </a>
                    </p>
                </form>
            </section>
        </main>
    </div>
</x-layout-login>

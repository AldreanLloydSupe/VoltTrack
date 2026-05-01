<x-layout-login title="Forgot Password | VoltTrack">
    <div class="relative min-h-screen overflow-x-hidden bg-[#0f172a] font-sans text-white">
        <div class="absolute inset-0 bg-[linear-gradient(135deg,#1e3a8a_0%,#1e293b_48%,#0f172a_100%)]"></div>
        <div class="absolute inset-0 opacity-25 [background-image:linear-gradient(rgba(255,255,255,.08)_1px,transparent_1px),linear-gradient(90deg,rgba(255,255,255,.08)_1px,transparent_1px)] [background-size:42px_42px]"></div>

        <header class="relative z-10 mx-auto flex w-full max-w-7xl items-center px-5 py-5 sm:px-8 sm:py-7 lg:px-12">
            <a href="{{ route('login') }}" class="text-3xl font-black tracking-tight text-white sm:text-4xl lg:text-[44px]">VoltTrack</a>
        </header>

        <main class="relative z-10 mx-auto grid min-h-[calc(100vh-86px)] w-full max-w-7xl items-center px-5 pb-8 sm:px-8 sm:pb-12 lg:min-h-[calc(100vh-108px)] lg:px-12">
            <section class="mx-auto w-full max-w-md rounded-[24px] border-4 border-slate-950 bg-white px-5 py-7 text-slate-950 shadow-[12px_12px_0_rgba(15,23,42,0.7)] sm:max-w-lg sm:rounded-[28px] sm:px-8 sm:py-10">
                <h2 class="text-2xl font-bold tracking-tight sm:text-3xl">Reset Resident Password</h2>
                <p class="mt-2 text-sm font-medium text-slate-500">Enter your resident email and set a new password.</p>

                @if ($errors->any())
                    <div class="mt-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-medium text-red-700">
                        Please check your input and try again.
                    </div>
                @endif

                <form action="{{ route('password.email') }}" method="POST" class="mt-6 space-y-5">
                    @csrf
                    <div>
                        <label class="mb-2 ml-1 block text-xs font-bold uppercase tracking-wider text-slate-600">Email Address</label>
                        <input
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            class="w-full rounded-xl border-2 border-slate-200 bg-slate-50 px-4 py-3 text-base text-[#001D4E] outline-none transition-all hover:border-slate-300 focus:border-[#3b82f6] focus:bg-white"
                            required
                        >
                        @error('email')
                            <p class="mt-1 ml-1 text-xs font-medium text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-2 ml-1 block text-xs font-bold uppercase tracking-wider text-slate-600">New Password</label>
                        <div class="relative">
                            <input
                                id="forgot-password-input"
                                type="password"
                                name="password"
                                class="w-full rounded-xl border-2 border-slate-200 bg-slate-50 px-4 py-3 pr-12 text-base text-[#001D4E] outline-none transition-all hover:border-slate-300 focus:border-[#3b82f6] focus:bg-white"
                                required
                            >
                            <button
                                type="button"
                                class="forgot-password-toggle absolute right-3 top-1/2 flex h-9 w-9 -translate-y-1/2 items-center justify-center rounded-full text-slate-500 transition-colors hover:bg-slate-200 hover:text-blue-600 focus:outline-none"
                                data-target="forgot-password-input"
                                aria-label="Show new password"
                                aria-pressed="false"
                            >
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <div>
                        <label class="mb-2 ml-1 block text-xs font-bold uppercase tracking-wider text-slate-600">Confirm Password</label>
                        <div class="relative">
                            <input
                                id="forgot-password-confirmation-input"
                                type="password"
                                name="password_confirmation"
                                class="w-full rounded-xl border-2 border-slate-200 bg-slate-50 px-4 py-3 pr-12 text-base text-[#001D4E] outline-none transition-all hover:border-slate-300 focus:border-[#3b82f6] focus:bg-white"
                                required
                            >
                            <button
                                type="button"
                                class="forgot-password-toggle absolute right-3 top-1/2 flex h-9 w-9 -translate-y-1/2 items-center justify-center rounded-full text-slate-500 transition-colors hover:bg-slate-200 hover:text-blue-600 focus:outline-none"
                                data-target="forgot-password-confirmation-input"
                                aria-label="Show confirm password"
                                aria-pressed="false"
                            >
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="w-full rounded-2xl border-2 border-[#1d4ed8] bg-[#3b82f6] py-3.5 text-sm font-bold tracking-wide text-white transition-all hover:bg-[#2563eb]">
                        Reset Password
                    </button>
                </form>

                <p class="mt-7 text-center text-sm font-medium text-slate-700">
                    Remember your password?
                    <a href="{{ route('login') }}" class="font-bold text-blue-600 hover:text-blue-800 hover:underline">Back to Login</a>
                </p>
            </section>
        </main>
    </div>

    <script>
        (() => {
            const initForgotPasswordToggles = () => {
                document.querySelectorAll('.forgot-password-toggle').forEach((button) => {
                    if (button.dataset.bound === 'true') {
                        return;
                    }

                    button.dataset.bound = 'true';

                    button.addEventListener('click', () => {
                        const input = document.getElementById(button.dataset.target);
                        const icon = button.querySelector('i');

                        if (!input || !icon) {
                            return;
                        }

                        const showPassword = input.type === 'password';
                        input.type = showPassword ? 'text' : 'password';
                        button.setAttribute('aria-pressed', showPassword ? 'true' : 'false');
                        button.setAttribute('aria-label', showPassword ? 'Hide password' : 'Show password');
                        icon.classList.toggle('fa-eye', !showPassword);
                        icon.classList.toggle('fa-eye-slash', showPassword);
                    });
                });
            };

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initForgotPasswordToggles);
            } else {
                initForgotPasswordToggles();
            }
        })();
    </script>
</x-layout-login>

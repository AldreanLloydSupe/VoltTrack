<x-layout-login title="VoltTrack Login">
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-[#1e3a8a] via-[#1e293b] to-[#0f172a]">
        
        <x-logincard>
            <div class="text-center mb-8">
                <h1 class="text-4xl font-bold text-white tracking-tight">
                    VoltTrack
                </h1>
                <p class="text-slate-300 font-medium mt-1">
                    Login
                </p>
            </div>

            @if (session('success'))
                <div class="mb-4 rounded-xl border border-emerald-400/40 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-100">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-4 rounded-xl border border-red-400/40 bg-red-500/10 px-4 py-3 text-sm text-red-100">
                    Please check your email and password, then try again.
                </div>
            @endif

            {{-- Login Data --}}
            <form action="{{ route('login.store') }}" method="POST" class="space-y-6">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2 ml-1">Email Address</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="w-full px-4 py-3 rounded-xl bg-slate-200/90 border-none focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                    @error('email')
                        <p class="mt-1 ml-1 text-xs text-red-200">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2 ml-1">
                        Password
                    </label>
                    <input type="password" name="password" class="w-full px-4 py-3 rounded-xl bg-slate-200/90 border-none focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                    @error('password')
                        <p class="mt-1 ml-1 text-xs text-red-200">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between text-[11px] font-bold text-slate-300 px-1">
                    <label class="flex items-center cursor-pointer">
                        <input type="checkbox" name="remember" class="rounded border-none bg-white/20 mr-2 text-blue-500" {{ old('remember') ? 'checked' : '' }}>
                        REMEMBER ME
                    </label>
                    <a href="#" class="hover:text-white uppercase transition-colors">
                        Forgot Password?
                    </a>
                </div>

                <div class="pt-2 flex flex-col items-center justify-center pt-2">
                    <button type="submit" class="w-[120px] py-2.5 bg-[#3b82f6] hover:bg-[#2563eb] text-white font-bold rounded-[14px] shadow-[0_4px_15px_rgba(59,130,246,0.6)] transition-all transform active:scale-95 text-sm tracking-wide">
                        Login
                    </button>
                </div>
            </form>
            <br>
            <div class="pt-2 flex flex-col items-center justify-center pt-2">
                    <p class="text-white">
                        New?
                        <a href="{{ url('/register') }}" class="font-bold">
                            Create Account
                        </a>
                    </p>
            </div>
        </x-logincard>

    </div>
</x-layout-login>

<x-layout-login title="Create Account | VoltTrack">
    <div class="min-h-screen py-10 flex items-center justify-center bg-gradient-to-br from-[#1e3a8a] via-[#1e293b] to-[#0f172a] font-sans">
        
        <x-logincard class="max-w-xl">
            <div class="text-center mb-6">
                <h1 class="text-3xl font-bold text-white tracking-tight">
                    Create your account
                </h1>
                <p class="text-slate-400 text-sm mt-1">
                    Start managing your utilities with VoltTrack today
                </p>
            </div>

            @if ($errors->any())
                <div class="mb-4 rounded-xl border border-red-400/40 bg-red-500/10 px-4 py-3 text-sm text-red-100">
                    Please fix the highlighted fields and try again.
                </div>
            @endif

            <form action="{{ route('register.store') }}" method="POST" class="space-y-4">
                @csrf
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 uppercase mb-1.5 ml-1">First Name</label>
                        <input type="text" name="first_name" value="{{ old('first_name') }}" placeholder="Enter your first name" class="w-full px-4 py-2.5 rounded-xl bg-slate-200/90 border-none outline-none text-sm focus:ring-2 focus:ring-blue-500 transition-all">
                        @error('first_name')
                            <p class="mt-1 ml-1 text-xs text-red-200">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 uppercase mb-1.5 ml-1">Last Name</label>
                        <input type="text" name="last_name" value="{{ old('last_name') }}" placeholder="Enter your last name" class="w-full px-4 py-2.5 rounded-xl bg-slate-200/90 border-none outline-none text-sm focus:ring-2 focus:ring-blue-500 transition-all">
                        @error('last_name')
                            <p class="mt-1 ml-1 text-xs text-red-200">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase mb-1.5 ml-1">Phone Number</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" placeholder="Enter your number" class="w-full px-4 py-2.5 rounded-xl bg-slate-200/90 border-none outline-none text-sm focus:ring-2 focus:ring-blue-500 transition-all">
                    @error('phone')
                        <p class="mt-1 ml-1 text-xs text-red-200">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase mb-1.5 ml-1">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="Enter your email" class="w-full px-4 py-2.5 rounded-xl bg-slate-200/90 border-none outline-none text-sm focus:ring-2 focus:ring-blue-500 transition-all">
                    @error('email')
                        <p class="mt-1 ml-1 text-xs text-red-200">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-2 gap-4 items-end">
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 uppercase mb-1.5 ml-1">House Number</label>
                        <input type="text" name="house_number" value="{{ old('house_number') }}" placeholder="House Number" class="w-full px-4 py-2.5 rounded-xl bg-slate-200/90 border-none outline-none text-sm focus:ring-2 focus:ring-blue-500 transition-all">
                        @error('house_number')
                            <p class="mt-1 ml-1 text-xs text-red-200">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="flex items-center space-x-4 pb-2.5 ml-1">
                        <span class="text-xs font-semibold text-slate-300 uppercase">
                            Gender:
                        </span>
                        <label class="inline-flex items-center text-sm text-slate-300 cursor-pointer">
                            <input type="radio" name="gender" value="male" class="mr-2 accent-blue-500" {{ old('gender') === 'male' ? 'checked' : '' }}> Male
                        </label>
                        <label class="inline-flex items-center text-sm text-slate-300 cursor-pointer">
                            <input type="radio" name="gender" value="female" class="mr-2 accent-blue-500" {{ old('gender') === 'female' ? 'checked' : '' }}> Female
                        </label>
                    </div>
                </div>
                @error('gender')
                    <p class="-mt-2 ml-1 text-xs text-red-200">{{ $message }}</p>
                @enderror

                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase mb-1.5 ml-1">
                        Password
                    </label>
                    <input type="password" name="password" placeholder="Enter your password" class="w-full px-4 py-2.5 rounded-xl bg-slate-200/90 border-none outline-none text-sm focus:ring-2 focus:ring-blue-500 transition-all">
                    @error('password')
                        <p class="mt-1 ml-1 text-xs text-red-200">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase mb-1.5 ml-1">
                        Confirm Password
                    </label>
                    <input type="password" name="password_confirmation" placeholder="Confirm Password" class="w-full px-4 py-2.5 rounded-xl bg-slate-200/90 border-none outline-none text-sm focus:ring-2 focus:ring-blue-500 transition-all">
                </div>

               
                @error('role')
                    <p class="-mt-2 ml-1 text-xs text-red-200">{{ $message }}</p>
                @enderror

                <div class="flex items-center ml-1">
                    <input type="checkbox" required class="mr-2 rounded bg-white/20 border-none text-blue-500 focus:ring-0">
                    <p class="text-[10px] text-slate-400">
                        I agree to the 
                        <a href="#" class="text-slate-200 hover:underline">
                            Terms of Service
                        </a> and 
                        <a href="#" class="text-slate-200 hover:underline">
                            Privacy Policy
                        </a>.
                    </p>
                </div>

                <div class="pt-4 flex flex-col items-center">
                    <button type="submit" class="w-full py-3.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-[0_0_20px_rgba(37,99,235,0.4)] transition-all transform active:scale-[0.98]">
                        Create Account
                    </button>
                    <p class="text-[11px] text-slate-400 mt-6">
                        Already have an account? <a href="{{ route('login') }}" class="text-slate-200 font-bold hover:underline">Sign In</a>
                    </p>
                </div>
            </form>
        </x-logincard>
    </div>
</x-layout-login>

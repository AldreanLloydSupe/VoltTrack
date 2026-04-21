<x-layout title="VoltTrack Login">
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-[#1e3a8a] via-[#1e293b] to-[#0f172a]">
        
        <x-logincard>
            <div class="text-center mb-8">
                <h1 class="text-4xl font-bold text-white tracking-tight">VoltTrack</h1>
                <p class="text-slate-300 font-medium mt-1">Login</p>
            </div>
            {{-- Login Data --}}
            <form action="#" method="POST" class="space-y-6">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2 ml-1">Email Address</label>
                    <input type="email" name="email" class="w-full px-4 py-3 rounded-xl bg-slate-200/90 border-none focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2 ml-1">Password</label>
                    <input type="password" name="password" class="w-full px-4 py-3 rounded-xl bg-slate-200/90 border-none focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                </div>

                <div class="flex items-center justify-between text-[11px] font-bold text-slate-300 px-1">
                    <label class="flex items-center cursor-pointer">
                        <input type="checkbox" class="rounded border-none bg-white/20 mr-2 text-blue-500">
                        REMEMBER ME
                    </label>
                    <a href="#" class="hover:text-white uppercase transition-colors">Forgot Password?</a>
                </div>

                <div class="pt-2 flex flex-col items-center justify-center pt-2">
                    <button type="submit" class="w-[120px] py-2.5 bg-[#3b82f6] hover:bg-[#2563eb] text-white font-bold rounded-[14px] shadow-[0_4px_15px_rgba(59,130,246,0.6)] transition-all transform active:scale-95 text-sm tracking-wide">
                        Login
                    </button>
                </div>

                <div class="pt-2 flex flex-col items-center justify-center pt-2">
                    <p class="text-white">
                        New?
                        <a href="{{ url('/register') }}" class="font-bold">
                            Create Account
                        </a>
                    </p>
                    
                </div>
            </form>
        </x-logincard>

    </div>
</x-layout>
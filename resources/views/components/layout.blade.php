@props([
    'title' => 'Pastilan nalimtan ang title',
    'showAdminNav' => true,
])

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{$title}}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;900&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased text-slate-900">
    @if ($showAdminNav)
        <header class="bg-[#1e3a8a] text-white py-4 px-10 flex items-center">
            <h1 class="text-3xl font-bold mr-32">
                VoltTrack
            </h1>

            <nav class="flex space-x-16 text-base font-semibold tracking-wide items-center">
                <a href="{{ route('admin.dashboard') }}" class="border-b-2 border-white ">
                    Dashboard
                </a>
                
                <div class="group relative cursor-pointer py-4">
                    <div class="flex items-center space-x-2">
                        <span>
                            Residents
                        </span>
                        <svg class="w-2.5 h-2.5 opacity-80 transition-transform duration-300 group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>

                    <div class="absolute left-0 top-full hidden group-hover:block w-56 bg-[#1e3a8a] shadow-xl rounded-b-lg border-t border-white/10 z-50 overflow-hidden">
                        <a href="{{ route('admin.pending') }}" class="block px-6 py-4 text-white font-bold hover:bg-white/10 transition-colors">
                            Pending Residents
                        </a>
                        <a href="{{ route('admin.residentList') }}" class="block px-6 py-4 text-white font-bold hover:bg-white/10 transition-colors border-t border-white/5">
                            Residents List
                        </a>
                    </div>
                </div>

                <div class="group relative cursor-pointer py-4">
                    <div class="flex items-center space-x-2">
                        <span>
                            Utilities
                        </span>
                        <svg class="w-2.5 h-2.5 opacity-80 transition-transform duration-300 group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>

                    <div class="absolute left-0 top-full hidden group-hover:block w-56 bg-[#1e3a8a] shadow-xl rounded-b-lg border-t border-white/10 z-50 overflow-hidden">
                        <a href="#" class="block px-6 py-4 text-white font-bold hover:bg-white/10 transition-colors">
                            Properties & Meters
                        </a>
                        <a href="{{ route('admin.billingHistory')}}" class="block px-6 py-4 text-white font-bold hover:bg-white/10 transition-colors border-t border-white/5">
                            Billing History
                        </a>
                    </div>
                </div>
            </nav>

            <div class="ml-auto flex items-center gap-4">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button
                        type="submit"
                        class="rounded-full border border-white/30 px-5 py-2 text-sm font-bold uppercase tracking-wide text-white transition hover:bg-white/10"
                    >
                        Log Out
                    </button>
                </form>

                <img src="{{ asset('image/profile.webp') }}" class="w-11 h-11 rounded-full border-2 border-white/20" alt="Profile">
            </div>
        </header>
    @endif
    {{ $slot }}
</body>
</html>

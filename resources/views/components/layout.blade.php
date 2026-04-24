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
        @php($user = auth()->user())
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
                        <a href="{{ route('admin.property')}}" class="block px-6 py-4 text-white font-bold hover:bg-white/10 transition-colors">
                            Properties & Meters
                        </a>
                        <a href="{{ route('admin.billingHistory')}}" class="block px-6 py-4 text-white font-bold hover:bg-white/10 transition-colors border-t border-white/5">
                            Billing History
                        </a>
                    </div>
                </div>
            </nav>

            <div class="ml-auto flex items-center gap-4">
                <div class="text-right">
                    <p class="text-sm font-semibold">{{ $user?->first_name }} {{ $user?->last_name }}</p>
                    <p class="text-xs uppercase tracking-[0.22em] text-blue-100">Admin</p>
                </div>

                <div class="flex h-12 w-12 items-center justify-center rounded-full border-2 border-white/25 bg-white/15 text-lg font-bold">
                    {{ $user ? strtoupper(substr($user->first_name, 0, 1)) : 'A' }}
                </div>

                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="rounded-full border border-white/20 px-4 py-2 text-xs font-bold uppercase tracking-[0.2em] text-white transition-all hover:bg-white/10">
                        Log Out
                    </button>
                </form>
            </div>
        </header>
    @endif
    {{ $slot }}
</body>
</html>

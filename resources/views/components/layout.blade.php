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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased text-slate-900">
    @if ($showAdminNav)
        @php($user = auth()->user())
        @php($unreadResidentMessages = 0)
        @php($latestAdminNotifications = collect())
        @if ($user && $user->isAdmin() && \Illuminate\Support\Facades\Schema::hasTable('admin_notifications'))
            @php($unreadResidentMessages = $user->adminNotifications()->whereNull('read_at')->count())
            @php($latestAdminNotifications = $user->adminNotifications()->with('resident')->latest()->limit(6)->get())
        @endif
        <header class="bg-[#1e3a8a] text-white py-4 px-10 flex items-center">
            <h1 class="text-3xl font-bold mr-32">
                VoltTrack
            </h1>

            <nav class="flex space-x-16 text-base font-semibold tracking-wide items-center">
                <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'border-b-2 border-white' : 'border-b-2 border-transparent hover:border-white/70' }}">
                    Dashboard
                </a>
                
                <div class="group relative cursor-pointer py-4 {{ request()->routeIs('admin.pending', 'admin.residentList', 'admin.residentInfo', 'admin.confirming') ? 'border-b-2 border-white' : 'border-b-2 border-transparent hover:border-white/70' }}">
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

                <div class="group relative cursor-pointer py-4 {{ request()->routeIs('admin.property', 'admin.propertyInfo', 'admin.createnew', 'admin.billingHistory', 'admin.Create.*') ? 'border-b-2 border-white' : 'border-b-2 border-transparent hover:border-white/70' }}">
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
                @if($user && $user->isAdmin())
                    <details class="relative">
                        <summary class="list-none cursor-pointer">
                            <div class="relative flex h-11 w-11 items-center justify-center rounded-full border border-white/25 bg-white/10 hover:bg-white/20 transition-all">
                                <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.4-1.4A2 2 0 0118 14.2V11a6 6 0 10-12 0v3.2c0 .5-.2 1-.6 1.4L4 17h5m6 0a3 3 0 11-6 0m6 0H9"></path>
                                </svg>
                                @if($unreadResidentMessages > 0)
                                    <span class="absolute -right-1 -top-1 inline-flex min-w-5 items-center justify-center rounded-full bg-emerald-400 px-1.5 py-0.5 text-[10px] font-black text-[#0f172a]">
                                        {{ $unreadResidentMessages }}
                                    </span>
                                @endif
                            </div>
                        </summary>

                        <div class="absolute right-0 top-full z-50 mt-3 w-96 rounded-xl border border-slate-200 bg-white text-slate-900 shadow-2xl">
                            <div class="border-b border-slate-100 px-4 py-3">
                                <p class="text-sm font-bold">Resident Notifications</p>
                                <p class="text-xs text-slate-500">Latest {{ $latestAdminNotifications->count() }}</p>
                            </div>

                            <div class="max-h-96 overflow-y-auto">
                                @forelse($latestAdminNotifications as $notice)
                                    <div class="border-b border-slate-100 px-4 py-3 last:border-b-0">
                                        <p class="text-sm font-semibold text-slate-900">
                                            {{ $notice->subject }}
                                            @if(is_null($notice->read_at))
                                                <span class="ml-2 inline-flex rounded bg-emerald-100 px-2 py-0.5 text-[10px] font-bold uppercase text-emerald-700">New</span>
                                            @endif
                                        </p>
                                        <p class="mt-1 text-xs text-slate-500">
                                            From {{ $notice->resident?->first_name }} {{ $notice->resident?->last_name }} • {{ $notice->created_at?->diffForHumans() }}
                                        </p>
                                        <p class="mt-2 line-clamp-3 text-sm text-slate-700 whitespace-pre-line">{{ $notice->message }}</p>
                                        @if($notice->resident_id)
                                            <a href="{{ route('admin.residentInfo', $notice->resident_id) }}" class="mt-2 inline-block text-xs font-semibold text-blue-600 hover:underline">
                                                View Resident
                                            </a>
                                        @endif
                                    </div>
                                @empty
                                    <div class="px-4 py-6 text-center text-sm text-slate-500">
                                        No resident messages yet.
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </details>
                @endif

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

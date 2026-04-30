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
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
            <h1 class="text-3xl font-bold mr-32">VoltTrack</h1>

            <nav class="flex space-x-16 text-base font-semibold tracking-wide items-center">
                <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'border-b-2 border-white' : 'border-b-2 border-transparent hover:border-white/70' }}">
                    Dashboard
                </a>

                <div class="group relative cursor-pointer py-4 {{ request()->routeIs('admin.pending', 'admin.residentList', 'admin.residentInfo', 'admin.confirming') ? 'border-b-2 border-white' : 'border-b-2 border-transparent hover:border-white/70' }}">
                    <div class="flex items-center space-x-2">
                        <span>Residents</span>
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
                        <span>Utilities</span>
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
                                    <span data-admin-unread-count class="absolute -right-1 -top-1 inline-flex min-w-5 items-center justify-center rounded-full bg-emerald-400 px-1.5 py-0.5 text-[10px] font-black text-[#0f172a]">
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
                                    <button
                                        type="button"
                                        class="admin-notification-open block w-full border-b border-slate-100 px-4 py-3 text-left transition-colors last:border-b-0 hover:bg-slate-50"
                                        data-read-url="{{ route('admin.notifications.read', $notice) }}"
                                        data-reply-url="{{ route('admin.notifications.reply', $notice) }}"
                                        data-delete-url="{{ route('notifications.destroy', $notice) }}"
                                        data-is-unread="{{ is_null($notice->read_at) ? 'true' : 'false' }}"
                                        data-subject="{{ $notice->subject }}"
                                        data-resident="{{ trim(($notice->resident?->first_name ?? 'Resident') . ' ' . ($notice->resident?->last_name ?? '')) }}"
                                        data-sent="{{ $notice->created_at?->format('M d, Y g:i A') ?? 'N/A' }}"
                                        data-message="{{ $notice->message }}"
                                        data-reply="{{ $notice->reply_message }}"
                                        data-replied-at="{{ $notice->replied_at?->format('M d, Y g:i A') ?? '' }}"
                                    >
                                        <span class="block text-sm font-semibold text-slate-900">
                                            {{ $notice->subject }}
                                            @if(is_null($notice->read_at))
                                                <span data-notification-new class="ml-2 inline-flex rounded bg-emerald-100 px-2 py-0.5 text-[10px] font-bold uppercase text-emerald-700">New</span>
                                            @endif
                                        </span>
                                        <span class="mt-1 block text-xs text-slate-500">
                                            From {{ $notice->resident?->first_name }} {{ $notice->resident?->last_name }} &bull; {{ $notice->created_at?->diffForHumans() }}
                                        </span>
                                        <span class="mt-2 block line-clamp-3 text-sm text-slate-700 whitespace-pre-line">{{ $notice->message }}</span>
                                    </button>
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

                <div class="h-12 w-12 overflow-hidden rounded-full border-2 border-white/25 bg-white">
                    <img src="{{ asset('image/profile.png') }}" alt="Profile logo" class="h-full w-full object-cover">
                </div>

                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="rounded-full border border-white/20 px-4 py-2 text-xs font-bold uppercase tracking-[0.2em] text-white transition-all hover:bg-white/10">
                        Log Out
                    </button>
                </form>
            </div>
        </header>

        @if($user && $user->isAdmin())
            <div id="admin-notification-modal" class="fixed inset-0 z-[80] hidden bg-slate-950/60 px-4 py-10">
                <div class="absolute inset-0" data-admin-notification-close></div>
                <div class="relative mx-auto mt-10 max-h-[86vh] w-full max-w-2xl overflow-y-auto rounded-2xl bg-white text-slate-900 shadow-2xl">
                    <div class="sticky top-0 z-10 flex items-start justify-between gap-4 border-b border-slate-100 bg-white px-6 py-5">
                        <div>
                            <p class="text-[11px] font-black uppercase tracking-[0.2em] text-slate-400">Resident Message</p>
                            <h2 id="admin-notification-subject" class="mt-1 text-xl font-black text-[#1e3a8a]"></h2>
                            <p id="admin-notification-meta" class="mt-1 text-xs font-semibold text-slate-400"></p>
                        </div>
                        <button type="button" data-admin-notification-close class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full border border-slate-200 text-slate-400 transition-colors hover:bg-slate-50 hover:text-slate-700" aria-label="Close notification">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>

                    <div class="px-6 py-5">
                        <div id="admin-notification-message" class="rounded-xl bg-slate-50 p-4 text-sm leading-6 text-slate-700 whitespace-pre-line"></div>

                        <div id="admin-notification-existing-reply" class="mt-4 hidden rounded-xl border border-blue-100 bg-blue-50 p-4">
                            <p class="text-[10px] font-black uppercase tracking-[0.18em] text-blue-700">Current Reply</p>
                            <p id="admin-notification-reply-text" class="mt-2 text-sm leading-6 text-slate-700 whitespace-pre-line"></p>
                            <p id="admin-notification-replied-at" class="mt-3 text-xs font-semibold text-slate-400"></p>
                        </div>

                        <form id="admin-notification-reply-form" method="POST" class="mt-5">
                            @csrf
                            <label for="admin-notification-reply-input" class="mb-2 block text-xs font-black uppercase tracking-[0.18em] text-slate-400">Reply to Resident</label>
                            <textarea id="admin-notification-reply-input" name="reply_message" rows="5" maxlength="2000" required class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm text-slate-700 focus:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-100"></textarea>
                            <div class="mt-4 flex justify-end">
                                <button type="submit" class="rounded-lg bg-[#001D4E] px-5 py-2.5 text-xs font-black uppercase tracking-[0.16em] text-white transition-colors hover:bg-[#163571]">
                                    Send Reply
                                </button>
                            </div>
                        </form>

                        <form id="admin-notification-delete-form" method="POST" class="mt-3" onsubmit="return confirm('Delete this notification permanently?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full rounded-lg border border-red-200 px-5 py-2.5 text-xs font-black uppercase tracking-[0.16em] text-red-600 transition-colors hover:bg-red-50">
                                Delete Message
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    @endif

    {{ $slot }}

    @if ($showAdminNav && isset($user) && $user && $user->isAdmin())
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const modal = document.getElementById('admin-notification-modal');
                const unreadBadge = document.querySelector('[data-admin-unread-count]');
                const subject = document.getElementById('admin-notification-subject');
                const meta = document.getElementById('admin-notification-meta');
                const message = document.getElementById('admin-notification-message');
                const replyForm = document.getElementById('admin-notification-reply-form');
                const replyInput = document.getElementById('admin-notification-reply-input');
                const deleteForm = document.getElementById('admin-notification-delete-form');
                const existingReply = document.getElementById('admin-notification-existing-reply');
                const replyText = document.getElementById('admin-notification-reply-text');
                const repliedAt = document.getElementById('admin-notification-replied-at');
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

                if (!modal) {
                    return;
                }

                const closeModal = () => {
                    modal.classList.add('hidden');
                    document.body.classList.remove('overflow-hidden');
                };

                document.querySelectorAll('[data-admin-notification-close]').forEach((button) => {
                    button.addEventListener('click', closeModal);
                });

                document.querySelectorAll('.admin-notification-open').forEach((button) => {
                    button.addEventListener('click', async () => {
                        subject.textContent = button.dataset.subject || 'Resident Message';
                        meta.textContent = `From ${button.dataset.resident || 'Resident'} - ${button.dataset.sent || 'N/A'}`;
                        message.textContent = button.dataset.message || '';
                        replyForm.action = button.dataset.replyUrl;
                        deleteForm.action = button.dataset.deleteUrl;
                        replyInput.value = button.dataset.reply || '';

                        if (button.dataset.reply) {
                            existingReply.classList.remove('hidden');
                            replyText.textContent = button.dataset.reply;
                            repliedAt.textContent = button.dataset.repliedAt ? `Sent ${button.dataset.repliedAt}` : '';
                        } else {
                            existingReply.classList.add('hidden');
                            replyText.textContent = '';
                            repliedAt.textContent = '';
                        }

                        modal.classList.remove('hidden');
                        document.body.classList.add('overflow-hidden');

                        if (button.dataset.isUnread === 'true') {
                            button.dataset.isUnread = 'false';
                            button.querySelector('[data-notification-new]')?.remove();

                            if (unreadBadge) {
                                const nextCount = Math.max((parseInt(unreadBadge.textContent, 10) || 1) - 1, 0);
                                unreadBadge.textContent = nextCount;
                                unreadBadge.classList.toggle('hidden', nextCount === 0);
                            }

                            await fetch(button.dataset.readUrl, {
                                method: 'POST',
                                headers: {
                                    'Accept': 'application/json',
                                    'X-CSRF-TOKEN': csrfToken,
                                },
                            });
                        }
                    });
                });

                document.addEventListener('keydown', (event) => {
                    if (event.key === 'Escape' && !modal.classList.contains('hidden')) {
                        closeModal();
                    }
                });
            });
        </script>
    @endif
</body>
</html>

{{-- Renders the Layout view for VoltTrack. --}}
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

    {{-- Conditional message/block --}}
    @if (file_exists(public_path('hot')) || file_exists(public_path('build/manifest.json')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<body class="font-sans antialiased text-slate-900">
    {{-- Conditional message/block --}}
    @if ($showAdminNav)
        @php($user = auth()->user())
        @php($unreadResidentMessages = 0)
        @php($latestAdminNotifications = collect())
        {{-- Conditional message/block --}}
        @if ($user && $user->isAdmin() && \Illuminate\Support\Facades\Schema::hasTable('admin_notifications'))
            @php($unreadResidentMessages = $user->adminNotifications()->whereNull('read_at')->count())
            @php($latestAdminNotifications = $user->adminNotifications()->with('resident')->latest()->limit(6)->get())
        @endif
        {{-- Page header --}}
        <header id="admin-top-nav" class="sticky top-0 z-50 flex flex-wrap items-center gap-4 bg-[#1E3A8A] px-4 py-4 text-white sm:px-6 lg:flex-nowrap lg:px-10">
            <h1 class="shrink-0 text-2xl font-bold sm:text-3xl lg:mr-16 xl:mr-32">
                VoltTrack
            </h1>

            <nav class="admin-main-nav order-3 flex w-full items-center gap-6 overflow-x-auto pb-1 text-sm font-semibold tracking-wide lg:order-none lg:w-auto lg:gap-12 lg:overflow-visible lg:pb-0 xl:gap-16 xl:text-base">
                <a href="{{ route('admin.dashboard') }}" data-instant-nav class="{{ request()->routeIs('admin.dashboard') ? 'border-b-2 border-white' : 'border-b-2 border-transparent hover:border-white/70' }}">
                    Dashboard
                </a>

                <div class="admin-nav-item group relative shrink-0 py-4 {{ request()->routeIs('admin.pending', 'admin.residentList', 'admin.residentInfo', 'admin.confirming') ? 'border-b-2 border-white' : 'border-b-2 border-transparent hover:border-white/70' }}">
                    <button type="button" class="admin-nav-toggle flex items-center space-x-2" aria-expanded="false">
                        <span>Residents</span>
                        <svg class="w-2.5 h-2.5 opacity-80 transition-transform duration-300 group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>

                    <div class="admin-nav-dropdown absolute left-0 top-full hidden group-hover:block group-focus-within:block w-56 bg-[#1e3a8a] shadow-xl rounded-b-lg border-t border-white/10 z-50 overflow-hidden">
                        <a href="{{ route('admin.pending') }}" data-instant-nav class="block px-6 py-4 text-white font-bold hover:bg-white/10 transition-colors">
                            Pending Residents
                        </a>
                        <a href="{{ route('admin.residentList') }}" data-instant-nav class="block px-6 py-4 text-white font-bold hover:bg-white/10 transition-colors border-t border-white/5">
                            Residents List
                        </a>
                    </div>
                </div>

                <div class="admin-nav-item group relative shrink-0 py-4 {{ request()->routeIs('admin.property', 'admin.propertyInfo', 'admin.createnew', 'admin.billingHistory', 'admin.financials', 'admin.Create.*') ? 'border-b-2 border-white' : 'border-b-2 border-transparent hover:border-white/70' }}">
                    <button type="button" class="admin-nav-toggle flex items-center space-x-2" aria-expanded="false">
                        <span>Utilities</span>
                        <svg class="w-2.5 h-2.5 opacity-80 transition-transform duration-300 group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>

                    <div class="admin-nav-dropdown absolute left-0 top-full hidden group-hover:block group-focus-within:block w-56 bg-[#1e3a8a] shadow-xl rounded-b-lg border-t border-white/10 z-50 overflow-hidden">
                        <a href="{{ route('admin.property')}}" data-instant-nav class="block px-6 py-4 text-white font-bold hover:bg-white/10 transition-colors">
                            Properties & Meters
                        </a>
                        <a href="{{ route('admin.billingHistory')}}" data-instant-nav class="block px-6 py-4 text-white font-bold hover:bg-white/10 transition-colors border-t border-white/5">
                            Billing History
                        </a>
                        <a href="{{ route('admin.financials') }}" data-instant-nav class="block px-6 py-4 text-white font-bold hover:bg-white/10 transition-colors border-t border-white/5">
                            Financials
                        </a>
                    </div>
                </div>

                <div class="admin-nav-item group relative shrink-0 py-4 {{ request()->routeIs('admin.auditLog', 'admin.deletedRecords') ? 'border-b-2 border-white' : 'border-b-2 border-transparent hover:border-white/70' }}">
                    <button type="button" class="admin-nav-toggle flex items-center space-x-2" aria-expanded="false">
                        <span>Activity Logs</span>
                        <svg class="w-2.5 h-2.5 opacity-80 transition-transform duration-300 group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>

                    <div class="admin-nav-dropdown absolute left-0 top-full hidden group-hover:block group-focus-within:block w-56 bg-[#1e3a8a] shadow-xl rounded-b-lg border-t border-white/10 z-50 overflow-hidden">
                        <a href="{{ route('admin.auditLog') }}" data-instant-nav class="block px-6 py-4 text-white font-bold hover:bg-white/10 transition-colors">
                            Audit Logs
                        </a>
                        <a href="{{ route('admin.deletedRecords') }}" data-instant-nav class="block px-6 py-4 text-white font-bold hover:bg-white/10 transition-colors border-t border-white/5">
                            Deleted Records
                        </a>
                    </div>
                </div>
            </nav>

            <div class="ml-auto flex min-w-0 items-center gap-3 sm:gap-4">
                {{-- Conditional message/block --}}
                @if($user && $user->isAdmin())
                    <details class="admin-notification-details relative">
                        <summary class="list-none cursor-pointer">
                            <div class="relative flex h-11 w-11 items-center justify-center rounded-full border border-white/25 bg-white/10 hover:bg-white/20 transition-all">
                                <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.4-1.4A2 2 0 0118 14.2V11a6 6 0 10-12 0v3.2c0 .5-.2 1-.6 1.4L4 17h5m6 0a3 3 0 11-6 0m6 0H9"></path>
                                </svg>
                                {{-- Conditional message/block --}}
                                @if($unreadResidentMessages > 0)
                                    <span data-admin-unread-count class="absolute -right-1 -top-1 inline-flex min-w-5 items-center justify-center rounded-full bg-emerald-400 px-1.5 py-0.5 text-[10px] font-black text-[#0f172a]">
                                        {{ $unreadResidentMessages }}
                                    </span>
                                @endif
                            </div>
                        </summary>

                        <div class="admin-notification-popover absolute right-0 top-full z-50 mt-3 w-96 rounded-xl border border-slate-200 bg-white text-slate-900 shadow-2xl">
                            <div class="border-b border-slate-100 px-4 py-3">
                                <p class="text-sm font-bold">Resident Notifications</p>
                                <p class="text-xs text-slate-500">Latest {{ $latestAdminNotifications->count() }}</p>
                            </div>

                            <div class="admin-notification-list max-h-96 overflow-y-auto">
                                {{-- List rendering --}}
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
                                            {{-- Conditional message/block --}}
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

                <div class="hidden text-right sm:block">
                    <p class="text-sm font-semibold">{{ $user?->first_name }} {{ $user?->last_name }}</p>
                    <p class="text-xs uppercase tracking-[0.22em] text-blue-100">Admin</p>
                </div>

                <div class="h-10 w-10 shrink-0 overflow-hidden rounded-full border-2 border-white/25 bg-white sm:h-12 sm:w-12">
                    <img src="{{ asset('image/profile.png') }}" alt="Profile logo" class="h-full w-full object-cover">
                </div>

                {{-- Form --}}
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="rounded-full border border-white/20 px-3 py-2 text-[10px] font-bold uppercase tracking-[0.16em] text-white transition-all hover:bg-white/10 sm:px-4 sm:text-xs sm:tracking-[0.2em]">
                        Log Out
                    </button>
                </form>
            </div>
        </header>

        {{-- Conditional message/block --}}
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

                        {{-- Form --}}
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

                        {{-- Form --}}
                        <form
                            id="admin-notification-delete-form"
                            method="POST"
                            class="mt-3"
                            data-confirm
                            data-confirm-title="Delete Message?"
                            data-confirm-message="This notification will be permanently deleted."
                            data-confirm-confirm-label="Delete Message"
                            data-confirm-variant="danger"
                        >
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

    {{-- Conditional message/block --}}
    @if ($showAdminNav)
        <div id="admin-page-content" class="admin-glass-page">
            {{ $slot }}
        </div>
    @else
        <div class="admin-glass-page">
            {{ $slot }}
        </div>
    @endif

    <div id="app-confirmation-modal" class="fixed inset-0 z-[10000] hidden items-center justify-center bg-slate-950/60 px-4 py-6 backdrop-blur-sm">
        <div class="absolute inset-0" data-confirm-cancel></div>
        <div class="relative w-full max-w-md rounded-2xl border border-slate-200 bg-white p-6 text-slate-900 shadow-2xl">
            <div class="flex items-start gap-4">
                <div id="app-confirmation-icon" class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-blue-700">
                    <i class="fas fa-check"></i>
                </div>
                <div class="min-w-0 flex-1">
                    <p id="app-confirmation-eyebrow" class="text-[10px] font-black uppercase tracking-[0.22em] text-slate-400">Confirmation Required</p>
                    <h2 id="app-confirmation-title" class="mt-1 text-xl font-black text-[#1e3a8a]">Confirm Action</h2>
                    <p id="app-confirmation-message" class="mt-3 text-sm font-semibold leading-6 text-slate-600">Please confirm before continuing.</p>
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <button type="button" data-confirm-cancel class="rounded-lg border border-slate-200 px-4 py-2 text-sm font-bold text-slate-600 transition-colors hover:bg-slate-50">
                    Cancel
                </button>
                <button type="button" id="app-confirmation-confirm" class="rounded-lg bg-[#001D4E] px-4 py-2 text-sm font-black text-white transition-colors hover:bg-[#163571]">
                    Confirm
                </button>
            </div>
        </div>
    </div>

    <script>
        (() => {
            if (window.__voltTrackConfirmationBound) {
                return;
            }

            window.__voltTrackConfirmationBound = true;
            let pendingForm = null;

            const getModal = () => document.getElementById('app-confirmation-modal');
            const getConfirmButton = () => document.getElementById('app-confirmation-confirm');

            const applyVariant = (variant) => {
                const icon = document.getElementById('app-confirmation-icon');
                const confirmButton = getConfirmButton();

                icon.className = 'flex h-12 w-12 shrink-0 items-center justify-center rounded-xl';
                confirmButton.className = 'rounded-lg px-4 py-2 text-sm font-black text-white transition-colors';

                if (variant === 'danger') {
                    icon.classList.add('bg-red-50', 'text-red-600');
                    icon.innerHTML = '<i class="fas fa-triangle-exclamation"></i>';
                    confirmButton.classList.add('bg-rose-500', 'hover:bg-rose-600');
                    return;
                }

                if (variant === 'success') {
                    icon.classList.add('bg-emerald-50', 'text-emerald-600');
                    icon.innerHTML = '<i class="fas fa-check"></i>';
                    confirmButton.classList.add('bg-emerald-500', 'hover:bg-emerald-600');
                    return;
                }

                icon.classList.add('bg-blue-50', 'text-blue-700');
                icon.innerHTML = '<i class="fas fa-circle-info"></i>';
                confirmButton.classList.add('bg-[#001D4E]', 'hover:bg-[#163571]');
            };

            const resolveConfirmation = (form) => {
                const fieldName = form.dataset.confirmWhenField;
                const fieldValue = form.dataset.confirmWhenValue;
                const field = fieldName ? Array.from(form.elements).find((element) => element.name === fieldName) : null;
                const matchesCondition = field && field.value === fieldValue;

                return {
                    title: matchesCondition
                        ? (form.dataset.confirmWhenTitle || form.dataset.confirmTitle || 'Confirm Action')
                        : (form.dataset.confirmTitle || 'Confirm Action'),
                    message: matchesCondition
                        ? (form.dataset.confirmWhenMessage || form.dataset.confirmMessage || 'Please confirm before continuing.')
                        : (form.dataset.confirmMessage || 'Please confirm before continuing.'),
                    confirmLabel: matchesCondition
                        ? (form.dataset.confirmWhenLabel || form.dataset.confirmConfirmLabel || 'Confirm')
                        : (form.dataset.confirmConfirmLabel || 'Confirm'),
                    variant: matchesCondition
                        ? (form.dataset.confirmWhenVariant || form.dataset.confirmVariant || 'default')
                        : (form.dataset.confirmVariant || 'default'),
                };
            };

            const openConfirmation = (form) => {
                const modal = getModal();
                const confirmButton = getConfirmButton();
                const title = document.getElementById('app-confirmation-title');
                const message = document.getElementById('app-confirmation-message');

                if (!modal || !confirmButton || !title || !message) {
                    form.dataset.confirmed = 'true';
                    form.requestSubmit();
                    return;
                }

                const confirmation = resolveConfirmation(form);
                pendingForm = form;
                title.textContent = confirmation.title;
                message.textContent = confirmation.message;
                confirmButton.textContent = confirmation.confirmLabel;
                applyVariant(confirmation.variant);

                modal.classList.remove('hidden');
                modal.classList.add('flex');
                document.body.classList.add('overflow-hidden');
                confirmButton.focus();
            };

            const closeConfirmation = () => {
                const modal = getModal();

                if (modal) {
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                }

                document.body.classList.remove('overflow-hidden');
                pendingForm = null;
            };

            document.addEventListener('click', (event) => {
                if (event.target.closest('[data-confirm-cancel]')) {
                    closeConfirmation();
                    return;
                }

                if (event.target.closest('#app-confirmation-confirm')) {
                    const form = pendingForm;
                    closeConfirmation();

                    if (form) {
                        form.dataset.confirmed = 'true';
                        form.requestSubmit();
                    }
                }
            });

            document.addEventListener('keydown', (event) => {
                const modal = getModal();

                if (event.key === 'Escape' && modal && !modal.classList.contains('hidden')) {
                    closeConfirmation();
                }
            });

            document.addEventListener('submit', (event) => {
                const form = event.target.closest('form[data-confirm]');

                if (!form || form.dataset.confirmed === 'true') {
                    if (form) {
                        delete form.dataset.confirmed;
                    }
                    return;
                }

                event.preventDefault();
                event.stopImmediatePropagation();
                openConfirmation(form);
            }, true);
        })();
    </script>

    {{-- Conditional message/block --}}
    @if ($showAdminNav && isset($user) && $user && $user->isAdmin())
        <script>
            const initAdminDropdownMenus = () => {
                const closeMenus = (except = null) => {
                    document.querySelectorAll('#admin-top-nav .admin-nav-item.is-open').forEach((item) => {
                        if (item === except) {
                            return;
                        }

                        item.classList.remove('is-open');
                        item.querySelector('.admin-nav-toggle')?.setAttribute('aria-expanded', 'false');
                    });
                };

                document.querySelectorAll('#admin-top-nav .admin-nav-toggle').forEach((button) => {
                    if (button.dataset.bound === 'true') {
                        return;
                    }

                    button.dataset.bound = 'true';

                    button.addEventListener('click', (event) => {
                        event.preventDefault();
                        event.stopPropagation();

                        const item = button.closest('.admin-nav-item');
                        if (!item) {
                            return;
                        }

                        const willOpen = !item.classList.contains('is-open');
                        closeMenus(item);
                        item.classList.toggle('is-open', willOpen);
                        button.setAttribute('aria-expanded', willOpen ? 'true' : 'false');
                    });
                });

                if (document.body.dataset.adminDropdownsBound === 'true') {
                    return;
                }

                document.body.dataset.adminDropdownsBound = 'true';

                document.addEventListener('click', (event) => {
                    if (!event.target.closest('#admin-top-nav .admin-nav-item')) {
                        closeMenus();
                    }
                });

                document.addEventListener('keydown', (event) => {
                    if (event.key === 'Escape') {
                        closeMenus();
                    }
                });
            };

            const initAdminNotificationModal = () => {
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

                if (modal.dataset.bound === 'true') {
                    return;
                }

                modal.dataset.bound = 'true';

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
            };

            window.initAdminDropdownMenus = initAdminDropdownMenus;
            window.initAdminNotificationModal = initAdminNotificationModal;
            document.addEventListener('DOMContentLoaded', initAdminDropdownMenus);
            document.addEventListener('DOMContentLoaded', initAdminNotificationModal);
        </script>
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const executeScripts = (container) => {
                    if (!container) {
                        return;
                    }

                    container.querySelectorAll('script').forEach((oldScript) => {
                        const newScript = document.createElement('script');

                        Array.from(oldScript.attributes).forEach((attr) => {
                            newScript.setAttribute(attr.name, attr.value);
                        });

                        if (!oldScript.src) {
                            newScript.textContent = oldScript.textContent;
                        }

                        oldScript.replaceWith(newScript);
                    });
                };

                const swapAdminPage = (doc) => {
                    const nextHeader = doc.getElementById('admin-top-nav');
                    const nextContent = doc.getElementById('admin-page-content');
                    const nextModal = doc.getElementById('admin-notification-modal');

                    const currentHeader = document.getElementById('admin-top-nav');
                    const currentContent = document.getElementById('admin-page-content');
                    const currentModal = document.getElementById('admin-notification-modal');

                    if (!nextHeader || !nextContent || !currentHeader || !currentContent) {
                        throw new Error('Instant navigation target missing.');
                    }

                    currentHeader.outerHTML = nextHeader.outerHTML;
                    currentContent.innerHTML = nextContent.innerHTML;

                    if (currentModal && nextModal) {
                        currentModal.outerHTML = nextModal.outerHTML;
                    }

                    document.title = doc.title;
                    executeScripts(document.getElementById('admin-page-content'));

                    if (typeof window.initAdminNotificationModal === 'function') {
                        window.initAdminNotificationModal();
                    }

                    if (typeof window.initAdminDropdownMenus === 'function') {
                        window.initAdminDropdownMenus();
                    }
                };

                const shouldInstantNavigate = (link) => {
                    if (!link || !link.matches('a[data-instant-nav]')) {
                        return false;
                    }

                    const href = link.getAttribute('href');
                    if (!href || href.startsWith('#')) {
                        return false;
                    }

                    const url = new URL(href, window.location.origin);
                    return url.origin === window.location.origin;
                };

                const loadPage = async (url, push = true) => {
                    const response = await fetch(url, {
                        method: 'GET',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-Instant-Nav': '1',
                        },
                    });

                    if (!response.ok) {
                        throw new Error('Navigation request failed.');
                    }

                    const html = await response.text();
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');

                    swapAdminPage(doc);

                    if (push) {
                        history.pushState({ instantNav: true }, '', url);
                    }
                };

                document.addEventListener('click', (event) => {
                    const link = event.target.closest('a[data-instant-nav]');
                    if (!shouldInstantNavigate(link)) {
                        return;
                    }

                    if (event.metaKey || event.ctrlKey || event.shiftKey || event.altKey) {
                        return;
                    }

                    event.preventDefault();

                    loadPage(link.href).catch(() => {
                        window.location.href = link.href;
                    });
                });

                document.addEventListener('submit', (event) => {
                    const form = event.target.closest('form[data-instant-form]');
                    if (!form) {
                        return;
                    }

                    event.preventDefault();

                    const action = form.getAttribute('action') || window.location.href;
                    const method = (form.getAttribute('method') || 'GET').toUpperCase();
                    const formData = new FormData(form);

                    fetch(action, {
                        method,
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-Instant-Nav': '1',
                        },
                    })
                        .then((response) => {
                            if (!response.ok) {
                                throw new Error('Form request failed.');
                            }

                            return response.text().then((html) => ({
                                html,
                                finalUrl: response.url || action,
                            }));
                        })
                        .then(({ html, finalUrl }) => {
                            const parser = new DOMParser();
                            const doc = parser.parseFromString(html, 'text/html');

                            swapAdminPage(doc);
                            history.pushState({ instantNav: true }, '', finalUrl);
                        })
                        .catch(() => {
                            form.submit();
                        });
                });

                window.addEventListener('popstate', () => {
                    loadPage(window.location.href, false).catch(() => {
                        window.location.reload();
                    });
                });
            });
        </script>
    @endif
</body>
</html>
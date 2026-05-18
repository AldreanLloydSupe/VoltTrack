{{-- Renders the Audit Log view for VoltTrack. --}}
<x-layout title="Audit Log | VoltTrack">
    {{-- Main page wrapper for the audit log screen --}}
    <main class="p-6 md:p-8 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto">
            {{-- Header and search/filter controls --}}
            <div class="mb-6 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div>
                    {{-- Page title and short description --}}
                    <h1 class="text-3xl font-black text-white">Audit Log</h1>
                    <p class="mt-1 text-sm font-semibold text-blue-100">Track key admin activity and account changes.</p>
                </div>

                {{-- Search form sends a GET request so filters stay in the URL --}}
                <form action="{{ route('admin.auditLog') }}" method="GET" class="flex w-full max-w-md gap-2">
                    <input
                        type="text"
                        name="search"
                        value="{{ $search }}"
                        placeholder="Search action, module, description"
                        class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm font-semibold text-slate-200 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                    >
                    <button type="submit" class="rounded-xl bg-[#001D4E] px-5 py-3 text-xs font-black uppercase tracking-[0.15em] text-white hover:bg-[#163571]">
                        Search
                    </button>
                </form>
            </div>

            {{-- Table container for audit log records --}}
            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="overflow-x-auto">
                    {{-- Data table --}}
                    <table class="min-w-full text-sm">
                        {{-- Table headings describe each audit log field --}}
                        <thead class="bg-slate-50 text-left text-xs uppercase tracking-wide text-slate-500">
                            <tr>
                                <th class="px-5 py-3 font-black">Date & Time</th>
                                <th class="px-5 py-3 font-black">Admin</th>
                                <th class="px-5 py-3 font-black">Module</th>
                                <th class="px-5 py-3 font-black">Action</th>
                                <th class="px-5 py-3 font-black">Description</th>
                            </tr>
                        </thead>
                        {{-- Table rows --}}
                        <tbody class="divide-y divide-slate-100">
                            {{-- Loop through logs; show fallback row when there are no records --}}
                            @forelse($logs as $log)
                                <tr class="align-top">
                                    {{-- Render formatted log timestamp --}}
                                    <td class="px-5 py-4 font-semibold text-slate-600">{{ $log->created_at?->format('M d, Y g:i:s A') }}</td>
                                    <td class="px-5 py-4">
                                        {{-- Admin account info tied to the log entry --}}
                                        <p class="font-bold text-slate-800">{{ $log->admin?->first_name }} {{ $log->admin?->last_name }}</p>
                                        <p class="text-xs text-slate-400">{{ $log->admin?->email ?? 'N/A' }}</p>
                                    </td>
                                    <td class="px-5 py-4 font-semibold text-slate-600">{{ $log->module ?? 'General' }}</td>
                                    <td class="px-5 py-4">
                                        {{-- Action badge converts underscores to readable words --}}
                                        <span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-black uppercase tracking-[0.1em] text-blue-700">
                                            {{ str_replace('_', ' ', $log->action) }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-4">
                                        {{-- Human-readable detail of what changed --}}
                                        <p class="font-semibold text-slate-700">{{ $log->description }}</p>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-5 py-8 text-center text-slate-500">No audit logs yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination summary and controls, only shown when multiple pages exist --}}
                @if($logs->hasPages())
                    <div class="flex flex-col gap-3 border-t border-slate-100 bg-slate-50/50 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
                        <p class="text-xs font-bold text-slate-400">
                            Showing
                            <span class="text-slate-600">{{ number_format($logs->firstItem() ?? 0) }}-{{ number_format($logs->lastItem() ?? 0) }}</span>
                            of {{ number_format($logs->total()) }} logs
                        </p>
                        <x-pagination-links :paginator="$logs" />
                    </div>
                @endif
            </div>
        </div>
    </main>
</x-layout>
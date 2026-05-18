{{-- Renders the Pagination Links view for VoltTrack. --}}
@props(['paginator'])

{{-- Conditional message/block --}}
@if ($paginator->hasPages())
    @php
        $maxVisiblePages = 10;
        $halfWindow = (int) floor($maxVisiblePages / 2);
        $startPage = max(1, $paginator->currentPage() - $halfWindow);
        $endPage = min($paginator->lastPage(), $startPage + $maxVisiblePages - 1);
        $startPage = max(1, $endPage - $maxVisiblePages + 1);
    @endphp

    <nav class="flex flex-wrap items-center justify-end gap-1" aria-label="Pagination Navigation">
        {{-- Conditional message/block --}}
        @if ($paginator->onFirstPage())
            <span class="grid h-9 min-w-9 place-items-center rounded-lg border border-slate-200 bg-slate-100 px-3 text-xs font-bold text-slate-400">
                <i class="fas fa-chevron-left"></i>
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="grid h-9 min-w-9 place-items-center rounded-lg border border-slate-200 bg-white px-3 text-xs font-bold text-slate-600 transition hover:bg-slate-50">
                <i class="fas fa-chevron-left"></i>
            </a>
        @endif

        @for ($page = $startPage; $page <= $endPage; $page++)
            {{-- Conditional message/block --}}
            @if ($page === $paginator->currentPage())
                <span class="grid h-9 min-w-9 place-items-center rounded-lg bg-blue-600 px-3 text-xs font-bold text-white shadow-md shadow-blue-200">
                    {{ $page }}
                </span>
            @else
                <a href="{{ $paginator->url($page) }}" class="grid h-9 min-w-9 place-items-center rounded-lg border border-slate-200 bg-white px-3 text-xs font-bold text-slate-700 transition hover:bg-slate-50">
                    {{ $page }}
                </a>
            @endif
        @endfor

        {{-- Conditional message/block --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="grid h-9 min-w-9 place-items-center rounded-lg border border-slate-200 bg-white px-3 text-xs font-bold text-slate-600 transition hover:bg-slate-50">
                <i class="fas fa-chevron-right"></i>
            </a>
        @else
            <span class="grid h-9 min-w-9 place-items-center rounded-lg border border-slate-200 bg-slate-100 px-3 text-xs font-bold text-slate-400">
                <i class="fas fa-chevron-right"></i>
            </span>
        @endif
    </nav>
@endif
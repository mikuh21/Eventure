@if ($paginator->hasPages())
<nav class="eventure-pagination" role="navigation" aria-label="Pagination">
    @if ($paginator->onFirstPage())
        <span class="page-arrow disabled" aria-disabled="true">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" width="16" height="16"><path d="M15 18l-6-6 6-6"/></svg>
        </span>
    @else
        <a class="page-arrow" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Previous">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" width="16" height="16"><path d="M15 18l-6-6 6-6"/></svg>
        </a>
    @endif

    <span class="page-results-text">
        Showing {{ $paginator->firstItem() }}–{{ $paginator->lastItem() }} of {{ number_format($paginator->total()) }} results
    </span>

    @if ($paginator->hasMorePages())
        <a class="page-arrow" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Next">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" width="16" height="16"><path d="M9 18l6-6-6-6"/></svg>
        </a>
    @else
        <span class="page-arrow disabled" aria-disabled="true">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" width="16" height="16"><path d="M9 18l6-6-6-6"/></svg>
        </span>
    @endif
</nav>
@endif

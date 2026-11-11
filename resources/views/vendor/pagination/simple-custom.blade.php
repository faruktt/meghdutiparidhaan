@if ($paginator->hasPages())
    <div class="custom-pagination-wrap" style="justify-content: flex-end;">
        <nav role="navigation" aria-label="Pagination Navigation">
            <ul class="custom-pagination-nav">
                {{-- Previous Page Link --}}
                @if ($paginator->onFirstPage())
                    <li aria-disabled="true">
                        <span class="page-link-disabled">
                            <i class="fa-solid fa-chevron-left" style="font-size: 11px;"></i> Previous
                        </span>
                    </li>
                @else
                    <li>
                        <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="page-link-btn">
                            <i class="fa-solid fa-chevron-left" style="font-size: 11px;"></i> Previous
                        </a>
                    </li>
                @endif

                {{-- Next Page Link --}}
                @if ($paginator->hasMorePages())
                    <li>
                        <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="page-link-btn">
                            Next <i class="fa-solid fa-chevron-right" style="font-size: 11px;"></i>
                        </a>
                    </li>
                @else
                    <li aria-disabled="true">
                        <span class="page-link-disabled">
                            Next <i class="fa-solid fa-chevron-right" style="font-size: 11px;"></i>
                        </span>
                    </li>
                @endif
            </ul>
        </nav>
    </div>
@endif


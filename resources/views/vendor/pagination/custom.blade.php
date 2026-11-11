@if ($paginator->hasPages())
    <div class="custom-pagination-wrap">
        {{-- Results Counter --}}
        <div class="custom-pagination-info">
            Showing <strong>{{ $paginator->firstItem() }}</strong> to <strong>{{ $paginator->lastItem() }}</strong> of <strong>{{ $paginator->total() }}</strong> results
        </div>

        {{-- Pagination Links --}}
        <nav role="navigation" aria-label="Pagination Navigation">
            <ul class="custom-pagination-nav">
                {{-- Previous Page Link --}}
                @if ($paginator->onFirstPage())
                    <li aria-disabled="true" aria-label="@lang('pagination.previous')">
                        <span class="page-link-disabled">
                            <i class="fa-solid fa-chevron-left" style="font-size: 11px;"></i>
                        </span>
                    </li>
                @else
                    <li>
                        <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="page-link-btn" aria-label="@lang('pagination.previous')">
                            <i class="fa-solid fa-chevron-left" style="font-size: 11px;"></i>
                        </a>
                    </li>
                @endif

                {{-- Pagination Elements --}}
                @foreach ($elements as $element)
                    {{-- "Three Dots" Separator --}}
                    @if (is_string($element))
                        <li aria-disabled="true"><span class="page-link-dots">{{ $element }}</span></li>
                    @endif

                    {{-- Array Of Links --}}
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <li aria-current="page"><span class="page-link-active">{{ $page }}</span></li>
                            @else
                                <li><a href="{{ $url }}" class="page-link-btn">{{ $page }}</a></li>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                {{-- Next Page Link --}}
                @if ($paginator->hasMorePages())
                    <li>
                        <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="page-link-btn" aria-label="@lang('pagination.next')">
                            <i class="fa-solid fa-chevron-right" style="font-size: 11px;"></i>
                        </a>
                    </li>
                @else
                    <li aria-disabled="true" aria-label="@lang('pagination.next')">
                        <span class="page-link-disabled">
                            <i class="fa-solid fa-chevron-right" style="font-size: 11px;"></i>
                        </span>
                    </li>
                @endif
            </ul>
        </nav>
    </div>

    <style>
        .custom-pagination-wrap {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
            padding: 10px 0;
            width: 100%;
            box-sizing: border-box;
        }
        .custom-pagination-info {
            font-size: 13px;
            color: #64748B;
        }
        .custom-pagination-info strong {
            color: #0F172A;
            font-weight: 700;
        }
        .custom-pagination-nav {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            list-style: none;
            margin: 0;
            padding: 0;
        }
        .custom-pagination-nav li {
            display: inline-block;
        }
        .page-link-btn, .page-link-active, .page-link-disabled, .page-link-dots {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 32px;
            height: 32px;
            padding: 0 8px;
            font-size: 12.5px;
            font-weight: 700;
            border-radius: 6px;
            text-decoration: none;
            box-sizing: border-box;
            transition: all 0.2s ease;
        }
        .page-link-btn {
            background: #FFFFFF;
            color: #334155;
            border: 1px solid #CBD5E1;
            cursor: pointer;
        }
        .page-link-btn:hover {
            background: #F8FAFC;
            border-color: #94A3B8;
            color: #0F172A;
        }
        .page-link-active {
            background: var(--brand-maroon, #5A101C) !important;
            color: #FFFFFF !important;
            border: 1px solid var(--brand-maroon, #5A101C) !important;
            box-shadow: 0 1px 3px rgba(90, 16, 28, 0.3);
        }
        /* In admin layout, use admin theme color */
        .admin-main .page-link-active {
            background: var(--admin-primary, #EA580C) !important;
            border-color: var(--admin-primary, #EA580C) !important;
            color: #FFFFFF !important;
        }
        .page-link-disabled {
            background: #F1F5F9;
            color: #94A3B8;
            border: 1px solid #E2E8F0;
            cursor: not-allowed;
        }
        .page-link-dots {
            color: #64748B;
            border: none;
            background: transparent;
        }
        @media (max-width: 640px) {
            .custom-pagination-wrap {
                flex-direction: column;
                align-items: center;
                gap: 10px;
                text-align: center;
            }
        }
    </style>
@endif


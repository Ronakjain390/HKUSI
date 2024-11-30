@if ($paginator->hasPages())
    <ul class="pagination pagination-round justify-content-end ">
        @if ($paginator->onFirstPage())
            <li class="page-item prev disabled">
                <a class="page-link 1" href="javascript:void(0);"><i class="ti ti-chevron-left ti-xs "></i></a>
            </li>
        @else
            <li class="page-item prev ">
                <a class="page-link 2" href="{{ $paginator->previousPageUrl() }}"><i class="ti ti-chevron-left ti-xs "></i></a>
            </li>
        @endif

        @foreach ($elements as $element)
            @if (is_string($element))
            <li class="page-item disabled">
                <a class="page-link" href="javascript:void(0);">{{ $element }}</a>
            </li>
            @endif
            @if(is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li class="page-item active">
                            <a class="page-link 3" href="javascript:void(0);">{{ $page }}</a>
                        </li>
                    @else
                        <li class="page-item ">
                            <a class="page-link 4" href="{{ $url }}">{{ $page }}</a>
                        </li>
                    @endif
                @endforeach
            @endif
        @endforeach

        @if ($paginator->hasMorePages())
            <li class="page-item next">
                <a class="page-link 5" href="{{ $paginator->nextPageUrl() }}"><i class="ti ti-chevron-right ti-xs "></i></a>
            </li>
        @else
            <li class="page-item disabled">
                <a class="page-link 6" href="{{ $paginator->nextPageUrl() }}"><i class="ti ti-chevron-right ti-xs "></i></a>
            </li>
        @endif
    </ul>
@endif
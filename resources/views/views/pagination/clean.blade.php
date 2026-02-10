@if ($paginator->hasPages())
    <div class="flex flex-col sm:flex-row items-center justify-between gap-3 px-4 py-3">

        {{-- INFO --}}
        <div class="text-sm text-gray-500">
            Menampilkan
            <span class="font-semibold">{{ $paginator->firstItem() }}</span>
            –
            <span class="font-semibold">{{ $paginator->lastItem() }}</span>
            dari
            <span class="font-semibold">{{ $paginator->total() }}</span> data
        </div>

        {{-- BUTTON --}}
        <nav class="flex items-center gap-1">
            {{-- PREV --}}
            @if ($paginator->onFirstPage())
                <span class="px-3 py-1 rounded-md bg-gray-200 text-gray-400 cursor-not-allowed">
                    ‹
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}"
                   class="px-3 py-1 rounded-md border hover:bg-blue-50 hover:text-blue-600">
                    ‹
                </a>
            @endif

            {{-- NUMBER --}}
            @foreach ($elements as $element)
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span
                                class="px-3 py-1 rounded-md bg-blue-600 text-white font-semibold">
                                {{ $page }}
                            </span>
                        @else
                            <a href="{{ $url }}"
                               class="px-3 py-1 rounded-md border hover:bg-blue-50 hover:text-blue-600">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- NEXT --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}"
                   class="px-3 py-1 rounded-md border hover:bg-blue-50 hover:text-blue-600">
                    ›
                </a>
            @else
                <span class="px-3 py-1 rounded-md bg-gray-200 text-gray-400 cursor-not-allowed">
                    ›
                </span>
            @endif
        </nav>
    </div>
@endif

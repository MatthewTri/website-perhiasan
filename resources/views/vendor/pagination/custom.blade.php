@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center justify-between">
        <div class="flex justify-between flex-1 sm:hidden">
            @if ($paginator->onFirstPage())
                <span class="relative inline-flex items-center px-4 py-2 text-xs font-bold text-stone-300 bg-stone-50 border border-stone-200 cursor-default leading-5 uppercase tracking-[0.15em]">
                    Previous
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="relative inline-flex items-center px-4 py-2 text-xs font-bold text-stone-700 bg-white border border-stone-300 leading-5 hover:text-stone-900 hover:border-stone-900 focus:outline-none focus:border-stone-900 transition ease-in-out duration-150 uppercase tracking-[0.15em]">
                    Previous
                </a>
            @endif

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="relative inline-flex items-center px-4 py-2 ml-3 text-xs font-bold text-stone-700 bg-white border border-stone-300 leading-5 hover:text-stone-900 hover:border-stone-900 focus:outline-none focus:border-stone-900 transition ease-in-out duration-150 uppercase tracking-[0.15em]">
                    Next
                </a>
            @else
                <span class="relative inline-flex items-center px-4 py-2 ml-3 text-xs font-bold text-stone-300 bg-stone-50 border border-stone-200 cursor-default leading-5 uppercase tracking-[0.15em]">
                    Next
                </span>
            @endif
        </div>

        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
            <div>
                <p class="text-xs text-stone-500 leading-5 uppercase tracking-[0.1em]">
                    Showing
                    <span class="font-bold text-stone-900">{{ $paginator->firstItem() ?? 0 }}</span>
                    to
                    <span class="font-bold text-stone-900">{{ $paginator->lastItem() ?? 0 }}</span>
                    of
                    <span class="font-bold text-stone-900">{{ $paginator->total() }}</span>
                    results
                </p>
            </div>

            <div>
                <span class="relative z-0 inline-flex shadow-sm rounded-sm">
                    {{-- Previous Page Link --}}
                    @if ($paginator->onFirstPage())
                        <span aria-disabled="true" aria-label="Previous">
                            <span class="relative inline-flex items-center px-3 py-2 text-xs font-bold text-stone-300 bg-stone-50 border border-stone-200 cursor-default rounded-l-sm leading-5" aria-hidden="true">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                            </span>
                        </span>
                    @else
                        <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="relative inline-flex items-center px-3 py-2 text-xs font-bold text-stone-600 bg-white border border-stone-300 rounded-l-sm leading-5 hover:text-white hover:bg-stone-900 hover:border-stone-900 focus:z-10 focus:outline-none focus:border-stone-900 transition ease-in-out duration-150" aria-label="Previous">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    @endif

                    {{-- Pagination Elements --}}
                    @foreach ($elements as $element)
                        {{-- "Three Dots" Separator --}}
                        @if (is_string($element))
                            <span aria-disabled="true">
                                <span class="relative inline-flex items-center px-4 py-2 -ml-px text-xs font-bold text-stone-400 bg-white border border-stone-200 cursor-default leading-5">{{ $element }}</span>
                            </span>
                        @endif

                        {{-- Array Of Links --}}
                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <span aria-current="page">
                                        <span class="relative inline-flex items-center px-4 py-2 -ml-px text-xs font-bold text-white bg-stone-900 border border-stone-900 cursor-default leading-5 uppercase tracking-[0.1em]">{{ $page }}</span>
                                    </span>
                                @else
                                    <a href="{{ $url }}" class="relative inline-flex items-center px-4 py-2 -ml-px text-xs font-bold text-stone-600 bg-white border border-stone-300 leading-5 hover:text-white hover:bg-[#E3CFCF] hover:border-[#E3CFCF] focus:z-10 focus:outline-none focus:border-stone-900 transition ease-in-out duration-150 uppercase tracking-[0.1em]" aria-label="Go to page {{ $page }}">
                                        {{ $page }}
                                    </a>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($paginator->hasMorePages())
                        <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="relative inline-flex items-center px-3 py-2 -ml-px text-xs font-bold text-stone-600 bg-white border border-stone-300 rounded-r-sm leading-5 hover:text-white hover:bg-stone-900 hover:border-stone-900 focus:z-10 focus:outline-none focus:border-stone-900 transition ease-in-out duration-150" aria-label="Next">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    @else
                        <span aria-disabled="true" aria-label="Next">
                            <span class="relative inline-flex items-center px-3 py-2 -ml-px text-xs font-bold text-stone-300 bg-stone-50 border border-stone-200 cursor-default rounded-r-sm leading-5" aria-hidden="true">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                </svg>
                            </span>
                        </span>
                    @endif
                </span>
            </div>
        </div>
    </nav>
@endif

@php
if (! isset($scrollTo)) {
    $scrollTo = 'body';
}

$scrollIntoViewJsSnippet = ($scrollTo !== false)
    ? <<<JS
       (\$el.closest('{$scrollTo}') || document.querySelector('{$scrollTo}')).scrollIntoView()
    JS
    : '';
@endphp

<div>
    @if ($paginator->hasPages())
        <nav role="navigation" aria-label="Pagination Navigation" class="flex flex-col sm:flex-row items-center justify-between gap-3 pt-2">
            {{-- Mobile Previous / Next --}}
            <div class="flex items-center justify-between w-full sm:hidden">
                @if ($paginator->onFirstPage())
                    <span class="px-3 py-1.5 text-xs font-medium text-zinc-600 bg-zinc-900/50 border border-zinc-800/50 rounded-lg cursor-not-allowed">
                        &laquo; Previous
                    </span>
                @else
                    <button type="button"
                            wire:click="previousPage('{{ $paginator->getPageName() }}')"
                            x-on:click="{{ $scrollIntoViewJsSnippet }}"
                            wire:loading.attr="disabled"
                            class="px-3 py-1.5 text-xs font-medium text-zinc-300 bg-zinc-900 border border-zinc-800 rounded-lg hover:bg-zinc-800 hover:text-white transition">
                        &laquo; Previous
                    </button>
                @endif

                <span class="text-xs text-zinc-500">
                    Hal. {{ $paginator->currentPage() }} / {{ $paginator->lastPage() }}
                </span>

                @if ($paginator->hasMorePages())
                    <button type="button"
                            wire:click="nextPage('{{ $paginator->getPageName() }}')"
                            x-on:click="{{ $scrollIntoViewJsSnippet }}"
                            wire:loading.attr="disabled"
                            class="px-3 py-1.5 text-xs font-medium text-zinc-300 bg-zinc-900 border border-zinc-800 rounded-lg hover:bg-zinc-800 hover:text-white transition">
                        Next &raquo;
                    </button>
                @else
                    <span class="px-3 py-1.5 text-xs font-medium text-zinc-600 bg-zinc-900/50 border border-zinc-800/50 rounded-lg cursor-not-allowed">
                        Next &raquo;
                    </span>
                @endif
            </div>

            {{-- Desktop Information --}}
            <div class="hidden sm:block">
                <p class="text-sm text-zinc-500">
                    Menampilkan
                    <span class="font-medium text-zinc-300">{{ $paginator->firstItem() }}</span>
                    sampai
                    <span class="font-medium text-zinc-300">{{ $paginator->lastItem() }}</span>
                    dari
                    <span class="font-medium text-zinc-300">{{ $paginator->total() }}</span>
                    hasil
                </p>
            </div>

            {{-- Desktop Page Buttons --}}
            <div class="hidden sm:flex items-center gap-1">
                {{-- Previous Page Link --}}
                @if ($paginator->onFirstPage())
                    <span class="p-1.5 rounded-lg bg-zinc-900/40 border border-zinc-800/40 text-zinc-600 cursor-not-allowed" aria-hidden="true" title="Previous">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </span>
                @else
                    <button type="button"
                            wire:click="previousPage('{{ $paginator->getPageName() }}')"
                            x-on:click="{{ $scrollIntoViewJsSnippet }}"
                            class="p-1.5 rounded-lg bg-zinc-900 border border-zinc-800 text-zinc-400 hover:bg-zinc-800 hover:text-zinc-200 transition cursor-pointer"
                            aria-label="Previous"
                            title="Previous">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>
                @endif

                {{-- Pagination Elements --}}
                @foreach ($elements as $element)
                    {{-- "Three Dots" Separator --}}
                    @if (is_string($element))
                        <span class="px-2.5 py-1.5 text-sm text-zinc-600 select-none cursor-default">{{ $element }}</span>
                    @endif

                    {{-- Array Of Links --}}
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            <span wire:key="paginator-{{ $paginator->getPageName() }}-page{{ $page }}">
                                @if ($page == $paginator->currentPage())
                                    <span aria-current="page"
                                          class="px-3 py-1.5 text-sm rounded-lg bg-zinc-100 text-zinc-950 font-medium cursor-default">
                                        {{ $page }}
                                    </span>
                                @else
                                    <button type="button"
                                            wire:click="gotoPage({{ $page }}, '{{ $paginator->getPageName() }}')"
                                            x-on:click="{{ $scrollIntoViewJsSnippet }}"
                                            class="px-3 py-1.5 text-sm rounded-lg bg-zinc-900 border border-zinc-800 text-zinc-400 hover:bg-zinc-800 hover:text-zinc-200 transition cursor-pointer"
                                            aria-label="{{ __('Go to page :page', ['page' => $page]) }}">
                                        {{ $page }}
                                    </button>
                                @endif
                            </span>
                        @endforeach
                    @endif
                @endforeach

                {{-- Next Page Link --}}
                @if ($paginator->hasMorePages())
                    <button type="button"
                            wire:click="nextPage('{{ $paginator->getPageName() }}')"
                            x-on:click="{{ $scrollIntoViewJsSnippet }}"
                            class="p-1.5 rounded-lg bg-zinc-900 border border-zinc-800 text-zinc-400 hover:bg-zinc-800 hover:text-zinc-200 transition cursor-pointer"
                            aria-label="Next"
                            title="Next">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                @else
                    <span class="p-1.5 rounded-lg bg-zinc-900/40 border border-zinc-800/40 text-zinc-600 cursor-not-allowed" aria-hidden="true" title="Next">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </span>
                @endif
            </div>
        </nav>
    @elseif ($paginator->total() > 0)
        <div class="flex items-center justify-between pt-2">
            <p class="text-sm text-zinc-500">
                Menampilkan
                <span class="font-medium text-zinc-300">{{ $paginator->firstItem() }}</span>
                sampai
                <span class="font-medium text-zinc-300">{{ $paginator->lastItem() }}</span>
                dari
                <span class="font-medium text-zinc-300">{{ $paginator->total() }}</span>
                hasil
            </p>
        </div>
    @endif
</div>


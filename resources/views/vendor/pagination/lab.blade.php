@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}"
        class="grid grid-cols-[1fr_auto_1fr] items-center gap-4 text-sm">

        {{-- Sebelumnya --}}
        @if ($paginator->onFirstPage())
            <span aria-disabled="true" class="inline-flex h-11 items-center gap-2 text-lab-muted/40">
                &larr; Sebelumnya
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev"
                class="inline-flex h-11 items-center gap-2 hover:underline hover:decoration-lab-accent hover:decoration-2 hover:underline-offset-4">
                &larr; Sebelumnya
            </a>
        @endif

        {{-- Layar kecil: cukup penanda halaman. Nomor lengkap ada di layar lebar. --}}
        <span class="tabular-nums text-lab-muted sm:hidden">
            {{ $paginator->currentPage() }} / {{ $paginator->lastPage() }}
        </span>

        {{-- Nomor halaman --}}
        <div class="hidden items-center gap-1 sm:flex">
            @foreach ($elements as $element)
                @if (is_string($element))
                    <span class="px-2 text-lab-muted">{{ $element }}</span>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span aria-current="page"
                                class="grid size-10 place-items-center rounded-md bg-lab-ink font-medium tabular-nums text-lab-bg">
                                {{ $page }}
                            </span>
                        @else
                            <a href="{{ $url }}" aria-label="Halaman {{ $page }}"
                                class="grid size-10 place-items-center rounded-md tabular-nums text-lab-muted transition hover:bg-lab-ink/5 hover:text-lab-ink">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach
                @endif
            @endforeach
        </div>

        {{-- Berikutnya --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next"
                class="inline-flex h-11 items-center gap-2 justify-self-end hover:underline hover:decoration-lab-accent hover:decoration-2 hover:underline-offset-4">
                Berikutnya &rarr;
            </a>
        @else
            <span aria-disabled="true" class="inline-flex h-11 items-center gap-2 justify-self-end text-lab-muted/40">
                Berikutnya &rarr;
            </span>
        @endif
    </nav>
@endif
@extends('layouts.lab')

@section('title', 'Pertemuan 8: Seeder dan Pagination')

@php
    /**
     * Membuat URL halaman ini dengan mempertahankan filter yang sedang aktif.
     * Hanya nilai yang diubah yang perlu dioper lewat $overrides.
     */
    $linkWith = function (array $overrides) use ($search, $status, $perPage) {
        $params = array_merge(
            ['q' => $search, 'status' => $status, 'per_page' => $perPage],
            $overrides,
        );

        return route('pertemuan8.index', array_filter(
            $params,
            fn ($value) => $value !== '' && $value !== null,
        ));
    };

    $statusOptions = ['' => 'Semua', 'published' => 'Published', 'draft' => 'Draft'];
    $activeLink = 'font-medium underline decoration-lab-accent decoration-2 underline-offset-8';
    $idleLink = 'text-lab-muted transition hover:text-lab-ink';
@endphp

@section('content')

    {{-- ================= JUDUL ================= --}}
    <header class="max-w-2xl">
        <p class="text-sm text-lab-muted">Pertemuan 8</p>
        <h1 class="mt-3 text-5xl font-semibold leading-[1.05] tracking-[-0.03em] text-balance">
            Seeder dan Pagination
        </h1>
        <p class="mt-6 text-base leading-relaxed text-lab-muted">
            Data contoh diisi lewat seeder, lalu ditampilkan per halaman dengan
            <code class="font-lab-mono text-sm text-lab-ink">paginate()</code>.
            Pencarian dan jumlah data per halaman tetap aktif saat pindah halaman.
        </p>
    </header>

    {{-- ================= PENCARIAN DAN FILTER ================= --}}
    <section class="mt-16" aria-label="Pencarian dan filter">

        {{-- Latihan 3: pencarian judul --}}
        <form method="GET" action="{{ route('pertemuan8.index') }}" class="flex gap-2">
            {{-- Filter lain dibawa sebagai hidden agar tidak hilang saat mencari. --}}
            <input type="hidden" name="status" value="{{ $status }}">
            <input type="hidden" name="per_page" value="{{ $perPage }}">

            <label for="q" class="sr-only">Cari judul proyek</label>
            <input id="q" type="search" name="q" value="{{ $search }}" placeholder="Cari judul proyek"
                class="h-11 min-w-0 flex-1 rounded-md border border-lab-rule bg-lab-surface px-3.5 text-sm placeholder:text-lab-muted focus-visible:outline-2 focus-visible:outline-lab-accent">

            <button type="submit"
                class="h-11 shrink-0 rounded-md bg-lab-ink px-5 text-sm font-medium text-lab-bg transition hover:bg-lab-ink/85">
                Cari
            </button>

            @if ($search !== '')
                <a href="{{ $linkWith(['q' => '']) }}"
                    class="inline-flex h-11 shrink-0 items-center px-2 text-sm text-lab-muted transition hover:text-lab-ink">
                    Reset
                </a>
            @endif
        </form>

        <div class="mt-6 flex flex-wrap items-center justify-between gap-x-10 border-b border-lab-rule">

            {{-- Filter status --}}
            <div class="flex items-center gap-5">
                <span class="text-xs text-lab-muted">Status</span>
                @foreach ($statusOptions as $value => $label)
                    <a href="{{ $linkWith(['status' => $value]) }}"
                        aria-current="{{ $status === $value ? 'true' : 'false' }}"
                        class="inline-flex h-11 items-center text-sm {{ $status === $value ? $activeLink : $idleLink }}">
                        {{ $label }}
                    </a>
                @endforeach
            </div>

            {{-- Latihan 4: jumlah data per halaman --}}
            <div class="flex items-center gap-5">
                <span class="text-xs text-lab-muted">Per halaman</span>
                @foreach ($perPageOptions as $option)
                    <a href="{{ $linkWith(['per_page' => $option]) }}"
                        aria-current="{{ $perPage === $option ? 'true' : 'false' }}"
                        class="inline-flex h-11 items-center text-sm tabular-nums {{ $perPage === $option ? $activeLink : $idleLink }}">
                        {{ $option }}
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ================= DAFTAR DATA ================= --}}
    @if ($projects->isEmpty())
        <div class="py-16 text-sm text-lab-muted">
            @if ($search !== '' || $status !== '')
                <p>Tidak ada proyek yang cocok dengan pencarian atau filter ini.</p>
                <a href="{{ $linkWith(['q' => '', 'status' => '']) }}"
                    class="mt-3 inline-block text-lab-ink underline decoration-lab-accent decoration-2 underline-offset-4">
                    Hapus filter
                </a>
            @else
                <p>
                    Belum ada data. Jalankan
                    <code class="font-lab-mono text-lab-ink">php artisan migrate:fresh --seed</code>.
                </p>
            @endif
        </div>
    @else
        <ol>
            @foreach ($projects as $project)
                {{-- Latihan 2: nomor urut berlanjut antar halaman.
                     firstItem() = nomor data pertama di halaman ini. --}}
                <li
                    class="grid grid-cols-[2rem_1fr] gap-x-4 gap-y-2 border-b border-lab-line py-5 last:border-b-0 sm:grid-cols-[2.5rem_1fr_auto] sm:gap-x-6">
                    <span class="text-right text-sm leading-6 tabular-nums text-lab-muted">
                        {{ $projects->firstItem() + $loop->index }}
                    </span>

                    <div class="min-w-0">
                        <h2 class="text-base font-medium leading-6">{{ $project->title }}</h2>
                        <p class="mt-1 line-clamp-1 text-sm text-lab-muted">{{ $project->description }}</p>
                    </div>

                    <div
                        class="col-start-2 flex items-center gap-4 text-xs leading-6 text-lab-muted sm:col-start-3 sm:row-start-1 sm:self-start sm:justify-end">
                        {{-- Titik terisi = published, titik kosong = draft (bentuk + teks, bukan warna saja). --}}
                        <span class="inline-flex items-center gap-1.5 sm:w-20">
                            <span aria-hidden="true"
                                class="size-2 rounded-full {{ $project->status === 'published' ? 'bg-lab-ink' : 'border border-lab-muted' }}"></span>
                            {{ ucfirst($project->status) }}
                        </span>
                        <time datetime="{{ $project->created_at->toDateString() }}" class="tabular-nums">
                            {{ $project->created_at->format('d M Y') }}
                        </time>
                    </div>
                </li>
            @endforeach
        </ol>

        {{-- ================= RINGKASAN HALAMAN (aturan ganda ala buku besar) ================= --}}
        @php
            $totals = [
                'Halaman' => $projects->currentPage() . ' / ' . $projects->lastPage(),
                'Jumlah data' => $projects->total(),
                'Per halaman' => $projects->perPage(),
                'Ditampilkan' => $projects->firstItem() . '–' . $projects->lastItem(),
            ];
        @endphp

        <dl class="grid grid-cols-2 gap-x-8 gap-y-6 border-t-[3px] border-double border-lab-ink pt-6 sm:grid-cols-4">
            @foreach ($totals as $label => $value)
                <div>
                    <dt class="text-xs text-lab-muted">{{ $label }}</dt>
                    <dd class="mt-1 text-2xl font-medium tabular-nums tracking-tight">{{ $value }}</dd>
                </div>
            @endforeach
        </dl>

        {{-- ================= TAUTAN HALAMAN ================= --}}
        <div class="mt-10">
            {{ $projects->links('vendor.pagination.lab') }}
        </div>
    @endif

@endsection
@extends('layouts.lab')

@section('title', 'Portfolio')

@php
    $fieldClass = 'h-11 w-full min-w-0 rounded-md border border-lab-rule bg-lab-surface px-3.5 text-sm text-lab-ink placeholder:text-lab-muted focus-visible:outline-2 focus-visible:outline-lab-accent';
@endphp

@section('content')

{{-- ================= JUDUL ================= --}}
<header class="max-w-2xl">
    <p class="text-sm text-lab-muted">Halaman publik</p>
    <h1 class="mt-3 text-5xl font-semibold leading-[1.05] tracking-[-0.03em] text-balance">
        Portfolio
    </h1>
    <p class="mt-6 text-base leading-relaxed text-lab-muted">
        Project yang sudah dipublikasikan. Project berstatus draft tidak ditampilkan di sini.
    </p>
</header>

{{-- ================= PENCARIAN, FILTER, URUTAN ================= --}}
{{-- Method GET: nilai filter tampil di URL sehingga bisa dibagikan dan dibawa pagination. --}}
<section class="mt-16" aria-label="Pencarian dan filter">
    <form method="GET" action="{{ route('portfolio.index') }}"
        class="grid grid-cols-1 gap-2 sm:grid-cols-2 lg:grid-cols-[minmax(0,1fr)_10rem_10rem_10rem_auto]">
        <div class="sm:col-span-2 lg:col-span-1">
            <label for="search" class="sr-only">Cari project</label>
            <input id="search" type="search" name="search" value="{{ $search }}"
                placeholder="Cari judul atau deskripsi" class="{{ $fieldClass }}">
        </div>

        <div>
            <label for="category" class="sr-only">Kategori</label>
            <select id="category" name="category" class="{{ $fieldClass }}">
                <option value="">Semua kategori</option>
                @foreach ($categories as $option)
                <option value="{{ $option->slug }}" @selected($category === $option->slug)>{{ $option->name }}</option>
                @endforeach
            </select>
        </div>

        {{-- Latihan: filter technology --}}
        <div>
            <label for="technology" class="sr-only">Technology</label>
            <select id="technology" name="technology" class="{{ $fieldClass }}">
                <option value="">Semua technology</option>
                @foreach ($technologies as $option)
                <option value="{{ $option->slug }}" @selected($technology === $option->slug)>{{ $option->name }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="sort" class="sr-only">Urutan</label>
            <select id="sort" name="sort" class="{{ $fieldClass }}">
                @foreach ($sortOptions as $value => $label)
                <option value="{{ $value }}" @selected($sort === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit"
            class="h-11 shrink-0 rounded-md bg-lab-ink px-5 text-sm font-medium text-lab-bg transition hover:bg-lab-ink/85">
            Cari
        </button>
    </form>
</section>

{{-- ================= DAFTAR PROJECT ================= --}}
<section class="mt-10" aria-label="Daftar project">
    <div class="flex flex-wrap items-baseline justify-between gap-x-4 gap-y-1 border-t-[3px] border-double border-lab-ink pt-6">
        <h2 class="text-sm font-medium">{{ $hasActiveFilter ? 'Hasil filter' : 'Semua project' }}</h2>
        <div class="flex items-baseline gap-4 text-sm">
            {{-- Latihan: total() = jumlah seluruh hasil, bukan hanya yang tampil di halaman ini --}}
            <span class="tabular-nums text-lab-muted">{{ $projects->total() }} hasil ditemukan</span>
            @if ($hasActiveFilter)
            <a href="{{ route('portfolio.index') }}"
                class="text-lab-ink underline decoration-lab-accent decoration-2 underline-offset-4">Reset</a>
            @endif
        </div>
    </div>

    <ul class="mt-6 grid grid-cols-1 gap-x-8 gap-y-10 sm:grid-cols-2 lg:grid-cols-3">
        @forelse ($projects as $project)
        <li class="min-w-0">
            <a href="{{ route('portfolio.show', $project) }}" class="group block">
                @if ($project->thumbnail_url)
                <img src="{{ $project->thumbnail_url }}" alt="Thumbnail {{ $project->title }}" loading="lazy"
                    class="aspect-[4/3] w-full border border-lab-line bg-lab-surface object-cover">
                @else
                <div class="flex aspect-[4/3] w-full items-center justify-center border border-lab-line bg-lab-surface text-sm text-lab-muted">
                    Tanpa gambar
                </div>
                @endif

                <h3 class="mt-3 text-base font-medium leading-snug underline-offset-4 group-hover:underline group-hover:decoration-lab-accent group-hover:decoration-2">
                    {{ $project->title }}
                </h3>
            </a>
            <p class="mt-1 text-xs text-lab-muted">{{ $project->category?->name ?? 'Tanpa kategori' }}</p>
            <p class="mt-2 text-sm leading-relaxed text-lab-muted">
                {{ $project->short_description ?: Str::limit($project->description, 120) }}
            </p>
            @if ($project->technologies->isNotEmpty())
            <p class="mt-2 font-lab-mono text-xs text-lab-muted">
                {{ $project->technologies->sortBy('name')->pluck('name')->join(' / ') }}
            </p>
            @endif
        </li>
        @empty
        <li class="col-span-full border-y border-lab-line py-6 text-sm text-lab-muted">Tidak ada project yang cocok.</li>
        @endforelse
    </ul>

    {{-- Pagination minimalis Lab (sama dengan Pertemuan 8) --}}
    <div class="mt-12">
        {{ $projects->links('vendor.pagination.lab') }}
    </div>
</section>

@endsection

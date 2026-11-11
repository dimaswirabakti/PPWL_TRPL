@extends('layouts.lab')

@section('title', 'Portfolio')

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

{{-- ================= DAFTAR PROJECT ================= --}}
<section class="mt-16" aria-label="Daftar project">
    <div class="flex items-baseline justify-between gap-4 border-t-[3px] border-double border-lab-ink pt-6">
        <h2 class="text-sm font-medium">Semua project</h2>
        <span class="text-sm tabular-nums text-lab-muted">{{ $projects->count() }} project</span>
    </div>

    @if ($projects->isEmpty())
    <p class="mt-6 border-y border-lab-line py-6 text-sm text-lab-muted">Belum ada project yang dipublikasikan.</p>
    @else
    <ul class="mt-6 grid grid-cols-1 gap-x-8 gap-y-10 sm:grid-cols-2 lg:grid-cols-3">
        @foreach ($projects as $project)
        <li>
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
                <p class="mt-1 text-xs text-lab-muted">{{ $project->category?->name ?? 'Tanpa kategori' }}</p>
            </a>
        </li>
        @endforeach
    </ul>
    @endif
</section>

@endsection

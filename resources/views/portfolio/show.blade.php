@extends('layouts.lab')

@section('title', $project->title.' | Portfolio')

@section('content')

<a href="{{ route('portfolio.index') }}" class="text-sm text-lab-muted transition hover:text-lab-ink">&larr; Semua project</a>

{{-- ================= JUDUL ================= --}}
<header class="mt-8 max-w-2xl">
    <p class="text-sm text-lab-muted">{{ $project->category?->name ?? 'Tanpa kategori' }}</p>
    <h1 class="mt-3 text-4xl font-semibold leading-[1.1] tracking-[-0.03em] text-balance sm:text-5xl">
        {{ $project->title }}
    </h1>
    @if ($project->short_description)
    <p class="mt-6 text-lg leading-relaxed text-lab-ink">{{ $project->short_description }}</p>
    @endif
    <div class="mt-6 whitespace-pre-line text-base leading-relaxed text-lab-muted">{{ $project->description }}</div>

    @if ($project->project_date)
    <p class="mt-6 text-sm text-lab-muted">
        Tanggal project
        <time datetime="{{ $project->project_date->toDateString() }}" class="ml-1 tabular-nums text-lab-ink">{{ $project->project_date->format('d M Y') }}</time>
    </p>
    @endif

    {{-- Tombol hanya muncul jika URL terisi. Tab baru + noopener agar halaman tujuan tidak bisa mengakses window ini. --}}
    @if ($project->project_url || $project->repository_url)
    <div class="mt-8 flex flex-wrap gap-3">
        @if ($project->project_url)
        <a href="{{ $project->project_url }}" target="_blank" rel="noopener noreferrer"
            class="inline-flex h-11 items-center rounded-md bg-lab-ink px-5 text-sm font-medium text-lab-bg transition hover:bg-lab-ink/85">
            Buka demo
        </a>
        @endif
        @if ($project->repository_url)
        <a href="{{ $project->repository_url }}" target="_blank" rel="noopener noreferrer"
            class="inline-flex h-11 items-center rounded-md border border-lab-rule px-5 text-sm font-medium text-lab-ink transition hover:border-lab-ink">
            Repositori
        </a>
        @endif
    </div>
    @endif
</header>

{{-- ================= TECHNOLOGY ================= --}}
@if ($project->technologies->isNotEmpty())
<section class="mt-10" aria-labelledby="technology">
    <h2 id="technology" class="text-sm font-medium">Technology</h2>
    <ul class="mt-3 flex flex-wrap gap-x-6 gap-y-1 border-t border-lab-rule pt-3 font-lab-mono text-sm text-lab-muted">
        @foreach ($project->technologies->sortBy('name') as $technology)
        <li>{{ $technology->name }}</li>
        @endforeach
    </ul>
</section>
@endif

{{-- ================= SKILL ================= --}}
@if ($project->skills->isNotEmpty())
<section class="mt-10" aria-labelledby="skill">
    <h2 id="skill" class="text-sm font-medium">Skill</h2>
    <ul class="mt-3 flex flex-wrap gap-x-6 gap-y-1 border-t border-lab-rule pt-3 text-sm text-lab-muted">
        @foreach ($project->skills as $skill)
        <li>{{ $skill->name }}</li>
        @endforeach
    </ul>
</section>
@endif

{{-- ================= GALERI ================= --}}
<section class="mt-16" aria-labelledby="galeri">
    <div class="flex items-baseline justify-between gap-4 border-t-[3px] border-double border-lab-ink pt-6">
        <h2 id="galeri" class="text-sm font-medium">Galeri</h2>
        <span class="text-sm tabular-nums text-lab-muted">{{ $project->images->count() }} gambar</span>
    </div>

    @if ($project->images->isEmpty())
    <div class="mt-6 flex aspect-[16/9] w-full items-center justify-center border border-lab-line bg-lab-surface text-sm text-lab-muted">
        Tanpa gambar
    </div>
    @else
    <ul class="mt-6 grid grid-cols-1 gap-x-8 gap-y-10 sm:grid-cols-2">
        @foreach ($project->images as $image)
        <li>
            <figure>
                <img src="{{ $image->url }}" alt="{{ $image->caption ?? 'Gambar '.$loop->iteration.' dari '.$project->title }}" loading="lazy"
                    class="aspect-[4/3] w-full border border-lab-line bg-lab-surface object-cover">
                @if ($image->caption)
                <figcaption class="mt-2 text-sm text-lab-muted">{{ $image->caption }}</figcaption>
                @endif
            </figure>
        </li>
        @endforeach
    </ul>
    @endif
</section>

@endsection

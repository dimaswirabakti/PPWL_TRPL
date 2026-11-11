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
    <div class="mt-6 whitespace-pre-line text-base leading-relaxed text-lab-muted">{{ $project->description }}</div>
</header>

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

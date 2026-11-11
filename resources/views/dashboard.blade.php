@extends('layouts.lab')

@section('title', 'Dashboard')

@section('content')

    {{-- ================= JUDUL ================= --}}
    <header class="max-w-2xl">
        <p class="text-sm text-lab-muted">Khusus user yang login</p>
        <h1 class="mt-3 text-5xl font-semibold leading-[1.05] tracking-[-0.03em] text-balance">
            Dashboard
        </h1>
        <p class="mt-6 text-base leading-relaxed text-lab-muted">
            Ringkasan isi portfolio. Setiap angka dihitung oleh database dengan
            <code class="font-lab-mono text-sm text-lab-ink">count()</code>,
            bukan dengan memuat semua baris ke PHP lebih dulu.
        </p>
    </header>

    {{-- ================= ANGKA RINGKASAN ================= --}}
    {{-- Titik dua pada :value artinya isinya variabel PHP, bukan teks biasa. --}}
    <section class="mt-16" aria-label="Ringkasan data">
        <dl class="grid grid-cols-2 gap-x-8 gap-y-8 border-t-[3px] border-double border-lab-ink pt-6 md:grid-cols-4">
            <x-stat-card label="Projects" :value="$totalProjects" color="text-lab-accent" />
            <x-stat-card label="Categories" :value="$totalCategories" />
            <x-stat-card label="Technologies" :value="$totalTechnologies" />
            <x-stat-card label="Skills" :value="$totalSkills" />
        </dl>
    </section>

    {{-- ================= PROJECT TERBARU ================= --}}
    <section class="mt-16" aria-labelledby="terbaru">
        <div class="flex items-baseline justify-between gap-4">
            <h2 id="terbaru" class="text-sm font-medium">Project terbaru</h2>
            <a href="{{ route('projects.index') }}" class="text-sm text-lab-muted transition hover:text-lab-ink">
                Kelola project &rarr;
            </a>
        </div>

        <ul class="mt-6 border-t border-lab-rule">
            @forelse ($latestProjects as $project)
                <li class="flex items-baseline justify-between gap-6 border-b border-lab-line py-4">
                    <div class="min-w-0">
                        <a href="{{ route('projects.show', $project->id) }}"
                            class="block truncate text-base font-medium underline-offset-4 hover:underline hover:decoration-lab-accent hover:decoration-2">
                            {{ $project->title }}
                        </a>
                        <p class="mt-1 text-xs text-lab-muted">
                            {{ $project->category?->name ?? 'Tanpa kategori' }}
                            &middot; {{ $project->created_at->format('d M Y') }}
                        </p>
                    </div>

                    {{-- Titik terisi = published, titik kosong = draft. Selalu disertai teks. --}}
                    <span class="inline-flex shrink-0 items-center gap-1.5 text-xs">
                        <span aria-hidden="true"
                            class="size-2 rounded-full {{ $project->status === 'published' ? 'bg-lab-ink' : 'border border-lab-muted' }}"></span>
                        {{ ucfirst($project->status) }}
                    </span>
                </li>
            @empty
                <li class="border-b border-lab-line py-6 text-sm text-lab-muted">Belum ada project.</li>
            @endforelse
        </ul>
    </section>

@endsection

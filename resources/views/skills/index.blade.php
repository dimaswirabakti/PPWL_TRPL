@extends('layouts.lab')

@section('title', 'Skill')

@section('content')

{{-- ================= JUDUL ================= --}}
<header class="max-w-2xl">
    <p class="text-sm text-lab-muted">Khusus user yang login</p>
    <h1 class="mt-3 text-5xl font-semibold leading-[1.05] tracking-[-0.03em] text-balance">
        Skill
    </h1>
    <p class="mt-6 text-base leading-relaxed text-lab-muted">
        Relasi many-to-many lewat tabel pivot
        <code class="font-lab-mono text-sm text-lab-ink">project_skill</code>.
        Jumlah project dihitung dengan
        <code class="font-lab-mono text-sm text-lab-ink">withCount('projects')</code>.
    </p>
</header>

<div class="mt-10">
    @include('partials.flash')
</div>

{{-- ================= DAFTAR SKILL ================= --}}
<section class="mt-6" aria-label="Daftar skill">
    <div class="border-t-[3px] border-double border-lab-ink">
        <div class="grid grid-cols-[minmax(0,1fr)_6rem_4rem] gap-x-6 border-b border-lab-line py-3 text-xs text-lab-muted" aria-hidden="true">
            <span>Nama</span>
            <span class="text-right">Project</span>
            <span></span>
        </div>

        <ul>
            @forelse ($skills as $skill)
            <li class="grid grid-cols-[minmax(0,1fr)_6rem_4rem] items-baseline gap-x-6 border-b border-lab-line py-4">
                <span class="truncate text-base font-medium">{{ $skill->name }}</span>
                <span class="text-right text-sm tabular-nums text-lab-muted">{{ $skill->projects_count }}</span>
                <a href="{{ route('skills.edit', $skill) }}"
                    class="text-right text-sm underline decoration-lab-accent decoration-2 underline-offset-4">
                    Ubah<span class="sr-only"> {{ $skill->name }}</span>
                </a>
            </li>
            @empty
            <li class="border-b border-lab-line py-6 text-sm text-lab-muted">Belum ada skill.</li>
            @endforelse
        </ul>
    </div>
</section>

@endsection

@extends('layouts.app')

@section('title', $project->title . ' | Portfolio')

@section('content')
<div class="mb-6">
    <a href="{{ route('projects.index') }}" class="text-sm text-zinc-500 hover:text-zinc-900">&larr; Kembali ke daftar</a>
</div>

<article class="rounded-lg border border-zinc-200 bg-white p-6">
    {{-- Thumbnail besar, atau kotak netral bila belum ada gambar --}}
    @if ($project->thumbnail_url)
    <img src="{{ $project->thumbnail_url }}" alt="Thumbnail {{ $project->title }}"
        class="mb-5 aspect-[16/9] w-full rounded-md border border-zinc-200 object-cover">
    @else
    <div class="mb-5 flex aspect-[16/9] w-full items-center justify-center rounded-md border border-zinc-200 bg-zinc-100 text-sm text-zinc-400">
        Tanpa gambar
    </div>
    @endif

    <div class="flex flex-wrap items-center gap-2.5">
        <h1 class="text-2xl font-semibold tracking-tight">{{ $project->title }}</h1>
        @include('partials.status-badge', ['status' => $project->status])
        @if ($project->is_featured)
        <span class="rounded border border-zinc-300 px-1.5 py-0.5 text-xs font-medium text-zinc-700">Unggulan</span>
        @endif
    </div>
    <p class="mt-1 text-xs text-zinc-400">
        {{ $project->category?->name ?? 'Tanpa kategori' }}
        &middot; Dibuat {{ $project->created_at->format('d M Y') }}
        @if ($project->project_date)
        &middot; Tanggal project {{ $project->project_date->format('d M Y') }}
        @endif
    </p>

    @if ($project->short_description)
    <p class="mt-4 text-sm font-medium text-zinc-700">{{ $project->short_description }}</p>
    @endif

    <div class="mt-4 whitespace-pre-line text-sm leading-relaxed text-zinc-700">{{ $project->description }}</div>

    {{-- Skill project (many-to-many) --}}
    <div class="mt-4 flex flex-wrap items-center gap-1.5">
        <span class="text-xs text-zinc-400">Skill:</span>
        @forelse ($project->skills as $skill)
        <span class="rounded border border-zinc-200 bg-zinc-50 px-1.5 py-0.5 text-xs text-zinc-600">{{ $skill->name }}</span>
        @empty
        <span class="text-xs text-zinc-500">Belum ada skill.</span>
        @endforelse
    </div>

    {{-- Technology project (many-to-many, pivot project_technology) --}}
    <div class="mt-2 flex flex-wrap items-center gap-1.5">
        <span class="text-xs text-zinc-400">Technology:</span>
        @forelse ($project->technologies->sortBy('name') as $technology)
        <span class="rounded border border-zinc-200 bg-zinc-50 px-1.5 py-0.5 text-xs text-zinc-600">{{ $technology->name }}</span>
        @empty
        <span class="text-xs text-zinc-500">Belum ada technology.</span>
        @endforelse
    </div>

    {{-- Detail tambahan Pertemuan 11 --}}
    <dl class="mt-4 grid grid-cols-1 gap-x-6 gap-y-2 border-t border-zinc-100 pt-4 text-sm sm:grid-cols-[8rem_1fr]">
        <dt class="text-zinc-400">Slug</dt>
        <dd class="break-all font-mono text-xs leading-5 text-zinc-700">{{ $project->slug ?? '-' }}</dd>
        <dt class="text-zinc-400">URL demo</dt>
        <dd class="break-all text-zinc-700">
            @if ($project->project_url)
            <a href="{{ $project->project_url }}" target="_blank" rel="noopener noreferrer" class="hover:underline">{{ $project->project_url }}</a>
            @else
            -
            @endif
        </dd>
        <dt class="text-zinc-400">URL repositori</dt>
        <dd class="break-all text-zinc-700">
            @if ($project->repository_url)
            <a href="{{ $project->repository_url }}" target="_blank" rel="noopener noreferrer" class="hover:underline">{{ $project->repository_url }}</a>
            @else
            -
            @endif
        </dd>
    </dl>

    @include('projects.partials.gallery-readonly')

    <div class="mt-6 flex items-center gap-3 border-t border-zinc-100 pt-4 text-sm">
        <a href="{{ route('projects.edit', $project->id) }}"
            class="rounded-md border border-zinc-300 px-3 py-1.5 font-medium text-zinc-700 transition hover:bg-zinc-50">Edit</a>
        <form action="{{ route('projects.destroy', $project->id) }}" method="POST"
            data-confirm-delete="Pindahkan proyek ini ke Trash?">
            @csrf
            @method('DELETE')
            <button type="submit"
                class="rounded-md border border-red-200 px-3 py-1.5 font-medium text-red-600 transition hover:bg-red-50">Hapus</button>
        </form>
    </div>
</article>
@endsection
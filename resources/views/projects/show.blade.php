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
    </div>
    <p class="mt-1 text-xs text-zinc-400">
        {{ $project->category?->name ?? 'Tanpa kategori' }}
        &middot; Dibuat {{ $project->created_at->format('d M Y') }}
    </p>

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
@extends('layouts.app')

@section('title', 'Projects | Portfolio')

@section('content')
<div class="mb-8 flex items-end justify-between gap-4">
    <div>
        <h1 class="text-2xl font-semibold tracking-tight">Proyek Saya</h1>
        <p class="mt-1 text-sm text-zinc-500">Kumpulan proyek yang pernah saya kerjakan.</p>
    </div>
    <a href="{{ route('projects.create') }}"
        class="shrink-0 rounded-md bg-zinc-900 px-3.5 py-2 text-sm font-medium text-white transition hover:bg-zinc-700">
        Tambah Proyek
    </a>
</div>

@forelse ($projects as $project)
<article class="mb-3 rounded-lg border border-zinc-200 bg-white p-5 transition hover:border-zinc-300">
    <div class="flex items-start justify-between gap-4">
        <div class="min-w-0">
            <h2 class="truncate font-medium">
                <a href="{{ route('projects.show', $project->id) }}" class="hover:underline">{{ $project->title }}</a>
            </h2>
            <p class="mt-1 line-clamp-2 text-sm text-zinc-500">{{ $project->description }}</p>
            <p class="mt-2 text-xs text-zinc-400">{{ $project->created_at->format('d M Y') }}</p>
        </div>

        <div class="flex shrink-0 items-center gap-3 text-sm">
            <a href="{{ route('projects.edit', $project->id) }}" class="text-zinc-600 hover:text-zinc-900">Edit</a>
            <form action="{{ route('projects.destroy', $project->id) }}" method="POST"
                data-confirm-delete="Yakin ingin menghapus &quot;{{ $project->title }}&quot;?">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-red-600 hover:text-red-700">Hapus</button>
            </form>
        </div>
    </div>
</article>
@empty
<div class="rounded-lg border border-dashed border-zinc-300 bg-white p-10 text-center">
    <p class="text-sm text-zinc-500">Belum ada proyek.</p>
    <a href="{{ route('projects.create') }}" class="mt-3 inline-block text-sm font-medium text-zinc-900 hover:underline">
        Tambah proyek pertama &rarr;
    </a>
</div>
@endforelse
@endsection
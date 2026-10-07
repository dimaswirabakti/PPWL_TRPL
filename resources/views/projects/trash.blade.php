@extends('layouts.app')

@section('title', 'Trash | Portfolio')

@section('content')
<div class="mb-6 flex items-end justify-between gap-4">
    <div>
        <h1 class="text-2xl font-semibold tracking-tight">Trash</h1>
        <p class="mt-1 text-sm text-zinc-500">Proyek yang sudah dihapus. Bisa dipulihkan atau dihapus permanen.</p>
    </div>
    <a href="{{ route('projects.index') }}" class="shrink-0 text-sm text-zinc-600 hover:text-zinc-900">&larr; Ke daftar proyek</a>
</div>

@forelse ($projects as $project)
<article class="mb-3 rounded-lg border border-zinc-200 bg-white p-5">
    <div class="flex items-start justify-between gap-4">
        <div class="min-w-0">
            <h2 class="truncate font-medium text-zinc-700">{{ $project->title }}</h2>
            <p class="mt-1 line-clamp-2 text-sm text-zinc-500">{{ $project->description }}</p>
            <p class="mt-2 text-xs text-zinc-400">Dihapus {{ $project->deleted_at->format('d M Y H:i') }}</p>
        </div>

        <div class="flex shrink-0 items-center gap-3 text-sm">
            <form action="{{ route('projects.restore', $project->id) }}" method="POST">
                @csrf
                @method('PATCH')
                <button type="submit" class="text-emerald-700 hover:text-emerald-800">Pulihkan</button>
            </form>
            <form action="{{ route('projects.force-delete', $project->id) }}" method="POST"
                data-confirm-delete="Hapus permanen &quot;{{ $project->title }}&quot;? Tidak bisa dipulihkan lagi.">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-red-600 hover:text-red-700">Hapus permanen</button>
            </form>
        </div>
    </div>
</article>
@empty
<div class="rounded-lg border border-dashed border-zinc-300 bg-white p-10 text-center">
    <p class="text-sm text-zinc-500">Trash kosong.</p>
</div>
@endforelse
@endsection
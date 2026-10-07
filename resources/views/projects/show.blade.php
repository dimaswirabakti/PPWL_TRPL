@extends('layouts.app')

@section('title', $project->title . ' | Portfolio')

@section('content')
<div class="mb-6">
    <a href="{{ route('projects.index') }}" class="text-sm text-zinc-500 hover:text-zinc-900">&larr; Kembali ke daftar</a>
</div>

<article class="rounded-lg border border-zinc-200 bg-white p-6">
    <div class="flex flex-wrap items-center gap-2.5">
        <h1 class="text-2xl font-semibold tracking-tight">{{ $project->title }}</h1>
        @include('partials.status-badge', ['status' => $project->status])
    </div>
    <p class="mt-1 text-xs text-zinc-400">Dibuat {{ $project->created_at->format('d M Y') }}</p>

    <div class="mt-4 whitespace-pre-line text-sm leading-relaxed text-zinc-700">{{ $project->description }}</div>

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
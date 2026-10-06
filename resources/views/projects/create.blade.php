@extends('layouts.app')

@section('title', 'Tambah Proyek | Portfolio')

@section('content')
<div class="mb-6">
    <a href="{{ route('projects.index') }}" class="text-sm text-zinc-500 hover:text-zinc-900">&larr; Kembali</a>
    <h1 class="mt-2 text-2xl font-semibold tracking-tight">Tambah Proyek</h1>
</div>

<form action="{{ route('projects.store') }}" method="POST"
    class="space-y-5 rounded-lg border border-zinc-200 bg-white p-6">
    @csrf
    @include('projects.form')

    <div class="flex items-center gap-3 pt-2">
        <button type="submit" class="rounded-md bg-zinc-900 px-4 py-2 text-sm font-medium text-white transition hover:bg-zinc-700">Simpan</button>
        <a href="{{ route('projects.index') }}" class="text-sm text-zinc-600 hover:text-zinc-900">Batal</a>
    </div>
</form>
@endsection
@extends('layouts.app')

@section('title', 'Tambah Proyek | Portfolio')

@section('content')
<div class="mb-6">
    <a href="{{ route('projects.index') }}" class="text-sm text-zinc-500 hover:text-zinc-900">&larr; Kembali</a>
    <h1 class="mt-2 text-2xl font-semibold tracking-tight">Tambah Proyek</h1>
</div>

<form action="{{ route('post.projects.store') }}" method="POST" enctype="multipart/form-data">
    class="space-y-5 rounded-lg border border-zinc-200 bg-white p-6">
    @csrf
    @include('projects.form')

    <div class="flex items-center gap-3 pt-2">
        <button type="submit" class="rounded-md bg-zinc-900 px-4 py-2 text-sm font-medium text-white transition hover:bg-zinc-700">Simpan</button>
        <a href="{{ route('projects.index') }}" class="text-sm text-zinc-600 hover:text-zinc-900">Batal</a>
    </div>
    <div class="mt-4">
        <x-input-label for="thumbnail" value="Thumbnail" />
        <input type="file" name="thumbnail" id="thumbnail" accept="image/*" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
        <x-input-error :messages="$errors->get('thumbnail')" class="mt-2" />
    </div>
</form>
@endsection
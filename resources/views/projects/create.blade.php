@extends('layouts.app')

@section('title', 'Tambah Proyek | Portfolio')

@section('content')
    <h1>Tambah Proyek</h1>

    @if ($errors->any())
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form action="{{ route('projects.store') }}" method="POST">
        @csrf

        <div>
            <label for="title">Judul</label>
            <input type="text" id="title" name="title" value="{{ old('title') }}">
        </div>

        <div>
            <label for="description">Deskripsi</label>
            <textarea id="description" name="description" rows="5">{{ old('description') }}</textarea>
        </div>

        <button type="submit">Simpan</button>
    </form>

    <a href="{{ route('projects.index') }}">&larr; Kembali</a>
@endsection
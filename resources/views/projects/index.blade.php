@extends('layouts.app')

@section('title', 'Projects | Portfolio')

@section('content')
    <h1>Proyek Saya</h1>

    <p><a href="{{ route('projects.create') }}">+ Tambah Proyek</a></p>

    <ul>
        @forelse ($projects as $project)
            <li>
                <a href="{{ route('projects.show', $project->id) }}">
                    {{ $project->title }}
                </a>
            </li>
        @empty
            <p>Belum ada proyek.</p>
        @endforelse
    </ul>
@endsection
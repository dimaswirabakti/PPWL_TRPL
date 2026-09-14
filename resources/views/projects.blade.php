@extends('layouts.app')

@section('title', 'Projects | Portfolio')

@section('content')
    <h1>Proyek Saya</h1>
    <ul>
        @forelse($projects as $project)
            <li>{{ $project }}</li>
        @empty
            <p>Belum ada proyek.</p>
        @endforelse
    </ul>
@endsection
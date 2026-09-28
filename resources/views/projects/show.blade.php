@extends('layouts.app')

@section('title', $project->title . ' | Portfolio')

@section('content')
    <h1>{{ $project->title }}</h1>
    <small>Dibuat: {{ $project->created_at }}</small>
    <p>{{ $project->description }}</p>

    <a href="{{ route('projects.index') }}">&larr; Kembali</a>
@endsection
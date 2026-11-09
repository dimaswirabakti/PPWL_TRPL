@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">{{ $project->name }}</h1>

    @if ($project->thumbnail)
    <div class="mb-8">
        <h2 class="text-xl font-semibold mb-2">Thumbnail</h2>
        {{-- Menggunakan asset('storage/...') untuk mengakses file di disk public --}}
        <img src="{{ asset('storage/' . $project->thumbnail) }}" alt="{{ $project->name }}" class="max-w-md rounded shadow">
    </div>
    @endif

    <h2 class="text-xl font-semibold mb-2">Gallery</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
        @foreach ($project->images as $img)
        <div class="border rounded p-2 shadow">
            <img src="{{ asset('storage/' . $img->image) }}" alt="{{ $img->caption ?: $project->name }}" class="w-full h-auto object-cover rounded">
            @if($img->caption)
            <p class="mt-2 text-sm text-gray-600 text-center">{{ $img->caption }}</p>
            @endif
        </div>
        @endforeach
    </div>
</div>
@endsection
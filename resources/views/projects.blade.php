@extends('layouts.app')

@section('content')
<div class="container mx-auto px-6 py-12 max-w-6xl">
    <div class="text-center mb-12">
        <h1 class="text-4xl font-extrabold text-zinc-900 mb-4">Portofolio</h1>
        <p class="text-lg text-zinc-500">Kumpulan proyek dan karya yang telah saya kerjakan.</p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">
        @forelse ($projects as $project)
        <div class="bg-white rounded-xl shadow-sm border border-zinc-100 overflow-hidden hover:shadow-lg transition-shadow duration-300 flex flex-col">
            <a href="{{ route('projects.show', $project->id) }}" class="block overflow-hidden h-56">
                @if ($project->thumbnail)
                <img src="{{ asset('storage/' . $project->thumbnail) }}" alt="{{ $project->name }}" class="w-full h-full object-cover transition-transform duration-500 hover:scale-105">
                @else
                <div class="w-full h-full bg-zinc-100 flex items-center justify-center text-zinc-400">
                    <span class="text-sm font-medium">Tanpa Thumbnail</span>
                </div>
                @endif
            </a>

            <div class="p-6 flex flex-col flex-grow">
                <span class="text-xs font-bold text-blue-600 uppercase tracking-wider mb-2">
                    {{ $project->category->name ?? 'Uncategorized' }}
                </span>
                <h2 class="text-xl font-bold text-zinc-800 mb-4 leading-tight">
                    <a href="{{ route('projects.show', $project->id) }}" class="hover:text-blue-600 transition-colors">
                        {{ $project->name }}
                    </a>
                </h2>

                <div class="mt-auto pt-4 border-t border-zinc-100">
                    <a href="{{ route('projects.show', $project->id) }}" class="text-sm font-semibold text-zinc-600 hover:text-blue-600 transition-colors flex items-center">
                        Lihat Detail
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full py-16 text-center text-zinc-500 bg-zinc-50 rounded-xl border border-dashed border-zinc-200">
            <svg class="mx-auto h-12 w-12 text-zinc-400 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                <path vector-effect="non-scaling-stroke" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
            </svg>
            <p class="text-lg font-medium text-zinc-600">Belum ada project yang diterbitkan.</p>
        </div>
        @endforelse
    </div>
</div>
@endsection
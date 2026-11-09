@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Dashboard Overview</h1>
        <p class="text-gray-500 mt-1">Ringkasan data portofolio dan proyek Anda.</p>
    </div>

    <!-- Statistik Utama -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Project Stats (Published/Draft gabungan) -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex flex-col">
            <span class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-2">Projects</span>
            <div class="text-4xl font-extrabold text-blue-600 mb-2">{{ $totalProjects }}</div>
            <div class="flex space-x-3 text-sm mt-auto">
                <span class="bg-green-100 text-green-700 px-2 py-1 rounded-md font-medium">{{ $publishedProjects }} Published</span>
                <span class="bg-gray-100 text-gray-700 px-2 py-1 rounded-md font-medium">{{ $draftProjects }} Draft</span>
            </div>
        </div>

        <x-stat-card label="Categories" :value="$totalCategories" color="text-emerald-600" />
        <x-stat-card label="Technologies" :value="$totalTechnologies" color="text-purple-600" />

        <!-- Skill Teratas -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <span class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-2">Skill Teratas</span>
            <div class="text-2xl font-bold text-orange-500 mt-2 truncate">{{ $topSkill }}</div>
        </div>
    </div>

    <!-- Tabel Kategori & Project Terbaru -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Project per Kategori -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden lg:col-span-1">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-100">
                <h3 class="font-bold text-gray-800">Project per Kategori</h3>
            </div>
            <ul class="divide-y divide-gray-100">
                @forelse($categories as $category)
                <li class="px-6 py-4 flex justify-between items-center hover:bg-gray-50 transition">
                    <span class="text-gray-700 font-medium">{{ $category->name }}</span>
                    <span class="bg-blue-100 text-blue-700 py-1 px-3 rounded-full text-xs font-bold">{{ $category->projects_count }}</span>
                </li>
                @empty
                <li class="px-6 py-4 text-gray-500 text-center text-sm">Belum ada kategori.</li>
                @endforelse
            </ul>
        </div>

        <!-- Project Terbaru -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden lg:col-span-2">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-100">
                <h3 class="font-bold text-gray-800">5 Project Terbaru</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="text-gray-400 text-sm border-b border-gray-100">
                            <th class="px-6 py-3 font-semibold">Nama Project</th>
                            <th class="px-6 py-3 font-semibold">Kategori</th>
                            <th class="px-6 py-3 font-semibold">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($latestProjects as $project)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 font-medium text-gray-800">{{ $project->title }}</td>
                            <td class="px-6 py-4 text-gray-600">{{ $project->category->name ?? '-' }}</td>
                            <td class="px-6 py-4">
                                @if($project->status === 'published')
                                <span class="bg-green-100 text-green-700 py-1 px-3 rounded-full text-xs font-semibold">Published</span>
                                @else
                                <span class="bg-gray-100 text-gray-700 py-1 px-3 rounded-full text-xs font-semibold">Draft</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-6 py-8 text-center text-gray-500">Belum ada project.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
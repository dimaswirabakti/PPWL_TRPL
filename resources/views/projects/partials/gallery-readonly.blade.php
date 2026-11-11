{{-- Galeri read-only untuk halaman show admin --}}
<div class="mt-6 border-t border-zinc-100 pt-4">
    <div class="flex items-baseline justify-between gap-4">
        <h2 class="text-sm font-medium text-zinc-700">Galeri</h2>
        <span class="text-xs tabular-nums text-zinc-400">{{ $project->images->count() }} / {{ \App\Http\Controllers\ProjectGalleryController::MAX_IMAGES }} gambar</span>
    </div>

    @if ($project->images->isEmpty())
    <p class="mt-3 text-sm text-zinc-500">Belum ada gambar galeri.</p>
    @else
    <ul class="mt-3 grid grid-cols-2 gap-3 sm:grid-cols-3">
        @foreach ($project->images as $image)
        <li>
            <img src="{{ $image->url }}" alt="{{ $image->caption ?? 'Gambar galeri '.$loop->iteration }}"
                class="aspect-[4/3] w-full rounded-md border border-zinc-200 object-cover">
            @if ($image->caption)
            <p class="mt-1 truncate text-xs text-zinc-500">{{ $image->caption }}</p>
            @endif
        </li>
        @endforeach
    </ul>
    @endif
</div>

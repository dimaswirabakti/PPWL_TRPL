{{-- Galeri project. Diletakkan DI LUAR form utama karena form tidak boleh bersarang. --}}
@php
$maxImages = \App\Http\Controllers\ProjectGalleryController::MAX_IMAGES;
$imageCount = $project->images->count();
$imageErrors = collect($errors->get('images'))->merge(collect($errors->get('images.*'))->flatten())->unique();
@endphp

<section id="galeri" class="mt-8 rounded-lg border border-zinc-200 bg-white p-6" aria-labelledby="judul-galeri">
    <div class="flex items-baseline justify-between gap-4">
        <h2 id="judul-galeri" class="text-lg font-semibold tracking-tight">Galeri</h2>
        <span class="text-sm tabular-nums {{ $imageCount >= $maxImages ? 'font-medium text-red-600' : 'text-zinc-500' }}">
            {{ $imageCount }} / {{ $maxImages }} gambar
        </span>
    </div>

    {{-- Form upload, hilang saat batas tercapai --}}
    @if ($imageCount < $maxImages)
    <form action="{{ route('projects.images.store', $project->id) }}" method="POST" enctype="multipart/form-data"
        class="mt-4 space-y-4 border-t border-zinc-100 pt-4">
        @csrf

        <div>
            <label for="images" class="mb-1.5 block text-sm font-medium text-zinc-700">Tambah gambar</label>
            <input type="file" id="images" name="images[]" multiple accept="image/jpeg,image/png,image/webp"
                class="block w-full text-sm text-zinc-600 file:mr-3 file:rounded-md file:border file:border-zinc-300 file:bg-white file:px-3 file:py-1.5 file:text-sm file:font-medium file:text-zinc-700 hover:file:bg-zinc-50">
            <p class="mt-1.5 text-xs text-zinc-400">
                Bisa pilih beberapa file. JPG, PNG, atau WEBP, maksimal 2 MB per file, lebar minimal 800 piksel.
                Sisa slot: {{ $maxImages - $imageCount }}.
            </p>
            @foreach ($imageErrors as $message)
            <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
            @endforeach
        </div>

        <div>
            <label for="gallery-caption" class="mb-1.5 block text-sm font-medium text-zinc-700">Caption <span class="font-normal text-zinc-400">(opsional, untuk semua gambar yang diunggah)</span></label>
            <input type="text" id="gallery-caption" name="caption" maxlength="150"
                value="{{ $errors->hasAny(['images', 'images.*', 'caption']) ? old('caption') : '' }}"
                class="block w-full rounded-md border bg-white px-3 py-2 text-sm outline-none transition focus:ring-2 focus:ring-zinc-900/10 {{ $errors->has('caption') ? 'border-red-400' : 'border-zinc-300 focus:border-zinc-400' }}">
            @error('caption')
            <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="rounded-md bg-zinc-900 px-4 py-2 text-sm font-medium text-white transition hover:bg-zinc-700">Unggah</button>
    </form>
    @else
    <p class="mt-4 border-t border-zinc-100 pt-4 text-sm text-zinc-500">
        Batas {{ $maxImages }} gambar tercapai. Hapus gambar lain untuk menambah yang baru.
    </p>
    @foreach ($imageErrors as $message)
    <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
    @endforeach
    @endif

    {{-- Daftar gambar: edit caption (PUT) dan hapus --}}
    @if ($project->images->isEmpty())
    <div class="mt-6 rounded-lg border border-dashed border-zinc-300 p-8 text-center text-sm text-zinc-500">
        Belum ada gambar galeri.
    </div>
    @else
    <ul class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-2">
        @foreach ($project->images as $image)
        @php($captionErrors = $errors->getBag("caption_{$image->id}"))
        <li class="rounded-md border border-zinc-200 p-3">
            <img src="{{ $image->url }}" alt="{{ $image->caption ?? 'Gambar galeri '.$loop->iteration }}"
                class="aspect-[4/3] w-full rounded border border-zinc-100 object-cover">

            <form action="{{ route('projects.images.update', [$project->id, $image->id]) }}" method="POST" class="mt-3 flex gap-2">
                @csrf
                @method('PUT')
                <label for="caption-{{ $image->id }}" class="sr-only">Caption gambar {{ $loop->iteration }}</label>
                <input type="text" id="caption-{{ $image->id }}" name="caption" maxlength="150" placeholder="Caption"
                    value="{{ $captionErrors->any() ? old('caption') : $image->caption }}"
                    class="block w-full min-w-0 rounded-md border bg-white px-2.5 py-1.5 text-sm outline-none transition focus:ring-2 focus:ring-zinc-900/10 {{ $captionErrors->has('caption') ? 'border-red-400' : 'border-zinc-300 focus:border-zinc-400' }}">
                <button type="submit" class="shrink-0 rounded-md border border-zinc-300 px-2.5 py-1.5 text-sm font-medium text-zinc-700 transition hover:bg-zinc-50">Simpan</button>
            </form>
            @if ($captionErrors->has('caption'))
            <p class="mt-1.5 text-xs text-red-600">{{ $captionErrors->first('caption') }}</p>
            @endif

            <form action="{{ route('projects.images.destroy', [$project->id, $image->id]) }}" method="POST" class="mt-2"
                data-confirm-delete="Hapus gambar ini dari galeri? File ikut terhapus.">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-sm text-red-600 hover:text-red-700">Hapus gambar</button>
            </form>
        </li>
        @endforeach
    </ul>
    @endif
</section>

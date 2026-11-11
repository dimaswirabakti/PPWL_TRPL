@php($project = $project ?? null)

<div>
    <label for="title" class="mb-1.5 block text-sm font-medium text-zinc-700">Judul</label>
    <input type="text" id="title" name="title"
        value="{{ old('title', $project->title ?? '') }}"
        class="block w-full rounded-md border bg-white px-3 py-2 text-sm outline-none transition focus:ring-2 focus:ring-zinc-900/10 {{ $errors->has('title') ? 'border-red-400' : 'border-zinc-300 focus:border-zinc-400' }}">
    @error('title')
    <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
    @enderror
</div>

<div>
    <label for="description" class="mb-1.5 block text-sm font-medium text-zinc-700">Deskripsi</label>
    <textarea id="description" name="description" rows="6"
        class="block w-full rounded-md border bg-white px-3 py-2 text-sm leading-relaxed outline-none transition focus:ring-2 focus:ring-zinc-900/10 {{ $errors->has('description') ? 'border-red-400' : 'border-zinc-300 focus:border-zinc-400' }}">{{ old('description', $project->description ?? '') }}</textarea>
    @error('description')
    <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
    @enderror
</div>

<div>
    <label for="status" class="mb-1.5 block text-sm font-medium text-zinc-700">Status</label>
    <select id="status" name="status"
        class="block w-full rounded-md border bg-white px-3 py-2 text-sm outline-none transition focus:ring-2 focus:ring-zinc-900/10 {{ $errors->has('status') ? 'border-red-400' : 'border-zinc-300 focus:border-zinc-400' }}">
        @foreach (['draft' => 'Draft', 'published' => 'Published'] as $value => $label)
        <option value="{{ $value }}" @selected(old('status', $project->status ?? 'draft') === $value)>{{ $label }}</option>
        @endforeach
    </select>
    @error('status')
    <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
    @enderror
</div>

<div>
    <label for="category_id" class="mb-1.5 block text-sm font-medium text-zinc-700">Kategori</label>
    <select id="category_id" name="category_id"
        class="block w-full rounded-md border bg-white px-3 py-2 text-sm outline-none transition focus:ring-2 focus:ring-zinc-900/10 {{ $errors->has('category_id') ? 'border-red-400' : 'border-zinc-300 focus:border-zinc-400' }}">
        <option value="">Tanpa kategori</option>
        @foreach ($categories as $category)
        <option value="{{ $category->id }}" @selected((string) old('category_id', $project->category_id ?? '') === (string) $category->id)>{{ $category->name }}</option>
        @endforeach
    </select>
    @error('category_id')
    <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
    @enderror
</div>

<div>
    <label for="thumbnail" class="mb-1.5 block text-sm font-medium text-zinc-700">Thumbnail</label>

    {{-- Pratinjau thumbnail yang tersimpan (hanya saat edit) --}}
    @if ($project?->thumbnail_url)
    <img src="{{ $project->thumbnail_url }}" alt="Thumbnail {{ $project->title }}"
        class="mb-3 aspect-[4/3] w-40 rounded-md border border-zinc-200 object-cover">
    @endif

    <input type="file" id="thumbnail" name="thumbnail" accept="image/jpeg,image/png,image/webp"
        class="block w-full text-sm text-zinc-600 file:mr-3 file:rounded-md file:border file:border-zinc-300 file:bg-white file:px-3 file:py-1.5 file:text-sm file:font-medium file:text-zinc-700 hover:file:bg-zinc-50">
    <p class="mt-1.5 text-xs text-zinc-400">
        JPG, PNG, atau WEBP. Maksimal 2 MB, minimal 400 x 300 piksel.
        @if ($project?->thumbnail)
        Kosongkan jika tidak ingin mengganti.
        @endif
    </p>
    @error('thumbnail')
    <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
    @enderror
</div>

<fieldset>
    <legend class="mb-1.5 block text-sm font-medium text-zinc-700">Skill</legend>
    @php($selectedSkills = array_map('intval', (array) old('skills', $project?->skills->pluck('id')->all() ?? [])))
    <div class="flex flex-wrap gap-x-5 gap-y-2">
        @forelse ($skills as $skill)
        <label class="inline-flex items-center gap-2 text-sm text-zinc-700">
            <input type="checkbox" name="skills[]" value="{{ $skill->id }}" @checked(in_array($skill->id, $selectedSkills, true))
                class="size-4 accent-zinc-900">
            {{ $skill->name }}
        </label>
        @empty
        <p class="text-sm text-zinc-500">Belum ada skill.</p>
        @endforelse
    </div>
    @if ($errors->has('skills') || $errors->has('skills.*'))
    <p class="mt-1.5 text-xs text-red-600">{{ $errors->first('skills') ?: $errors->first('skills.*') }}</p>
    @endif
</fieldset>

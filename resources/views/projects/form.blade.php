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
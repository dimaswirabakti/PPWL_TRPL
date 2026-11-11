<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Project;
use App\Models\Skill;
use App\Models\Technology;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProjectController extends Controller
{
    public function index(Request $request): View
    {
        $filter = $request->query('filter'); // '', 'published', atau 'draft'

        // with() + withCount() mencegah N+1 saat menampilkan kategori dan jumlah gambar
        $query = Project::query()->with('category')->withCount('images');

        if ($filter === 'published') {
            $query->published();            // pakai scope published()
        } elseif ($filter === 'draft') {
            $query->where('status', 'draft');
        }

        $projects = $query->latest()->get();
        $trashedCount = Project::onlyTrashed()->count();

        return view('projects.index', compact('projects', 'filter', 'trashedCount'));
    }

    public function create(): View
    {
        $categories = Category::orderBy('name')->get();
        $skills = Skill::orderBy('name')->get();
        $technologies = Technology::orderBy('name')->get();

        return view('projects.create', compact('categories', 'skills', 'technologies'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validatedData($request);
        $validated = $this->prepareProjectAttributes($request, $validated);

        // Slug hanya dibuat sekali saat project baru disimpan
        $validated['slug'] = $this->uniqueSlug($validated['title']);

        $project = Project::create($validated);
        $project->skills()->sync($request->input('skills', []));
        $project->technologies()->sync($request->input('technologies', []));

        return redirect()->route('projects.index')
            ->with('success', 'Proyek berhasil ditambahkan.');
    }

    public function show(string $id): View
    {
        $project = Project::with(['category', 'images', 'skills', 'technologies'])->findOrFail($id);

        return view('projects.show', compact('project'));
    }

    public function edit(string $id): View
    {
        $project = Project::with(['images', 'skills', 'technologies'])->findOrFail($id);
        $categories = Category::orderBy('name')->get();
        $skills = Skill::orderBy('name')->get();
        $technologies = Technology::orderBy('name')->get();

        return view('projects.edit', compact('project', 'categories', 'skills', 'technologies'));
    }

    public function update(Request $request, string $id): RedirectResponse
    {
        $validated = $this->validatedData($request, $id);

        $project = Project::findOrFail($id);
        $oldThumbnail = $project->thumbnail;

        // Slug tidak ikut diubah saat update agar URL publik tetap sama
        $validated = $this->prepareProjectAttributes($request, $validated);
        $project->update($validated);
        $project->skills()->sync($request->input('skills', []));
        $project->technologies()->sync($request->input('technologies', []));

        // File lama baru dihapus SETELAH file baru tersimpan dan data diperbarui
        if (isset($validated['thumbnail']) && $oldThumbnail && $oldThumbnail !== $validated['thumbnail']) {
            Storage::disk('public')->delete($oldThumbnail);
        }

        return redirect()->route('projects.index')
            ->with('success', 'Proyek berhasil diperbarui.');
    }

    public function destroy(string $id): RedirectResponse
    {
        $project = Project::findOrFail($id);
        $project->delete(); // soft delete: mengisi kolom deleted_at, file tetap disimpan

        return redirect()->route('projects.index')
            ->with('success', 'Proyek dipindahkan ke Trash.');
    }

    /**
     * Halaman Trash: hanya menampilkan data yang sudah di-soft delete.
     */
    public function trash(): View
    {
        $projects = Project::onlyTrashed()->latest('deleted_at')->get();

        return view('projects.trash', compact('projects'));
    }

    /**
     * Pulihkan data dari Trash.
     */
    public function restore(string $id): RedirectResponse
    {
        $project = Project::onlyTrashed()->findOrFail($id);
        $project->restore();

        return redirect()->route('projects.trash')
            ->with('success', 'Proyek berhasil dipulihkan.');
    }

    /**
     * Hapus permanen (tidak bisa dipulihkan lagi).
     * File dihapus lebih dulu, karena cascade ikut menghapus baris project_images.
     */
    public function forceDelete(string $id): RedirectResponse
    {
        $project = Project::onlyTrashed()->with('images')->findOrFail($id);

        $paths = $project->images->pluck('image')
            ->push($project->thumbnail)
            ->filter()
            ->all();

        Storage::disk('public')->delete($paths);
        Storage::disk('public')->deleteDirectory("gallery/{$project->id}");

        $project->forceDelete();

        return redirect()->route('projects.trash')
            ->with('success', 'Proyek dihapus permanen.');
    }

    /**
     * Aturan validasi dipakai bersama oleh store() dan update().
     * $id diisi saat update agar judul milik project itu sendiri tidak dianggap duplikat.
     * Rule::unique tidak mengabaikan baris soft-deleted, jadi judul di Trash tetap terhitung.
     *
     * @return array<string, mixed>
     */
    private function validatedData(Request $request, ?string $id = null): array
    {
        return $request->validate([
            'title' => ['required', 'min:5', 'max:255', Rule::unique('projects', 'title')->ignore($id)],
            'short_description' => 'nullable|max:255',
            'description' => 'required|min:10',
            'status' => 'required|in:draft,published',
            'category_id' => 'nullable|exists:categories,id',
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048|dimensions:min_width=400,min_height=300',
            'skills' => 'nullable|array',
            'skills.*' => 'exists:skills,id',
            'project_date' => 'nullable|date',
            'project_url' => 'nullable|url|max:255',
            'repository_url' => 'nullable|url|max:255',
            'technologies' => 'nullable|array',
            'technologies.*' => 'exists:technologies,id',
        ], [
            'title.required' => 'Judul wajib diisi.',
            'title.min' => 'Judul minimal 5 karakter.',
            'title.max' => 'Judul maksimal 255 karakter.',
            'title.unique' => 'Judul sudah dipakai project lain.',
            'short_description.max' => 'Deskripsi singkat maksimal 255 karakter.',
            'description.required' => 'Deskripsi wajib diisi.',
            'description.min' => 'Deskripsi minimal 10 karakter.',
            'status.required' => 'Status wajib dipilih.',
            'status.in' => 'Status harus draft atau published.',
            'category_id.exists' => 'Kategori yang dipilih tidak ditemukan.',
            'thumbnail.image' => 'Thumbnail harus berupa gambar.',
            'thumbnail.mimes' => 'Thumbnail harus berformat jpg, jpeg, png, atau webp.',
            'thumbnail.max' => 'Ukuran thumbnail maksimal 2 MB.',
            'thumbnail.dimensions' => 'Thumbnail minimal berukuran 400 x 300 piksel.',
            'skills.array' => 'Format pilihan skill tidak valid.',
            'skills.*.exists' => 'Skill yang dipilih tidak ditemukan.',
            'project_date.date' => 'Tanggal project tidak valid.',
            'project_url.url' => 'URL demo harus berupa URL yang valid, contoh https://contoh.com.',
            'project_url.max' => 'URL demo maksimal 255 karakter.',
            'repository_url.url' => 'URL repositori harus berupa URL yang valid, contoh https://github.com/nama/repo.',
            'repository_url.max' => 'URL repositori maksimal 255 karakter.',
            'technologies.array' => 'Format pilihan technology tidak valid.',
            'technologies.*.exists' => 'Technology yang dipilih tidak ditemukan.',
        ]);
    }

    /**
     * Ganti objek UploadedFile dengan string path hasil simpan.
     * Jika tidak ada file, key thumbnail dibuang agar thumbnail lama tidak terhapus.
     * Key skills dan technologies juga dibuang karena disimpan lewat sync(), bukan kolom tabel projects.
     * Checkbox is_featured yang tidak dicentang tidak terkirim, jadi dibaca dengan boolean() (default false).
     *
     * @param  array<string, mixed>  $validated
     * @return array<string, mixed>
     */
    private function prepareProjectAttributes(Request $request, array $validated): array
    {
        unset($validated['thumbnail'], $validated['skills'], $validated['technologies']);

        $validated['is_featured'] = $request->boolean('is_featured');

        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $request->file('thumbnail')->store('thumbnails', 'public');
        }

        return $validated;
    }

    /**
     * Slug dari judul. Jika sudah dipakai (termasuk project di Trash), diberi sufiks -2, -3, dst.
     */
    private function uniqueSlug(string $title): string
    {
        $base = Str::slug($title) ?: 'project';
        $slug = $base;
        $suffix = 2;

        while (Project::withTrashed()->where('slug', $slug)->exists()) {
            $slug = $base.'-'.$suffix++;
        }

        return $slug;
    }
}

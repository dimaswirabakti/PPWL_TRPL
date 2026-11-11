<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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

        return view('projects.create', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validatedData($request);
        $validated = $this->withUploadedThumbnail($request, $validated);

        Project::create($validated);

        return redirect()->route('projects.index')
            ->with('success', 'Proyek berhasil ditambahkan.');
    }

    public function show(string $id): View
    {
        $project = Project::with(['category', 'images'])->findOrFail($id);

        return view('projects.show', compact('project'));
    }

    public function edit(string $id): View
    {
        $project = Project::with('images')->findOrFail($id);
        $categories = Category::orderBy('name')->get();

        return view('projects.edit', compact('project', 'categories'));
    }

    public function update(Request $request, string $id): RedirectResponse
    {
        $validated = $this->validatedData($request);

        $project = Project::findOrFail($id);
        $oldThumbnail = $project->thumbnail;

        $validated = $this->withUploadedThumbnail($request, $validated);
        $project->update($validated);

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
     */
    private function validatedData(Request $request): array
    {
        return $request->validate([
            'title' => 'required|min:5|max:255',
            'description' => 'required|min:10',
            'status' => 'required|in:draft,published',
            'category_id' => 'nullable|exists:categories,id',
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048|dimensions:min_width=400,min_height=300',
        ], [
            'title.required' => 'Judul wajib diisi.',
            'title.min' => 'Judul minimal 5 karakter.',
            'title.max' => 'Judul maksimal 255 karakter.',
            'description.required' => 'Deskripsi wajib diisi.',
            'description.min' => 'Deskripsi minimal 10 karakter.',
            'status.required' => 'Status wajib dipilih.',
            'status.in' => 'Status harus draft atau published.',
            'category_id.exists' => 'Kategori yang dipilih tidak ditemukan.',
            'thumbnail.image' => 'Thumbnail harus berupa gambar.',
            'thumbnail.mimes' => 'Thumbnail harus berformat jpg, jpeg, png, atau webp.',
            'thumbnail.max' => 'Ukuran thumbnail maksimal 2 MB.',
            'thumbnail.dimensions' => 'Thumbnail minimal berukuran 400 x 300 piksel.',
        ]);
    }

    /**
     * Ganti objek UploadedFile dengan string path hasil simpan.
     * Jika tidak ada file, key thumbnail dibuang agar thumbnail lama tidak terhapus.
     *
     * @param  array<string, mixed>  $validated
     * @return array<string, mixed>
     */
    private function withUploadedThumbnail(Request $request, array $validated): array
    {
        unset($validated['thumbnail']);

        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $request->file('thumbnail')->store('thumbnails', 'public');
        }

        return $validated;
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->query('filter'); // '', 'published', atau 'draft'

        $query = Project::query();

        if ($filter === 'published') {
            $query->published();            // pakai scope published()
        } elseif ($filter === 'draft') {
            $query->where('status', 'draft');
        }

        $projects     = $query->latest()->get();
        $trashedCount = Project::onlyTrashed()->count();

        return view('projects.index', compact('projects', 'filter', 'trashedCount'));
    }

    public function create()
    {
        return view('projects.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|max:255',
            'category_id' => 'required|exists:categories,id', // pastikan kamu melempar data category ke view create
            'thumbnail'   => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048|dimensions:min_width=400,min_height=300',
        ]);

        if ($request->hasFile('thumbnail')) {
            // Simpan ke disk 'public'
            $validated['thumbnail'] = $request->file('thumbnail')->store('projects/thumbnails', 'public');
        }

        Project::create($validated);

        return redirect()->route('post.projects.index')->with('success', 'Project berhasil ditambahkan.');
    }

    public function show(string $id)
    {
        $project = Project::findOrFail($id);
        return view('projects.show', compact('project'));
    }

    public function edit(string $id)
    {
        $project = Project::findOrFail($id);
        return view('projects.edit', compact('project'));
    }

    public function update(Request $request, string $id)
    {
        $validated = $this->validatedData($request);

        $project = Project::findOrFail($id);
        $project->update($validated);

        return redirect()->route('projects.index')
            ->with('success', 'Proyek berhasil diperbarui.');
    }

    public function destroy(Project $project)
    {
        // Hapus file fisik thumbnail
        if ($project->thumbnail) {
            Storage::disk('public')->delete($project->thumbnail);
        }

        // Hapus semua file fisik gambar gallery (jika ada)
        foreach ($project->images as $image) {
            Storage::disk('public')->delete($image->image);
        }

        $project->delete(); // Karena pakai soft deletes, ini mungkin tidak menghapus baris dari tabel project_images otomatis kecuali kamu atur cascade on soft delete

        return redirect()->route('post.projects.index')->with('success', 'Project dihapus.');
    }

    /**
     * Halaman Trash: hanya menampilkan data yang sudah di-soft delete.
     */
    public function trash()
    {
        $projects = Project::onlyTrashed()->latest('deleted_at')->get();
        return view('projects.trash', compact('projects'));
    }

    /**
     * Pulihkan data dari Trash.
     */
    public function restore(string $id)
    {
        $project = Project::onlyTrashed()->findOrFail($id);
        $project->restore();

        return redirect()->route('projects.trash')
            ->with('success', 'Proyek berhasil dipulihkan.');
    }

    /**
     * Hapus permanen (tidak bisa dipulihkan lagi).
     */
    public function forceDelete(string $id)
    {
        $project = Project::onlyTrashed()->findOrFail($id);
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
            'title'       => 'required|min:5|max:255',
            'description' => 'required|min:10',
            'status'      => 'required|in:draft,published',
        ], [
            'title.required'       => 'Judul wajib diisi.',
            'title.min'            => 'Judul minimal 5 karakter.',
            'title.max'            => 'Judul maksimal 255 karakter.',
            'description.required' => 'Deskripsi wajib diisi.',
            'description.min'      => 'Deskripsi minimal 10 karakter.',
            'status.required'      => 'Status wajib dipilih.',
            'status.in'            => 'Status harus draft atau published.',
        ]);
    }
}

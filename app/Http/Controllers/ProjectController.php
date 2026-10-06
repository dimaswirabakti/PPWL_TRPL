<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::latest()->get();
        return view('projects.index', compact('projects'));
    }

    public function create()
    {
        return view('projects.create');
    }

    public function store(Request $request)
    {
        $validated = $this->validatedData($request);

        Project::create($validated);

        return redirect()->route('projects.index')
            ->with('success', 'Proyek berhasil ditambahkan.');
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

    public function destroy(string $id)
    {
        $project = Project::findOrFail($id);
        $project->delete();

        return redirect()->route('projects.index')
            ->with('success', 'Proyek berhasil dihapus.');
    }

    /**
     * Aturan validasi dipakai bersama oleh store() dan update().
     */
    private function validatedData(Request $request): array
    {
        return $request->validate([
            'title'       => 'required|min:5|max:255',
            'description' => 'required|min:10',
        ], [
            'title.required'       => 'Judul wajib diisi.',
            'title.min'            => 'Judul minimal 5 karakter.',
            'title.max'            => 'Judul maksimal 255 karakter.',
            'description.required' => 'Deskripsi wajib diisi.',
            'description.min'      => 'Deskripsi minimal 10 karakter.',
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::with('category')->latest()->get();
        return view('posts.projects.index', compact('projects'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('posts.projects.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|max:255',
            'category_id' => 'required|exists:categories,id',
            'status'      => 'required|in:published,draft',
            'thumbnail'   => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048|dimensions:min_width=400,min_height=300',
        ]);

        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $request->file('thumbnail')->store('projects/thumbnails', 'public');
        }

        Project::create($validated);
        return redirect()->route('post.projects.index')->with('success', 'Project berhasil ditambahkan.');
    }

    public function edit(Project $project)
    {
        $categories = Category::all();
        return view('posts.projects.edit', compact('project', 'categories'));
    }

    public function update(Request $request, Project $project)
    {
        $validated = $request->validate([
            'name'        => 'required|max:255',
            'category_id' => 'required|exists:categories,id',
            'status'      => 'required|in:published,draft',
            'thumbnail'   => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048|dimensions:min_width=400,min_height=300',
        ]);

        if ($request->hasFile('thumbnail')) {
            // Hapus file lama jika ada
            if ($project->thumbnail) {
                Storage::disk('public')->delete($project->thumbnail);
            }
            $validated['thumbnail'] = $request->file('thumbnail')->store('projects/thumbnails', 'public');
        }

        $project->update($validated);
        return redirect()->route('post.projects.index')->with('success', 'Project berhasil diperbarui.');
    }

    public function destroy(Project $project)
    {
        if ($project->thumbnail) {
            Storage::disk('public')->delete($project->thumbnail);
        }
        foreach ($project->images as $image) {
            Storage::disk('public')->delete($image->image);
        }
        $project->delete();
        return redirect()->route('post.projects.index')->with('success', 'Project dihapus.');
    }
}

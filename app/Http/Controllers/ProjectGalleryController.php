<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProjectGalleryController extends Controller
{
    public function store(Request $request, Project $project)
    {
        $request->validate([
            'images'   => 'required|array|max:10',
            'images.*' => 'image|mimes:jpg,jpeg,png,webp|max:4096',
        ]);

        foreach ($request->file('images') as $file) {
            $project->images()->create([
                'image' => $file->store('projects/gallery', 'public'),
            ]);
        }

        return back()->with('success', 'Gambar gallery berhasil ditambahkan.');
    }

    public function destroy(Project $project, ProjectImage $image)
    {
        // Pastikan gambar yang mau dihapus benar milik project ini
        abort_if($image->project_id !== $project->id, 404);

        // Hapus file fisiknya
        Storage::disk('public')->delete($image->image);

        // Hapus dari database
        $image->delete();

        return back()->with('success', 'Gambar gallery dihapus.');
    }
}

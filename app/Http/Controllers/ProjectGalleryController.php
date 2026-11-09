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
            'images'   => 'required|array',
            // Validasi: gambar minimal lebar 800px (ditolak jika 500px)
            'images.*' => 'image|mimes:jpg,jpeg,png,webp|max:4096|dimensions:min_width=800',
        ], [
            'images.*.dimensions' => 'Setiap gambar harus memiliki lebar minimal 800 px.'
        ]);

        $currentImagesCount = $project->images()->count();
        $newImagesCount = count($request->file('images'));

        // Indikator: upload gambar ke-11 ditolak
        if (($currentImagesCount + $newImagesCount) > 10) {
            return back()->withErrors(['images' => 'Gagal! Maksimal total 10 gambar per project. Saat ini sudah ada ' . $currentImagesCount . ' gambar.']);
        }

        foreach ($request->file('images') as $file) {
            $project->images()->create([
                'image' => $file->store('projects/gallery', 'public'),
            ]);
        }

        return back()->with('success', 'Gambar gallery berhasil ditambahkan.');
    }

    public function update(Request $request, Project $project, ProjectImage $image)
    {
        abort_if($image->project_id !== $project->id, 404);

        $validated = $request->validate([
            'caption' => 'nullable|string|max:255',
        ]);

        $image->update($validated);

        return back()->with('success', 'Caption gambar berhasil diperbarui.');
    }

    public function destroy(Project $project, ProjectImage $image)
    {
        abort_if($image->project_id !== $project->id, 404);

        Storage::disk('public')->delete($image->image);
        $image->delete();

        return back()->with('success', 'Gambar gallery dihapus.');
    }
}

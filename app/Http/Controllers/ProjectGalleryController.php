<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectImage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class ProjectGalleryController extends Controller
{
    // Batas total gambar galeri per project (yang sudah ada + yang baru)
    public const MAX_IMAGES = 10;

    /**
     * Upload satu atau beberapa gambar sekaligus ke galeri project.
     */
    public function store(Request $request, Project $project): RedirectResponse
    {
        $request->validate([
            'images' => 'required|array|min:1',
            'images.*' => 'image|mimes:jpg,jpeg,png,webp|max:2048|dimensions:min_width=800',
            'caption' => 'nullable|string|max:150',
        ], [
            'images.required' => 'Pilih minimal satu gambar.',
            'images.array' => 'Format unggahan gambar tidak valid.',
            'images.min' => 'Pilih minimal satu gambar.',
            'images.*.image' => 'Setiap file harus berupa gambar.',
            'images.*.mimes' => 'Gambar harus berformat jpg, jpeg, png, atau webp.',
            'images.*.max' => 'Ukuran setiap gambar maksimal 2 MB.',
            'images.*.dimensions' => 'Lebar gambar galeri minimal 800 piksel.',
            'caption.max' => 'Caption maksimal 150 karakter.',
        ]);

        $files = $request->file('images');
        $existingCount = $project->images()->count();

        // Cek batas total SEBELUM menyimpan file apa pun
        if ($existingCount + count($files) > self::MAX_IMAGES) {
            $remaining = max(self::MAX_IMAGES - $existingCount, 0);

            throw ValidationException::withMessages([
                'images' => 'Maksimal '.self::MAX_IMAGES." gambar per project. Sisa slot: {$remaining}.",
            ]);
        }

        $nextOrder = (int) $project->images()->max('sort_order') + 1;

        foreach ($files as $file) {
            $project->images()->create([
                'image' => $file->store("gallery/{$project->id}", 'public'),
                'caption' => $request->input('caption'),
                'sort_order' => $nextOrder++,
            ]);
        }

        return redirect()->route('projects.edit', $project->id)
            ->with('success', count($files).' gambar ditambahkan ke galeri.');
    }

    /**
     * Ubah caption satu gambar.
     * Error disimpan di bag khusus per gambar agar tampil di form yang tepat.
     */
    public function update(Request $request, Project $project, ProjectImage $image): RedirectResponse
    {
        abort_if($image->project_id !== $project->id, 404);

        $validated = $request->validateWithBag("caption_{$image->id}", [
            'caption' => 'nullable|string|max:150',
        ], [
            'caption.string' => 'Caption harus berupa teks.',
            'caption.max' => 'Caption maksimal 150 karakter.',
        ]);

        $image->update(['caption' => $validated['caption'] ?? null]);

        return redirect()->route('projects.edit', $project->id)
            ->with('success', 'Caption gambar diperbarui.');
    }

    /**
     * Hapus satu gambar: file dulu, lalu barisnya.
     */
    public function destroy(Project $project, ProjectImage $image): RedirectResponse
    {
        abort_if($image->project_id !== $project->id, 404);

        Storage::disk('public')->delete($image->image);
        $image->delete();

        return redirect()->route('projects.edit', $project->id)
            ->with('success', 'Gambar dihapus dari galeri.');
    }
}

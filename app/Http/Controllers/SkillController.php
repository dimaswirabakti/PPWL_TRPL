<?php

namespace App\Http\Controllers;

use App\Models\Skill;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class SkillController extends Controller
{
    /**
     * Daftar skill beserta jumlah project yang memakainya.
     */
    public function index(): View
    {
        $skills = Skill::withCount('projects')->orderBy('name')->get();

        return view('skills.index', compact('skills'));
    }

    public function edit(Skill $skill): View
    {
        return view('skills.edit', compact('skill'));
    }

    /**
     * Ganti nama skill. Nama harus unik, kecuali nama milik skill ini sendiri.
     */
    public function update(Request $request, Skill $skill): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:50', Rule::unique('skills', 'name')->ignore($skill->id)],
        ], [
            'name.required' => 'Nama skill wajib diisi.',
            'name.string' => 'Nama skill harus berupa teks.',
            'name.max' => 'Nama skill maksimal 50 karakter.',
            'name.unique' => 'Nama skill sudah dipakai.',
        ]);

        $skill->update($validated);

        return redirect()->route('skills.index')
            ->with('success', 'Skill berhasil diperbarui.');
    }
}

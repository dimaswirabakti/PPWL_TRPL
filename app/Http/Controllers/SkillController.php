<?php

namespace App\Http\Controllers;

use App\Models\Skill;
use Illuminate\Http\Request;

class SkillController extends Controller
{
    public function edit(Skill $skill)
    {
        return view('posts.skills.edit', compact('skill'));
    }

    public function update(Request $request, Skill $skill)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:skills,name,' . $skill->id,
        ]);

        $skill->update($validated);

        // Diasumsikan kembali ke dashboard atau index skill
        return redirect()->route('post.dashboard.index')->with('success', 'Skill berhasil diperbarui.');
    }
}

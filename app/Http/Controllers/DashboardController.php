<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Project;
use App\Models\Skill;
use App\Models\Technology;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Jika tabel skill belum direlasikan, kita ambil skill terbaru saja sebagai contoh "Teratas"
        $topSkill = Skill::latest()->first();

        return view('posts.dashboard', [
            'totalProjects'     => Project::count(),
            'publishedProjects' => Project::where('status', 'published')->count(),
            'draftProjects'     => Project::where('status', 'draft')->count(),
            'totalCategories'   => Category::count(),
            'totalTechnologies' => Technology::count(),
            'topSkill'          => $topSkill ? $topSkill->name : 'Belum ada data',

            // withCount('projects') untuk mengambil jumlah project per kategori
            'categories'        => Category::withCount('projects')->get(),
            'latestProjects'    => Project::with('category')->latest()->take(5)->get(),
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Project;
use App\Models\Skill;
use App\Models\Technology;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        return view('dashboard', [
            'totalProjects' => Project::count(),
            'publishedProjects' => Project::published()->count(),
            'draftProjects' => Project::where('status', 'draft')->count(),
            'totalCategories' => Category::count(),
            'totalTechnologies' => Technology::count(),
            'totalSkills' => Skill::count(),
            'latestProjects' => Project::with('category')->latest()->take(5)->get(),
            // Latihan: 5 skill yang paling banyak dipakai project
            'topSkills' => Skill::withCount('projects')
                ->orderByDesc('projects_count')
                ->orderBy('name')
                ->take(5)
                ->get(),
            // Latihan: jumlah project di setiap kategori
            'categories' => Category::withCount('projects')->orderBy('name')->get(),
        ]);
    }
}

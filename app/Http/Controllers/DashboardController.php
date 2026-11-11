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
            'totalCategories' => Category::count(),
            'totalTechnologies' => Technology::count(),
            'totalSkills' => Skill::count(),
            'latestProjects' => Project::with('category')->latest()->take(5)->get(),
        ]);
    }
}

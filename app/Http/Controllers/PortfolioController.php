<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\View\View;

class PortfolioController extends Controller
{
    /**
     * Halaman publik: hanya project berstatus published.
     */
    public function index(): View
    {
        $projects = Project::published()
            ->with('category')
            ->latest()
            ->get();

        return view('portfolio.index', compact('projects'));
    }

    /**
     * Detail publik. Project draft dianggap tidak ada (404).
     */
    public function show(Project $project): View
    {
        abort_if($project->status !== 'published', 404);

        $project->load(['category', 'images', 'skills']);

        return view('portfolio.show', compact('project'));
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class PortfolioController extends Controller
{
    public function projects()
    {
        // Menampilkan semua project
        return view('projects', ['projects' => Project::latest()->get()]);
    }

    public function show(Project $project)
    {
        // Memuat relasi category dan images agar tidak terjadi N+1 query problem
        $project->load(['category', 'images']);
        return view('portofolio.project', compact('project'));
    }
}

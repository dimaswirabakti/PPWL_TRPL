<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\View\View;

class Pertemuan8Controller extends Controller
{
    // Latihan 4: pilihan jumlah data per halaman
    private const PER_PAGE_OPTIONS = [5, 10, 25];

    private const PER_PAGE_DEFAULT = 10;

    public function index(Request $request): View
    {
        $search = trim((string) $request->query('q', ''));
        $status = (string) $request->query('status', '');
        $perPage = (int) $request->query('per_page', self::PER_PAGE_DEFAULT);

        if (! in_array($perPage, self::PER_PAGE_OPTIONS, true)) {
            $perPage = self::PER_PAGE_DEFAULT;
        }

        $projects = Project::query()
            ->when($search !== '', function ($query) use ($search) {
                // % dan _ di-escape agar tidak diperlakukan sebagai wildcard LIKE.
                $keyword = addcslashes($search, '%_\\');

                $query->where('title', 'like', "%{$keyword}%");
            })
            ->when($status === 'published', fn($query) => $query->published())
            ->when($status === 'draft', fn($query) => $query->where('status', 'draft'))
            // id dipakai sebagai pemecah seri: tanpa ini, data dengan created_at
            // identik bisa berpindah-pindah urutan antar halaman.
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->paginate($perPage)
            // withQueryString() membuat q, status, dan per_page ikut terbawa
            // pada tautan halaman 2, 3, dan seterusnya (Latihan 3).
            ->withQueryString();

        $perPageOptions = self::PER_PAGE_OPTIONS;

        return view('pertemuan8.index', compact('projects', 'search', 'status', 'perPage', 'perPageOptions'));
    }
}

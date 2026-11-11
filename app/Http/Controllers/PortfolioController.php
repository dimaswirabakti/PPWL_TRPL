<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Project;
use App\Models\Technology;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PortfolioController extends Controller
{
    // Pilihan urutan yang diizinkan (whitelist). Nilai lain dianggap 'terbaru'.
    public const SORT_OPTIONS = [
        'terbaru' => 'Terbaru',
        'terlama' => 'Terlama',
        'judul' => 'Judul A-Z',
        'tanggal' => 'Tanggal project',
    ];

    // Alias lama yang tetap diterima
    private const SORT_ALIASES = ['az' => 'judul'];

    private const PER_PAGE = 9;

    /**
     * Halaman publik: hanya project berstatus published.
     * Mendukung ?search=, ?category=slug, ?technology=slug, ?sort=, dan ?page=.
     */
    public function index(Request $request): View
    {
        $search = $this->queryText($request, 'search');
        $category = $this->queryText($request, 'category');
        $technology = $this->queryText($request, 'technology');
        $sort = $this->normalizeSort($request->query('sort'));

        $projects = Project::published()
            ->with('category', 'technologies')
            ->when($request->filled('search'), function (Builder $query) use ($search) {
                // % dan _ di-escape agar tidak diperlakukan sebagai wildcard LIKE
                $keyword = addcslashes($search, '%_\\');

                // Semua orWhere dibungkus SATU grup agar filter published tetap berlaku
                $query->where(function (Builder $group) use ($keyword) {
                    $group->where('title', 'like', "%{$keyword}%")
                        ->orWhere('short_description', 'like', "%{$keyword}%")
                        ->orWhere('description', 'like', "%{$keyword}%");
                });
            })
            ->when($request->filled('category'), function (Builder $query) use ($category) {
                $query->whereHas('category', fn (Builder $categoryQuery) => $categoryQuery->where('slug', $category));
            })
            // Latihan: filter technology, bisa digabung dengan search dan category
            ->when($request->filled('technology'), function (Builder $query) use ($technology) {
                $query->whereHas('technologies', fn (Builder $technologyQuery) => $technologyQuery->where('slug', $technology));
            })
            ->tap(fn (Builder $query) => $this->applySort($query, $sort))
            ->paginate(self::PER_PAGE)
            // search, category, technology, dan sort ikut terbawa ke link halaman berikutnya
            ->withQueryString();

        $categories = Category::orderBy('name')->get();
        $technologies = Technology::orderBy('name')->get();
        $sortOptions = self::SORT_OPTIONS;

        // Tombol Reset hanya muncul jika ada filter yang sedang aktif
        $hasActiveFilter = $search !== '' || $category !== '' || $technology !== '';

        // Nilai filter dikirim ke view agar nilai lama tetap tampil di form
        return view('portfolio.index', compact(
            'projects',
            'categories',
            'technologies',
            'search',
            'category',
            'technology',
            'sort',
            'sortOptions',
            'hasActiveFilter',
        ));
    }

    /**
     * Detail publik lewat slug. Project draft dianggap tidak ada (404).
     * Project soft-deleted otomatis 404 karena tidak ikut route model binding.
     */
    public function show(Project $project): View
    {
        abort_if($project->status !== 'published', 404);

        $project->load(['category', 'technologies', 'images', 'skills']);

        return view('portfolio.show', compact('project'));
    }

    /**
     * Ambil parameter URL sebagai teks yang sudah di-trim.
     * Nilai berbentuk array (misalnya ?search[]=x) dianggap kosong agar tidak error.
     */
    private function queryText(Request $request, string $key): string
    {
        $value = $request->query($key);

        return is_string($value) ? trim($value) : '';
    }

    /**
     * Ubah nilai sort dari URL menjadi salah satu kunci SORT_OPTIONS.
     */
    private function normalizeSort(mixed $sort): string
    {
        $sort = is_string($sort) ? (self::SORT_ALIASES[$sort] ?? $sort) : '';

        return array_key_exists($sort, self::SORT_OPTIONS) ? $sort : 'terbaru';
    }

    /**
     * Terapkan urutan. id dipakai sebagai pembeda agar urutan stabil antar halaman.
     */
    private function applySort(Builder $query, string $sort): void
    {
        match ($sort) {
            'terlama' => $query->oldest()->orderBy('id'),
            'judul' => $query->orderBy('title')->orderBy('id'),
            // Latihan: tanggal project terbaru dulu, baris tanpa tanggal di akhir
            'tanggal' => $query->orderByRaw('project_date IS NULL')
                ->orderByDesc('project_date')
                ->latest()
                ->orderByDesc('id'),
            default => $query->latest()->orderByDesc('id'),
        };
    }
}

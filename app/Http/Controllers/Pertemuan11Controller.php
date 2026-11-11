<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Project;
use App\Models\Technology;
use Illuminate\Routing\Route as RouteDefinition;
use Illuminate\Support\Facades\Route;
use Illuminate\View\View;

class Pertemuan11Controller extends Controller
{
    // Route khusus Pertemuan 11 yang ditampilkan pada tabel
    private const ROUTE_NAMES = [
        'pertemuan-11',
        'portfolio.index',
        'portfolio.show',
        'projects.index',
        'projects.create',
        'projects.store',
        'projects.edit',
        'projects.update',
    ];

    public function index(): View
    {
        $routes = collect(self::ROUTE_NAMES)
            ->map(fn (string $name) => Route::getRoutes()->getByName($name))
            ->filter()
            ->map(function (RouteDefinition $route): array {
                $middleware = array_values(array_filter(
                    $route->gatherMiddleware(),
                    fn ($item) => is_string($item) && $item !== 'web',
                ));
                $methods = array_values(array_diff($route->methods(), ['HEAD']));

                return [
                    'methods' => implode('|', $methods),
                    'uri' => '/'.ltrim($this->uriWithBindingFields($route), '/'),
                    'name' => $route->getName(),
                    'locked' => in_array('auth', $middleware, true),
                    'access' => in_array('auth', $middleware, true) ? 'Harus login' : 'Publik',
                    // Hanya GET tanpa parameter yang bisa langsung diklik
                    'linkable' => $methods === ['GET'] && ! str_contains($route->uri(), '{'),
                ];
            })
            ->values();

        return view('pertemuan11.index', [
            'routes' => $routes,
            'examples' => $this->examples(),
        ]);
    }

    /**
     * Tampilkan kolom binding pada URI, misalnya portfolio/{project} menjadi portfolio/{project:slug}.
     */
    private function uriWithBindingFields(RouteDefinition $route): string
    {
        $uri = $route->uri();

        foreach ($route->bindingFields() as $parameter => $field) {
            $uri = str_replace('{'.$parameter.'}', '{'.$parameter.':'.$field.'}', $uri);
        }

        return $uri;
    }

    /**
     * Contoh tautan "coba langsung" ke /portfolio.
     * Slug diambil dari data yang benar-benar ada dan punya project published.
     *
     * @return list<array{label: string, url: string}>
     */
    private function examples(): array
    {
        $category = Category::whereHas('projects', fn ($query) => $query->published())
            ->withCount(['projects' => fn ($query) => $query->published()])
            ->orderByDesc('projects_count')
            ->first();

        $technology = Technology::whereHas('projects', fn ($query) => $query->published())
            ->withCount(['projects' => fn ($query) => $query->published()])
            ->orderByDesc('projects_count')
            ->first();

        $project = Project::published()->whereNotNull('slug')->latest()->first();

        $examples = [
            ['label' => 'Cari kata "laravel"', 'url' => route('portfolio.index', ['search' => 'laravel'])],
        ];

        if ($category) {
            $examples[] = ['label' => "Kategori {$category->name}", 'url' => route('portfolio.index', ['category' => $category->slug])];
        }

        if ($technology) {
            $examples[] = ['label' => "Technology {$technology->name}", 'url' => route('portfolio.index', ['technology' => $technology->slug])];
        }

        $examples[] = ['label' => 'Urut judul A-Z', 'url' => route('portfolio.index', ['sort' => 'judul'])];
        $examples[] = ['label' => 'Urut tanggal project', 'url' => route('portfolio.index', ['sort' => 'tanggal'])];

        if ($category && $technology) {
            $examples[] = [
                'label' => 'Gabungan search, kategori, technology, dan urutan',
                'url' => route('portfolio.index', [
                    'search' => 'dengan',
                    'category' => $category->slug,
                    'technology' => $technology->slug,
                    'sort' => 'terlama',
                ]),
            ];
        }

        $examples[] = ['label' => 'Halaman 2 dengan urutan judul', 'url' => route('portfolio.index', ['sort' => 'judul', 'page' => 2])];

        if ($project) {
            $examples[] = ['label' => "Detail lewat slug: {$project->title}", 'url' => route('portfolio.show', $project)];
        }

        return $examples;
    }
}

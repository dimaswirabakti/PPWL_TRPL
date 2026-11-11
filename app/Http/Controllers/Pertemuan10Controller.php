<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Route as RouteDefinition;
use Illuminate\Support\Facades\Route;
use Illuminate\View\View;

class Pertemuan10Controller extends Controller
{
    // Route khusus Pertemuan 10 yang ditampilkan pada tabel
    private const ROUTE_NAMES = [
        'pertemuan-10',
        'portfolio.index',
        'portfolio.show',
        'dashboard',
        'projects.images.store',
        'projects.images.update',
        'projects.images.destroy',
        'skills.index',
        'skills.edit',
        'skills.update',
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
                    'uri' => '/'.ltrim($route->uri(), '/'),
                    'name' => $route->getName(),
                    'locked' => in_array('auth', $middleware, true),
                    'access' => in_array('auth', $middleware, true) ? 'Harus login' : 'Publik',
                    // Hanya GET tanpa parameter yang bisa langsung diklik
                    'linkable' => $methods === ['GET'] && ! str_contains($route->uri(), '{'),
                ];
            })
            ->values();

        return view('pertemuan10.index', ['routes' => $routes]);
    }
}

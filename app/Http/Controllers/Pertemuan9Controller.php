<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Route as RouteDefinition;
use Illuminate\Support\Facades\Route;
use Illuminate\View\View;

class Pertemuan9Controller extends Controller
{
    // Route yang ditampilkan pada tabel peta akses
    private const ROUTE_NAMES = [
        'home',
        'pertemuan8.index',
        'login',
        'projects.index',
        'projects.trash',
        'pertemuan9.admin',
    ];

    public function index(): View
    {
        $routes = collect(self::ROUTE_NAMES)
            ->map(fn(string $name) => Route::getRoutes()->getByName($name))
            ->filter()
            ->map(function (RouteDefinition $route): array {
                $middleware = array_values(array_filter(
                    $route->gatherMiddleware(),
                    fn($item) => is_string($item) && $item !== 'web',
                ));

                return [
                    'uri' => '/' . ltrim($route->uri(), '/'),
                    'middleware' => $middleware,
                    'locked' => in_array('auth', $middleware, true),
                    'access' => $this->accessLabel($middleware),
                ];
            });

        return view('pertemuan9.index', ['routes' => $routes]);
    }

    public function admin(): View
    {
        return view('pertemuan9.admin');
    }

    private function accessLabel(array $middleware): string
    {
        return match (true) {
            in_array('admin', $middleware, true) => 'Hanya admin',
            in_array('auth', $middleware, true) => 'Harus login',
            in_array('guest', $middleware, true) => 'Hanya tamu',
            default => 'Siapa saja',
        };
    }
}

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Lab TRPL')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="flex min-h-screen flex-col bg-lab-bg font-lab text-lab-ink antialiased">

    <header class="border-b border-lab-line">
        <div class="mx-auto flex h-14 w-full max-w-4xl items-center justify-between px-6">
            <a href="{{ route('home') }}" class="text-sm font-semibold tracking-tight">dwb</a>

            <nav class="flex items-center gap-6 text-sm">
                <a href="{{ route('projects.index') }}" class="py-4 text-lab-muted transition hover:text-lab-ink">
                    Portfolio
                </a>
                <a href="{{ route('pertemuan8.index') }}"
                    class="py-4 {{ request()->routeIs('pertemuan8.*') ? 'font-medium text-lab-ink' : 'text-lab-muted transition hover:text-lab-ink' }}">
                    Pertemuan 8
                </a>
            </nav>
        </div>
    </header>

    <main class="mx-auto w-full max-w-4xl flex-1 px-6 pb-24 pt-16 sm:pt-24">
        @yield('content')
    </main>

    <footer class="border-t border-lab-line">
        <div class="mx-auto w-full max-w-4xl px-6 py-8 text-xs text-lab-muted">
            Praktikum Pemrograman Web 2 &middot; Seeder dan Pagination
        </div>
    </footer>

</body>

</html>
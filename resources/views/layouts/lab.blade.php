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
        <div class="mx-auto flex min-h-14 w-full max-w-4xl flex-wrap items-center justify-between gap-x-6 px-6">
            <a href="{{ route('home') }}" class="shrink-0 py-2 text-sm font-semibold tracking-tight">dwb</a>

            <nav class="flex flex-wrap items-center justify-end gap-x-4 whitespace-nowrap text-sm sm:gap-x-6">
                <a href="{{ route('projects.index') }}" class="py-2 text-lab-muted transition hover:text-lab-ink">
                    Portfolio
                </a>

                {{-- Pertemuan 10: Dashboard hanya muncul untuk user yang login. --}}
                @auth
                    <a href="{{ route('dashboard') }}"
                        class="py-2 {{ request()->routeIs('dashboard') ? 'font-medium text-lab-ink' : 'text-lab-muted transition hover:text-lab-ink' }}">
                        Dashboard
                    </a>
                @endauth

                <a href="{{ route('pertemuan8.index') }}"
                    class="py-2 {{ request()->routeIs('pertemuan8.*') ? 'font-medium text-lab-ink' : 'text-lab-muted transition hover:text-lab-ink' }}">
                    Lab 08
                </a>
                <a href="{{ route('pertemuan9.index') }}"
                    class="py-2 {{ request()->routeIs('pertemuan9.*') ? 'font-medium text-lab-ink' : 'text-lab-muted transition hover:text-lab-ink' }}">
                    Lab 09
                </a>

                {{-- Pertemuan 9: tamu melihat "Masuk", user yang login melihat "Keluar".
                     Logout wajib POST + @csrf, bukan link biasa. --}}
                @auth
                    {{-- Latihan: nama user yang login, sekaligus tautan ke halaman profil. --}}
                    <a href="{{ route('profile-saya') }}"
                        class="max-w-20 truncate py-2 sm:max-w-32 {{ request()->routeIs('profile-saya') ? 'font-medium text-lab-ink' : 'text-lab-muted transition hover:text-lab-ink' }}">
                        {{ Auth::user()->name }}
                    </a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="py-2 text-lab-muted transition hover:text-lab-ink">
                            Keluar
                        </button>
                    </form>
                @endauth

                @guest
                    <a href="{{ route('login') }}"
                        class="py-2 {{ request()->routeIs('login') ? 'font-medium text-lab-ink' : 'text-lab-muted transition hover:text-lab-ink' }}">
                        Masuk
                    </a>
                @endguest
            </nav>
        </div>
    </header>

    <main class="mx-auto w-full max-w-4xl flex-1 px-6 pb-24 pt-16 sm:pt-24">
        @yield('content')
    </main>

    <footer class="border-t border-lab-line">
        <div class="mx-auto w-full max-w-4xl px-6 py-8 text-xs text-lab-muted">
            Praktikum Pemrograman Web 2
        </div>
    </footer>

</body>

</html>
@extends('layouts.lab')

@section('title', 'Pertemuan 9 — Authentication dan Middleware')

@section('content')

{{-- ================= JUDUL ================= --}}
<header class="max-w-2xl">
    <p class="text-sm text-lab-muted">Pertemuan 9</p>
    <h1 class="mt-3 text-5xl font-semibold leading-[1.05] tracking-[-0.03em] text-balance">
        Authentication dan Middleware
    </h1>
    <p class="mt-6 text-base leading-relaxed text-lab-muted">
        Login mengingat siapa pengunjungnya lewat session. Middleware
        <code class="font-lab-mono text-sm text-lab-ink">auth</code>
        memeriksa session itu sebelum controller berjalan.
    </p>
</header>

{{-- ================= STATUS SESI ================= --}}
<section class="mt-16" aria-label="Status sesi">
    <dl class="grid grid-cols-1 gap-x-8 gap-y-6 border-t-[3px] border-double border-lab-ink pt-6 sm:grid-cols-2">
        <div>
            <dt class="text-xs text-lab-muted">Status</dt>
            <dd class="mt-1 text-2xl font-medium tracking-tight">
                @auth
                Sudah login
                @endauth
                @guest
                Tamu
                @endguest
            </dd>
        </div>
        <div>
            <dt class="text-xs text-lab-muted">Akun</dt>
            <dd class="mt-1 min-w-0 truncate text-2xl font-medium tracking-tight">
                @auth
                {{ auth()->user()->email }}
                @endauth
                @guest
                <a href="{{ route('login') }}"
                    class="underline decoration-lab-accent decoration-2 underline-offset-4">Masuk dulu</a>
                @endguest
            </dd>
        </div>
    </dl>
</section>

{{-- ================= PETA AKSES ================= --}}
<section class="mt-16" aria-labelledby="peta-akses">
    <h2 id="peta-akses" class="text-sm font-medium">Peta akses</h2>
    <p class="mt-1 text-sm text-lab-muted">
        Dibaca langsung dari route yang terdaftar. Klik path untuk mencobanya.
    </p>

    <div class="mt-6 border-t border-lab-rule">
        <div class="hidden grid-cols-[minmax(0,1fr)_9rem_8rem] gap-x-6 border-b border-lab-line py-3 text-xs text-lab-muted sm:grid"
            aria-hidden="true">
            <span>Path</span>
            <span>Middleware</span>
            <span>Akses</span>
        </div>

        <ul>
            @foreach ($routes as $route)
            <li
                class="grid grid-cols-[minmax(0,1fr)_auto] items-baseline gap-x-6 gap-y-1 border-b border-lab-line py-4 sm:grid-cols-[minmax(0,1fr)_9rem_8rem]">
                <a href="{{ $route['uri'] }}"
                    class="truncate font-lab-mono text-sm underline-offset-4 hover:underline hover:decoration-lab-accent hover:decoration-2">
                    {{ $route['uri'] }}
                </a>

                {{-- Titik terisi = terkunci (perlu login). Titik kosong = terbuka.
                             Selalu disertai teks, jadi tidak bergantung pada bentuk saja. --}}
                <span class="order-2 inline-flex items-center gap-1.5 text-xs sm:order-3">
                    <span aria-hidden="true"
                        class="size-2 rounded-full {{ $route['locked'] ? 'bg-lab-ink' : 'border border-lab-muted' }}"></span>
                    {{ $route['access'] }}
                </span>

                <span
                    class="order-3 col-span-2 font-lab-mono text-xs text-lab-muted sm:order-2 sm:col-span-1">
                    {{ $route['middleware'] ? implode(', ', $route['middleware']) : 'tanpa middleware' }}
                </span>
            </li>
            @endforeach
        </ul>
    </div>
</section>

{{-- ================= ALUR REQUEST ================= --}}
<section class="mt-16" aria-labelledby="alur-request">
    <h2 id="alur-request" class="text-sm font-medium">Alur request</h2>

    <ol class="mt-6 border-t border-lab-rule">
        @php
        $steps = [
        ['Request masuk', 'Browser meminta halaman yang dikunci, misalnya /projects.'],
        ['Middleware auth', 'Memeriksa session. Belum login: dialihkan ke /login. Sudah login: lanjut.'],
        ['Controller', 'Baru berjalan setelah middleware meloloskan request.'],
        ];
        @endphp

        @foreach ($steps as [$title, $text])
        <li class="grid grid-cols-[2rem_1fr] gap-x-4 border-b border-lab-line py-5 sm:grid-cols-[2.5rem_1fr] sm:gap-x-6">
            <span class="text-right text-sm leading-6 tabular-nums text-lab-muted">{{ $loop->iteration }}</span>
            <div>
                <h3 class="text-base font-medium leading-6">{{ $title }}</h3>
                <p class="mt-1 text-sm text-lab-muted">{{ $text }}</p>
            </div>
        </li>
        @endforeach
    </ol>
</section>

@endsection
@extends('layouts.lab')

@section('title', 'Pertemuan 11 — Search, Filter, dan Sorting')

@section('content')

{{-- ================= JUDUL ================= --}}
<header class="max-w-2xl">
    <p class="text-sm text-lab-muted">Pertemuan 11</p>
    <h1 class="mt-3 text-5xl font-semibold leading-[1.05] tracking-[-0.03em] text-balance">
        Search, Filter, dan Sorting
    </h1>
    <p class="mt-6 text-base leading-relaxed text-lab-muted">
        Semua pilihan dikirim lewat form
        <code class="font-lab-mono text-sm text-lab-ink">GET</code>,
        jadi tersimpan di URL. Hasilnya bisa dibagikan, dan
        <code class="font-lab-mono text-sm text-lab-ink">withQueryString()</code>
        membawanya ke halaman berikutnya.
    </p>
</header>

{{-- ================= RINGKASAN FITUR ================= --}}
<section class="mt-16" aria-labelledby="fitur">
    <h2 id="fitur" class="text-sm font-medium">Fitur</h2>

    <ol class="mt-6 border-t-[3px] border-double border-lab-ink">
        @php
        $features = [
        ['Search', 'Kata kunci dicocokkan ke title, short_description, dan description. Ketiga orWhere dibungkus satu grup where() agar project draft tidak ikut tampil.'],
        ['Filter kategori dan technology', 'Memakai slug di URL, contoh ?category=web dan ?technology=laravel, lewat whereHas(). Keduanya bisa digabung dengan search.'],
        ['Sorting', 'Pilihan dibatasi whitelist: terbaru, terlama, judul A-Z, dan tanggal project (tanpa tanggal di akhir). Nilai lain kembali ke terbaru.'],
        ['Pagination dengan query string', '9 project per halaman. Link halaman 2 dan seterusnya tetap membawa search, kategori, technology, dan urutan.'],
        ['Detail berbasis slug', 'Alamat detail menjadi /portfolio/{slug}. Slug dibuat dari judul saat project disimpan dan tidak berubah saat judul diedit. ID angka, draft, dan project di Trash menghasilkan 404.'],
        ];
        @endphp

        @foreach ($features as [$title, $text])
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

{{-- ================= COBA LANGSUNG ================= --}}
<section class="mt-16" aria-labelledby="coba">
    <h2 id="coba" class="text-sm font-medium">Coba langsung</h2>
    <p class="mt-1 text-sm text-lab-muted">
        Slug kategori, technology, dan project diambil dari data yang ada sekarang.
    </p>

    <ul class="mt-6 border-t border-lab-rule">
        @foreach ($examples as $example)
        <li class="border-b border-lab-line py-4">
            <p class="text-sm">{{ $example['label'] }}</p>
            <a href="{{ $example['url'] }}"
                class="mt-1 block break-all font-lab-mono text-xs text-lab-muted underline-offset-4 hover:text-lab-ink hover:underline hover:decoration-lab-accent hover:decoration-2">
                {{ Str::after($example['url'], url('/')) }}
            </a>
        </li>
        @endforeach
    </ul>
</section>

{{-- ================= ROUTE PERTEMUAN 11 ================= --}}
<section class="mt-16" aria-labelledby="peta-route">
    <h2 id="peta-route" class="text-sm font-medium">Route Pertemuan 11</h2>
    <p class="mt-1 text-sm text-lab-muted">
        Dibaca langsung dari route yang terdaftar. Path GET tanpa parameter bisa diklik.
    </p>

    <div class="mt-6 border-t border-lab-rule">
        <div class="hidden grid-cols-[4.5rem_minmax(0,1fr)_11rem_7rem] gap-x-6 border-b border-lab-line py-3 text-xs text-lab-muted sm:grid"
            aria-hidden="true">
            <span>Method</span>
            <span>Path</span>
            <span>Nama</span>
            <span>Akses</span>
        </div>

        <ul>
            @foreach ($routes as $route)
            <li
                class="grid grid-cols-[minmax(0,1fr)_auto] items-baseline gap-x-6 gap-y-1 border-b border-lab-line py-4 sm:grid-cols-[4.5rem_minmax(0,1fr)_11rem_7rem]">
                <span class="order-1 font-lab-mono text-xs text-lab-muted">{{ $route['methods'] }}</span>

                {{-- Titik terisi = perlu login. Titik kosong = publik. Selalu disertai teks. --}}
                <span class="order-2 inline-flex items-center gap-1.5 text-xs sm:order-4">
                    <span aria-hidden="true"
                        class="size-2 rounded-full {{ $route['locked'] ? 'bg-lab-ink' : 'border border-lab-muted' }}"></span>
                    {{ $route['access'] }}
                </span>

                @if ($route['linkable'])
                <a href="{{ $route['uri'] }}"
                    class="order-3 col-span-2 truncate font-lab-mono text-sm underline-offset-4 hover:underline hover:decoration-lab-accent hover:decoration-2 sm:order-2 sm:col-span-1">
                    {{ $route['uri'] }}
                </a>
                @else
                <span class="order-3 col-span-2 truncate font-lab-mono text-sm sm:order-2 sm:col-span-1">{{ $route['uri'] }}</span>
                @endif

                <span class="order-4 col-span-2 truncate font-lab-mono text-xs text-lab-muted sm:order-3 sm:col-span-1">
                    {{ $route['name'] }}
                </span>
            </li>
            @endforeach
        </ul>
    </div>
</section>

@endsection

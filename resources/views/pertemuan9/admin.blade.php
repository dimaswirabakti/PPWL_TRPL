@extends('layouts.lab')

@section('title', 'Halaman Admin')

@section('content')

<header class="max-w-2xl">
    <p class="text-sm text-lab-muted">Pertemuan 9 &middot; Middleware custom</p>
    <h1 class="mt-3 text-5xl font-semibold leading-[1.05] tracking-[-0.03em] text-balance">
        Halaman admin
    </h1>
    <p class="mt-6 text-base leading-relaxed text-lab-muted">
        Kamu bisa membuka halaman ini karena request lolos dua penjaga, berurutan.
        User lain yang sudah login tetap ditolak dengan 403.
    </p>
</header>

<section class="mt-16" aria-label="Middleware yang dilewati">
    <ol class="border-t-[3px] border-double border-lab-ink">
        @php
        $guards = [
        ['auth', 'Sudah login.'],
        ['admin', 'Role akun adalah ' . auth()->user()->role . '.'],
        ];
        @endphp

        @foreach ($guards as [$name, $text])
        <li class="grid grid-cols-[2rem_1fr_auto] items-baseline gap-x-4 border-b border-lab-line py-5 sm:grid-cols-[2.5rem_1fr_auto] sm:gap-x-6">
            <span class="text-right text-sm tabular-nums text-lab-muted">{{ $loop->iteration }}</span>
            <div class="min-w-0">
                <h2 class="font-lab-mono text-sm font-medium">{{ $name }}</h2>
                <p class="mt-1 truncate text-sm text-lab-muted">{{ $text }}</p>
            </div>
            <span class="inline-flex items-center gap-1.5 text-xs">
                <span aria-hidden="true" class="size-2 rounded-full bg-lab-ink"></span>
                Lolos
            </span>
        </li>
        @endforeach
    </ol>
</section>

<a href="{{ route('pertemuan9.index') }}"
    class="mt-10 inline-flex h-11 items-center text-sm underline decoration-lab-accent decoration-2 underline-offset-4">
    &larr; Kembali ke Pertemuan 9
</a>

@endsection
@extends('layouts.lab')

@section('title', 'Profil Saya')

@section('content')

<header class="max-w-2xl">
    <p class="text-sm text-lab-muted">Khusus user yang login</p>
    <h1 class="mt-3 text-5xl font-semibold leading-[1.05] tracking-[-0.03em] text-balance">
        Profil saya
    </h1>
    <p class="mt-6 text-base leading-relaxed text-lab-muted">
        Halaman ini dikunci middleware
        <code class="font-lab-mono text-sm text-lab-ink">auth</code>.
        Tamu yang membukanya dialihkan ke halaman login.
    </p>
</header>

@php
$user = Auth::user();

$rows = [
'Nama' => $user->name,
'Email' => $user->email,
'Role' => ucfirst($user->role),
'Bergabung' => $user->created_at->format('d M Y'),
];
@endphp

<dl class="mt-16 border-t-[3px] border-double border-lab-ink">
    @foreach ($rows as $label => $value)
    <div class="grid gap-x-6 border-b border-lab-line py-5 sm:grid-cols-[10rem_1fr] sm:items-baseline">
        <dt class="text-sm text-lab-muted">{{ $label }}</dt>
        <dd class="mt-1 min-w-0 break-words text-base font-medium sm:mt-0">{{ $value }}</dd>
    </div>
    @endforeach
</dl>

@endsection
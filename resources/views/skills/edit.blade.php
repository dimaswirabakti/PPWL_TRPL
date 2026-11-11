@extends('layouts.lab')

@section('title', 'Ubah Skill')

@section('content')

<a href="{{ route('skills.index') }}" class="text-sm text-lab-muted transition hover:text-lab-ink">&larr; Semua skill</a>

<header class="mt-8 max-w-2xl">
    <p class="text-sm text-lab-muted">Ubah skill</p>
    <h1 class="mt-3 text-4xl font-semibold leading-[1.1] tracking-[-0.03em] text-balance">
        {{ $skill->name }}
    </h1>
</header>

<form action="{{ route('skills.update', $skill) }}" method="POST"
    class="mt-12 max-w-md border-t-[3px] border-double border-lab-ink pt-6">
    @csrf
    @method('PUT')

    <label for="name" class="block text-sm font-medium">Nama skill</label>
    <input type="text" id="name" name="name" maxlength="50" required
        value="{{ old('name', $skill->name) }}"
        class="mt-2 block h-11 w-full border bg-lab-surface px-3 text-base outline-none focus:border-lab-ink {{ $errors->has('name') ? 'border-lab-accent' : 'border-lab-rule' }}">
    @error('name')
    <p class="mt-2 text-sm text-lab-accent">{{ $message }}</p>
    @enderror

    <div class="mt-6 flex items-center gap-6">
        <button type="submit" class="h-11 bg-lab-ink px-5 text-sm font-medium text-lab-surface hover:bg-lab-accent">
            Simpan
        </button>
        <a href="{{ route('skills.index') }}" class="text-sm text-lab-muted hover:text-lab-ink">Batal</a>
    </div>
</form>

@endsection

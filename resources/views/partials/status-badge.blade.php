@php
$styles = [
'published' => 'border-emerald-200 bg-emerald-50 text-emerald-700',
'draft' => 'border-zinc-200 bg-zinc-100 text-zinc-600',
];
$labels = ['published' => 'Published', 'draft' => 'Draft'];
@endphp
<span class="inline-flex items-center rounded-full border px-2 py-0.5 text-xs font-medium {{ $styles[$status] ?? $styles['draft'] }}">
    {{ $labels[$status] ?? ucfirst($status) }}
</span>
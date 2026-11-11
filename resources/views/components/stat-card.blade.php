@props(['label', 'value', 'color' => 'text-lab-ink'])

<div>
    <dt class="text-xs text-lab-muted">{{ $label }}</dt>
    <dd class="mt-1 text-4xl font-semibold tabular-nums tracking-tight {{ $color }}">{{ $value }}</dd>
</div>

@props(['items' => []])

@if (count($items))
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb small py-1 px-3 rounded-pill d-inline-flex" style="background: var(--qomer-surface-elevated); border: 1px solid var(--qomer-border-subtle);">
            @foreach ($items as $label => $url)
                @if ($loop->last || !$url)
                    <li class="breadcrumb-item active fw-semibold" aria-current="page" style="color: var(--qomer-emerald);">{{ $label }}</li>
                @else
                    <li class="breadcrumb-item"><a href="{{ $url }}" class="text-decoration-none" style="color: var(--qomer-text-muted);">{{ $label }}</a></li>
                @endif
            @endforeach
        </ol>
    </nav>
@endif

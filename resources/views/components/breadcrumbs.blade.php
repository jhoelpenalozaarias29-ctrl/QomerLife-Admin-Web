@props(['items' => []])

@if (count($items))
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb small">
            @foreach ($items as $label => $url)
                @if ($loop->last || !$url)
                    <li class="breadcrumb-item active" aria-current="page">{{ $label }}</li>
                @else
                    <li class="breadcrumb-item"><a href="{{ $url }}">{{ $label }}</a></li>
                @endif
            @endforeach
        </ol>
    </nav>
@endif

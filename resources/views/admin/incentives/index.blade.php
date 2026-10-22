@extends('layouts.admin')

@section('title', 'Catálogo de Incentivos')

@section('content')
<div class="container-fluid px-0">

    {{-- Encabezado --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <h1 class="h3 mb-1 fw-bold" style="color:var(--qomer-text);">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="var(--qomer-amber)" stroke-width="2" class="me-2" style="vertical-align:-4px"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                Catálogo de Incentivos y Premios
            </h1>
            <p class="small mb-0" style="color:var(--qomer-text-muted);">Gestiona beneficios y recompensas que los usuarios canjean con sus EcoPoints desde la app.</p>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            @if(count($incentives) === 0)
                <form action="{{ route('admin.incentives.seedDefaults') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-sm px-3 py-2 fw-semibold"
                            style="background:rgba(0,199,129,.1);color:var(--qomer-emerald);border:1px solid rgba(0,199,129,.35);border-radius:10px;">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-1"><polyline points="8 17 12 21 16 17"/><line x1="12" y1="12" x2="12" y2="21"/><path d="M20.88 18.09A5 5 0 0 0 18 9h-1.26A8 8 0 1 0 3 16.29"/></svg>
                        Cargar Catálogo Inicial
                    </button>
                </form>
            @endif
            <a href="{{ route('admin.incentives.create') }}" class="btn btn-qomer btn-sm px-4">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-1"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Nuevo Incentivo
            </a>
        </div>
    </div>

    {{-- KPIs --}}
    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="card-stat p-3" style="border-left:3px solid var(--qomer-emerald);">
                <div class="text-xs fw-bold text-uppercase mb-1" style="color:var(--qomer-text-muted);letter-spacing:.08em;">Total Incentivos</div>
                <div class="h4 mb-0 fw-bold" style="color:var(--qomer-emerald);">{{ number_format($kpis['total'] ?? 0) }}</div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card-stat p-3" style="border-left:3px solid var(--qomer-cyan);">
                <div class="text-xs fw-bold text-uppercase mb-1" style="color:var(--qomer-text-muted);letter-spacing:.08em;">Activos para Canje</div>
                <div class="h4 mb-0 fw-bold" style="color:var(--qomer-cyan);">{{ number_format($kpis['active'] ?? 0) }}</div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card-stat p-3" style="border-left:3px solid var(--qomer-amber);">
                <div class="text-xs fw-bold text-uppercase mb-1" style="color:var(--qomer-text-muted);letter-spacing:.08em;">Puntos Promedio</div>
                <div class="h4 mb-0 fw-bold" style="color:var(--qomer-amber);">{{ number_format($kpis['avgPoints'] ?? 0) }} <span class="fs-6">pts</span></div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card-stat p-3" style="border-left:3px solid var(--qomer-emerald);">
                <div class="text-xs fw-bold text-uppercase mb-1" style="color:var(--qomer-text-muted);letter-spacing:.08em;">Stock Total</div>
                <div class="h4 mb-0 fw-bold" style="color:var(--qomer-emerald);">{{ number_format($kpis['totalStock'] ?? 0) }} <span class="fs-6">uds</span></div>
            </div>
        </div>
    </div>

    {{-- Filtros --}}
    <div class="card-stat mb-4 p-0 overflow-hidden">
        <div class="px-4 py-3" style="border-bottom:1px solid var(--qomer-border);">
            <span class="small fw-semibold text-uppercase" style="color:var(--qomer-text-muted);letter-spacing:.07em;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="var(--qomer-amber)" stroke-width="2" class="me-1"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
                Filtros de Búsqueda
            </span>
        </div>
        <div class="p-4">
            <form action="{{ route('admin.incentives.index') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-5">
                    <label class="form-label small fw-semibold" style="color:var(--qomer-text-muted);">Búsqueda</label>
                    <div class="input-group">
                        <span class="input-group-text" style="background:var(--qomer-surface-elevated);border-color:var(--qomer-border);color:var(--qomer-text-muted);">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                        </span>
                        <input type="text" name="search" class="form-control qomer-input"
                               placeholder="Título, descripción o ID..." value="{{ $search ?? '' }}">
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <label class="form-label small fw-semibold" style="color:var(--qomer-text-muted);">Categoría</label>
                    <select name="category" class="form-select qomer-input">
                        <option value="">Todas las categorías</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}" {{ ($category ?? '') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 col-sm-6">
                    <label class="form-label small fw-semibold" style="color:var(--qomer-text-muted);">Disponibilidad</label>
                    <select name="availability" class="form-select qomer-input">
                        <option value="">Todos</option>
                        <option value="active"   {{ ($availability ?? '') === 'active'   ? 'selected' : '' }}>Activos</option>
                        <option value="inactive" {{ ($availability ?? '') === 'inactive' ? 'selected' : '' }}>Pausados</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-qomer flex-grow-1">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-1"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
                        Filtrar
                    </button>
                    @if(!empty($search) || !empty($category) || !empty($availability))
                        <a href="{{ route('admin.incentives.index') }}" class="btn"
                           style="background:var(--qomer-surface-elevated);color:var(--qomer-text-muted);border:1px solid var(--qomer-border);" title="Limpiar">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6 6 18M6 6l12 12"/></svg>
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    {{-- Grid de Tarjetas de Incentivos --}}
    <div class="row g-4 mb-4">
        @forelse($incentives as $incentive)
            <div class="col-md-6 col-xl-3">
                <div class="incentive-card h-100 position-relative overflow-hidden" style="background:var(--qomer-surface);border:1px solid var(--qomer-border);border-radius:16px;transition:transform .25s,box-shadow .25s,border-color .25s;">

                    {{-- Badges de estado --}}
                    <div class="position-absolute top-0 start-0 m-2 z-1">
                        <span class="badge" style="background:rgba(15,20,17,.75);backdrop-filter:blur(8px);color:var(--qomer-text-muted);border:1px solid var(--qomer-border);">{{ $incentive->category }}</span>
                    </div>
                    <div class="position-absolute top-0 end-0 m-2 z-1">
                        @if($incentive->isAvailable)
                            <span class="badge-emerald"><span class="status-pulse me-1"></span>Activo</span>
                        @else
                            <span class="badge" style="background:rgba(100,116,139,.2);color:#94a3b8;border:1px solid rgba(100,116,139,.3);">Pausado</span>
                        @endif
                    </div>

                    {{-- Imagen --}}
                    <div class="ratio ratio-16x9 overflow-hidden" style="border-bottom:1px solid var(--qomer-border);">
                        @if(!empty($incentive->imageUrl))
                            <img src="{{ $incentive->imageUrl }}" alt="{{ $incentive->title }}"
                                 style="object-fit:cover;width:100%;height:100%;"
                                 onerror="this.onerror=null;this.src='https://placehold.co/600x400/141A17/00C781?text={{ urlencode($incentive->title) }}';">
                        @else
                            <div class="d-flex align-items-center justify-content-center" style="background:var(--qomer-surface-elevated);">
                                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="var(--qomer-text-muted)" stroke-width="1" class="opacity-40"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                            </div>
                        @endif
                    </div>

                    {{-- Cuerpo --}}
                    <div class="p-3 d-flex flex-column" style="min-height:180px;">
                        <h6 class="fw-bold mb-1 text-truncate" style="color:var(--qomer-text);" title="{{ $incentive->title }}">{{ $incentive->title }}</h6>
                        <p class="small flex-grow-1 mb-3" style="color:var(--qomer-text-muted);display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;min-height:36px;">
                            {{ $incentive->description }}
                        </p>

                        {{-- Puntos y Stock --}}
                        <div class="p-2 rounded-3 mb-3 d-flex justify-content-between align-items-center" style="background:var(--qomer-surface-elevated);border:1px solid var(--qomer-border);">
                            <div>
                                <div style="font-size:10px;color:var(--qomer-text-muted);font-weight:700;letter-spacing:.06em;">COSTO</div>
                                <div class="fw-bold" style="color:var(--qomer-amber);">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="var(--qomer-amber)" stroke="none" class="me-1"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                                    {{ number_format($incentive->ecoPointsRequired) }} pts
                                </div>
                            </div>
                            <div class="text-end">
                                <div style="font-size:10px;color:var(--qomer-text-muted);font-weight:700;letter-spacing:.06em;">STOCK</div>
                                <div class="fw-semibold small" style="color:var(--qomer-text);">{{ $incentive->availableQuantity }} uds</div>
                            </div>
                        </div>

                        {{-- Acciones --}}
                        <div class="d-flex gap-2 pt-2" style="border-top:1px solid var(--qomer-border);">
                            <form action="{{ route('admin.incentives.toggle', $incentive->id) }}" method="POST" class="d-inline flex-grow-1">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-sm w-100 fw-semibold"
                                        style="background:{{ $incentive->isAvailable ? 'rgba(245,158,11,.12)' : 'rgba(0,199,129,.12)' }};color:{{ $incentive->isAvailable ? 'var(--qomer-amber)' : 'var(--qomer-emerald)' }};border:1px solid {{ $incentive->isAvailable ? 'rgba(245,158,11,.3)' : 'rgba(0,199,129,.3)' }};border-radius:8px;">
                                    @if($incentive->isAvailable)
                                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-1"><rect x="6" y="4" width="4" height="16"/><rect x="14" y="4" width="4" height="16"/></svg>Pausar
                                    @else
                                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-1"><polygon points="5 3 19 12 5 21 5 3"/></svg>Activar
                                    @endif
                                </button>
                            </form>

                            <a href="{{ route('admin.incentives.edit', $incentive->id) }}"
                               class="btn btn-sm" style="background:rgba(0,199,129,.12);color:var(--qomer-emerald);border:1px solid rgba(0,199,129,.3);border-radius:8px;" title="Editar">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                            </a>

                            <form action="{{ route('admin.incentives.destroy', $incentive->id) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('¿Seguro que deseas eliminar este incentivo del catálogo?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm"
                                        style="background:rgba(239,68,68,.12);color:#f87171;border:1px solid rgba(239,68,68,.3);border-radius:8px;" title="Eliminar">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="text-center py-5 card-stat">
                    <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="var(--qomer-text-muted)" stroke-width="1" class="mb-3 d-block mx-auto opacity-40"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                    <h5 class="fw-bold mb-1" style="color:var(--qomer-text);">No hay incentivos registrados</h5>
                    <p class="small mb-4" style="color:var(--qomer-text-muted);">Crea incentivos para que los usuarios puedan canjear sus EcoPoints.</p>
                    <div class="d-flex gap-3 justify-content-center">
                        <form action="{{ route('admin.incentives.seedDefaults') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-sm px-4" style="background:rgba(0,199,129,.1);color:var(--qomer-emerald);border:1px solid rgba(0,199,129,.35);border-radius:10px;">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-1"><polyline points="8 17 12 21 16 17"/><line x1="12" y1="12" x2="12" y2="21"/><path d="M20.88 18.09A5 5 0 0 0 18 9h-1.26A8 8 0 1 0 3 16.29"/></svg>
                                Cargar Catálogo Inicial
                            </button>
                        </form>
                        <a href="{{ route('admin.incentives.create') }}" class="btn btn-qomer btn-sm px-4">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-1"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                            Crear Primer Incentivo
                        </a>
                    </div>
                </div>
            </div>
        @endforelse
    </div>
</div>

<style>
.incentive-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 40px rgba(0,199,129,.12) !important;
    border-color: rgba(0,199,129,.35) !important;
}
</style>
@endsection

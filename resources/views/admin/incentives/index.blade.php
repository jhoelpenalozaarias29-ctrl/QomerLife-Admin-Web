@extends('layouts.admin')

@section('title', 'Catálogo de Incentivos y Premios')

@section('content')
<div class="container-fluid px-0">

    {{-- Encabezado Principal --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <h1 class="h3 mb-1 text-gray-800 fw-bold">
                <i class="bi bi-award me-2 text-primary"></i>Catálogo de Incentivos y Premios
            </h1>
            <p class="text-muted mb-0 small">
                Gestiona los beneficios y recompensas que los usuarios pueden canjear desde la aplicación móvil con sus EcoPoints.
            </p>
        </div>
        <div class="d-flex gap-2">
            @if(count($incentives) === 0)
                <form action="{{ route('admin.incentives.seedDefaults') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-outline-success shadow-sm">
                        <i class="bi bi-cloud-arrow-down me-1"></i> Cargar Catálogo Oficial Inicial
                    </button>
                </form>
            @endif
            <a href="{{ route('admin.incentives.create') }}" class="btn btn-primary shadow-sm">
                <i class="bi bi-plus-circle me-1"></i> Nuevo Incentivo
            </a>
        </div>
    </div>

    {{-- Tarjetas KPI de Resumen --}}
    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm border-start border-4 border-primary h-100">
                <div class="card-body py-3">
                    <div class="text-xs text-uppercase text-muted fw-bold mb-1">Total Incentivos</div>
                    <div class="h4 mb-0 fw-bold text-primary">{{ number_format($kpis['total'] ?? 0) }}</div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm border-start border-4 border-success h-100">
                <div class="card-body py-3">
                    <div class="text-xs text-uppercase text-muted fw-bold mb-1">Activos para Canje</div>
                    <div class="h4 mb-0 fw-bold text-success">{{ number_format($kpis['active'] ?? 0) }}</div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm border-start border-4 border-warning h-100">
                <div class="card-body py-3">
                    <div class="text-xs text-uppercase text-muted fw-bold mb-1">Puntos Promedio</div>
                    <div class="h4 mb-0 fw-bold text-warning">{{ number_format($kpis['avgPoints'] ?? 0) }} pts</div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm border-start border-4 border-info h-100">
                <div class="card-body py-3">
                    <div class="text-xs text-uppercase text-muted fw-bold mb-1">Stock Total Disponible</div>
                    <div class="h4 mb-0 fw-bold text-info">{{ number_format($kpis['totalStock'] ?? 0) }} uds</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Barra de Filtros --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form action="{{ route('admin.incentives.index') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-5">
                    <label class="form-label small fw-semibold text-muted">Búsqueda</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-search"></i></span>
                        <input type="text" name="search" class="form-control border-start-0" 
                               placeholder="Título, descripción o ID..." value="{{ $search ?? '' }}">
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <label class="form-label small fw-semibold text-muted">Categoría</label>
                    <select name="category" class="form-select">
                        <option value="">Todas las categorías</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}" {{ ($category ?? '') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 col-sm-6">
                    <label class="form-label small fw-semibold text-muted">Disponibilidad</label>
                    <select name="availability" class="form-select">
                        <option value="">Todos</option>
                        <option value="active" {{ ($availability ?? '') === 'active' ? 'selected' : '' }}>Activos</option>
                        <option value="inactive" {{ ($availability ?? '') === 'inactive' ? 'selected' : '' }}>Pausados</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-grow-1 shadow-sm">
                        <i class="bi bi-funnel-fill me-1"></i> Filtrar
                    </button>
                    @if(!empty($search) || !empty($category) || !empty($availability))
                        <a href="{{ route('admin.incentives.index') }}" class="btn btn-outline-secondary" title="Limpiar filtros">
                            <i class="bi bi-x-lg"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    {{-- Listado de Incentivos (Vista Catálogo en Cuadrícula) --}}
    <div class="row g-4 mb-4">
        @forelse($incentives as $incentive)
            <div class="col-md-6 col-xl-3">
                <div class="card h-100 border-0 shadow-sm transition-hover position-relative overflow-hidden">
                    {{-- Badge de Categoría y Estado --}}
                    <div class="position-absolute top-0 start-0 m-2 z-1">
                        <span class="badge bg-dark bg-opacity-75 backdrop-blur px-2 py-1">
                            {{ $incentive->category }}
                        </span>
                    </div>
                    <div class="position-absolute top-0 end-0 m-2 z-1">
                        @if($incentive->isAvailable)
                            <span class="badge bg-success px-2 py-1"><i class="bi bi-check-circle me-1"></i>Disponible</span>
                        @else
                            <span class="badge bg-secondary px-2 py-1"><i class="bi bi-pause-circle me-1"></i>Pausado</span>
                        @endif
                    </div>

                    {{-- Imagen del Incentivo --}}
                    <div class="bg-light text-center ratio ratio-16x9 overflow-hidden">
                        @if(!empty($incentive->imageUrl))
                            <img src="{{ $incentive->imageUrl }}" alt="{{ $incentive->title }}" class="object-fit-cover w-100 h-100"
                                 onerror="this.onerror=null; this.src='https://placehold.co/600x400/e9ecef/495057?text={{ urlencode($incentive->title) }}';">
                        @else
                            <div class="d-flex align-items-center justify-content-center h-100 text-muted">
                                <i class="bi bi-image fs-1 opacity-50"></i>
                            </div>
                        @endif
                    </div>

                    {{-- Cuerpo de la Tarjeta --}}
                    <div class="card-body d-flex flex-column p-3">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h5 class="card-title fw-bold text-dark mb-0 fs-6 text-truncate" title="{{ $incentive->title }}">
                                {{ $incentive->title }}
                            </h5>
                        </div>
                        <p class="card-text text-muted small flex-grow-1 mb-3" style="min-height: 40px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                            {{ $incentive->description }}
                        </p>

                        {{-- Información de Puntos y Stock --}}
                        <div class="bg-light rounded p-2 mb-3 d-flex justify-content-between align-items-center">
                            <div>
                                <span class="small text-muted d-block" style="font-size: 11px;">COSTO</span>
                                <span class="fw-bold text-success fs-6">
                                    <i class="bi bi-stars me-1"></i>{{ number_format($incentive->ecoPointsRequired) }} pts
                                </span>
                            </div>
                            <div class="text-end">
                                <span class="small text-muted d-block" style="font-size: 11px;">STOCK</span>
                                <span class="fw-semibold text-secondary small">
                                    {{ $incentive->availableQuantity }} uds
                                </span>
                            </div>
                        </div>

                        {{-- Botones de Acción y Toggle --}}
                        <div class="d-flex gap-2 align-items-center pt-2 border-top">
                            <form action="{{ route('admin.incentives.toggle', $incentive->id) }}" method="POST" class="d-inline flex-grow-1">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-sm w-100 {{ $incentive->isAvailable ? 'btn-outline-warning' : 'btn-outline-success' }}" 
                                        title="{{ $incentive->isAvailable ? 'Pausar este incentivo' : 'Activar para canje' }}">
                                    @if($incentive->isAvailable)
                                        <i class="bi bi-pause-fill"></i> Pausar
                                    @else
                                        <i class="bi bi-play-fill"></i> Activar
                                    @endif
                                </button>
                            </form>

                            <a href="{{ route('admin.incentives.edit', $incentive->id) }}" class="btn btn-sm btn-outline-primary" title="Editar">
                                <i class="bi bi-pencil"></i>
                            </a>

                            <form action="{{ route('admin.incentives.destroy', $incentive->id) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('¿Seguro que deseas eliminar este incentivo del catálogo?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Eliminar">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card border-0 shadow-sm py-5 text-center text-muted">
                    <i class="bi bi-gift fs-1 d-block mb-3 opacity-50"></i>
                    <h5 class="fw-bold text-dark">No hay incentivos registrados</h5>
                    <p class="small text-muted mb-3">Crea incentivos para que los usuarios puedan canjear sus EcoPoints en la aplicación móvil.</p>
                    <div>
                        <form action="{{ route('admin.incentives.seedDefaults') }}" method="POST" class="d-inline me-2">
                            @csrf
                            <button type="submit" class="btn btn-success shadow-sm">
                                <i class="bi bi-cloud-arrow-down me-1"></i> Cargar Catálogo Oficial Inicial (4 incentivos)
                            </button>
                        </form>
                        <a href="{{ route('admin.incentives.create') }}" class="btn btn-primary shadow-sm">
                            <i class="bi bi-plus-circle me-1"></i> Crear mi Primer Incentivo
                        </a>
                    </div>
                </div>
            </div>
        @endforelse
    </div>
</div>

<style>
.transition-hover {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.transition-hover:hover {
    transform: translateY(-4px);
    box-shadow: 0 .5rem 1rem rgba(0,0,0,.12)!important;
}
.backdrop-blur {
    backdrop-filter: blur(4px);
}
</style>
@endsection

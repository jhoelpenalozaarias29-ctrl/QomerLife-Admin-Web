@extends('layouts.admin')

@section('title', 'Centro de Reportes')

@section('content')
<div class="container-fluid px-0">

    {{-- Encabezado Principal y Botones de Acción --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <h1 class="h3 mb-1 text-gray-800 fw-bold">
                <i class="bi bi-file-earmark-bar-graph me-2 text-primary"></i>Centro de Reportes y Análisis
            </h1>
            <p class="text-muted mb-0 small">
                Consulta interactiva, filtrado avanzado, edición directa y exportación oficial de datos.
            </p>
        </div>
        <div class="d-flex gap-2 print-hide">
            @php
                $csvRoute = match($type) {
                    'users' => route('admin.reports.users.csv', request()->query()),
                    'redemptions' => route('admin.reports.redemptions.csv', request()->query()),
                    default => route('admin.reports.transactions.csv', request()->query()),
                };
            @endphp
            <button type="button" onclick="window.print()" class="btn btn-outline-secondary shadow-sm">
                <i class="bi bi-printer me-1"></i> Imprimir / PDF
            </button>
            <a href="{{ $csvRoute }}" class="btn btn-success shadow-sm">
                <i class="bi bi-filetype-csv me-1"></i> Exportar CSV Filtrado
            </a>
        </div>
    </div>

    {{-- Encabezado visible únicamente al Imprimir --}}
    <div class="d-none print-only mb-4 border-bottom pb-3">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="h4 mb-0 fw-bold">Q'OMER LIFE — REPORTE OFICIAL DEL SISTEMA</h2>
                <div class="text-muted small">
                    Tipo de Reporte: <span class="fw-bold text-uppercase">{{ $type }}</span>
                    | Generado el: {{ date('d/m/Y H:i:s') }}
                </div>
            </div>
            <div class="text-end text-muted small">
                Estado: Oficial y Auditado<br>
                Registros incluidos: {{ count($items) }}
            </div>
        </div>
    </div>

    {{-- Navegación por Tipo de Reporte (Pills) --}}
    <div class="card border-0 shadow-sm mb-4 print-hide">
        <div class="card-body p-2">
            <ul class="nav nav-pills nav-fill gap-2">
                <li class="nav-item">
                    <a class="nav-link py-2 {{ $type === 'transactions' ? 'active shadow-sm fw-semibold' : 'text-secondary' }}" 
                       href="{{ route('admin.reports.index', ['type' => 'transactions']) }}">
                        <i class="bi bi-arrow-left-right me-1"></i> Transacciones de Reciclaje
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link py-2 {{ $type === 'users' ? 'active shadow-sm fw-semibold' : 'text-secondary' }}" 
                       href="{{ route('admin.reports.index', ['type' => 'users']) }}">
                        <i class="bi bi-people me-1"></i> Usuarios Registrados
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link py-2 {{ $type === 'redemptions' ? 'active shadow-sm fw-semibold' : 'text-secondary' }}" 
                       href="{{ route('admin.reports.index', ['type' => 'redemptions']) }}">
                        <i class="bi bi-gift me-1"></i> Canjes de Recompensas
                    </a>
                </li>
            </ul>
        </div>
    </div>

    {{-- Tarjetas KPI de Resumen Dinámico --}}
    <div class="row g-3 mb-4">
        @if($type === 'transactions')
            <div class="col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm border-start border-4 border-primary h-100">
                    <div class="card-body py-3">
                        <div class="text-xs text-uppercase text-muted fw-bold mb-1">Registros Filtrados</div>
                        <div class="h4 mb-0 fw-bold text-primary">{{ number_format($kpis['totalRecords'] ?? 0) }}</div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm border-start border-4 border-success h-100">
                    <div class="card-body py-3">
                        <div class="text-xs text-uppercase text-muted fw-bold mb-1">EcoPoints Otorgados</div>
                        <div class="h4 mb-0 fw-bold text-success">{{ number_format($kpis['totalPoints'] ?? 0) }} pts</div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm border-start border-4 border-info h-100">
                    <div class="card-body py-3">
                        <div class="text-xs text-uppercase text-muted fw-bold mb-1">Botellas PET</div>
                        <div class="h4 mb-0 fw-bold text-info">{{ number_format($kpis['totalPet'] ?? 0) }} uds</div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm border-start border-4 border-warning h-100">
                    <div class="card-body py-3">
                        <div class="text-xs text-uppercase text-muted fw-bold mb-1">Latas de Aluminio</div>
                        <div class="h4 mb-0 fw-bold text-warning">{{ number_format($kpis['totalAlum'] ?? 0) }} uds</div>
                    </div>
                </div>
            </div>
        @elseif($type === 'users')
            <div class="col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm border-start border-4 border-primary h-100">
                    <div class="card-body py-3">
                        <div class="text-xs text-uppercase text-muted fw-bold mb-1">Total Usuarios</div>
                        <div class="h4 mb-0 fw-bold text-primary">{{ number_format($kpis['totalRecords'] ?? 0) }}</div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm border-start border-4 border-success h-100">
                    <div class="card-body py-3">
                        <div class="text-xs text-uppercase text-muted fw-bold mb-1">Usuarios Activos</div>
                        <div class="h4 mb-0 fw-bold text-success">{{ number_format($kpis['activeCount'] ?? 0) }}</div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm border-start border-4 border-warning h-100">
                    <div class="card-body py-3">
                        <div class="text-xs text-uppercase text-muted fw-bold mb-1">Administradores</div>
                        <div class="h4 mb-0 fw-bold text-warning">{{ number_format($kpis['adminCount'] ?? 0) }}</div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm border-start border-4 border-info h-100">
                    <div class="card-body py-3">
                        <div class="text-xs text-uppercase text-muted fw-bold mb-1">EcoPoints Acumulados</div>
                        <div class="h4 mb-0 fw-bold text-info">{{ number_format($kpis['totalPoints'] ?? 0) }} pts</div>
                    </div>
                </div>
            </div>
        @elseif($type === 'redemptions')
            <div class="col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm border-start border-4 border-primary h-100">
                    <div class="card-body py-3">
                        <div class="text-xs text-uppercase text-muted fw-bold mb-1">Total Canjes</div>
                        <div class="h4 mb-0 fw-bold text-primary">{{ number_format($kpis['totalRecords'] ?? 0) }}</div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm border-start border-4 border-danger h-100">
                    <div class="card-body py-3">
                        <div class="text-xs text-uppercase text-muted fw-bold mb-1">Puntos Canjeados</div>
                        <div class="h4 mb-0 fw-bold text-danger">{{ number_format($kpis['redeemedPoints'] ?? 0) }} pts</div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm border-start border-4 border-warning h-100">
                    <div class="card-body py-3">
                        <div class="text-xs text-uppercase text-muted fw-bold mb-1">Pendientes</div>
                        <div class="h4 mb-0 fw-bold text-warning">{{ number_format($kpis['pendingCount'] ?? 0) }}</div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm border-start border-4 border-success h-100">
                    <div class="card-body py-3">
                        <div class="text-xs text-uppercase text-muted fw-bold mb-1">Aprobados</div>
                        <div class="h4 mb-0 fw-bold text-success">{{ number_format($kpis['approvedCount'] ?? 0) }}</div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    {{-- Barra de Filtros Interactiva --}}
    <div class="card border-0 shadow-sm mb-4 print-hide">
        <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
            <span class="fw-semibold text-secondary small text-uppercase">
                <i class="bi bi-funnel me-1 text-primary"></i> Filtros de Consulta y Exportación
            </span>
            <span class="badge bg-light text-secondary border">
                {{ count($items) }} resultado(s) encontrado(s)
            </span>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.reports.index') }}" class="row g-3 align-items-end">
                <input type="hidden" name="type" value="{{ $type }}">

                {{-- Campo de Búsqueda --}}
                <div class="col-md-3">
                    <label class="form-label small fw-semibold text-muted">Búsqueda rápida</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-search"></i></span>
                        <input type="text" name="search" class="form-control border-start-0" 
                               placeholder="Nombre, email, token, ID..." value="{{ $filters['search'] ?? '' }}">
                    </div>
                </div>

                {{-- Rango de Fecha Desde --}}
                <div class="col-md-2 col-sm-6">
                    <label class="form-label small fw-semibold text-muted">Fecha Desde</label>
                    <input type="date" name="date_from" class="form-control" value="{{ $filters['date_from'] ?? '' }}">
                </div>

                {{-- Rango de Fecha Hasta --}}
                <div class="col-md-2 col-sm-6">
                    <label class="form-label small fw-semibold text-muted">Fecha Hasta</label>
                    <input type="date" name="date_to" class="form-control" value="{{ $filters['date_to'] ?? '' }}">
                </div>

                {{-- Filtros condicionales por tipo --}}
                @if($type === 'transactions')
                    <div class="col-md-2">
                        <label class="form-label small fw-semibold text-muted">Material</label>
                        <select name="material" class="form-select">
                            <option value="">Todos los materiales</option>
                            <option value="PET" {{ ($filters['material'] ?? '') === 'PET' ? 'selected' : '' }}>PET / Botella</option>
                            <option value="ALUMINIO" {{ ($filters['material'] ?? '') === 'ALUMINIO' ? 'selected' : '' }}>Aluminio / Lata</option>
                            <option value="VIDRIO" {{ ($filters['material'] ?? '') === 'VIDRIO' ? 'selected' : '' }}>Vidrio</option>
                        </select>
                    </div>
                @elseif($type === 'users')
                    <div class="col-md-2">
                        <label class="form-label small fw-semibold text-muted">Rol</label>
                        <select name="role" class="form-select">
                            <option value="">Todos los roles</option>
                            <option value="USER" {{ ($filters['role'] ?? '') === 'USER' ? 'selected' : '' }}>Usuario</option>
                            <option value="ADMIN" {{ ($filters['role'] ?? '') === 'ADMIN' ? 'selected' : '' }}>Administrador</option>
                            <option value="SUPERVISOR" {{ ($filters['role'] ?? '') === 'SUPERVISOR' ? 'selected' : '' }}>Supervisor</option>
                        </select>
                    </div>
                    <div class="col-md-1">
                        <label class="form-label small fw-semibold text-muted">Estado</label>
                        <select name="status" class="form-select">
                            <option value="">Todos</option>
                            <option value="ACTIVE" {{ ($filters['status'] ?? '') === 'ACTIVE' ? 'selected' : '' }}>Activo</option>
                            <option value="SUSPENDED" {{ ($filters['status'] ?? '') === 'SUSPENDED' ? 'selected' : '' }}>Suspendido</option>
                            <option value="INACTIVE" {{ ($filters['status'] ?? '') === 'INACTIVE' ? 'selected' : '' }}>Inactivo</option>
                        </select>
                    </div>
                @elseif($type === 'redemptions')
                    <div class="col-md-2">
                        <label class="form-label small fw-semibold text-muted">Estado del Canje</label>
                        <select name="status" class="form-select">
                            <option value="">Todos los estados</option>
                            <option value="PENDING" {{ ($filters['status'] ?? '') === 'PENDING' ? 'selected' : '' }}>Pendiente</option>
                            <option value="APPROVED" {{ ($filters['status'] ?? '') === 'APPROVED' ? 'selected' : '' }}>Aprobado</option>
                            <option value="REJECTED" {{ ($filters['status'] ?? '') === 'REJECTED' ? 'selected' : '' }}>Rechazado</option>
                        </select>
                    </div>
                @endif

                {{-- Botones Aplicar y Limpiar --}}
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-grow-1 shadow-sm">
                        <i class="bi bi-funnel-fill me-1"></i> Filtrar
                    </button>
                    <a href="{{ route('admin.reports.index', ['type' => $type]) }}" class="btn btn-outline-secondary" title="Restablecer filtros">
                        <i class="bi bi-arrow-counterclockwise"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- Tabla de Previsualización y Edición --}}
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-muted small text-uppercase">
                        @if($type === 'transactions')
                            <tr>
                                <th class="ps-4">Token / ID</th>
                                <th>Usuario</th>
                                <th>Máquina</th>
                                <th>Material</th>
                                <th class="text-center">Botellas</th>
                                <th class="text-center">Latas</th>
                                <th class="text-center">EcoPoints</th>
                                <th>Estado</th>
                                <th>Fecha</th>
                                <th class="text-end pe-4 print-hide">Acciones / Edición</th>
                            </tr>
                        @elseif($type === 'users')
                            <tr>
                                <th class="ps-4">Nombre</th>
                                <th>Correo Electrónico</th>
                                <th>Rol</th>
                                <th>Estado</th>
                                <th class="text-center">EcoPoints</th>
                                <th>Último Acceso</th>
                                <th class="text-end pe-4 print-hide">Acciones / Edición</th>
                            </tr>
                        @elseif($type === 'redemptions')
                            <tr>
                                <th class="ps-4">ID Canje</th>
                                <th>Usuario</th>
                                <th>Recompensa</th>
                                <th class="text-center">Puntos Gastados</th>
                                <th class="text-center">Estado</th>
                                <th>Fecha</th>
                                <th class="text-end pe-4 print-hide">Acciones / Gestión</th>
                            </tr>
                        @endif
                    </thead>
                    <tbody>
                        @forelse($items as $item)
                            @if($type === 'transactions')
                                @php
                                    $txDate = 'N/A';
                                    $rawDate = $item['fechaCreacion'] ?? $item['createdAt'] ?? null;
                                    if ($rawDate) {
                                        if (is_object($rawDate) && method_exists($rawDate, 'get')) {
                                            $txDate = $rawDate->get()->format('d/m/Y H:i');
                                        } elseif ($rawDate instanceof \DateTimeInterface) {
                                            $txDate = $rawDate->format('d/m/Y H:i');
                                        } elseif (is_numeric($rawDate)) {
                                            $txDate = date('d/m/Y H:i', (int)$rawDate);
                                        } else {
                                            $txDate = date('d/m/Y H:i', strtotime((string)$rawDate));
                                        }
                                    }
                                @endphp
                                <tr>
                                    <td class="ps-4">
                                        <span class="font-monospace text-dark fw-semibold small">
                                            {{ \Illuminate\Support\Str::limit($item['token'] ?? $item['id'] ?? '', 12) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="small text-muted">{{ $item['userId'] ?? 'Anónimo' }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-secondary border">
                                            {{ $item['machineId'] ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td>
                                        @php $mat = strtoupper($item['material'] ?? 'PET'); @endphp
                                        @if($mat === 'PET')
                                            <span class="badge bg-success-subtle text-success border border-success-subtle">PET / Plástico</span>
                                        @elseif($mat === 'ALUMINIO')
                                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle">Aluminio</span>
                                        @else
                                            <span class="badge bg-secondary-subtle text-secondary">{{ $mat }}</span>
                                        @endif
                                    </td>
                                    <td class="text-center fw-semibold text-secondary">
                                        {{ $item['plasticBottlesCount'] ?? ($item['bottles'] ?? 0) }}
                                    </td>
                                    <td class="text-center fw-semibold text-secondary">
                                        {{ $item['aluminumCansCount'] ?? ($item['cans'] ?? 0) }}
                                    </td>
                                    <td class="text-center fw-bold text-success">
                                        +{{ number_format($item['ecoPoints'] ?? 0) }}
                                    </td>
                                    <td>
                                        @if(($item['usado'] ?? false))
                                            <span class="badge bg-secondary">Canjeado</span>
                                        @else
                                            <span class="badge bg-success">Disponible</span>
                                        @endif
                                    </td>
                                    <td class="small text-muted">{{ $txDate }}</td>
                                    <td class="text-end pe-4 print-hide">
                                        <a href="{{ route('admin.transactions.show', $item['id'] ?? '') }}" 
                                           class="btn btn-sm btn-outline-info" title="Ver Detalle">
                                            <i class="bi bi-eye"></i> Detalle
                                        </a>
                                    </td>
                                </tr>
                            @elseif($type === 'users')
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-semibold text-dark">{{ $item->name ?: 'Sin Nombre' }}</div>
                                        <div class="small text-muted font-monospace">{{ \Illuminate\Support\Str::limit($item->uid, 14) }}</div>
                                    </td>
                                    <td>
                                        <span class="small">{{ $item->email }}</span>
                                    </td>
                                    <td>
                                        @if(strtoupper($item->role ?? '') === 'ADMIN')
                                            <span class="badge bg-primary">Administrador</span>
                                        @elseif(strtoupper($item->role ?? '') === 'SUPERVISOR')
                                            <span class="badge bg-info text-dark">Supervisor</span>
                                        @else
                                            <span class="badge bg-secondary">Usuario</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if(strtoupper($item->status ?? 'ACTIVE') === 'ACTIVE')
                                            <span class="badge bg-success">Activo</span>
                                        @elseif(strtoupper($item->status ?? '') === 'SUSPENDED')
                                            <span class="badge bg-warning text-dark">Suspendido</span>
                                        @else
                                            <span class="badge bg-danger">Inactivo</span>
                                        @endif
                                    </td>
                                    <td class="text-center fw-bold text-success">
                                        {{ number_format($item->attributes['ecoPoints'] ?? 0) }}
                                    </td>
                                    <td class="small text-muted">
                                        @if($item->lastLogin instanceof \DateTimeInterface)
                                            {{ $item->lastLogin->format('d/m/Y H:i') }}
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td class="text-end pe-4 print-hide">
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('admin.users.show', $item->uid) }}" class="btn btn-outline-secondary" title="Ver Perfil">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.users.edit', $item->uid) }}" class="btn btn-primary" title="Editar Usuario">
                                                <i class="bi bi-pencil me-1"></i> Editar
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @elseif($type === 'redemptions')
                                @php
                                    $redDate = 'N/A';
                                    $rawRed = $item['createdAt'] ?? null;
                                    if ($rawRed) {
                                        $redDate = is_numeric($rawRed) ? date('d/m/Y H:i', (int)$rawRed) : date('d/m/Y H:i', strtotime((string)$rawRed));
                                    }
                                    $st = strtoupper($item['status'] ?? 'PENDING');
                                @endphp
                                <tr>
                                    <td class="ps-4">
                                        <span class="font-monospace text-muted small">
                                            {{ \Illuminate\Support\Str::limit($item['id'] ?? '', 10) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="small">{{ $item['userId'] ?? 'N/A' }}</span>
                                    </td>
                                    <td>
                                        <span class="fw-semibold text-dark">{{ $item['rewardName'] ?? ($item['rewardId'] ?? 'Recompensa') }}</span>
                                    </td>
                                    <td class="text-center fw-bold text-danger">
                                        -{{ number_format($item['ecoPoints'] ?? ($item['pointsCost'] ?? 0)) }}
                                    </td>
                                    <td class="text-center">
                                        @if($st === 'APPROVED')
                                            <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Aprobado</span>
                                        @elseif($st === 'PENDING')
                                            <span class="badge bg-warning text-dark"><i class="bi bi-hourglass-split me-1"></i>Pendiente</span>
                                        @elseif($st === 'REJECTED')
                                            <span class="badge bg-danger"><i class="bi bi-x-circle me-1"></i>Rechazado</span>
                                        @else
                                            <span class="badge bg-secondary">{{ $st }}</span>
                                        @endif
                                    </td>
                                    <td class="small text-muted">{{ $redDate }}</td>
                                    <td class="text-end pe-4 print-hide">
                                        <a href="{{ route('admin.redemptions.show', $item['id'] ?? '') }}" class="btn btn-sm btn-primary" title="Gestionar Canje">
                                            <i class="bi bi-gear me-1"></i> Gestionar
                                        </a>
                                    </td>
                                </tr>
                            @endif
                        @empty
                            <tr>
                                <td colspan="10" class="text-center py-5 text-muted">
                                    <i class="bi bi-inbox fs-1 d-block mb-2 opacity-50"></i>
                                    No se encontraron registros que coincidan con los filtros aplicados.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white border-top py-3 d-flex justify-content-between align-items-center">
            <span class="small text-muted">
                Mostrando <strong class="text-dark">{{ count($items) }}</strong> registro(s) auditado(s).
            </span>
            <div class="print-hide">
                <a href="#top" class="small text-decoration-none text-secondary">
                    <i class="bi bi-arrow-up"></i> Volver arriba
                </a>
            </div>
        </div>
    </div>
</div>

<style>
@media print {
    .print-hide, .qomer-sidebar, header, .navbar, .breadcrumb {
        display: none !important;
    }
    .print-only {
        display: block !important;
    }
    .qomer-main {
        margin-left: 0 !important;
        padding: 0 !important;
    }
    .card {
        box-shadow: none !important;
        border: 1px solid #dee2e6 !important;
    }
    table {
        font-size: 11px !important;
    }
}
</style>
@endsection

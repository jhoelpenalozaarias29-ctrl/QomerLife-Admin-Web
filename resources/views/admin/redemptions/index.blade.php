@extends('layouts.admin')

@section('title', 'Canjes de Recompensas')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
        <div>
            <h1 class="h3 mb-1 text-gray-800 fw-bold">
                <i class="bi bi-gift me-2 text-info"></i>Canjes de Recompensas
            </h1>
            <p class="text-muted mb-0 small">Control, validación y entrega de beneficios solicitados por los usuarios.</p>
        </div>
        <div>
            <a href="{{ route('admin.reports.index', ['type' => 'redemptions']) }}" class="btn btn-outline-info shadow-sm">
                <i class="bi bi-file-earmark-bar-graph me-1"></i> Ver en Reportes
            </a>
        </div>
    </div>

    {{-- Métricas Rápidas --}}
    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm border-start border-4 border-primary">
                <div class="card-body py-3">
                    <div class="text-xs text-uppercase text-muted fw-bold mb-1">Total Canjes</div>
                    <div class="h4 mb-0 fw-bold text-primary">{{ number_format($kpis['total'] ?? count($redemptions)) }}</div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm border-start border-4 border-danger">
                <div class="card-body py-3">
                    <div class="text-xs text-uppercase text-muted fw-bold mb-1">Puntos Canjeados</div>
                    <div class="h4 mb-0 fw-bold text-danger">{{ number_format($kpis['points'] ?? 0) }} pts</div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm border-start border-4 border-warning">
                <div class="card-body py-3">
                    <div class="text-xs text-uppercase text-muted fw-bold mb-1">Pendientes</div>
                    <div class="h4 mb-0 fw-bold text-warning">{{ number_format($kpis['pending'] ?? 0) }}</div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm border-start border-4 border-success">
                <div class="card-body py-3">
                    <div class="text-xs text-uppercase text-muted fw-bold mb-1">Aprobados</div>
                    <div class="h4 mb-0 fw-bold text-success">{{ number_format($kpis['approved'] ?? 0) }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Barra de Filtros --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form action="{{ route('admin.redemptions.index') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label small fw-semibold text-muted">Búsqueda rápida</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-search"></i></span>
                        <input type="text" name="search" class="form-control border-start-0" 
                               placeholder="ID, usuario o recompensa..." value="{{ $search ?? '' }}">
                    </div>
                </div>
                <div class="col-md-2 col-sm-6">
                    <label class="form-label small fw-semibold text-muted">Fecha Desde</label>
                    <input type="date" name="date_from" class="form-control" value="{{ $date_from ?? '' }}">
                </div>
                <div class="col-md-2 col-sm-6">
                    <label class="form-label small fw-semibold text-muted">Fecha Hasta</label>
                    <input type="date" name="date_to" class="form-control" value="{{ $date_to ?? '' }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-semibold text-muted">Estado</label>
                    <select name="status" class="form-select">
                        <option value="">Todos los estados</option>
                        <option value="PENDING" {{ ($status ?? '') === 'PENDING' ? 'selected' : '' }}>Pendiente</option>
                        <option value="APPROVED" {{ ($status ?? '') === 'APPROVED' ? 'selected' : '' }}>Aprobado</option>
                        <option value="REJECTED" {{ ($status ?? '') === 'REJECTED' ? 'selected' : '' }}>Rechazado</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-grow-1 shadow-sm">
                        <i class="bi bi-funnel-fill me-1"></i> Filtrar
                    </button>
                    @if(!empty($search) || !empty($status) || !empty($date_from) || !empty($date_to))
                        <a href="{{ route('admin.redemptions.index') }}" class="btn btn-outline-secondary" title="Limpiar filtros">
                            <i class="bi bi-x-lg"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    {{-- Tabla de Canjes --}}
    <div class="card shadow-sm mb-4 border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-muted small text-uppercase">
                        <tr>
                            <th class="ps-4">ID Canje</th>
                            <th>Usuario</th>
                            <th>Reward / Producto</th>
                            <th class="text-center">EcoPoints Gastados</th>
                            <th class="text-center">Estado</th>
                            <th>Fecha</th>
                            <th class="text-end pe-4">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($redemptions as $redemption)
                        @php
                            $redId = $redemption['id'] ?? (is_object($redemption) && method_exists($redemption, 'id') ? $redemption->id() : '');
                            $st = strtoupper($redemption['status'] ?? 'PENDING');
                            $redDate = 'N/A';
                            $rawRed = $redemption['createdAt'] ?? null;
                            if ($rawRed) {
                                $redDate = is_numeric($rawRed) ? date('d/m/Y H:i', (int)$rawRed) : date('d/m/Y H:i', strtotime((string)$rawRed));
                            }
                        @endphp
                        <tr>
                            <td class="ps-4 font-monospace text-muted small">{{ \Illuminate\Support\Str::limit($redId, 10) }}</td>
                            <td><span class="small">{{ $redemption['userId'] ?? 'N/A' }}</span></td>
                            <td class="fw-semibold text-dark">{{ $redemption['rewardName'] ?? ($redemption['rewardId'] ?? 'N/A') }}</td>
                            <td class="text-center fw-bold text-danger">-{{ number_format($redemption['ecoPoints'] ?? ($redemption['pointsCost'] ?? 0)) }}</td>
                            <td class="text-center">
                                @if($st === 'PENDING')
                                    <span class="badge bg-warning text-dark px-3 py-2 rounded-pill"><i class="bi bi-hourglass-split me-1"></i>Pendiente</span>
                                @elseif($st === 'APPROVED')
                                    <span class="badge bg-success px-3 py-2 rounded-pill"><i class="bi bi-check-circle me-1"></i>Aprobado</span>
                                @elseif($st === 'REJECTED')
                                    <span class="badge bg-danger px-3 py-2 rounded-pill"><i class="bi bi-x-circle me-1"></i>Rechazado</span>
                                @else
                                    <span class="badge bg-secondary px-3 py-2 rounded-pill">{{ $st }}</span>
                                @endif
                            </td>
                            <td class="small text-muted">{{ $redDate }}</td>
                            <td class="text-end pe-4">
                                <a href="{{ route('admin.redemptions.show', $redId) }}" class="btn btn-sm btn-outline-primary" title="Gestionar Canje">
                                    <i class="bi bi-gear me-1"></i> Gestionar
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="bi bi-gift fs-1 d-block mb-3 opacity-50"></i>
                                No se encontraron canjes con los filtros aplicados.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white border-top py-3 d-flex justify-content-between align-items-center">
            <span class="small text-muted">Total: <strong class="text-dark">{{ count($redemptions) }}</strong> canje(s).</span>
        </div>
    </div>
</div>
@endsection

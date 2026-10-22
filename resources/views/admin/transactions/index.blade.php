@extends('layouts.admin')

@section('title', 'Transacciones de Reciclaje')

@section('content')
<div class="container-fluid px-0">

    {{-- Encabezado --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <h1 class="h3 mb-1 fw-bold" style="color:var(--qomer-text);">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="var(--qomer-emerald)" stroke-width="2" class="me-2" style="vertical-align:-4px"><polyline points="17 1 21 5 17 9"/><path d="M3 11V9a4 4 0 0 1 4-4h14M7 23l-4-4 4-4"/><path d="M21 13v2a4 4 0 0 1-4 4H3"/></svg>
                Transacciones de Reciclaje
            </h1>
            <p class="small mb-0" style="color:var(--qomer-text-muted);">Historial de depósitos y emisión de EcoPoints registrados por las máquinas.</p>
        </div>
        <a href="{{ route('admin.reports.index', ['type' => 'transactions']) }}"
           class="btn btn-sm px-3 py-2 fw-semibold"
           style="background:var(--qomer-surface-elevated);color:var(--qomer-emerald);border:1px solid rgba(0,199,129,.35);border-radius:10px;">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-1"><path d="M18 20V10"/><path d="M12 20V4"/><path d="M6 20v-6"/></svg>
            Ver en Reportes
        </a>
    </div>

    {{-- KPIs --}}
    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="card-stat p-3" style="border-left:3px solid var(--qomer-emerald);">
                <div class="text-xs fw-bold text-uppercase mb-1" style="color:var(--qomer-text-muted);letter-spacing:.08em;">Transacciones</div>
                <div class="h4 mb-0 fw-bold" style="color:var(--qomer-emerald);">{{ number_format($kpis['total'] ?? count($transactions)) }}</div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card-stat p-3" style="border-left:3px solid var(--qomer-amber);">
                <div class="text-xs fw-bold text-uppercase mb-1" style="color:var(--qomer-text-muted);letter-spacing:.08em;">EcoPoints Otorgados</div>
                <div class="h4 mb-0 fw-bold" style="color:var(--qomer-amber);">{{ number_format($kpis['points'] ?? 0) }} <span class="fs-6">pts</span></div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card-stat p-3" style="border-left:3px solid var(--qomer-emerald);">
                <div class="text-xs fw-bold text-uppercase mb-1" style="color:var(--qomer-text-muted);letter-spacing:.08em;">Botellas PET</div>
                <div class="h4 mb-0 fw-bold" style="color:var(--qomer-emerald);">{{ number_format($kpis['bottles'] ?? 0) }} <span class="fs-6">uds</span></div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card-stat p-3" style="border-left:3px solid var(--qomer-cyan);">
                <div class="text-xs fw-bold text-uppercase mb-1" style="color:var(--qomer-text-muted);letter-spacing:.08em;">Latas Aluminio</div>
                <div class="h4 mb-0 fw-bold" style="color:var(--qomer-cyan);">{{ number_format($kpis['cans'] ?? 0) }} <span class="fs-6">uds</span></div>
            </div>
        </div>
    </div>

    {{-- Filtros --}}
    <div class="card-stat mb-4 p-0 overflow-hidden">
        <div class="px-4 py-3" style="border-bottom:1px solid var(--qomer-border);">
            <span class="small fw-semibold text-uppercase" style="color:var(--qomer-text-muted);letter-spacing:.07em;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="var(--qomer-emerald)" stroke-width="2" class="me-1"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
                Filtros de Búsqueda
            </span>
        </div>
        <div class="p-4">
            <form method="GET" action="{{ route('admin.transactions.index') }}" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label small fw-semibold" style="color:var(--qomer-text-muted);">Buscar</label>
                    <div class="input-group">
                        <span class="input-group-text" style="background:var(--qomer-surface-elevated);border-color:var(--qomer-border);color:var(--qomer-text-muted);">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                        </span>
                        <input type="text" class="form-control qomer-input" name="search"
                               placeholder="Token, usuario, máquina..." value="{{ $search ?? '' }}">
                    </div>
                </div>
                <div class="col-md-2 col-sm-6">
                    <label class="form-label small fw-semibold" style="color:var(--qomer-text-muted);">Fecha Desde</label>
                    <input type="date" name="date_from" class="form-control qomer-input" value="{{ $date_from ?? '' }}">
                </div>
                <div class="col-md-2 col-sm-6">
                    <label class="form-label small fw-semibold" style="color:var(--qomer-text-muted);">Fecha Hasta</label>
                    <input type="date" name="date_to" class="form-control qomer-input" value="{{ $date_to ?? '' }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-semibold" style="color:var(--qomer-text-muted);">Material</label>
                    <select name="material" class="form-select qomer-input">
                        <option value="">Todos</option>
                        <option value="PET"      {{ ($material ?? '') === 'PET'      ? 'selected' : '' }}>PET / Plástico</option>
                        <option value="ALUMINIO" {{ ($material ?? '') === 'ALUMINIO' ? 'selected' : '' }}>Aluminio / Lata</option>
                        <option value="VIDRIO"   {{ ($material ?? '') === 'VIDRIO'   ? 'selected' : '' }}>Vidrio</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-qomer flex-grow-1">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-1"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
                        Filtrar
                    </button>
                    @if(!empty($search) || !empty($material) || !empty($date_from) || !empty($date_to))
                        <a href="{{ route('admin.transactions.index') }}" class="btn"
                           style="background:var(--qomer-surface-elevated);color:var(--qomer-text-muted);border:1px solid var(--qomer-border);" title="Limpiar">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6 6 18M6 6l12 12"/></svg>
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    {{-- Tabla --}}
    <div class="card-stat p-0 overflow-hidden">
        <div class="table-responsive">
            <table class="table mb-0 align-middle">
                <thead>
                    <tr style="background:var(--qomer-surface-elevated);border-bottom:1px solid var(--qomer-border);">
                        <th class="ps-4 py-3 text-xs fw-bold text-uppercase" style="color:var(--qomer-text-muted);letter-spacing:.08em;">Token</th>
                        <th class="py-3 text-xs fw-bold text-uppercase" style="color:var(--qomer-text-muted);letter-spacing:.08em;">Máquina</th>
                        <th class="py-3 text-xs fw-bold text-uppercase" style="color:var(--qomer-text-muted);letter-spacing:.08em;">Usuario (ID)</th>
                        <th class="py-3 text-xs fw-bold text-uppercase" style="color:var(--qomer-text-muted);letter-spacing:.08em;">Material</th>
                        <th class="py-3 text-xs fw-bold text-uppercase text-center" style="color:var(--qomer-text-muted);letter-spacing:.08em;">PET</th>
                        <th class="py-3 text-xs fw-bold text-uppercase text-center" style="color:var(--qomer-text-muted);letter-spacing:.08em;">Aluminio</th>
                        <th class="py-3 text-xs fw-bold text-uppercase text-center" style="color:var(--qomer-text-muted);letter-spacing:.08em;">EcoPoints</th>
                        <th class="py-3 text-xs fw-bold text-uppercase" style="color:var(--qomer-text-muted);letter-spacing:.08em;">Estado</th>
                        <th class="py-3 text-xs fw-bold text-uppercase" style="color:var(--qomer-text-muted);letter-spacing:.08em;">Fecha</th>
                        <th class="py-3 text-xs fw-bold text-uppercase text-center pe-4" style="color:var(--qomer-text-muted);letter-spacing:.08em;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $transaction)
                    <tr class="qomer-table-row" style="border-bottom:1px solid var(--qomer-border);">
                        <td class="ps-4 py-3">
                            <span class="font-mono small" style="color:var(--qomer-emerald);">
                                {{ \Illuminate\Support\Str::limit($transaction['token'] ?? $transaction['id'] ?? '', 10) }}
                            </span>
                        </td>
                        <td class="py-3">
                            <span class="badge" style="background:rgba(100,116,139,.15);color:#94a3b8;border:1px solid rgba(100,116,139,.3);font-family:monospace;">
                                {{ $transaction['machineId'] ?? 'N/A' }}
                            </span>
                        </td>
                        <td class="py-3">
                            <span class="small font-mono" style="color:var(--qomer-text-muted);">
                                {{ \Illuminate\Support\Str::limit($transaction['userId'] ?? 'N/A', 18) }}
                            </span>
                        </td>
                        <td class="py-3">
                            @php $mat = strtoupper($transaction['material'] ?? ''); @endphp
                            @if($mat === 'PET')
                                <span class="badge-emerald">
                                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-1"><path d="M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10z"/></svg>PET
                                </span>
                            @elseif(str_contains($mat, 'ALUM') || str_contains($mat, 'LAT'))
                                <span class="badge-cyan">
                                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-1"><circle cx="12" cy="12" r="10"/></svg>Aluminio
                                </span>
                            @elseif(str_contains($mat, 'VIDRIO') || str_contains($mat, 'GLASS'))
                                <span class="badge" style="background:rgba(139,92,246,.15);color:#a78bfa;border:1px solid rgba(139,92,246,.3);">Vidrio</span>
                            @else
                                <span class="badge" style="background:rgba(100,116,139,.15);color:#94a3b8;border:1px solid rgba(100,116,139,.3);">{{ $mat ?: 'N/A' }}</span>
                            @endif
                        </td>
                        <td class="py-3 text-center">
                            @php $pet = (int)($transaction['plasticBottlesCount'] ?? 0); @endphp
                            @if($pet > 0)
                                <span class="fw-bold" style="color:var(--qomer-emerald);">{{ $pet }}</span>
                            @else
                                <span style="color:var(--qomer-text-muted);">—</span>
                            @endif
                        </td>
                        <td class="py-3 text-center">
                            @php $alu = (int)($transaction['aluminumCansCount'] ?? 0); @endphp
                            @if($alu > 0)
                                <span class="fw-bold" style="color:var(--qomer-cyan);">{{ $alu }}</span>
                            @else
                                <span style="color:var(--qomer-text-muted);">—</span>
                            @endif
                        </td>
                        <td class="py-3 text-center">
                            <span class="fw-bold font-mono" style="color:var(--qomer-amber);">+{{ number_format($transaction['ecoPoints'] ?? 0) }}</span>
                        </td>
                        <td class="py-3">
                            @php $estado = strtoupper($transaction['estado'] ?? ''); @endphp
                            @if($estado === 'USADA')
                                <span class="badge-emerald">Usada</span>
                            @elseif($estado === 'PENDIENTE')
                                <span class="badge-amber">Pendiente</span>
                            @elseif($estado === 'EXPIRADA')
                                <span class="badge" style="background:rgba(239,68,68,.15);color:#f87171;border:1px solid rgba(239,68,68,.3);">Expirada</span>
                            @else
                                <span class="badge" style="background:rgba(100,116,139,.15);color:#94a3b8;border:1px solid rgba(100,116,139,.3);">{{ $transaction['estado'] ?? 'N/A' }}</span>
                            @endif
                        </td>
                        <td class="py-3 small" style="color:var(--qomer-text-muted);">
                            @php
                                $fecha = $transaction['fechaCreacion'] ?? $transaction['createdAt'] ?? null;
                                if ($fecha && is_object($fecha) && method_exists($fecha, 'get')) {
                                    echo $fecha->get()->format('d/m/Y H:i');
                                } elseif ($fecha instanceof \DateTimeInterface) {
                                    echo $fecha->format('d/m/Y H:i');
                                } elseif (is_numeric($fecha)) {
                                    echo date('d/m/Y H:i', (int)($fecha > 1e10 ? $fecha/1000 : $fecha));
                                } elseif ($fecha) {
                                    echo e($fecha);
                                } else {
                                    echo '<span style="color:var(--qomer-text-muted)">N/A</span>';
                                }
                            @endphp
                        </td>
                        <td class="py-3 text-center pe-4">
                            <a href="{{ route('admin.transactions.show', $transaction['id'] ?? '') }}"
                               class="btn btn-sm" style="background:rgba(6,182,212,.12);color:var(--qomer-cyan);border:1px solid rgba(6,182,212,.3);border-radius:8px;" title="Ver Detalles">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center py-5">
                            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="var(--qomer-text-muted)" stroke-width="1" class="mb-3 d-block mx-auto opacity-50"><polyline points="17 1 21 5 17 9"/><path d="M3 11V9a4 4 0 0 1 4-4h14M7 23l-4-4 4-4"/><path d="M21 13v2a4 4 0 0 1-4 4H3"/></svg>
                            <p style="color:var(--qomer-text-muted);">No se encontraron transacciones.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
.qomer-table-row { transition: background .18s; }
.qomer-table-row:hover { background: rgba(0,199,129,.04) !important; }
.qomer-table-row:last-child { border-bottom: none !important; }
</style>
@endsection

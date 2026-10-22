@extends('layouts.admin')

@section('title', 'Canjes de Recompensas')

@section('content')
<div class="container-fluid px-0">

    {{-- Encabezado --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <h1 class="h3 mb-1 fw-bold" style="color:var(--qomer-text);">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="var(--qomer-cyan)" stroke-width="2" class="me-2" style="vertical-align:-4px"><polyline points="20 12 20 22 4 22 4 12"/><rect x="2" y="7" width="20" height="5"/><path d="M12 22V7"/><path d="M12 7H7.5a2.5 2.5 0 0 1 0-5C11 2 12 7 12 7z"/><path d="M12 7h4.5a2.5 2.5 0 0 0 0-5C13 2 12 7 12 7z"/></svg>
                Canjes de Recompensas
            </h1>
            <p class="small mb-0" style="color:var(--qomer-text-muted);">Control, validación y entrega de beneficios solicitados por los usuarios.</p>
        </div>
        <a href="{{ route('admin.reports.index', ['type' => 'redemptions']) }}"
           class="btn btn-sm px-3 py-2 fw-semibold"
           style="background:var(--qomer-surface-elevated);color:var(--qomer-cyan);border:1px solid rgba(6,182,212,.35);border-radius:10px;">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-1"><path d="M18 20V10"/><path d="M12 20V4"/><path d="M6 20v-6"/></svg>
            Ver en Reportes
        </a>
    </div>

    {{-- KPIs --}}
    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="card-stat p-3" style="border-left:3px solid var(--qomer-emerald);">
                <div class="text-xs fw-bold text-uppercase mb-1" style="color:var(--qomer-text-muted);letter-spacing:.08em;">Total Canjes</div>
                <div class="h4 mb-0 fw-bold" style="color:var(--qomer-emerald);">{{ number_format($kpis['total'] ?? count($redemptions)) }}</div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card-stat p-3" style="border-left:3px solid #f87171;">
                <div class="text-xs fw-bold text-uppercase mb-1" style="color:var(--qomer-text-muted);letter-spacing:.08em;">Puntos Canjeados</div>
                <div class="h4 mb-0 fw-bold" style="color:#f87171;">{{ number_format($kpis['points'] ?? 0) }} <span class="fs-6">pts</span></div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card-stat p-3" style="border-left:3px solid var(--qomer-amber);">
                <div class="text-xs fw-bold text-uppercase mb-1" style="color:var(--qomer-text-muted);letter-spacing:.08em;">Pendientes</div>
                <div class="h4 mb-0 fw-bold" style="color:var(--qomer-amber);">{{ number_format($kpis['pending'] ?? 0) }}</div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card-stat p-3" style="border-left:3px solid var(--qomer-emerald);">
                <div class="text-xs fw-bold text-uppercase mb-1" style="color:var(--qomer-text-muted);letter-spacing:.08em;">Aprobados</div>
                <div class="h4 mb-0 fw-bold" style="color:var(--qomer-emerald);">{{ number_format($kpis['approved'] ?? 0) }}</div>
            </div>
        </div>
    </div>

    {{-- Filtros --}}
    <div class="card-stat mb-4 p-0 overflow-hidden">
        <div class="px-4 py-3" style="border-bottom:1px solid var(--qomer-border);">
            <span class="small fw-semibold text-uppercase" style="color:var(--qomer-text-muted);letter-spacing:.07em;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="var(--qomer-cyan)" stroke-width="2" class="me-1"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
                Filtros de Búsqueda
            </span>
        </div>
        <div class="p-4">
            <form action="{{ route('admin.redemptions.index') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label small fw-semibold" style="color:var(--qomer-text-muted);">Búsqueda rápida</label>
                    <div class="input-group">
                        <span class="input-group-text" style="background:var(--qomer-surface-elevated);border-color:var(--qomer-border);color:var(--qomer-text-muted);">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                        </span>
                        <input type="text" name="search" class="form-control qomer-input"
                               placeholder="ID, usuario o recompensa..." value="{{ $search ?? '' }}">
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
                    <label class="form-label small fw-semibold" style="color:var(--qomer-text-muted);">Estado</label>
                    <select name="status" class="form-select qomer-input">
                        <option value="">Todos</option>
                        <option value="PENDING"  {{ ($status ?? '') === 'PENDING'  ? 'selected' : '' }}>Pendiente</option>
                        <option value="APPROVED" {{ ($status ?? '') === 'APPROVED' ? 'selected' : '' }}>Aprobado</option>
                        <option value="REJECTED" {{ ($status ?? '') === 'REJECTED' ? 'selected' : '' }}>Rechazado</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-qomer flex-grow-1">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-1"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
                        Filtrar
                    </button>
                    @if(!empty($search) || !empty($status) || !empty($date_from) || !empty($date_to))
                        <a href="{{ route('admin.redemptions.index') }}" class="btn"
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
                        <th class="ps-4 py-3 text-xs fw-bold text-uppercase" style="color:var(--qomer-text-muted);letter-spacing:.08em;">ID Canje</th>
                        <th class="py-3 text-xs fw-bold text-uppercase" style="color:var(--qomer-text-muted);letter-spacing:.08em;">Usuario</th>
                        <th class="py-3 text-xs fw-bold text-uppercase" style="color:var(--qomer-text-muted);letter-spacing:.08em;">Reward / Producto</th>
                        <th class="py-3 text-xs fw-bold text-uppercase text-center" style="color:var(--qomer-text-muted);letter-spacing:.08em;">Puntos Gastados</th>
                        <th class="py-3 text-xs fw-bold text-uppercase text-center" style="color:var(--qomer-text-muted);letter-spacing:.08em;">Estado</th>
                        <th class="py-3 text-xs fw-bold text-uppercase" style="color:var(--qomer-text-muted);letter-spacing:.08em;">Fecha</th>
                        <th class="py-3 text-xs fw-bold text-uppercase text-end pe-4" style="color:var(--qomer-text-muted);letter-spacing:.08em;">Acciones</th>
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
                    <tr class="qomer-table-row" style="border-bottom:1px solid var(--qomer-border);">
                        <td class="ps-4 py-3">
                            <span class="font-mono small" style="color:var(--qomer-text-muted);">{{ \Illuminate\Support\Str::limit($redId, 10) }}</span>
                        </td>
                        <td class="py-3">
                            <span class="small" style="color:var(--qomer-text-muted);">{{ $redemption['userId'] ?? 'N/A' }}</span>
                        </td>
                        <td class="py-3">
                            <span class="fw-semibold" style="color:var(--qomer-text);">{{ $redemption['rewardName'] ?? ($redemption['rewardId'] ?? 'N/A') }}</span>
                        </td>
                        <td class="py-3 text-center">
                            <span class="fw-bold font-mono" style="color:#f87171;">-{{ number_format($redemption['ecoPoints'] ?? ($redemption['pointsCost'] ?? 0)) }}</span>
                        </td>
                        <td class="py-3 text-center">
                            @if($st === 'PENDING')
                                <span class="badge-amber">
                                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-1"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>Pendiente
                                </span>
                            @elseif($st === 'APPROVED')
                                <span class="badge-emerald">
                                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-1"><polyline points="20 6 9 17 4 12"/></svg>Aprobado
                                </span>
                            @elseif($st === 'REJECTED')
                                <span class="badge" style="background:rgba(239,68,68,.15);color:#f87171;border:1px solid rgba(239,68,68,.3);">
                                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-1"><path d="M18 6 6 18M6 6l12 12"/></svg>Rechazado
                                </span>
                            @else
                                <span class="badge" style="background:rgba(100,116,139,.15);color:#94a3b8;border:1px solid rgba(100,116,139,.3);">{{ $st }}</span>
                            @endif
                        </td>
                        <td class="py-3 small" style="color:var(--qomer-text-muted);">{{ $redDate }}</td>
                        <td class="py-3 text-end pe-4">
                            <a href="{{ route('admin.redemptions.show', $redId) }}"
                               class="btn btn-sm px-3" style="background:rgba(0,199,129,.12);color:var(--qomer-emerald);border:1px solid rgba(0,199,129,.3);border-radius:8px;" title="Gestionar Canje">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-1"><circle cx="12" cy="12" r="3"/><path d="M19.07 4.93a10 10 0 0 1 0 14.14M4.93 4.93a10 10 0 0 0 0 14.14"/></svg>
                                Gestionar
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="var(--qomer-text-muted)" stroke-width="1" class="mb-3 d-block mx-auto opacity-50"><polyline points="20 12 20 22 4 22 4 12"/><rect x="2" y="7" width="20" height="5"/><path d="M12 22V7"/></svg>
                            <p style="color:var(--qomer-text-muted);">No se encontraron canjes con los filtros aplicados.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 d-flex justify-content-between align-items-center" style="border-top:1px solid var(--qomer-border);background:var(--qomer-surface-elevated);">
            <span class="small" style="color:var(--qomer-text-muted);">
                Total: <strong style="color:var(--qomer-text);">{{ count($redemptions) }}</strong> canje(s).
            </span>
        </div>
    </div>
</div>

<style>
.qomer-table-row { transition: background .18s; }
.qomer-table-row:hover { background: rgba(0,199,129,.04) !important; }
.qomer-table-row:last-child { border-bottom: none !important; }
</style>
@endsection

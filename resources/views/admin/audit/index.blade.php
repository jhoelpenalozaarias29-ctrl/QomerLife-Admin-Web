@extends('layouts.admin')

@section('title', 'Registros de Auditoría')

@section('content')
<div class="container-fluid px-0">

    {{-- Encabezado --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <h1 class="h3 mb-1 fw-bold" style="color:var(--qomer-text);">
                <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="var(--qomer-emerald)" stroke-width="2" class="me-2" style="vertical-align:-4px"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><polyline points="9 12 11 14 15 10"/></svg>
                Registros de Auditoría
            </h1>
            <p class="small mb-0" style="color:var(--qomer-text-muted);">Historial de acciones administrativas, cambios y eventos de seguridad del sistema.</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <span class="status-pulse"></span>
            <span class="small fw-semibold" style="color:var(--qomer-emerald);">Sistema monitoreado</span>
        </div>
    </div>

    {{-- Tabla de Auditoría --}}
    <div class="card-stat p-0 overflow-hidden">
        <div class="px-4 py-3 d-flex justify-content-between align-items-center" style="border-bottom:1px solid var(--qomer-border);background:var(--qomer-surface-elevated);">
            <span class="fw-semibold" style="color:var(--qomer-text);">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--qomer-emerald)" stroke-width="2" class="me-2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                Log de Eventos del Sistema
            </span>
            <span class="badge" style="background:rgba(0,199,129,.1);color:var(--qomer-emerald);border:1px solid rgba(0,199,129,.3);">
                {{ count($logs) }} registro(s)
            </span>
        </div>

        <div class="table-responsive">
            <table class="table mb-0 align-middle">
                <thead>
                    <tr style="background:var(--qomer-surface-elevated);border-bottom:1px solid var(--qomer-border);">
                        <th class="ps-4 py-3 text-xs fw-bold text-uppercase" style="color:var(--qomer-text-muted);letter-spacing:.08em;width:15%;">Fecha / Hora</th>
                        <th class="py-3 text-xs fw-bold text-uppercase" style="color:var(--qomer-text-muted);letter-spacing:.08em;width:15%;">Acción</th>
                        <th class="py-3 text-xs fw-bold text-uppercase" style="color:var(--qomer-text-muted);letter-spacing:.08em;width:40%;">Descripción</th>
                        <th class="py-3 text-xs fw-bold text-uppercase" style="color:var(--qomer-text-muted);letter-spacing:.08em;width:15%;">Usuario</th>
                        <th class="py-3 pe-4 text-xs fw-bold text-uppercase" style="color:var(--qomer-text-muted);letter-spacing:.08em;width:15%;">IP</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                    @php
                        $action = $log['action'] ?? '';
                        $actionLower = strtolower($action);

                        // Determinar tipo de acción
                        if (str_contains($actionLower, 'create') || str_contains($actionLower, 'add') || str_contains($actionLower, 'store')) {
                            $badgeColor = 'rgba(0,199,129,.15)';
                            $textColor = 'var(--qomer-emerald)';
                            $borderColor = 'rgba(0,199,129,.3)';
                            $icon = '<path d="M12 5v14M5 12h14"/>';
                        } elseif (str_contains($actionLower, 'update') || str_contains($actionLower, 'edit') || str_contains($actionLower, 'patch')) {
                            $badgeColor = 'rgba(245,158,11,.15)';
                            $textColor = 'var(--qomer-amber)';
                            $borderColor = 'rgba(245,158,11,.3)';
                            $icon = '<path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>';
                        } elseif (str_contains($actionLower, 'delete') || str_contains($actionLower, 'remove') || str_contains($actionLower, 'destroy')) {
                            $badgeColor = 'rgba(239,68,68,.15)';
                            $textColor = '#f87171';
                            $borderColor = 'rgba(239,68,68,.3)';
                            $icon = '<polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/>';
                        } elseif (str_contains($actionLower, 'login') || str_contains($actionLower, 'auth') || str_contains($actionLower, 'logout')) {
                            $badgeColor = 'rgba(6,182,212,.15)';
                            $textColor = 'var(--qomer-cyan)';
                            $borderColor = 'rgba(6,182,212,.3)';
                            $icon = '<path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/>';
                        } else {
                            $badgeColor = 'rgba(100,116,139,.15)';
                            $textColor = '#94a3b8';
                            $borderColor = 'rgba(100,116,139,.3)';
                            $icon = '<circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>';
                        }
                    @endphp
                    <tr class="qomer-table-row" style="border-bottom:1px solid var(--qomer-border);">
                        <td class="ps-4 py-3">
                            @if(isset($log['createdAt']))
                                <div class="fw-semibold small" style="color:var(--qomer-text);">{{ \Carbon\Carbon::parse($log['createdAt'])->format('d/m/Y') }}</div>
                                <div class="font-mono" style="font-size:11px;color:var(--qomer-text-muted);">{{ \Carbon\Carbon::parse($log['createdAt'])->format('H:i:s') }}</div>
                            @else
                                <span style="color:var(--qomer-text-muted);">N/A</span>
                            @endif
                        </td>
                        <td class="py-3">
                            <span class="badge d-inline-flex align-items-center gap-1"
                                  style="background:{{ $badgeColor }};color:{{ $textColor }};border:1px solid {{ $borderColor }};border-radius:8px;padding:5px 10px;">
                                <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">{!! $icon !!}</svg>
                                {{ $action }}
                            </span>
                        </td>
                        <td class="py-3 small" style="color:var(--qomer-text);">{{ $log['description'] ?? '' }}</td>
                        <td class="py-3">
                            <div class="fw-semibold small" style="color:var(--qomer-text);">{{ $log['userEmail'] ?? 'Sistema' }}</div>
                            @if(isset($log['userId']))
                                <div class="font-mono" style="font-size:11px;color:var(--qomer-text-muted);" title="ID: {{ $log['userId'] }}">
                                    {{ Str::limit($log['userId'], 8) }}
                                </div>
                            @endif
                        </td>
                        <td class="py-3 pe-4 font-mono small" style="color:var(--qomer-text-muted);">
                            {{ $log['ipAddress'] ?? 'N/A' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5">
                            <svg width="56" height="56" viewBox="0 0 24 24" fill="none" stroke="var(--qomer-text-muted)" stroke-width="1" class="mb-3 d-block mx-auto opacity-40"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><polyline points="9 12 11 14 15 10"/></svg>
                            <p class="fw-semibold mb-1" style="color:var(--qomer-text);">Sin registros de auditoría</p>
                            <p class="small" style="color:var(--qomer-text-muted);">No hay registros de auditoría disponibles en este momento.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-4 py-3 d-flex justify-content-between align-items-center" style="border-top:1px solid var(--qomer-border);background:var(--qomer-surface-elevated);">
            <span class="small" style="color:var(--qomer-text-muted);">
                Total: <strong style="color:var(--qomer-text);">{{ count($logs) }}</strong> evento(s) registrado(s).
            </span>
            <span class="small" style="color:var(--qomer-text-muted);">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-1"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                Logs auditados y protegidos en Firestore.
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

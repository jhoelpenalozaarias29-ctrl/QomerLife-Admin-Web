@extends('layouts.admin')

@section('title', 'Gestión de Usuarios')

@section('content')
<div class="container-fluid px-0">

    {{-- Encabezado --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <h1 class="h3 mb-1 fw-bold" style="color: var(--qomer-text);">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="var(--qomer-emerald)" stroke-width="2" class="me-2" style="vertical-align:-4px"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                Gestión de Usuarios
            </h1>
            <p style="color: var(--qomer-text-muted);" class="mb-0 small">Administración integral de cuentas, roles y control de EcoPoints.</p>
        </div>
        <a href="{{ route('admin.reports.index', ['type' => 'users']) }}"
           class="btn btn-sm px-3 py-2 fw-semibold"
           style="background:var(--qomer-surface-elevated);color:var(--qomer-emerald);border:1px solid rgba(0,199,129,.35);border-radius:10px;transition:.2s;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-1"><path d="M18 20V10"/><path d="M12 20V4"/><path d="M6 20v-6"/></svg>
            Ver en Reportes
        </a>
    </div>

    {{-- KPIs --}}
    <div class="row g-3 mb-4">
        <div class="col-sm-4">
            <div class="card-stat p-3" style="border-left:3px solid var(--qomer-emerald);">
                <div class="text-xs fw-bold text-uppercase mb-1" style="color:var(--qomer-text-muted);letter-spacing:.08em;">Usuarios Filtrados</div>
                <div class="h4 mb-0 fw-bold" style="color:var(--qomer-emerald);">{{ number_format($kpis['total'] ?? count($users)) }}</div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="card-stat p-3" style="border-left:3px solid var(--qomer-amber);">
                <div class="text-xs fw-bold text-uppercase mb-1" style="color:var(--qomer-text-muted);letter-spacing:.08em;">Administradores</div>
                <div class="h4 mb-0 fw-bold" style="color:var(--qomer-amber);">{{ number_format($kpis['admins'] ?? 0) }}</div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="card-stat p-3" style="border-left:3px solid var(--qomer-cyan);">
                <div class="text-xs fw-bold text-uppercase mb-1" style="color:var(--qomer-text-muted);letter-spacing:.08em;">EcoPoints Acumulados</div>
                <div class="h4 mb-0 fw-bold" style="color:var(--qomer-cyan);">{{ number_format($kpis['points'] ?? 0) }} <span class="fs-6">pts</span></div>
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
            <form action="{{ route('admin.users.index') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-5">
                    <label class="form-label small fw-semibold" style="color:var(--qomer-text-muted);">Búsqueda rápida</label>
                    <div class="input-group">
                        <span class="input-group-text" style="background:var(--qomer-surface-elevated);border-color:var(--qomer-border);color:var(--qomer-text-muted);">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                        </span>
                        <input type="text" name="search" class="form-control qomer-input"
                               placeholder="Nombre, email o UID..." value="{{ $search ?? '' }}">
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <label class="form-label small fw-semibold" style="color:var(--qomer-text-muted);">Rol</label>
                    <select name="role" class="form-select qomer-input">
                        <option value="">Todos los roles</option>
                        <option value="USER" {{ ($role ?? '') === 'USER' ? 'selected' : '' }}>Usuario</option>
                        <option value="ADMIN" {{ ($role ?? '') === 'ADMIN' ? 'selected' : '' }}>Administrador</option>
                        <option value="SUPERVISOR" {{ ($role ?? '') === 'SUPERVISOR' ? 'selected' : '' }}>Supervisor</option>
                    </select>
                </div>
                <div class="col-md-2 col-sm-6">
                    <label class="form-label small fw-semibold" style="color:var(--qomer-text-muted);">Estado</label>
                    <select name="status" class="form-select qomer-input">
                        <option value="">Todos</option>
                        <option value="ACTIVE" {{ ($status ?? '') === 'ACTIVE' ? 'selected' : '' }}>Activo</option>
                        <option value="SUSPENDED" {{ ($status ?? '') === 'SUSPENDED' ? 'selected' : '' }}>Suspendido</option>
                        <option value="INACTIVE" {{ ($status ?? '') === 'INACTIVE' ? 'selected' : '' }}>Inactivo</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-qomer flex-grow-1">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-1"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
                        Filtrar
                    </button>
                    @if(!empty($search) || !empty($role) || !empty($status))
                        <a href="{{ route('admin.users.index') }}" class="btn"
                           style="background:var(--qomer-surface-elevated);color:var(--qomer-text-muted);border:1px solid var(--qomer-border);" title="Limpiar">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6 6 18M6 6l12 12"/></svg>
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    {{-- Tabla de Usuarios --}}
    <div class="card-stat p-0 overflow-hidden">
        <div class="table-responsive">
            <table class="table mb-0 align-middle" style="border-collapse:separate;">
                <thead>
                    <tr style="background:var(--qomer-surface-elevated);border-bottom:1px solid var(--qomer-border);">
                        <th class="ps-4 py-3 text-xs fw-bold text-uppercase" style="color:var(--qomer-text-muted);letter-spacing:.08em;">Usuario</th>
                        <th class="py-3 text-xs fw-bold text-uppercase" style="color:var(--qomer-text-muted);letter-spacing:.08em;">Email</th>
                        <th class="py-3 text-xs fw-bold text-uppercase" style="color:var(--qomer-text-muted);letter-spacing:.08em;">Rol</th>
                        <th class="py-3 text-xs fw-bold text-uppercase" style="color:var(--qomer-text-muted);letter-spacing:.08em;">Estado</th>
                        <th class="py-3 text-xs fw-bold text-uppercase text-center" style="color:var(--qomer-text-muted);letter-spacing:.08em;">EcoPoints</th>
                        <th class="py-3 text-xs fw-bold text-uppercase text-end pe-4" style="color:var(--qomer-text-muted);letter-spacing:.08em;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users ?? [] as $user)
                        <tr class="qomer-table-row" style="border-bottom:1px solid var(--qomer-border);">
                            <td class="ps-4 py-3">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="d-flex align-items-center justify-content-center fw-bold rounded-circle"
                                         style="width:38px;height:38px;background:linear-gradient(135deg,rgba(0,199,129,.2),rgba(6,182,212,.2));color:var(--qomer-emerald);font-size:14px;border:1px solid rgba(0,199,129,.3);">
                                        {{ strtoupper(substr($user->name ?: 'U', 0, 2)) }}
                                    </div>
                                    <div>
                                        <div class="fw-semibold" style="color:var(--qomer-text);">{{ $user->name ?: 'Sin Nombre' }}</div>
                                        <div class="small font-mono" style="color:var(--qomer-text-muted);">{{ \Illuminate\Support\Str::limit($user->uid, 12) }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3"><span class="small" style="color:var(--qomer-text-muted);">{{ $user->email ?? 'N/A' }}</span></td>
                            <td class="py-3">
                                @if(($user->role ?? '') === 'ADMIN')
                                    <span class="badge-emerald">Admin</span>
                                @elseif(($user->role ?? '') === 'SUPERVISOR')
                                    <span class="badge-cyan">Supervisor</span>
                                @else
                                    <span class="badge" style="background:rgba(100,116,139,.15);color:#94a3b8;border:1px solid rgba(100,116,139,.3);">Usuario</span>
                                @endif
                            </td>
                            <td class="py-3">
                                @if(($user->status ?? 'ACTIVE') === 'ACTIVE')
                                    <span class="badge-emerald"><span class="status-pulse me-1"></span>Activo</span>
                                @elseif(($user->status ?? '') === 'SUSPENDED')
                                    <span class="badge-amber">Suspendido</span>
                                @else
                                    <span class="badge" style="background:rgba(239,68,68,.15);color:#f87171;border:1px solid rgba(239,68,68,.3);">Inactivo</span>
                                @endif
                            </td>
                            <td class="py-3 text-center">
                                <span class="fw-bold font-mono" style="color:var(--qomer-amber);">
                                    {{ number_format($user->attributes['ecoPoints'] ?? 0) }}
                                </span>
                            </td>
                            <td class="py-3 text-end pe-4">
                                <div class="d-flex gap-2 justify-content-end">
                                    <a href="{{ route('admin.users.show', $user->uid) }}"
                                       class="btn btn-sm" style="background:rgba(6,182,212,.12);color:var(--qomer-cyan);border:1px solid rgba(6,182,212,.3);border-radius:8px;" title="Ver detalle">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                    </a>
                                    <a href="{{ route('admin.users.edit', $user->uid) }}"
                                       class="btn btn-sm" style="background:rgba(0,199,129,.12);color:var(--qomer-emerald);border:1px solid rgba(0,199,129,.3);border-radius:8px;" title="Editar">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                    </a>
                                    <form action="{{ route('admin.users.destroy', $user->uid) }}" method="POST" class="d-inline"
                                          onsubmit="return confirm('¿Confirmas eliminar este usuario?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm"
                                                style="background:rgba(239,68,68,.12);color:#f87171;border:1px solid rgba(239,68,68,.3);border-radius:8px;" title="Eliminar">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="var(--qomer-text-muted)" stroke-width="1" class="mb-3 d-block mx-auto opacity-50"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                                <p style="color:var(--qomer-text-muted);">No se encontraron usuarios con los criterios especificados.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 d-flex justify-content-between align-items-center" style="border-top:1px solid var(--qomer-border);background:var(--qomer-surface-elevated);">
            <span class="small" style="color:var(--qomer-text-muted);">
                Total: <strong style="color:var(--qomer-text);">{{ count($users) }}</strong> usuario(s) encontrado(s).
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

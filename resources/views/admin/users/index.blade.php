@extends('layouts.admin')

@section('title', 'Gestión de Usuarios')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
        <div>
            <h1 class="h3 mb-1 text-gray-800 fw-bold">
                <i class="bi bi-people me-2 text-primary"></i>Gestión de Usuarios
            </h1>
            <p class="text-muted mb-0 small">Administración integral de cuentas, asignación de roles y control de EcoPoints.</p>
        </div>
        <div>
            <a href="{{ route('admin.reports.index', ['type' => 'users']) }}" class="btn btn-outline-primary shadow-sm">
                <i class="bi bi-file-earmark-bar-graph me-1"></i> Ver en Reportes
            </a>
        </div>
    </div>

    {{-- Métricas Rápidas --}}
    <div class="row g-3 mb-4">
        <div class="col-sm-4">
            <div class="card border-0 shadow-sm border-start border-4 border-primary">
                <div class="card-body py-3">
                    <div class="text-xs text-uppercase text-muted fw-bold mb-1">Usuarios Filtrados</div>
                    <div class="h4 mb-0 fw-bold text-primary">{{ number_format($kpis['total'] ?? count($users)) }}</div>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="card border-0 shadow-sm border-start border-4 border-warning">
                <div class="card-body py-3">
                    <div class="text-xs text-uppercase text-muted fw-bold mb-1">Administradores</div>
                    <div class="h4 mb-0 fw-bold text-warning">{{ number_format($kpis['admins'] ?? 0) }}</div>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="card border-0 shadow-sm border-start border-4 border-success">
                <div class="card-body py-3">
                    <div class="text-xs text-uppercase text-muted fw-bold mb-1">EcoPoints Acumulados</div>
                    <div class="h4 mb-0 fw-bold text-success">{{ number_format($kpis['points'] ?? 0) }} pts</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Barra de Filtros --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form action="{{ route('admin.users.index') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-5">
                    <label class="form-label small fw-semibold text-muted">Búsqueda rápida</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-search"></i></span>
                        <input type="text" name="search" class="form-control border-start-0" 
                               placeholder="Nombre, email o UID..." value="{{ $search ?? '' }}">
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <label class="form-label small fw-semibold text-muted">Rol</label>
                    <select name="role" class="form-select">
                        <option value="">Todos los roles</option>
                        <option value="USER" {{ ($role ?? '') === 'USER' ? 'selected' : '' }}>Usuario</option>
                        <option value="ADMIN" {{ ($role ?? '') === 'ADMIN' ? 'selected' : '' }}>Administrador</option>
                        <option value="SUPERVISOR" {{ ($role ?? '') === 'SUPERVISOR' ? 'selected' : '' }}>Supervisor</option>
                    </select>
                </div>
                <div class="col-md-2 col-sm-6">
                    <label class="form-label small fw-semibold text-muted">Estado</label>
                    <select name="status" class="form-select">
                        <option value="">Todos</option>
                        <option value="ACTIVE" {{ ($status ?? '') === 'ACTIVE' ? 'selected' : '' }}>Activo</option>
                        <option value="SUSPENDED" {{ ($status ?? '') === 'SUSPENDED' ? 'selected' : '' }}>Suspendido</option>
                        <option value="INACTIVE" {{ ($status ?? '') === 'INACTIVE' ? 'selected' : '' }}>Inactivo</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-grow-1 shadow-sm">
                        <i class="bi bi-funnel-fill me-1"></i> Filtrar
                    </button>
                    @if(!empty($search) || !empty($role) || !empty($status))
                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary" title="Limpiar filtros">
                            <i class="bi bi-x-lg"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    {{-- Tabla de Usuarios --}}
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light text-muted small text-uppercase">
                        <tr>
                            <th class="ps-4">Usuario</th>
                            <th>Email</th>
                            <th>Rol</th>
                            <th>Estado</th>
                            <th class="text-center">EcoPoints</th>
                            <th class="text-end pe-4">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users ?? [] as $user)
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="avatar-initials bg-primary-subtle text-primary fw-bold rounded-circle d-flex align-items-center justify-content-center" style="width: 38px; height: 38px; font-size: 14px;">
                                            {{ strtoupper(substr($user->name ?: 'U', 0, 2)) }}
                                        </div>
                                        <div>
                                            <div class="fw-semibold text-dark">{{ $user->name ?: 'Sin Nombre' }}</div>
                                            <div class="small text-muted font-monospace">{{ \Illuminate\Support\Str::limit($user->uid, 12) }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="small">{{ $user->email ?? 'N/A' }}</span>
                                </td>
                                <td>
                                    @if(($user->role ?? '') === 'ADMIN')
                                        <span class="badge bg-primary">Administrador</span>
                                    @elseif(($user->role ?? '') === 'SUPERVISOR')
                                        <span class="badge bg-info text-dark">Supervisor</span>
                                    @else
                                        <span class="badge bg-secondary">Usuario</span>
                                    @endif
                                </td>
                                <td>
                                    @if(($user->status ?? 'ACTIVE') === 'ACTIVE')
                                        <span class="badge bg-success">Activo</span>
                                    @elseif(($user->status ?? '') === 'SUSPENDED')
                                        <span class="badge bg-warning text-dark">Suspendido</span>
                                    @else
                                        <span class="badge bg-danger">Inactivo</span>
                                    @endif
                                </td>
                                <td class="text-center fw-bold text-success">
                                    {{ number_format($user->attributes['ecoPoints'] ?? 0) }}
                                </td>
                                <td class="text-end pe-4">
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('admin.users.show', $user->uid) }}" class="btn btn-outline-info" title="Ver detalle">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.users.edit', $user->uid) }}" class="btn btn-outline-primary" title="Editar">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('admin.users.destroy', $user->uid) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Confirmas eliminar este usuario?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger" title="Eliminar">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-secondary">
                                    <i class="bi bi-people fs-1 d-block mb-2 opacity-50"></i>
                                    No se encontraron usuarios con los criterios especificados.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white border-top py-3 d-flex justify-content-between align-items-center">
            <span class="small text-muted">Total: <strong class="text-dark">{{ count($users) }}</strong> usuario(s).</span>
        </div>
    </div>
</div>
@endsection

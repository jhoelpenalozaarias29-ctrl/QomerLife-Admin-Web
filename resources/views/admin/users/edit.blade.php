@extends('layouts.admin')

@section('title', 'Editar Usuario')

@section('content')
<div class="container-fluid px-0">

    {{-- Encabezado --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 fw-bold" style="color:var(--qomer-text);">
                <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="var(--qomer-emerald)" stroke-width="2" class="me-2" style="vertical-align:-4px"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                Editar Usuario
            </h1>
            <p class="small mb-0" style="color:var(--qomer-text-muted);">Modifica el rol, estado y métricas del usuario en Q'omer Life.</p>
        </div>
        <a href="{{ route('admin.users.index') }}" class="btn btn-sm px-3"
           style="background:var(--qomer-surface-elevated);color:var(--qomer-text-muted);border:1px solid var(--qomer-border);border-radius:10px;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-1"><path d="m15 18-6-6 6-6"/></svg>
            Volver
        </a>
    </div>

    <div class="row g-4">
        {{-- Formulario Principal --}}
        <div class="col-md-8">
            <div class="card-stat p-0 overflow-hidden">
                <div class="px-4 py-3" style="border-bottom:1px solid var(--qomer-border);background:var(--qomer-surface-elevated);">
                    <span class="fw-semibold" style="color:var(--qomer-text);">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--qomer-emerald)" stroke-width="2" class="me-2"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 21V9"/></svg>
                        Datos del Usuario
                    </span>
                </div>
                <div class="p-4">
                    <form action="{{ route('admin.users.update', $user->uid) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label for="name" class="form-label small fw-semibold" style="color:var(--qomer-text-muted);">Nombre Completo</label>
                            <input type="text" class="form-control qomer-input @error('name') is-invalid @enderror"
                                   id="name" name="name"
                                   value="{{ old('name', $user->name ?? '') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="email" class="form-label small fw-semibold" style="color:var(--qomer-text-muted);">Correo Electrónico</label>
                            <input type="email" class="form-control qomer-input @error('email') is-invalid @enderror"
                                   id="email" name="email"
                                   value="{{ old('email', $user->email ?? '') }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="role" class="form-label small fw-semibold" style="color:var(--qomer-text-muted);">Rol del Usuario</label>
                                <select class="form-select qomer-input @error('role') is-invalid @enderror" id="role" name="role" required>
                                    <option value="USER"       {{ old('role', $user->role ?? 'USER') === 'USER'       ? 'selected' : '' }}>Usuario</option>
                                    <option value="SUPERVISOR" {{ old('role', $user->role ?? '') === 'SUPERVISOR' ? 'selected' : '' }}>Supervisor</option>
                                    <option value="ADMIN"      {{ old('role', $user->role ?? '') === 'ADMIN'      ? 'selected' : '' }}>Administrador</option>
                                </select>
                                @error('role')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="status" class="form-label small fw-semibold" style="color:var(--qomer-text-muted);">Estado de la Cuenta</label>
                                <select class="form-select qomer-input @error('status') is-invalid @enderror" id="status" name="status" required>
                                    <option value="ACTIVE"    {{ old('status', $user->status ?? 'ACTIVE') === 'ACTIVE'    ? 'selected' : '' }}>Activo</option>
                                    <option value="INACTIVE"  {{ old('status', $user->status ?? '') === 'INACTIVE'  ? 'selected' : '' }}>Inactivo</option>
                                    <option value="SUSPENDED" {{ old('status', $user->status ?? '') === 'SUSPENDED' ? 'selected' : '' }}>Suspendido</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="ecoPoints" class="form-label small fw-semibold" style="color:var(--qomer-text-muted);">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="var(--qomer-amber)" stroke-width="2" class="me-1"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                                    EcoPoints
                                </label>
                                <input type="number"
                                       class="form-control qomer-input @error('ecoPoints') is-invalid @enderror"
                                       id="ecoPoints" name="ecoPoints"
                                       value="{{ old('ecoPoints', $user->attributes['ecoPoints'] ?? 0) }}"
                                       min="0" required>
                                @error('ecoPoints')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="ecoLevel" class="form-label small fw-semibold" style="color:var(--qomer-text-muted);">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="var(--qomer-emerald)" stroke-width="2" class="me-1"><path d="M18 20V10M12 20V4M6 20v-6"/></svg>
                                    EcoLevel
                                </label>
                                <input type="number"
                                       class="form-control qomer-input @error('ecoLevel') is-invalid @enderror"
                                       id="ecoLevel" name="ecoLevel"
                                       value="{{ old('ecoLevel', $user->attributes['ecoLevel'] ?? 1) }}"
                                       min="1" required>
                                @error('ecoLevel')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 pt-3" style="border-top:1px solid var(--qomer-border);">
                            <a href="{{ route('admin.users.index') }}"
                               class="btn px-4" style="background:var(--qomer-surface-elevated);color:var(--qomer-text-muted);border:1px solid var(--qomer-border);border-radius:10px;">
                                Cancelar
                            </a>
                            <button type="submit" class="btn btn-qomer px-4">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-1"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                                Guardar Cambios
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Panel de Info --}}
        <div class="col-md-4">
            <div class="card-stat p-0 overflow-hidden">
                <div class="px-4 py-3" style="border-bottom:1px solid var(--qomer-border);background:var(--qomer-surface-elevated);">
                    <span class="fw-semibold small text-uppercase" style="color:var(--qomer-text-muted);letter-spacing:.07em;">Info del Usuario</span>
                </div>
                <div class="p-4">
                    <div class="mb-4">
                        <div class="text-xs fw-bold text-uppercase mb-2" style="color:var(--qomer-text-muted);">UID Firebase</div>
                        <div class="font-mono small text-break p-2 rounded" style="background:var(--qomer-surface-elevated);color:var(--qomer-emerald);border:1px solid var(--qomer-border);">{{ $user->uid }}</div>
                    </div>
                    <div>
                        <div class="text-xs fw-bold text-uppercase mb-2" style="color:var(--qomer-text-muted);">Último Login</div>
                        <div style="color:var(--qomer-text);">
                            @if($user->lastLogin instanceof \DateTimeInterface)
                                {{ $user->lastLogin->format('d/m/Y H:i') }}
                            @else
                                <span style="color:var(--qomer-text-muted);">Nunca registrado</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-3 p-3 rounded-3" style="background:rgba(245,158,11,.08);border:1px solid rgba(245,158,11,.25);">
                <div class="text-xs fw-bold text-uppercase mb-2" style="color:var(--qomer-amber);">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-1"><path d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                    Advertencia
                </div>
                <p class="small mb-0" style="color:var(--qomer-text-muted);">
                    Los cambios de rol y EcoPoints se sincronizan inmediatamente con Firestore y afectan la app móvil en tiempo real.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection

@extends('layouts.admin')

@section('title', 'Detalle de Usuario')

@section('content')
<div class="container-fluid px-0">

    {{-- Encabezado --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 fw-bold" style="color: var(--qomer-text);">
                <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="var(--qomer-emerald)" stroke-width="2" class="me-2" style="vertical-align:-4px"><circle cx="12" cy="8" r="4"/><path d="M6 20v-2a4 4 0 0 1 4-4h4a4 4 0 0 1 4 4v2"/></svg>
                Perfil de Usuario
            </h1>
            <p class="small mb-0" style="color:var(--qomer-text-muted);">Información detallada y métricas del usuario en la plataforma Q'omer Life.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.users.index') }}" class="btn btn-sm px-3"
               style="background:var(--qomer-surface-elevated);color:var(--qomer-text-muted);border:1px solid var(--qomer-border);border-radius:10px;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-1"><path d="m15 18-6-6 6-6"/></svg>
                Volver
            </a>
            <a href="{{ route('admin.users.edit', $user->uid) }}" class="btn btn-qomer btn-sm px-3">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-1"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                Editar
            </a>
        </div>
    </div>

    <div class="row g-4">
        {{-- Tarjeta de Perfil --}}
        <div class="col-md-4">
            <div class="card-stat p-4 text-center h-100">
                <div class="mx-auto mb-4 d-flex align-items-center justify-content-center rounded-circle"
                     style="width:96px;height:96px;background:linear-gradient(135deg,rgba(0,199,129,.25),rgba(6,182,212,.25));border:2px solid rgba(0,199,129,.4);">
                    @if(!empty($user->photoUrl))
                        <img src="{{ $user->photoUrl }}" alt="Foto" class="rounded-circle" style="width:88px;height:88px;object-fit:cover;">
                    @else
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="var(--qomer-emerald)" stroke-width="1.5"><circle cx="12" cy="8" r="4"/><path d="M6 20v-2a4 4 0 0 1 4-4h4a4 4 0 0 1 4 4v2"/></svg>
                    @endif
                </div>

                <h4 class="fw-bold mb-1" style="color:var(--qomer-text);">{{ $user->name ?? 'N/A' }}</h4>
                <p class="small mb-3" style="color:var(--qomer-text-muted);">{{ $user->email ?? 'N/A' }}</p>

                <div class="mb-3">
                    @if(($user->status ?? 'ACTIVE') === 'ACTIVE')
                        <span class="badge-emerald"><span class="status-pulse me-1"></span>Activo</span>
                    @elseif(($user->status ?? '') === 'SUSPENDED')
                        <span class="badge-amber">Suspendido</span>
                    @else
                        <span class="badge" style="background:rgba(239,68,68,.15);color:#f87171;border:1px solid rgba(239,68,68,.3);">Inactivo</span>
                    @endif
                </div>

                {{-- EcoPoints destacado --}}
                <div class="p-3 rounded-3 mb-3" style="background:linear-gradient(135deg,rgba(245,158,11,.1),rgba(245,158,11,.05));border:1px solid rgba(245,158,11,.25);">
                    <div class="text-xs fw-bold text-uppercase mb-1" style="color:var(--qomer-amber);letter-spacing:.1em;">EcoPoints</div>
                    <div class="h2 fw-bold mb-0" style="color:var(--qomer-amber);">
                        {{ number_format($user->attributes['ecoPoints'] ?? 0) }}
                    </div>
                    <div class="small" style="color:var(--qomer-text-muted);">puntos disponibles</div>
                </div>

                <div class="p-3 rounded-3" style="background:rgba(0,199,129,.07);border:1px solid rgba(0,199,129,.2);">
                    <div class="text-xs fw-bold text-uppercase mb-1" style="color:var(--qomer-emerald);letter-spacing:.1em;">EcoLevel</div>
                    <div class="h4 fw-bold mb-0" style="color:var(--qomer-emerald);">Nivel {{ $user->attributes['ecoLevel'] ?? 1 }}</div>
                </div>
            </div>
        </div>

        {{-- Información General --}}
        <div class="col-md-8">
            <div class="card-stat p-0 overflow-hidden h-100">
                <div class="px-4 py-3" style="border-bottom:1px solid var(--qomer-border);background:var(--qomer-surface-elevated);">
                    <span class="fw-semibold" style="color:var(--qomer-text);">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--qomer-emerald)" stroke-width="2" class="me-2"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/></svg>
                        Información del Sistema
                    </span>
                </div>
                <div class="p-4">
                    <div class="row g-4">
                        <div class="col-sm-6">
                            <div class="p-3 rounded-3" style="background:var(--qomer-surface-elevated);border:1px solid var(--qomer-border);">
                                <div class="text-xs fw-bold text-uppercase mb-2" style="color:var(--qomer-text-muted);letter-spacing:.08em;">UID del Sistema</div>
                                <div class="font-mono small text-break" style="color:var(--qomer-emerald);">{{ $user->uid ?? 'N/A' }}</div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="p-3 rounded-3" style="background:var(--qomer-surface-elevated);border:1px solid var(--qomer-border);">
                                <div class="text-xs fw-bold text-uppercase mb-2" style="color:var(--qomer-text-muted);letter-spacing:.08em;">Rol Asignado</div>
                                @if(($user->role ?? '') === 'ADMIN')
                                    <span class="badge-emerald">Administrador</span>
                                @elseif(($user->role ?? '') === 'SUPERVISOR')
                                    <span class="badge-cyan">Supervisor</span>
                                @else
                                    <span class="badge" style="background:rgba(100,116,139,.15);color:#94a3b8;border:1px solid rgba(100,116,139,.3);">Usuario</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="p-3 rounded-3" style="background:var(--qomer-surface-elevated);border:1px solid var(--qomer-border);">
                                <div class="text-xs fw-bold text-uppercase mb-2" style="color:var(--qomer-text-muted);letter-spacing:.08em;">Último Login</div>
                                <div style="color:var(--qomer-text);">
                                    @if($user->lastLogin instanceof \DateTimeInterface)
                                        {{ $user->lastLogin->format('d/m/Y H:i') }}
                                    @else
                                        <span style="color:var(--qomer-text-muted);">Nunca</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="p-3 rounded-3" style="background:var(--qomer-surface-elevated);border:1px solid var(--qomer-border);">
                                <div class="text-xs fw-bold text-uppercase mb-2" style="color:var(--qomer-text-muted);letter-spacing:.08em;">Fecha de Registro</div>
                                <div style="color:var(--qomer-text);">
                                    @if(isset($user->attributes['createdAt']))
                                        @php $createdAt = $user->attributes['createdAt']; @endphp
                                        @if($createdAt instanceof \DateTimeInterface)
                                            {{ $createdAt->format('d/m/Y H:i') }}
                                        @elseif(is_object($createdAt) && method_exists($createdAt, 'get'))
                                            {{ $createdAt->get()->format('d/m/Y H:i') }}
                                        @else
                                            {{ $createdAt }}
                                        @endif
                                    @else
                                        <span style="color:var(--qomer-text-muted);">N/A</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

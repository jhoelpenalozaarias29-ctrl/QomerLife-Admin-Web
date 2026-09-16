@extends('layouts.admin')

@section('title', 'Detalle de Usuario')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Detalle de Usuario</h1>
    <div>
        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary me-2">
            <i class="bi bi-arrow-left"></i> Volver
        </a>
        <a href="{{ route('admin.users.edit', $user->uid) }}" class="btn btn-primary">
            <i class="bi bi-pencil"></i> Editar
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card border-0 shadow-sm text-center h-100">
            <div class="card-body d-flex flex-column align-items-center justify-content-center">
                @if(!empty($user->photoUrl))
                    <img src="{{ $user->photoUrl }}" alt="Foto de perfil" class="rounded-circle mb-3" width="120" height="120" style="object-fit: cover;">
                @else
                    <div class="rounded-circle bg-light d-flex align-items-center justify-content-center mb-3" style="width: 120px; height: 120px;">
                        <i class="bi bi-person text-secondary" style="font-size: 4rem;"></i>
                    </div>
                @endif
                <h4 class="card-title">{{ $user->name ?? 'N/A' }}</h4>
                <p class="text-muted">{{ $user->email ?? 'N/A' }}</p>
                
                <div class="mt-2">
                    @if(($user->status ?? 'ACTIVE') === 'ACTIVE')
                        <span class="badge bg-success fs-6">Activo</span>
                    @elseif(($user->status ?? '') === 'SUSPENDED')
                        <span class="badge bg-warning text-dark fs-6">Suspendido</span>
                    @else
                        <span class="badge bg-danger fs-6">Inactivo</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-8 mb-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-transparent border-bottom">
                <h5 class="mb-0">Información General</h5>
            </div>
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-sm-3 text-secondary fw-normal">UID</dt>
                    <dd class="col-sm-9 fw-semibold font-monospace small">{{ $user->uid ?? 'N/A' }}</dd>
                    
                    <dt class="col-sm-3 text-secondary fw-normal mt-3">Rol</dt>
                    <dd class="col-sm-9 mt-3">
                        @if(($user->role ?? '') === 'ADMIN')
                            <span class="badge bg-primary">Administrador</span>
                        @elseif(($user->role ?? '') === 'SUPERVISOR')
                            <span class="badge bg-info text-dark">Supervisor</span>
                        @else
                            <span class="badge bg-secondary">Usuario</span>
                        @endif
                    </dd>
                    
                    <dt class="col-sm-3 text-secondary fw-normal mt-3">EcoPoints</dt>
                    <dd class="col-sm-9 mt-3">
                        <span class="text-success fw-bold fs-5">{{ number_format($user->attributes['ecoPoints'] ?? 0) }}</span> pts
                    </dd>
                    
                    <dt class="col-sm-3 text-secondary fw-normal mt-3">EcoLevel</dt>
                    <dd class="col-sm-9 mt-3">
                        Nivel {{ $user->attributes['ecoLevel'] ?? 1 }}
                    </dd>
                    
                    <dt class="col-sm-3 text-secondary fw-normal mt-3">Último Login</dt>
                    <dd class="col-sm-9 mt-3">
                        @if($user->lastLogin instanceof \DateTimeInterface)
                            {{ $user->lastLogin->format('d/m/Y H:i') }}
                        @else
                            Nunca
                        @endif
                    </dd>
                    
                    <dt class="col-sm-3 text-secondary fw-normal mt-3">Fecha de Registro</dt>
                    <dd class="col-sm-9 mt-3">
                        @if(isset($user->attributes['createdAt']))
                            @php
                                $createdAt = $user->attributes['createdAt'];
                            @endphp
                            @if($createdAt instanceof \DateTimeInterface)
                                {{ $createdAt->format('d/m/Y H:i') }}
                            @elseif(is_object($createdAt) && method_exists($createdAt, 'get'))
                                {{ $createdAt->get()->format('d/m/Y H:i') }}
                            @else
                                {{ $createdAt }}
                            @endif
                        @else
                            N/A
                        @endif
                    </dd>
                </dl>
            </div>
        </div>
    </div>
</div>
@endsection

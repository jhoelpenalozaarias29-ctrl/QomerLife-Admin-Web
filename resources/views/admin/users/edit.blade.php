@extends('layouts.admin')

@section('title', 'Editar Usuario')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Editar Usuario</h1>
    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Volver
    </a>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <form action="{{ route('admin.users.update', $user->uid) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">Nombre</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name ?? '') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email ?? '') }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="role" class="form-label">Rol</label>
                            <select class="form-select @error('role') is-invalid @enderror" id="role" name="role" required>
                                <option value="USER" {{ old('role', $user->role ?? 'USER') === 'USER' ? 'selected' : '' }}>Usuario</option>
                                <option value="SUPERVISOR" {{ old('role', $user->role ?? '') === 'SUPERVISOR' ? 'selected' : '' }}>Supervisor</option>
                                <option value="ADMIN" {{ old('role', $user->role ?? '') === 'ADMIN' ? 'selected' : '' }}>Administrador</option>
                            </select>
                            @error('role')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mt-3 mt-md-0">
                            <label for="status" class="form-label">Estado</label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                <option value="ACTIVE" {{ old('status', $user->status ?? 'ACTIVE') === 'ACTIVE' ? 'selected' : '' }}>Activo</option>
                                <option value="INACTIVE" {{ old('status', $user->status ?? '') === 'INACTIVE' ? 'selected' : '' }}>Inactivo</option>
                                <option value="SUSPENDED" {{ old('status', $user->status ?? '') === 'SUSPENDED' ? 'selected' : '' }}>Suspendido</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="ecoPoints" class="form-label">EcoPoints</label>
                            <input type="number" class="form-control @error('ecoPoints') is-invalid @enderror" id="ecoPoints" name="ecoPoints" value="{{ old('ecoPoints', $user->attributes['ecoPoints'] ?? 0) }}" min="0" required>
                            @error('ecoPoints')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mt-3 mt-md-0">
                            <label for="ecoLevel" class="form-label">EcoLevel</label>
                            <input type="number" class="form-control @error('ecoLevel') is-invalid @enderror" id="ecoLevel" name="ecoLevel" value="{{ old('ecoLevel', $user->attributes['ecoLevel'] ?? 1) }}" min="1" required>
                            @error('ecoLevel')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-end gap-2 border-top pt-3">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-light">Cancelar</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent">
                <h6 class="mb-0 text-secondary">Información del usuario</h6>
            </div>
            <div class="card-body">
                <p class="small text-muted mb-1"><strong>UID:</strong></p>
                <p class="small font-monospace text-break">{{ $user->uid }}</p>
                <p class="small text-muted mb-1"><strong>Último Login:</strong></p>
                <p class="small">
                    @if($user->lastLogin instanceof \DateTimeInterface)
                        {{ $user->lastLogin->format('d/m/Y H:i') }}
                    @else
                        Nunca
                    @endif
                </p>
            </div>
        </div>
    </div>
</div>
@endsection

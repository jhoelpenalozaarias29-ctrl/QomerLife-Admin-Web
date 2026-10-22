@extends('layouts.auth')

@section('title', 'Acceso Administrativo')

@section('content')
    <div class="card shadow-lg">
        <div class="card-body p-4 p-sm-5">
            <div class="text-center mb-4">
                <div class="d-inline-flex p-3 rounded-circle mb-3" 
                     style="background: rgba(0, 199, 129, 0.15); border: 1px solid rgba(0, 199, 129, 0.35); box-shadow: 0 0 20px rgba(0, 199, 129, 0.25);">
                    <i class="bi bi-recycle fs-1 text-success" style="color: #00C781 !important;"></i>
                </div>
                <h2 class="h4 fw-bold text-white mb-1" style="font-family: 'Outfit', sans-serif;">Q'omer Life Admin</h2>
                <p class="text-muted small mb-0">Consola de Control y Gestión de Reciclaje</p>
            </div>

            <form method="POST" action="{{ route('admin.login.post') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label text-light small fw-semibold">Correo Electrónico</label>
                    <div class="input-group">
                        <span class="input-group-text bg-dark border-secondary border-opacity-25 text-muted"><i class="bi bi-envelope"></i></span>
                        <input type="email" name="email" value="{{ old('email') }}" class="form-control" placeholder="admin@qomer.com" required autofocus>
                    </div>
                    @error('email')
                        <div class="form-text text-danger small mt-1"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label text-light small fw-semibold">Contraseña</label>
                    <div class="input-group">
                        <span class="input-group-text bg-dark border-secondary border-opacity-25 text-muted"><i class="bi bi-lock"></i></span>
                        <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                    </div>
                    @error('password')
                        <div class="form-text text-danger small mt-1"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-qomer w-100">
                    <i class="bi bi-box-arrow-in-right me-1"></i> Iniciar Sesión Segura
                </button>
            </form>
        </div>
    </div>
@endsection

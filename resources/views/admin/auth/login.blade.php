@extends('layouts.auth')

@section('title', 'Iniciar sesión')

@section('content')
    <div class="card shadow-sm">
        <div class="card-body p-4">
            <div class="text-center mb-4">
                <i class="bi bi-recycle fs-1 text-white"></i>
                <h2 class="h4 mt-3 text-white">Q'omer Life Admin</h2>
                <p class="text-white-75">Inicia sesión para acceder al panel administrativo.</p>
            </div>

            <form method="POST" action="{{ route('admin.login.post') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label text-white">Correo electrónico</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="form-control" required>
                    @error('email')
                        <div class="form-text text-warning">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label text-white">Contraseña</label>
                    <input type="password" name="password" class="form-control" required>
                    @error('password')
                        <div class="form-text text-warning">{{ $message }}</div>
                    @enderror
                </div>

                <button class="btn btn-white w-100 text-primary">Iniciar sesión</button>
            </form>
        </div>
    </div>
@endsection

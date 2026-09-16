@extends('layouts.admin')

@section('title', 'Detalle de Canje')

@section('content')
<div class="container-fluid">
    <div class="row mb-4 align-items-center">
        <div class="col-md-8">
            <h1 class="h3 mb-2 text-gray-800">Detalle de Canje</h1>
            <p class="mb-0 text-muted">Información completa y gestión del estado del canje.</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('admin.redemptions.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i> Volver a Canjes
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow border-0 mb-4 h-100">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-primary"><i class="bi bi-gift me-2"></i>Información del Canje</h6>
                </div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-4 py-2 border-bottom">ID de Canje</dt>
                        <dd class="col-sm-8 py-2 border-bottom font-monospace">{{ $redemption['id'] ?? 'N/A' }}</dd>

                        <dt class="col-sm-4 py-2 border-bottom">Usuario ID</dt>
                        <dd class="col-sm-8 py-2 border-bottom"><span class="font-monospace text-primary">{{ $redemption['userId'] ?? 'N/A' }}</span></dd>

                        <dt class="col-sm-4 py-2 border-bottom">Reward / Producto</dt>
                        <dd class="col-sm-8 py-2 border-bottom fw-medium">{{ $redemption['rewardName'] ?? $redemption['rewardId'] ?? 'N/A' }}</dd>

                        <dt class="col-sm-4 py-2 border-bottom">EcoPoints Gastados</dt>
                        <dd class="col-sm-8 py-2 border-bottom fw-bold text-danger">-{{ $redemption['ecoPoints'] ?? 0 }}</dd>

                        <dt class="col-sm-4 py-2 border-bottom">Fecha de solicitud</dt>
                        <dd class="col-sm-8 py-2 border-bottom">
                            @if(isset($redemption['createdAt']))
                                {{ \Carbon\Carbon::parse($redemption['createdAt'])->format('d/m/Y H:i:s') }}
                            @else
                                <span class="text-muted">N/A</span>
                            @endif
                        </dd>

                        <dt class="col-sm-4 py-2">Estado Actual</dt>
                        <dd class="col-sm-8 py-2">
                            @php
                                $status = $redemption['status'] ?? '';
                            @endphp
                            @if($status === 'PENDING')
                                <span class="badge bg-warning text-dark px-3 py-2 rounded-pill"><i class="bi bi-hourglass-split me-1"></i>PENDIENTE</span>
                            @elseif($status === 'APPROVED')
                                <span class="badge bg-success px-3 py-2 rounded-pill"><i class="bi bi-check-circle me-1"></i>APROBADO</span>
                            @elseif($status === 'REJECTED')
                                <span class="badge bg-danger px-3 py-2 rounded-pill"><i class="bi bi-x-circle me-1"></i>RECHAZADO</span>
                            @else
                                <span class="badge bg-secondary px-3 py-2 rounded-pill">{{ $status }}</span>
                            @endif
                        </dd>
                    </dl>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card shadow border-0 mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-primary"><i class="bi bi-gear me-2"></i>Gestionar Estado</h6>
                </div>
                <div class="card-body bg-light">
                    <form action="{{ route('admin.redemptions.updateStatus', $redemption['id'] ?? '') }}" method="POST">
                        @csrf
                        @method('PATCH')
                        
                        <div class="mb-4">
                            <label for="status" class="form-label fw-medium">Actualizar estado del canje</label>
                            <select name="status" id="status" class="form-select form-select-lg" required>
                                <option value="PENDING" {{ ($redemption['status'] ?? '') == 'PENDING' ? 'selected' : '' }}>PENDIENTE</option>
                                <option value="APPROVED" {{ ($redemption['status'] ?? '') == 'APPROVED' ? 'selected' : '' }}>APROBAR</option>
                                <option value="REJECTED" {{ ($redemption['status'] ?? '') == 'REJECTED' ? 'selected' : '' }}>RECHAZAR</option>
                            </select>
                            <div class="form-text mt-2">
                                <i class="bi bi-info-circle me-1"></i>Al cambiar el estado a "RECHAZADO" se debería devolver los EcoPoints al usuario según la lógica de negocio.
                            </div>
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-save me-1"></i> Guardar Cambios
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

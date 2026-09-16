@extends('layouts.admin')

@section('title', 'Canjes')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-6">
            <h1 class="h3 mb-2 text-gray-800">Canjes de Recompensas</h1>
            <p class="mb-4">Listado de canjes realizados por los usuarios.</p>
        </div>
    </div>

    <!-- Redemptions Table -->
    <div class="card shadow mb-4 border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" width="100%" cellspacing="0">
                    <thead class="table-light text-muted">
                        <tr>
                            <th class="ps-4">ID</th>
                            <th>Usuario</th>
                            <th>Reward / Producto</th>
                            <th class="text-center">EcoPoints Gastados</th>
                            <th class="text-center">Estado</th>
                            <th>Fecha</th>
                            <th class="text-center pe-4">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($redemptions as $redemption)
                        <tr>
                            <td class="ps-4 font-monospace text-muted">{{ Str::limit($redemption['id'] ?? $redemption->id(), 8) }}</td>
                            <td>{{ $redemption['userId'] ?? 'N/A' }}</td>
                            <td class="fw-medium text-dark">{{ $redemption['rewardName'] ?? $redemption['rewardId'] ?? 'N/A' }}</td>
                            <td class="text-center fw-bold text-danger">-{{ $redemption['ecoPoints'] ?? 0 }}</td>
                            <td class="text-center">
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
                            </td>
                            <td>
                                @if(isset($redemption['createdAt']))
                                    {{ \Carbon\Carbon::parse($redemption['createdAt'])->format('d/m/Y H:i') }}
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td class="text-center pe-4">
                                <a href="{{ route('admin.redemptions.show', $redemption['id'] ?? $redemption->id()) }}" class="btn btn-sm btn-outline-info" title="Ver Detalles">
                                    <i class="bi bi-eye"></i> Ver
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="bi bi-gift fs-1 d-block mb-3 opacity-50"></i>
                                No se encontraron canjes de recompensas.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

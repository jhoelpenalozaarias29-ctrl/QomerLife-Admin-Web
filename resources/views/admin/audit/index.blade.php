@extends('layouts.admin')

@section('title', 'Auditoría')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3 mb-2 text-gray-800">Registros de Auditoría</h1>
            <p class="mb-4 text-muted">Historial de acciones administrativas, cambios y eventos de seguridad en el sistema.</p>
        </div>
        <div class="col-md-4 text-end">
            <!-- Posibles filtros aquí -->
        </div>
    </div>

    <!-- Audit Table -->
    <div class="card shadow mb-4 border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" width="100%" cellspacing="0">
                    <thead class="table-light text-muted">
                        <tr>
                            <th class="ps-4" style="width: 15%">Fecha</th>
                            <th style="width: 15%">Acción</th>
                            <th style="width: 40%">Descripción</th>
                            <th style="width: 15%">Usuario</th>
                            <th class="pe-4" style="width: 15%">IP</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                        <tr>
                            <td class="ps-4">
                                @if(isset($log['createdAt']))
                                    <div class="fw-medium text-dark">{{ \Carbon\Carbon::parse($log['createdAt'])->format('d/m/Y') }}</div>
                                    <div class="small text-muted">{{ \Carbon\Carbon::parse($log['createdAt'])->format('H:i:s') }}</div>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>
                                @php
                                    $action = $log['action'] ?? '';
                                    $actionLower = strtolower($action);
                                    
                                    $badgeClass = 'bg-secondary';
                                    $icon = 'bi-record-circle';
                                    
                                    if(str_contains($actionLower, 'create') || str_contains($actionLower, 'add')) {
                                        $badgeClass = 'bg-success bg-opacity-10 text-success border border-success border-opacity-25';
                                        $icon = 'bi-plus-circle';
                                    } elseif(str_contains($actionLower, 'update') || str_contains($actionLower, 'edit')) {
                                        $badgeClass = 'bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25';
                                        $icon = 'bi-pencil-square';
                                    } elseif(str_contains($actionLower, 'delete') || str_contains($actionLower, 'remove')) {
                                        $badgeClass = 'bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25';
                                        $icon = 'bi-trash';
                                    } elseif(str_contains($actionLower, 'login') || str_contains($actionLower, 'auth')) {
                                        $badgeClass = 'bg-info bg-opacity-10 text-info border border-info border-opacity-25';
                                        $icon = 'bi-box-arrow-in-right';
                                    }
                                @endphp
                                <span class="badge {{ $badgeClass }} px-2 py-1"><i class="bi {{ $icon }} me-1"></i>{{ $action }}</span>
                            </td>
                            <td>
                                <span class="text-dark">{{ $log['description'] ?? '' }}</span>
                            </td>
                            <td>
                                <div class="fw-medium">{{ $log['userEmail'] ?? 'Sistema' }}</div>
                                @if(isset($log['userId']))
                                    <div class="small text-muted font-monospace" title="ID: {{ $log['userId'] }}">{{ Str::limit($log['userId'], 8) }}</div>
                                @endif
                            </td>
                            <td class="pe-4 text-muted font-monospace small">
                                {{ $log['ipAddress'] ?? 'N/A' }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="bi bi-shield-check fs-1 d-block mb-3 opacity-50"></i>
                                No hay registros de auditoría disponibles en este momento.
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

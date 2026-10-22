@extends('layouts.admin')

@section('title', 'Detalle de Transacción')

@section('content')
<div class="container-fluid px-0">

    {{-- Encabezado --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 fw-bold" style="color:var(--qomer-text);">
                <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="var(--qomer-emerald)" stroke-width="2" class="me-2" style="vertical-align:-4px"><polyline points="17 1 21 5 17 9"/><path d="M3 11V9a4 4 0 0 1 4-4h14M7 23l-4-4 4-4"/><path d="M21 13v2a4 4 0 0 1-4 4H3"/></svg>
                Detalle de Transacción
            </h1>
            <p class="small mb-0" style="color:var(--qomer-text-muted);">Información completa y métricas del depósito registrado.</p>
        </div>
        <a href="{{ route('admin.transactions.index') }}" class="btn btn-sm px-3"
           style="background:var(--qomer-surface-elevated);color:var(--qomer-text-muted);border:1px solid var(--qomer-border);border-radius:10px;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-1"><path d="m15 18-6-6 6-6"/></svg>
            Volver a Transacciones
        </a>
    </div>

    <div class="card-stat p-0 overflow-hidden mb-4">
        <div class="px-4 py-3 d-flex align-items-center justify-content-between" style="border-bottom:1px solid var(--qomer-border);background:var(--qomer-surface-elevated);">
            <div class="d-flex align-items-center">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--qomer-emerald)" stroke-width="2" class="me-2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                <span class="fw-semibold" style="color:var(--qomer-text);">Transacción:</span>
                <span class="font-mono small ms-2 px-2 py-1 rounded" style="background:var(--qomer-surface);color:var(--qomer-emerald);border:1px solid var(--qomer-border);">
                    {{ $transaction['id'] ?? 'N/A' }}
                </span>
            </div>
            <div>
                @php $st = strtoupper($transaction['status'] ?? $transaction['estado'] ?? ''); @endphp
                @if($st === 'COMPLETED' || $st === 'USADA')
                    <span class="badge-emerald"><span class="status-pulse me-1"></span>Completada</span>
                @elseif($st === 'PENDING' || $st === 'PENDIENTE')
                    <span class="badge-amber">Pendiente</span>
                @else
                    <span class="badge" style="background:rgba(100,116,139,.15);color:#94a3b8;border:1px solid rgba(100,116,139,.3);">{{ $st ?: 'N/A' }}</span>
                @endif
            </div>
        </div>
        <div class="p-4">
            <dl class="row mb-0 g-3">
                <dt class="col-sm-3 py-2" style="color:var(--qomer-text-muted);">ID Completo</dt>
                <dd class="col-sm-9 py-2 font-mono small" style="color:var(--qomer-text);">{{ $transaction['id'] ?? 'N/A' }}</dd>

                <dt class="col-sm-3 py-2" style="color:var(--qomer-text-muted);">Usuario ID</dt>
                <dd class="col-sm-9 py-2 font-mono small" style="color:var(--qomer-cyan);">{{ $transaction['userId'] ?? 'N/A' }}</dd>

                <dt class="col-sm-3 py-2" style="color:var(--qomer-text-muted);">Tipo / Material</dt>
                <dd class="col-sm-9 py-2">
                    @php $typeVal = strtoupper($transaction['type'] ?? $transaction['material'] ?? ''); @endphp
                    @if(str_contains($typeVal, 'RECYCLE') || str_contains($typeVal, 'PET'))
                        <span class="badge-emerald"><i class="bi bi-recycle me-1"></i>Reciclaje PET</span>
                    @elseif(str_contains($typeVal, 'ALUM') || str_contains($typeVal, 'CAN'))
                        <span class="badge-cyan"><i class="bi bi-circle-fill me-1"></i>Aluminio</span>
                    @else
                        <span class="badge" style="background:rgba(100,116,139,.15);color:#94a3b8;border:1px solid rgba(100,116,139,.3);">{{ $typeVal ?: 'Desconocido' }}</span>
                    @endif
                </dd>

                <dt class="col-sm-3 py-2" style="color:var(--qomer-text-muted);">Botellas PET</dt>
                <dd class="col-sm-9 py-2 fw-bold" style="color:var(--qomer-emerald);">{{ $transaction['plasticBottlesCount'] ?? $transaction['bottles'] ?? 0 }} uds</dd>

                <dt class="col-sm-3 py-2" style="color:var(--qomer-text-muted);">Latas de Aluminio</dt>
                <dd class="col-sm-9 py-2 fw-bold" style="color:var(--qomer-cyan);">{{ $transaction['aluminumCansCount'] ?? $transaction['cans'] ?? 0 }} uds</dd>

                <dt class="col-sm-3 py-2" style="color:var(--qomer-text-muted);">EcoPoints Generados</dt>
                <dd class="col-sm-9 py-2 fw-bold font-mono fs-5" style="color:var(--qomer-amber);">+{{ number_format($transaction['ecoPoints'] ?? 0) }} pts</dd>

                <dt class="col-sm-3 py-2" style="color:var(--qomer-text-muted);">Fecha de Creación</dt>
                <dd class="col-sm-9 py-2" style="color:var(--qomer-text);">
                    @php
                        $fecha = $transaction['fechaCreacion'] ?? $transaction['createdAt'] ?? null;
                        if ($fecha && is_object($fecha) && method_exists($fecha, 'get')) {
                            echo $fecha->get()->format('d/m/Y H:i:s');
                        } elseif ($fecha instanceof \DateTimeInterface) {
                            echo $fecha->format('d/m/Y H:i:s');
                        } elseif (is_numeric($fecha)) {
                            echo date('d/m/Y H:i:s', (int)($fecha > 1e10 ? $fecha/1000 : $fecha));
                        } elseif ($fecha) {
                            echo e($fecha);
                        } else {
                            echo '<span style="color:var(--qomer-text-muted)">N/A</span>';
                        }
                    @endphp
                </dd>

                @if(isset($transaction['machineId']))
                <dt class="col-sm-3 py-2" style="color:var(--qomer-text-muted);">Máquina ID</dt>
                <dd class="col-sm-9 py-2"><span class="badge" style="background:rgba(100,116,139,.15);color:#94a3b8;border:1px solid rgba(100,116,139,.3);font-family:monospace;">{{ $transaction['machineId'] }}</span></dd>
                @endif
                
                @foreach($transaction as $key => $value)
                    @if(!in_array($key, ['id', 'userId', 'type', 'material', 'plasticBottlesCount', 'aluminumCansCount', 'bottles', 'cans', 'ecoPoints', 'createdAt', 'fechaCreacion', 'status', 'estado', 'machineId', '_id']))
                        <dt class="col-sm-3 py-2" style="color:var(--qomer-text-muted);">{{ ucfirst($key) }}</dt>
                        <dd class="col-sm-9 py-2" style="color:var(--qomer-text);">
                            @if(is_array($value))
                                <code>{{ json_encode($value) }}</code>
                            @else
                                {{ $value }}
                            @endif
                        </dd>
                    @endif
                @endforeach
            </dl>
        </div>
    </div>
</div>
@endsection

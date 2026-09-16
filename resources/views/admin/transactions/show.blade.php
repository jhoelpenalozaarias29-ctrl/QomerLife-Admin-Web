@extends('layouts.admin')

@section('title', 'Detalle de Transacción')

@section('content')
<div class="container-fluid">
    <div class="row mb-4 align-items-center">
        <div class="col-md-8">
            <h1 class="h3 mb-2 text-gray-800">Detalle de Transacción</h1>
            <p class="mb-0 text-muted">Información completa de la transacción.</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('admin.transactions.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i> Volver a Transacciones
            </a>
        </div>
    </div>

    <div class="card shadow border-0 mb-4">
        <div class="card-header bg-white py-3 d-flex align-items-center">
            <h6 class="m-0 font-weight-bold text-primary flex-grow-1"><i class="bi bi-receipt me-2"></i>Transacción ID: <span class="text-muted font-monospace">{{ $transaction['id'] ?? 'N/A' }}</span></h6>
        </div>
        <div class="card-body">
            <dl class="row mb-0">
                <dt class="col-sm-3 py-2 border-bottom">ID Completo</dt>
                <dd class="col-sm-9 py-2 border-bottom font-monospace">{{ $transaction['id'] ?? 'N/A' }}</dd>

                <dt class="col-sm-3 py-2 border-bottom">Usuario ID</dt>
                <dd class="col-sm-9 py-2 border-bottom">
                    <span class="font-monospace text-primary">{{ $transaction['userId'] ?? 'N/A' }}</span>
                </dd>

                <dt class="col-sm-3 py-2 border-bottom">Tipo</dt>
                <dd class="col-sm-9 py-2 border-bottom">
                    @if(isset($transaction['type']) && $transaction['type'] == 'RECYCLE')
                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-1"><i class="bi bi-recycle me-1"></i>Reciclaje</span>
                    @elseif(isset($transaction['type']) && $transaction['type'] == 'REWARD')
                        <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-2 py-1"><i class="bi bi-star me-1"></i>Recompensa</span>
                    @else
                        <span class="badge bg-secondary">{{ $transaction['type'] ?? 'Desconocido' }}</span>
                    @endif
                </dd>

                <dt class="col-sm-3 py-2 border-bottom">Botellas</dt>
                <dd class="col-sm-9 py-2 border-bottom">{{ $transaction['bottles'] ?? 0 }}</dd>

                <dt class="col-sm-3 py-2 border-bottom">Latas</dt>
                <dd class="col-sm-9 py-2 border-bottom">{{ $transaction['cans'] ?? 0 }}</dd>

                <dt class="col-sm-3 py-2 border-bottom">EcoPoints</dt>
                <dd class="col-sm-9 py-2 border-bottom fw-bold text-success">+{{ $transaction['ecoPoints'] ?? 0 }}</dd>

                <dt class="col-sm-3 py-2 border-bottom">Fecha de creación</dt>
                <dd class="col-sm-9 py-2 border-bottom">
                    @if(isset($transaction['createdAt']))
                        {{ \Carbon\Carbon::parse($transaction['createdAt'])->format('d/m/Y H:i:s') }}
                    @else
                        <span class="text-muted">N/A</span>
                    @endif
                </dd>

                <dt class="col-sm-3 py-2 border-bottom">Estado</dt>
                <dd class="col-sm-9 py-2 border-bottom">
                    @if(isset($transaction['status']) && $transaction['status'] == 'COMPLETED')
                        <span class="badge bg-success rounded-pill px-3">Completada</span>
                    @elseif(isset($transaction['status']) && $transaction['status'] == 'PENDING')
                        <span class="badge bg-warning text-dark rounded-pill px-3">Pendiente</span>
                    @else
                        <span class="badge bg-secondary rounded-pill px-3">{{ $transaction['status'] ?? 'N/A' }}</span>
                    @endif
                </dd>

                @if(isset($transaction['machineId']))
                <dt class="col-sm-3 py-2 border-bottom">Máquina ID</dt>
                <dd class="col-sm-9 py-2 border-bottom"><span class="font-monospace">{{ $transaction['machineId'] }}</span></dd>
                @endif
                
                @foreach($transaction as $key => $value)
                    @if(!in_array($key, ['id', 'userId', 'type', 'bottles', 'cans', 'ecoPoints', 'createdAt', 'status', 'machineId', '_id']))
                        <dt class="col-sm-3 py-2 border-bottom text-muted">{{ ucfirst($key) }}</dt>
                        <dd class="col-sm-9 py-2 border-bottom text-muted">
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

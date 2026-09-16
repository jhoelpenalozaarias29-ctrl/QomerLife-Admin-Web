@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')

{{-- KPI Cards --}}
<div class="row g-4 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm border-start border-4 border-primary h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <i class="bi bi-people fs-1 text-primary"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <div class="text-secondary small fw-bold text-uppercase">Usuarios totales</div>
                        <div class="fs-3 fw-semibold">{{ $totalUsers ?? 0 }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm border-start border-4 border-success h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <i class="bi bi-recycle fs-1 text-success"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <div class="text-secondary small fw-bold text-uppercase">Transacciones</div>
                        <div class="fs-3 fw-semibold">{{ $totalTransactions ?? 0 }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm border-start border-4 border-warning h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <i class="bi bi-gem fs-1 text-warning"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <div class="text-secondary small fw-bold text-uppercase">EcoPoints entregados</div>
                        <div class="fs-3 fw-semibold">{{ number_format($totalEcoPoints ?? 0) }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm border-start border-4 border-info h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <i class="bi bi-gift fs-1 text-info"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <div class="text-secondary small fw-bold text-uppercase">Canjes</div>
                        <div class="fs-3 fw-semibold">{{ $totalRedemptions ?? 0 }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Activity Tables --}}
<div class="row g-4 mb-4">
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-transparent border-bottom d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Transacciones Recientes</h5>
                <div class="d-flex align-items-center gap-2">
                    <span class="text-muted small">
                        <i class="bi bi-arrow-clockwise me-1"></i>
                        <span id="refresh-countdown">60</span>s
                    </span>
                    <a href="{{ route('admin.transactions.index') }}" class="btn btn-sm btn-outline-secondary">Ver todas</a>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3">Token</th>
                                <th>Máquina</th>
                                <th>Material</th>
                                <th>PET</th>
                                <th>Alum.</th>
                                <th>EcoPoints</th>
                                <th>Fecha</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentTransactions ?? [] as $tx)
                                <tr>
                                    <td class="ps-3">
                                        <span class="font-monospace small text-muted">{{ \Illuminate\Support\Str::limit($tx['token'] ?? $tx['id'] ?? '', 8) }}</span>
                                    </td>
                                    <td><span class="small fw-semibold">{{ $tx['machineId'] ?? 'N/A' }}</span></td>
                                    <td>
                                        @php $mat = strtoupper($tx['material'] ?? ''); @endphp
                                        @if($mat === 'PET')
                                            <span class="badge bg-success bg-opacity-15 text-success border border-success border-opacity-25">PET</span>
                                        @elseif($mat === 'ALUMINIO' || $mat === 'ALU')
                                            <span class="badge bg-info bg-opacity-15 text-info border border-info border-opacity-25">Alum.</span>
                                        @else
                                            <span class="badge bg-secondary bg-opacity-15 text-secondary">{{ $mat ?: 'N/A' }}</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <span class="fw-semibold text-success">{{ $tx['plasticBottlesCount'] ?? 0 }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="fw-semibold text-info">{{ $tx['aluminumCansCount'] ?? 0 }}</span>
                                    </td>
                                    <td>
                                        <span class="fw-semibold text-warning">+{{ number_format($tx['ecoPoints'] ?? 0) }}</span>
                                    </td>
                                    <td>
                                        @php
                                            $fecha = $tx['fechaCreacion'] ?? $tx['createdAt'] ?? null;
                                            if ($fecha && is_object($fecha) && method_exists($fecha, 'get')) {
                                                echo $fecha->get()->format('d/m/Y H:i');
                                            } elseif ($fecha instanceof \DateTimeInterface) {
                                                echo $fecha->format('d/m/Y H:i');
                                            } elseif ($fecha) {
                                                echo $fecha;
                                            } else {
                                                echo 'N/A';
                                            }
                                        @endphp
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4 text-secondary">
                                        <i class="bi bi-inbox d-block fs-3 mb-1 opacity-50"></i>
                                        Sin transacciones registradas
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-transparent border-bottom d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Canjes Recientes</h5>
                <a href="{{ route('admin.redemptions.index') }}" class="btn btn-sm btn-outline-secondary">Ver todos</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3">Incentivo</th>
                                <th>Puntos</th>
                                <th>Código</th>
                                <th>Fecha</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentRedemptions ?? [] as $redemption)
                                <tr>
                                    <td class="ps-3">
                                        <span class="small">{{ \Illuminate\Support\Str::limit($redemption['incentiveTitle'] ?? 'N/A', 22) }}</span>
                                    </td>
                                    <td>
                                        <span class="fw-semibold text-danger">-{{ number_format($redemption['pointsSpent'] ?? 0) }}</span>
                                    </td>
                                    <td>
                                        <span class="font-monospace small text-muted">{{ $redemption['redemptionCode'] ?? '—' }}</span>
                                    </td>
                                    <td>
                                        @php
                                            $ts = $redemption['timestamp'] ?? $redemption['createdAt'] ?? null;
                                            if ($ts && is_object($ts) && method_exists($ts, 'get')) {
                                                echo $ts->get()->format('d/m/Y H:i');
                                            } elseif ($ts instanceof \DateTimeInterface) {
                                                echo $ts->format('d/m/Y H:i');
                                            } elseif (is_numeric($ts)) {
                                                // milisegundos (como en tu app)
                                                echo date('d/m/Y H:i', (int)($ts / 1000));
                                            } elseif ($ts) {
                                                echo $ts;
                                            } else {
                                                echo 'N/A';
                                            }
                                        @endphp
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-secondary">
                                        <i class="bi bi-gift d-block fs-3 mb-1 opacity-50"></i>
                                        Sin canjes registrados
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Recycling Stats --}}
<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h5 class="card-title mb-4">Estadísticas de Reciclaje</h5>
                <div class="row text-center g-3">
                    <div class="col-6 col-md-4">
                        <div class="p-3 rounded-3 bg-success bg-opacity-10 h-100">
                            <i class="bi bi-droplet-fill text-success fs-2 mb-2 d-block"></i>
                            <div class="text-secondary small mb-1">Botellas PET / Plástico</div>
                            <div class="fs-2 fw-bold text-success">{{ number_format($totalBottles ?? 0) }}</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-4">
                        <div class="p-3 rounded-3 bg-info bg-opacity-10 h-100">
                            <i class="bi bi-circle-fill text-info fs-2 mb-2 d-block"></i>
                            <div class="text-secondary small mb-1">Latas / Aluminio</div>
                            <div class="fs-2 fw-bold text-info">{{ number_format($totalCans ?? 0) }}</div>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="p-3 rounded-3 bg-warning bg-opacity-10 h-100">
                            <i class="bi bi-lightning-charge-fill text-warning fs-2 mb-2 d-block"></i>
                            <div class="text-secondary small mb-1">EcoPoints Totales</div>
                            <div class="fs-2 fw-bold text-warning">{{ number_format($totalEcoPoints ?? 0) }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Auto-refresh --}}
<script>
    let seconds = 60;
    const el = document.getElementById('refresh-countdown');
    setInterval(() => {
        seconds--;
        if (el) el.textContent = seconds;
        if (seconds <= 0) window.location.reload();
    }, 1000);
</script>
@endsection

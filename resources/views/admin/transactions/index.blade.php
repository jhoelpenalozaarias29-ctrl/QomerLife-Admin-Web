@extends('layouts.admin')

@section('title', 'Transacciones')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-1">Transacciones</h1>
        <p class="text-muted mb-0 small">Reciclajes registrados por las máquinas Qomer</p>
    </div>
</div>

{{-- Buscador / Filtros --}}
<div class="card mb-4 shadow-sm border-0">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.transactions.index') }}" class="row g-3 align-items-end">
            <div class="col-md-5">
                <label class="form-label small fw-semibold text-secondary">Buscar</label>
                <div class="input-group">
                    <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                    <input type="text" class="form-control border-start-0" name="search"
                        placeholder="Token, usuario, máquina..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-semibold text-secondary">Material</label>
                <select name="material" class="form-select">
                    <option value="">Todos los materiales</option>
                    <option value="PET"      {{ request('material') == 'PET'      ? 'selected' : '' }}>PET / Plástico</option>
                    <option value="ALUMINIO" {{ request('material') == 'ALUMINIO' ? 'selected' : '' }}>Aluminio / Lata</option>
                </select>
            </div>
            <div class="col-md-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-grow-1">
                    <i class="bi bi-funnel me-1"></i> Filtrar
                </button>
                @if(request('search') || request('material'))
                    <a href="{{ route('admin.transactions.index') }}" class="btn btn-outline-secondary">Limpiar</a>
                @endif
            </div>
        </form>
    </div>
</div>

{{-- Tabla de transacciones --}}
<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light text-muted small">
                    <tr>
                        <th class="ps-4">Token</th>
                        <th>Máquina</th>
                        <th>Usuario (ID)</th>
                        <th>Material</th>
                        <th class="text-center">
                            <i class="bi bi-droplet-fill text-success me-1"></i>PET
                        </th>
                        <th class="text-center">
                            <i class="bi bi-circle-fill text-info me-1"></i>Aluminio
                        </th>
                        <th class="text-center">EcoPoints</th>
                        <th>Estado</th>
                        <th>Fecha</th>
                        <th class="text-center pe-4">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $transaction)
                    <tr>
                        {{-- Token (ID del doc) --}}
                        <td class="ps-4">
                            <span class="font-monospace text-muted small">
                                {{ \Illuminate\Support\Str::limit($transaction['token'] ?? $transaction['id'] ?? '', 10) }}
                            </span>
                        </td>

                        {{-- Máquina --}}
                        <td>
                            <span class="badge bg-dark bg-opacity-10 text-dark">
                                <i class="bi bi-cpu me-1"></i>{{ $transaction['machineId'] ?? 'N/A' }}
                            </span>
                        </td>

                        {{-- Usuario --}}
                        <td>
                            <span class="small font-monospace text-muted">
                                {{ \Illuminate\Support\Str::limit($transaction['userId'] ?? 'N/A', 18) }}
                            </span>
                        </td>

                        {{-- Material --}}
                        <td>
                            @php $mat = strtoupper($transaction['material'] ?? ''); @endphp
                            @if($mat === 'PET')
                                <span class="badge bg-success bg-opacity-15 text-success border border-success border-opacity-25">
                                    <i class="bi bi-droplet-fill me-1"></i>PET
                                </span>
                            @elseif(str_contains($mat, 'ALUM') || str_contains($mat, 'LAT'))
                                <span class="badge bg-info bg-opacity-15 text-info border border-info border-opacity-25">
                                    <i class="bi bi-circle-fill me-1"></i>Aluminio
                                </span>
                            @elseif(str_contains($mat, 'VIDRIO') || str_contains($mat, 'GLASS'))
                                <span class="badge bg-primary bg-opacity-15 text-primary border border-primary border-opacity-25">
                                    <i class="bi bi-gem me-1"></i>Vidrio
                                </span>
                            @else
                                <span class="badge bg-secondary">{{ $mat ?: 'N/A' }}</span>
                            @endif
                        </td>

                        {{-- Botellas PET --}}
                        <td class="text-center">
                            @php $pet = (int)($transaction['plasticBottlesCount'] ?? 0); @endphp
                            @if($pet > 0)
                                <span class="fw-bold text-success">{{ $pet }}</span>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>

                        {{-- Latas aluminio --}}
                        <td class="text-center">
                            @php $alu = (int)($transaction['aluminumCansCount'] ?? 0); @endphp
                            @if($alu > 0)
                                <span class="fw-bold text-info">{{ $alu }}</span>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>

                        {{-- EcoPoints --}}
                        <td class="text-center">
                            <span class="fw-bold text-warning fs-6">+{{ number_format($transaction['ecoPoints'] ?? 0) }}</span>
                        </td>

                        {{-- Estado --}}
                        <td>
                            @php $estado = strtoupper($transaction['estado'] ?? ''); @endphp
                            @if($estado === 'USADA')
                                <span class="badge bg-success">Usada</span>
                            @elseif($estado === 'PENDIENTE')
                                <span class="badge bg-warning text-dark">Pendiente</span>
                            @elseif($estado === 'EXPIRADA')
                                <span class="badge bg-danger">Expirada</span>
                            @else
                                <span class="badge bg-secondary">{{ $transaction['estado'] ?? 'N/A' }}</span>
                            @endif
                        </td>

                        {{-- Fecha (campo real del C#: fechaCreacion) --}}
                        <td>
                            @php
                                $fecha = $transaction['fechaCreacion'] ?? $transaction['createdAt'] ?? null;
                                if ($fecha && is_object($fecha) && method_exists($fecha, 'get')) {
                                    echo $fecha->get()->format('d/m/Y H:i');
                                } elseif ($fecha instanceof \DateTimeInterface) {
                                    echo $fecha->format('d/m/Y H:i');
                                } elseif (is_numeric($fecha)) {
                                    echo date('d/m/Y H:i', (int)($fecha > 1e10 ? $fecha/1000 : $fecha));
                                } elseif ($fecha) {
                                    echo e($fecha);
                                } else {
                                    echo '<span class="text-muted">N/A</span>';
                                }
                            @endphp
                        </td>

                        {{-- Acciones --}}
                        <td class="text-center pe-4">
                            <a href="{{ route('admin.transactions.show', $transaction['id'] ?? '') }}"
                               class="btn btn-sm btn-outline-info" title="Ver Detalles">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center py-5 text-muted">
                            <i class="bi bi-inbox fs-1 d-block mb-3 opacity-50"></i>
                            No se encontraron transacciones.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@extends('layouts.admin')

@section('title', 'Centro de Reportes')

@section('content')
<div class="container-fluid px-0">

    {{-- Encabezado --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <h1 class="h3 mb-1 fw-bold" style="color:var(--qomer-text);">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="var(--qomer-emerald)" stroke-width="2" class="me-2" style="vertical-align:-4px"><path d="M18 20V10"/><path d="M12 20V4"/><path d="M6 20v-6"/></svg>
                Centro de Reportes y Análisis
            </h1>
            <p class="small mb-0" style="color:var(--qomer-text-muted);">Consulta interactiva, filtrado avanzado y exportación oficial de datos Q'omer Life.</p>
        </div>
        <div class="d-flex gap-2 print-hide flex-wrap">
            @php
                $csvRoute = match($type) {
                    'users'       => route('admin.reports.users.csv', request()->query()),
                    'redemptions' => route('admin.reports.redemptions.csv', request()->query()),
                    default       => route('admin.reports.transactions.csv', request()->query()),
                };
            @endphp
            <button type="button" onclick="window.print()"
                    class="btn btn-sm px-3 py-2 fw-semibold"
                    style="background:var(--qomer-surface-elevated);color:var(--qomer-text-muted);border:1px solid var(--qomer-border);border-radius:10px;">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-1"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                Imprimir / PDF
            </button>
            <a href="{{ $csvRoute }}" class="btn btn-qomer btn-sm px-4">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-1"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                Exportar CSV Filtrado
            </a>
        </div>
    </div>

    {{-- Cabecera solo para impresión --}}
    <div class="d-none print-only mb-4 pb-3" style="border-bottom:2px solid #00C781;">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="h4 mb-0 fw-bold">Q'OMER LIFE — REPORTE OFICIAL DEL SISTEMA</h2>
                <div class="text-muted small">
                    Tipo de Reporte: <span class="fw-bold text-uppercase">{{ $type }}</span>
                    | Generado el: {{ date('d/m/Y H:i:s') }}
                </div>
            </div>
            <div class="text-end text-muted small">
                Estado: Oficial y Auditado<br>
                Registros incluidos: {{ count($items) }}
            </div>
        </div>
    </div>

    {{-- Navegación por tipo --}}
    <div class="card-stat mb-4 p-2 print-hide">
        <div class="nav nav-pills gap-2">
            <a class="nav-link px-4 py-2 fw-semibold {{ $type === 'transactions' ? 'active-tab' : '' }}"
               href="{{ route('admin.reports.index', ['type' => 'transactions']) }}"
               style="{{ $type === 'transactions' ? 'background:var(--qomer-emerald);color:#0B0F0D;border-radius:10px;' : 'color:var(--qomer-text-muted);border-radius:10px;' }}">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-1"><polyline points="17 1 21 5 17 9"/><path d="M3 11V9a4 4 0 0 1 4-4h14M7 23l-4-4 4-4"/><path d="M21 13v2a4 4 0 0 1-4 4H3"/></svg>
                Transacciones
            </a>
            <a class="nav-link px-4 py-2 fw-semibold {{ $type === 'users' ? 'active-tab' : '' }}"
               href="{{ route('admin.reports.index', ['type' => 'users']) }}"
               style="{{ $type === 'users' ? 'background:var(--qomer-emerald);color:#0B0F0D;border-radius:10px;' : 'color:var(--qomer-text-muted);border-radius:10px;' }}">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-1"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                Usuarios
            </a>
            <a class="nav-link px-4 py-2 fw-semibold {{ $type === 'redemptions' ? 'active-tab' : '' }}"
               href="{{ route('admin.reports.index', ['type' => 'redemptions']) }}"
               style="{{ $type === 'redemptions' ? 'background:var(--qomer-emerald);color:#0B0F0D;border-radius:10px;' : 'color:var(--qomer-text-muted);border-radius:10px;' }}">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-1"><polyline points="20 12 20 22 4 22 4 12"/><rect x="2" y="7" width="20" height="5"/><path d="M12 22V7"/></svg>
                Canjes
            </a>
        </div>
    </div>

    {{-- KPIs dinámicos --}}
    <div class="row g-3 mb-4">
        @if($type === 'transactions')
            <div class="col-sm-6 col-xl-3">
                <div class="card-stat p-3" style="border-left:3px solid var(--qomer-emerald);">
                    <div class="text-xs fw-bold text-uppercase mb-1" style="color:var(--qomer-text-muted);letter-spacing:.08em;">Registros Filtrados</div>
                    <div class="h4 mb-0 fw-bold" style="color:var(--qomer-emerald);">{{ number_format($kpis['totalRecords'] ?? 0) }}</div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="card-stat p-3" style="border-left:3px solid var(--qomer-amber);">
                    <div class="text-xs fw-bold text-uppercase mb-1" style="color:var(--qomer-text-muted);letter-spacing:.08em;">EcoPoints Otorgados</div>
                    <div class="h4 mb-0 fw-bold" style="color:var(--qomer-amber);">{{ number_format($kpis['totalPoints'] ?? 0) }} <span class="fs-6">pts</span></div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="card-stat p-3" style="border-left:3px solid var(--qomer-emerald);">
                    <div class="text-xs fw-bold text-uppercase mb-1" style="color:var(--qomer-text-muted);letter-spacing:.08em;">Botellas PET</div>
                    <div class="h4 mb-0 fw-bold" style="color:var(--qomer-emerald);">{{ number_format($kpis['totalPet'] ?? 0) }} <span class="fs-6">uds</span></div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="card-stat p-3" style="border-left:3px solid var(--qomer-cyan);">
                    <div class="text-xs fw-bold text-uppercase mb-1" style="color:var(--qomer-text-muted);letter-spacing:.08em;">Latas Aluminio</div>
                    <div class="h4 mb-0 fw-bold" style="color:var(--qomer-cyan);">{{ number_format($kpis['totalAlum'] ?? 0) }} <span class="fs-6">uds</span></div>
                </div>
            </div>
        @elseif($type === 'users')
            <div class="col-sm-6 col-xl-3">
                <div class="card-stat p-3" style="border-left:3px solid var(--qomer-emerald);">
                    <div class="text-xs fw-bold text-uppercase mb-1" style="color:var(--qomer-text-muted);letter-spacing:.08em;">Total Usuarios</div>
                    <div class="h4 mb-0 fw-bold" style="color:var(--qomer-emerald);">{{ number_format($kpis['totalRecords'] ?? 0) }}</div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="card-stat p-3" style="border-left:3px solid var(--qomer-cyan);">
                    <div class="text-xs fw-bold text-uppercase mb-1" style="color:var(--qomer-text-muted);letter-spacing:.08em;">Usuarios Activos</div>
                    <div class="h4 mb-0 fw-bold" style="color:var(--qomer-cyan);">{{ number_format($kpis['activeCount'] ?? 0) }}</div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="card-stat p-3" style="border-left:3px solid var(--qomer-amber);">
                    <div class="text-xs fw-bold text-uppercase mb-1" style="color:var(--qomer-text-muted);letter-spacing:.08em;">Administradores</div>
                    <div class="h4 mb-0 fw-bold" style="color:var(--qomer-amber);">{{ number_format($kpis['adminCount'] ?? 0) }}</div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="card-stat p-3" style="border-left:3px solid var(--qomer-emerald);">
                    <div class="text-xs fw-bold text-uppercase mb-1" style="color:var(--qomer-text-muted);letter-spacing:.08em;">EcoPoints Acumulados</div>
                    <div class="h4 mb-0 fw-bold" style="color:var(--qomer-emerald);">{{ number_format($kpis['totalPoints'] ?? 0) }} <span class="fs-6">pts</span></div>
                </div>
            </div>
        @elseif($type === 'redemptions')
            <div class="col-sm-6 col-xl-3">
                <div class="card-stat p-3" style="border-left:3px solid var(--qomer-emerald);">
                    <div class="text-xs fw-bold text-uppercase mb-1" style="color:var(--qomer-text-muted);letter-spacing:.08em;">Total Canjes</div>
                    <div class="h4 mb-0 fw-bold" style="color:var(--qomer-emerald);">{{ number_format($kpis['totalRecords'] ?? 0) }}</div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="card-stat p-3" style="border-left:3px solid #f87171;">
                    <div class="text-xs fw-bold text-uppercase mb-1" style="color:var(--qomer-text-muted);letter-spacing:.08em;">Puntos Canjeados</div>
                    <div class="h4 mb-0 fw-bold" style="color:#f87171;">{{ number_format($kpis['redeemedPoints'] ?? 0) }} <span class="fs-6">pts</span></div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="card-stat p-3" style="border-left:3px solid var(--qomer-amber);">
                    <div class="text-xs fw-bold text-uppercase mb-1" style="color:var(--qomer-text-muted);letter-spacing:.08em;">Pendientes</div>
                    <div class="h4 mb-0 fw-bold" style="color:var(--qomer-amber);">{{ number_format($kpis['pendingCount'] ?? 0) }}</div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="card-stat p-3" style="border-left:3px solid var(--qomer-emerald);">
                    <div class="text-xs fw-bold text-uppercase mb-1" style="color:var(--qomer-text-muted);letter-spacing:.08em;">Aprobados</div>
                    <div class="h4 mb-0 fw-bold" style="color:var(--qomer-emerald);">{{ number_format($kpis['approvedCount'] ?? 0) }}</div>
                </div>
            </div>
        @endif
    </div>

    {{-- Panel de Filtros --}}
    <div class="card-stat mb-4 p-0 overflow-hidden print-hide">
        <div class="px-4 py-3 d-flex justify-content-between align-items-center" style="border-bottom:1px solid var(--qomer-border);background:var(--qomer-surface-elevated);">
            <span class="small fw-semibold text-uppercase" style="color:var(--qomer-text-muted);letter-spacing:.07em;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="var(--qomer-emerald)" stroke-width="2" class="me-1"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
                Filtros de Consulta y Exportación
            </span>
            <span class="badge" style="background:rgba(0,199,129,.1);color:var(--qomer-emerald);border:1px solid rgba(0,199,129,.3);">
                {{ count($items) }} resultado(s)
            </span>
        </div>
        <div class="p-4">
            <form method="GET" action="{{ route('admin.reports.index') }}" class="row g-3 align-items-end">
                <input type="hidden" name="type" value="{{ $type }}">

                <div class="col-md-3">
                    <label class="form-label small fw-semibold" style="color:var(--qomer-text-muted);">Búsqueda rápida</label>
                    <div class="input-group">
                        <span class="input-group-text" style="background:var(--qomer-surface-elevated);border-color:var(--qomer-border);color:var(--qomer-text-muted);">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                        </span>
                        <input type="text" name="search" class="form-control qomer-input"
                               placeholder="Nombre, email, token, ID..." value="{{ $filters['search'] ?? '' }}">
                    </div>
                </div>
                <div class="col-md-2 col-sm-6">
                    <label class="form-label small fw-semibold" style="color:var(--qomer-text-muted);">Fecha Desde</label>
                    <input type="date" name="date_from" class="form-control qomer-input" value="{{ $filters['date_from'] ?? '' }}">
                </div>
                <div class="col-md-2 col-sm-6">
                    <label class="form-label small fw-semibold" style="color:var(--qomer-text-muted);">Fecha Hasta</label>
                    <input type="date" name="date_to" class="form-control qomer-input" value="{{ $filters['date_to'] ?? '' }}">
                </div>

                @if($type === 'transactions')
                    <div class="col-md-2">
                        <label class="form-label small fw-semibold" style="color:var(--qomer-text-muted);">Material</label>
                        <select name="material" class="form-select qomer-input">
                            <option value="">Todos</option>
                            <option value="PET"      {{ ($filters['material'] ?? '') === 'PET'      ? 'selected' : '' }}>PET / Botella</option>
                            <option value="ALUMINIO" {{ ($filters['material'] ?? '') === 'ALUMINIO' ? 'selected' : '' }}>Aluminio / Lata</option>
                            <option value="VIDRIO"   {{ ($filters['material'] ?? '') === 'VIDRIO'   ? 'selected' : '' }}>Vidrio</option>
                        </select>
                    </div>
                @elseif($type === 'users')
                    <div class="col-md-1">
                        <label class="form-label small fw-semibold" style="color:var(--qomer-text-muted);">Rol</label>
                        <select name="role" class="form-select qomer-input">
                            <option value="">Todos</option>
                            <option value="USER"       {{ ($filters['role'] ?? '') === 'USER'       ? 'selected' : '' }}>Usuario</option>
                            <option value="ADMIN"      {{ ($filters['role'] ?? '') === 'ADMIN'      ? 'selected' : '' }}>Admin</option>
                            <option value="SUPERVISOR" {{ ($filters['role'] ?? '') === 'SUPERVISOR' ? 'selected' : '' }}>Supervisor</option>
                        </select>
                    </div>
                    <div class="col-md-1">
                        <label class="form-label small fw-semibold" style="color:var(--qomer-text-muted);">Estado</label>
                        <select name="status" class="form-select qomer-input">
                            <option value="">Todos</option>
                            <option value="ACTIVE"    {{ ($filters['status'] ?? '') === 'ACTIVE'    ? 'selected' : '' }}>Activo</option>
                            <option value="SUSPENDED" {{ ($filters['status'] ?? '') === 'SUSPENDED' ? 'selected' : '' }}>Suspendido</option>
                            <option value="INACTIVE"  {{ ($filters['status'] ?? '') === 'INACTIVE'  ? 'selected' : '' }}>Inactivo</option>
                        </select>
                    </div>
                @elseif($type === 'redemptions')
                    <div class="col-md-2">
                        <label class="form-label small fw-semibold" style="color:var(--qomer-text-muted);">Estado del Canje</label>
                        <select name="status" class="form-select qomer-input">
                            <option value="">Todos</option>
                            <option value="PENDING"  {{ ($filters['status'] ?? '') === 'PENDING'  ? 'selected' : '' }}>Pendiente</option>
                            <option value="APPROVED" {{ ($filters['status'] ?? '') === 'APPROVED' ? 'selected' : '' }}>Aprobado</option>
                            <option value="REJECTED" {{ ($filters['status'] ?? '') === 'REJECTED' ? 'selected' : '' }}>Rechazado</option>
                        </select>
                    </div>
                @endif

                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-qomer flex-grow-1">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-1"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
                        Filtrar
                    </button>
                    <a href="{{ route('admin.reports.index', ['type' => $type]) }}" class="btn"
                       style="background:var(--qomer-surface-elevated);color:var(--qomer-text-muted);border:1px solid var(--qomer-border);" title="Restablecer">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/></svg>
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- Tabla de Previsualización --}}
    <div class="card-stat p-0 overflow-hidden">
        <div class="table-responsive">
            <table class="table mb-0 align-middle">
                <thead>
                    <tr style="background:var(--qomer-surface-elevated);border-bottom:1px solid var(--qomer-border);">
                        @if($type === 'transactions')
                            <th class="ps-4 py-3 text-xs fw-bold text-uppercase" style="color:var(--qomer-text-muted);letter-spacing:.08em;">Token / ID</th>
                            <th class="py-3 text-xs fw-bold text-uppercase" style="color:var(--qomer-text-muted);letter-spacing:.08em;">Usuario</th>
                            <th class="py-3 text-xs fw-bold text-uppercase" style="color:var(--qomer-text-muted);letter-spacing:.08em;">Máquina</th>
                            <th class="py-3 text-xs fw-bold text-uppercase" style="color:var(--qomer-text-muted);letter-spacing:.08em;">Material</th>
                            <th class="py-3 text-xs fw-bold text-uppercase text-center" style="color:var(--qomer-text-muted);letter-spacing:.08em;">PET</th>
                            <th class="py-3 text-xs fw-bold text-uppercase text-center" style="color:var(--qomer-text-muted);letter-spacing:.08em;">Latas</th>
                            <th class="py-3 text-xs fw-bold text-uppercase text-center" style="color:var(--qomer-text-muted);letter-spacing:.08em;">EcoPoints</th>
                            <th class="py-3 text-xs fw-bold text-uppercase" style="color:var(--qomer-text-muted);letter-spacing:.08em;">Estado</th>
                            <th class="py-3 text-xs fw-bold text-uppercase" style="color:var(--qomer-text-muted);letter-spacing:.08em;">Fecha</th>
                            <th class="py-3 text-xs fw-bold text-uppercase text-end pe-4 print-hide" style="color:var(--qomer-text-muted);letter-spacing:.08em;">Acciones</th>
                        @elseif($type === 'users')
                            <th class="ps-4 py-3 text-xs fw-bold text-uppercase" style="color:var(--qomer-text-muted);letter-spacing:.08em;">Nombre</th>
                            <th class="py-3 text-xs fw-bold text-uppercase" style="color:var(--qomer-text-muted);letter-spacing:.08em;">Correo</th>
                            <th class="py-3 text-xs fw-bold text-uppercase" style="color:var(--qomer-text-muted);letter-spacing:.08em;">Rol</th>
                            <th class="py-3 text-xs fw-bold text-uppercase" style="color:var(--qomer-text-muted);letter-spacing:.08em;">Estado</th>
                            <th class="py-3 text-xs fw-bold text-uppercase text-center" style="color:var(--qomer-text-muted);letter-spacing:.08em;">EcoPoints</th>
                            <th class="py-3 text-xs fw-bold text-uppercase" style="color:var(--qomer-text-muted);letter-spacing:.08em;">Último Acceso</th>
                            <th class="py-3 text-xs fw-bold text-uppercase text-end pe-4 print-hide" style="color:var(--qomer-text-muted);letter-spacing:.08em;">Acciones</th>
                        @elseif($type === 'redemptions')
                            <th class="ps-4 py-3 text-xs fw-bold text-uppercase" style="color:var(--qomer-text-muted);letter-spacing:.08em;">ID Canje</th>
                            <th class="py-3 text-xs fw-bold text-uppercase" style="color:var(--qomer-text-muted);letter-spacing:.08em;">Usuario</th>
                            <th class="py-3 text-xs fw-bold text-uppercase" style="color:var(--qomer-text-muted);letter-spacing:.08em;">Recompensa</th>
                            <th class="py-3 text-xs fw-bold text-uppercase text-center" style="color:var(--qomer-text-muted);letter-spacing:.08em;">Puntos</th>
                            <th class="py-3 text-xs fw-bold text-uppercase text-center" style="color:var(--qomer-text-muted);letter-spacing:.08em;">Estado</th>
                            <th class="py-3 text-xs fw-bold text-uppercase" style="color:var(--qomer-text-muted);letter-spacing:.08em;">Fecha</th>
                            <th class="py-3 text-xs fw-bold text-uppercase text-end pe-4 print-hide" style="color:var(--qomer-text-muted);letter-spacing:.08em;">Acciones</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $item)
                        @if($type === 'transactions')
                            @php
                                $txDate = 'N/A';
                                $rawDate = $item['fechaCreacion'] ?? $item['createdAt'] ?? null;
                                if ($rawDate) {
                                    if (is_object($rawDate) && method_exists($rawDate, 'get')) {
                                        $txDate = $rawDate->get()->format('d/m/Y H:i');
                                    } elseif ($rawDate instanceof \DateTimeInterface) {
                                        $txDate = $rawDate->format('d/m/Y H:i');
                                    } elseif (is_numeric($rawDate)) {
                                        $txDate = date('d/m/Y H:i', (int)$rawDate);
                                    } else {
                                        $txDate = date('d/m/Y H:i', strtotime((string)$rawDate));
                                    }
                                }
                                $mat = strtoupper($item['material'] ?? 'PET');
                            @endphp
                            <tr class="qomer-table-row" style="border-bottom:1px solid var(--qomer-border);">
                                <td class="ps-4 py-3 font-mono small" style="color:var(--qomer-emerald);">{{ \Illuminate\Support\Str::limit($item['token'] ?? $item['id'] ?? '', 12) }}</td>
                                <td class="py-3 small" style="color:var(--qomer-text-muted);">{{ \Illuminate\Support\Str::limit($item['userId'] ?? 'Anónimo', 16) }}</td>
                                <td class="py-3">
                                    <span class="badge" style="background:rgba(100,116,139,.15);color:#94a3b8;border:1px solid rgba(100,116,139,.3);font-family:monospace;">{{ $item['machineId'] ?? 'N/A' }}</span>
                                </td>
                                <td class="py-3">
                                    @if($mat === 'PET')
                                        <span class="badge-emerald">PET</span>
                                    @elseif($mat === 'ALUMINIO')
                                        <span class="badge-cyan">Aluminio</span>
                                    @else
                                        <span class="badge" style="background:rgba(100,116,139,.15);color:#94a3b8;border:1px solid rgba(100,116,139,.3);">{{ $mat }}</span>
                                    @endif
                                </td>
                                <td class="py-3 text-center fw-semibold" style="color:var(--qomer-emerald);">{{ $item['plasticBottlesCount'] ?? ($item['bottles'] ?? 0) }}</td>
                                <td class="py-3 text-center fw-semibold" style="color:var(--qomer-cyan);">{{ $item['aluminumCansCount'] ?? ($item['cans'] ?? 0) }}</td>
                                <td class="py-3 text-center fw-bold font-mono" style="color:var(--qomer-amber);">+{{ number_format($item['ecoPoints'] ?? 0) }}</td>
                                <td class="py-3">
                                    @if(($item['usado'] ?? false))
                                        <span class="badge" style="background:rgba(100,116,139,.15);color:#94a3b8;border:1px solid rgba(100,116,139,.3);">Canjeado</span>
                                    @else
                                        <span class="badge-emerald">Disponible</span>
                                    @endif
                                </td>
                                <td class="py-3 small" style="color:var(--qomer-text-muted);">{{ $txDate }}</td>
                                <td class="py-3 text-end pe-4 print-hide">
                                    <a href="{{ route('admin.transactions.show', $item['id'] ?? '') }}"
                                       class="btn btn-sm" style="background:rgba(6,182,212,.12);color:var(--qomer-cyan);border:1px solid rgba(6,182,212,.3);border-radius:8px;">
                                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-1"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                        Detalle
                                    </a>
                                </td>
                            </tr>
                        @elseif($type === 'users')
                            <tr class="qomer-table-row" style="border-bottom:1px solid var(--qomer-border);">
                                <td class="ps-4 py-3">
                                    <div class="fw-semibold" style="color:var(--qomer-text);">{{ $item->name ?: 'Sin Nombre' }}</div>
                                    <div class="font-mono small" style="color:var(--qomer-text-muted);">{{ \Illuminate\Support\Str::limit($item->uid, 14) }}</div>
                                </td>
                                <td class="py-3 small" style="color:var(--qomer-text-muted);">{{ $item->email }}</td>
                                <td class="py-3">
                                    @if(strtoupper($item->role ?? '') === 'ADMIN')
                                        <span class="badge-emerald">Admin</span>
                                    @elseif(strtoupper($item->role ?? '') === 'SUPERVISOR')
                                        <span class="badge-cyan">Supervisor</span>
                                    @else
                                        <span class="badge" style="background:rgba(100,116,139,.15);color:#94a3b8;border:1px solid rgba(100,116,139,.3);">Usuario</span>
                                    @endif
                                </td>
                                <td class="py-3">
                                    @if(strtoupper($item->status ?? 'ACTIVE') === 'ACTIVE')
                                        <span class="badge-emerald">Activo</span>
                                    @elseif(strtoupper($item->status ?? '') === 'SUSPENDED')
                                        <span class="badge-amber">Suspendido</span>
                                    @else
                                        <span class="badge" style="background:rgba(239,68,68,.15);color:#f87171;border:1px solid rgba(239,68,68,.3);">Inactivo</span>
                                    @endif
                                </td>
                                <td class="py-3 text-center fw-bold font-mono" style="color:var(--qomer-amber);">{{ number_format($item->attributes['ecoPoints'] ?? 0) }}</td>
                                <td class="py-3 small" style="color:var(--qomer-text-muted);">
                                    @if($item->lastLogin instanceof \DateTimeInterface)
                                        {{ $item->lastLogin->format('d/m/Y H:i') }}
                                    @else N/A @endif
                                </td>
                                <td class="py-3 text-end pe-4 print-hide">
                                    <div class="d-flex gap-2 justify-content-end">
                                        <a href="{{ route('admin.users.show', $item->uid) }}"
                                           class="btn btn-sm" style="background:rgba(100,116,139,.12);color:#94a3b8;border:1px solid rgba(100,116,139,.3);border-radius:8px;">
                                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                        </a>
                                        <a href="{{ route('admin.users.edit', $item->uid) }}"
                                           class="btn btn-sm" style="background:rgba(0,199,129,.12);color:var(--qomer-emerald);border:1px solid rgba(0,199,129,.3);border-radius:8px;">
                                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @elseif($type === 'redemptions')
                            @php
                                $redDate = 'N/A';
                                $rawRed = $item['createdAt'] ?? null;
                                if ($rawRed) {
                                    $redDate = is_numeric($rawRed) ? date('d/m/Y H:i', (int)$rawRed) : date('d/m/Y H:i', strtotime((string)$rawRed));
                                }
                                $st = strtoupper($item['status'] ?? 'PENDING');
                            @endphp
                            <tr class="qomer-table-row" style="border-bottom:1px solid var(--qomer-border);">
                                <td class="ps-4 py-3 font-mono small" style="color:var(--qomer-text-muted);">{{ \Illuminate\Support\Str::limit($item['id'] ?? '', 10) }}</td>
                                <td class="py-3 small" style="color:var(--qomer-text-muted);">{{ $item['userId'] ?? 'N/A' }}</td>
                                <td class="py-3 fw-semibold" style="color:var(--qomer-text);">{{ $item['rewardName'] ?? ($item['rewardId'] ?? 'Recompensa') }}</td>
                                <td class="py-3 text-center fw-bold font-mono" style="color:#f87171;">-{{ number_format($item['ecoPoints'] ?? ($item['pointsCost'] ?? 0)) }}</td>
                                <td class="py-3 text-center">
                                    @if($st === 'APPROVED')
                                        <span class="badge-emerald">Aprobado</span>
                                    @elseif($st === 'PENDING')
                                        <span class="badge-amber">Pendiente</span>
                                    @elseif($st === 'REJECTED')
                                        <span class="badge" style="background:rgba(239,68,68,.15);color:#f87171;border:1px solid rgba(239,68,68,.3);">Rechazado</span>
                                    @else
                                        <span class="badge" style="background:rgba(100,116,139,.15);color:#94a3b8;border:1px solid rgba(100,116,139,.3);">{{ $st }}</span>
                                    @endif
                                </td>
                                <td class="py-3 small" style="color:var(--qomer-text-muted);">{{ $redDate }}</td>
                                <td class="py-3 text-end pe-4 print-hide">
                                    <a href="{{ route('admin.redemptions.show', $item['id'] ?? '') }}"
                                       class="btn btn-sm px-3" style="background:rgba(0,199,129,.12);color:var(--qomer-emerald);border:1px solid rgba(0,199,129,.3);border-radius:8px;">
                                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-1"><circle cx="12" cy="12" r="3"/><path d="M19.07 4.93a10 10 0 0 1 0 14.14M4.93 4.93a10 10 0 0 0 0 14.14"/></svg>
                                        Gestionar
                                    </a>
                                </td>
                            </tr>
                        @endif
                    @empty
                        <tr>
                            <td colspan="10" class="text-center py-5">
                                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="var(--qomer-text-muted)" stroke-width="1" class="mb-3 d-block mx-auto opacity-50"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/></svg>
                                <p style="color:var(--qomer-text-muted);">No se encontraron registros con los filtros aplicados.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 d-flex justify-content-between align-items-center print-hide" style="border-top:1px solid var(--qomer-border);background:var(--qomer-surface-elevated);">
            <span class="small" style="color:var(--qomer-text-muted);">
                Mostrando <strong style="color:var(--qomer-text);">{{ count($items) }}</strong> registro(s) auditado(s).
            </span>
            <a href="#top" class="small" style="color:var(--qomer-text-muted);text-decoration:none;">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-1"><path d="m18 15-6-6-6 6"/></svg>
                Volver arriba
            </a>
        </div>
    </div>
</div>

<style>
.qomer-table-row { transition: background .18s; }
.qomer-table-row:hover { background: rgba(0,199,129,.04) !important; }
.qomer-table-row:last-child { border-bottom: none !important; }

@media print {
    .print-hide, .qomer-sidebar, header, .navbar, .breadcrumb { display: none !important; }
    .print-only { display: block !important; }
    .qomer-main { margin-left: 0 !important; padding: 0 !important; }
    .card-stat { box-shadow: none !important; border: 1px solid #dee2e6 !important; }
    table { font-size: 11px !important; }
}
</style>
@endsection

@extends('layouts.admin')

@section('title', 'Reportes')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 mb-2 text-gray-800">Centro de Reportes</h1>
            <p class="mb-4 text-muted">Exportación de datos y estadísticas del sistema. 
                <span class="d-inline-block ms-2 px-2 py-1 bg-light rounded border">
                    <i class="bi bi-printer me-1"></i> Nota: Puede generar un PDF imprimiendo esta página o cualquier reporte visual (Ctrl+P).
                </span>
            </p>
        </div>
    </div>

    <div class="row">
        <!-- Users Report Card -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-0 border-start border-4 border-primary shadow h-100 py-2 transition-hover">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs fw-bold text-primary text-uppercase mb-1">
                                Datos de Usuarios
                            </div>
                            <div class="h5 mb-0 fw-bold text-gray-800 mb-3">Reporte de Usuarios</div>
                            <p class="text-muted small mb-4">Exportar listado completo de usuarios registrados, incluyendo EcoPoints actuales, correo y estado en formato CSV.</p>
                            <a href="{{ route('admin.reports.users.csv') }}" class="btn btn-outline-primary w-100">
                                <i class="bi bi-filetype-csv me-1"></i> Exportar CSV
                            </a>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-people fa-2x text-primary opacity-25" style="font-size: 3rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Transactions Report Card -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-0 border-start border-4 border-success shadow h-100 py-2 transition-hover">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs fw-bold text-success text-uppercase mb-1">
                                Actividad
                            </div>
                            <div class="h5 mb-0 fw-bold text-gray-800 mb-3">Historial de Transacciones</div>
                            <p class="text-muted small mb-4">Exportar registro detallado de todas las transacciones, aportes de reciclaje, y recompensas en el sistema.</p>
                            <a href="{{ route('admin.reports.transactions.csv') }}" class="btn btn-outline-success w-100">
                                <i class="bi bi-filetype-csv me-1"></i> Exportar CSV
                            </a>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-arrow-left-right fa-2x text-success opacity-25" style="font-size: 3rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Redemptions Report Card -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-0 border-start border-4 border-info shadow h-100 py-2 transition-hover">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs fw-bold text-info text-uppercase mb-1">
                                Beneficios
                            </div>
                            <div class="h5 mb-0 fw-bold text-gray-800 mb-3">Registro de Canjes</div>
                            <p class="text-muted small mb-4">Exportar registro de canjes de EcoPoints por productos, con estado (pendientes, aprobados, rechazados).</p>
                            <a href="{{ route('admin.reports.redemptions.csv') }}" class="btn btn-outline-info w-100">
                                <i class="bi bi-filetype-csv me-1"></i> Exportar CSV
                            </a>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-gift fa-2x text-info opacity-25" style="font-size: 3rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.transition-hover {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.transition-hover:hover {
    transform: translateY(-5px);
    box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
}
</style>
@endsection

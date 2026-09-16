@extends('layouts.admin')

@section('title', 'Configuración')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 mb-2 text-gray-800">Configuración del Sistema</h1>
            <p class="mb-4 text-muted">Ajustes generales y parámetros de reglas de negocio para Q'omer Life.</p>
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
            <div class="card shadow border-0 mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-primary"><i class="bi bi-sliders me-2"></i>Parámetros Generales</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.settings.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <h5 class="card-title text-dark mb-3 mt-2"><i class="bi bi-shield-lock text-success me-2"></i>Restricciones y Sistema</h5>

                        <div class="mb-4">
                            <label for="maxDailyTransactions" class="form-label fw-medium">Máximo Transacciones Diarias por Usuario</label>
                            <input type="number" class="form-control" id="maxDailyTransactions" name="maxDailyTransactions" 
                                value="{{ old('maxDailyTransactions', $settings['maxDailyTransactions'] ?? 5) }}" required>
                            <div class="form-text">Límite de veces que un usuario puede reciclar en un día para prevenir fraude.</div>
                        </div>

                        <div class="mb-4">
                            <label for="systemName" class="form-label fw-medium">Nombre del Sistema</label>
                            <input type="text" class="form-control" id="systemName" name="systemName" 
                                value="{{ old('systemName', $settings['systemName'] ?? "Q'omer Life") }}" required>
                        </div>

                        <div class="mb-4 card bg-light border-0">
                            <div class="card-body">
                                <div class="form-check form-switch form-switch-lg">
                                    <input class="form-check-input" type="checkbox" id="systemActive" name="systemActive" value="1" 
                                        {{ old('systemActive', $settings['systemActive'] ?? true) ? 'checked' : '' }}>
                                    <label class="form-check-label ms-2 fw-medium" for="systemActive">Sistema Activo (Permitir operaciones)</label>
                                </div>
                                <div class="form-text ms-2 mt-2">
                                    Al desactivar esta opción, los usuarios no podrán realizar reciclajes ni canjes, mostrará modo mantenimiento en las apps.
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mt-4">
                            <button type="submit" class="btn btn-primary btn-lg px-4">
                                <i class="bi bi-save me-2"></i> Guardar Configuración
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card shadow border-0 mb-4 bg-primary text-white">
                <div class="card-body">
                    <h5 class="card-title"><i class="bi bi-info-circle me-2"></i>Información</h5>
                    <p class="card-text">
                        La configuración de esta pantalla afecta directamente la lógica de negocio en la aplicación móvil y máquinas de reciclaje en tiempo real.
                    </p>
                    <p class="card-text mb-0">
                        Asegúrese de guardar los cambios para que tomen efecto global en la plataforma.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Sección CRUD de Productos -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow border-0 mb-4">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary"><i class="bi bi-upc-scan me-2"></i>Gestión de Productos (Códigos de Barras)</h6>
                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#createProductModal">
                        <i class="bi bi-plus-circle me-1"></i> Añadir Producto
                    </button>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3">Código de Barras</th>
                                    <th>Nombre</th>
                                    <th>Material</th>
                                    <th>Presentación</th>
                                    <th class="text-center">EcoPoints</th>
                                    <th class="text-center">Estado</th>
                                    <th class="text-end pe-3">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($productos as $p)
                                    <tr>
                                        <td class="ps-3 font-monospace">{{ $p['codigoBarras'] ?? $p['id'] ?? 'N/A' }}</td>
                                        <td class="fw-medium">{{ $p['nombre'] ?? 'N/A' }}</td>
                                        <td>{{ $p['material'] ?? 'N/A' }}</td>
                                        <td>{{ $p['presentacion'] ?? 'N/A' }}</td>
                                        <td class="text-center text-success fw-bold">+{{ $p['ecoPoints'] ?? 0 }}</td>
                                        <td class="text-center">
                                            @if(isset($p['activo']) && $p['activo'])
                                                <span class="badge bg-success">Activo</span>
                                            @else
                                                <span class="badge bg-danger">Inactivo</span>
                                            @endif
                                        </td>
                                        <td class="text-end pe-3">
                                            <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#editProductModal{{ $p['id'] }}">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <form action="{{ route('admin.products.destroy', $p['id']) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Estás seguro de eliminar este producto?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>

                                    <!-- Modal Editar -->
                                    <div class="modal fade" id="editProductModal{{ $p['id'] }}" tabindex="-1" aria-labelledby="editProductModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <form action="{{ route('admin.products.update', $p['id']) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Editar Producto</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label class="form-label">Código de Barras</label>
                                                            <input type="text" class="form-control" value="{{ $p['codigoBarras'] ?? $p['id'] }}" disabled>
                                                            <div class="form-text">El código de barras no se puede modificar.</div>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Nombre del Producto</label>
                                                            <input type="text" class="form-control" name="nombre" value="{{ $p['nombre'] ?? '' }}" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Material (Ej: Botella PET)</label>
                                                            <input type="text" class="form-control" name="material" value="{{ $p['material'] ?? '' }}" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Presentación (Ej: 2 litros)</label>
                                                            <input type="text" class="form-control" name="presentacion" value="{{ $p['presentacion'] ?? '' }}" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">EcoPoints a Asignar</label>
                                                            <input type="number" class="form-control" name="ecoPoints" value="{{ $p['ecoPoints'] ?? 0 }}" required>
                                                        </div>
                                                        <div class="form-check form-switch mt-3">
                                                            <input class="form-check-input" type="checkbox" name="activo" value="1" {{ (isset($p['activo']) && $p['activo']) ? 'checked' : '' }}>
                                                            <label class="form-check-label">Producto Activo</label>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4 text-muted">
                                            No hay productos registrados.
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
</div>

<!-- Modal Crear -->
<div class="modal fade" id="createProductModal" tabindex="-1" aria-labelledby="createProductModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('admin.products.store') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createProductModalLabel">Nuevo Producto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Código de Barras <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="codigoBarras" required placeholder="Ej: 7771609000052">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nombre del Producto <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="nombre" required placeholder="Ej: Vital">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Material <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="material" required placeholder="Ej: Botella PET">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Presentación <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="presentacion" required placeholder="Ej: 2 litros">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">EcoPoints a Asignar <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" name="ecoPoints" required value="1" min="0">
                    </div>
                    <div class="form-check form-switch mt-3">
                        <input class="form-check-input" type="checkbox" name="activo" value="1" checked>
                        <label class="form-check-label">Producto Activo</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Crear Producto</button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection

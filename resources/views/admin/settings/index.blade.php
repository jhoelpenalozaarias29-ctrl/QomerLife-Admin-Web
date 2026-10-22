@extends('layouts.admin')

@section('title', 'Configuración del Sistema')

@section('content')
<div class="container-fluid px-0">

    {{-- Encabezado --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <h1 class="h3 mb-1 fw-bold" style="color:var(--qomer-text);">
                <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="var(--qomer-emerald)" stroke-width="2" class="me-2" style="vertical-align:-4px"><circle cx="12" cy="12" r="3"/><path d="M19.07 4.93a10 10 0 0 1 0 14.14M4.93 4.93a10 10 0 0 0 0 14.14"/><path d="M20.66 2.34a16 16 0 0 1 0 19.32"/><path d="M3.34 2.34a16 16 0 0 0 0 19.32"/></svg>
                Configuración del Sistema
            </h1>
            <p class="small mb-0" style="color:var(--qomer-text-muted);">Ajustes generales y parámetros de reglas de negocio para Q'omer Life.</p>
        </div>
    </div>

    {{-- Alerta de sesión (ya manejada en layout, pero por si acaso) --}}
    @if(session('success'))
        <div class="alert mb-4 p-3 rounded-3 d-flex align-items-center gap-3"
             style="background:rgba(0,199,129,.1);border:1px solid rgba(0,199,129,.35);color:var(--qomer-emerald);">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
            <span class="fw-semibold">{{ session('success') }}</span>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" style="filter:invert(.6);"></button>
        </div>
    @endif

    <div class="row g-4">
        {{-- Formulario Principal --}}
        <div class="col-lg-8">
            <div class="card-stat p-0 overflow-hidden mb-4">
                <div class="px-4 py-3" style="border-bottom:1px solid var(--qomer-border);background:var(--qomer-surface-elevated);">
                    <span class="fw-semibold" style="color:var(--qomer-text);">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--qomer-emerald)" stroke-width="2" class="me-2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                        Parámetros Generales
                    </span>
                </div>
                <div class="p-4">
                    <form action="{{ route('admin.settings.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <h6 class="fw-semibold mb-4" style="color:var(--qomer-emerald);">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                            Restricciones y Sistema
                        </h6>

                        <div class="mb-4">
                            <label for="maxDailyTransactions" class="form-label small fw-semibold" style="color:var(--qomer-text-muted);">
                                Máximo Transacciones Diarias por Usuario
                            </label>
                            <input type="number" class="form-control qomer-input" id="maxDailyTransactions"
                                   name="maxDailyTransactions"
                                   value="{{ old('maxDailyTransactions', $settings['maxDailyTransactions'] ?? 5) }}"
                                   required>
                            <div class="form-text mt-1" style="color:var(--qomer-text-muted);">
                                Límite de veces que un usuario puede reciclar en un día para prevenir fraude.
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="systemName" class="form-label small fw-semibold" style="color:var(--qomer-text-muted);">
                                Nombre del Sistema
                            </label>
                            <input type="text" class="form-control qomer-input" id="systemName"
                                   name="systemName"
                                   value="{{ old('systemName', $settings['systemName'] ?? \"Q'omer Life\") }}"
                                   required>
                        </div>

                        <div class="mb-5 p-4 rounded-3" style="background:var(--qomer-surface-elevated);border:1px solid var(--qomer-border);">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <div class="fw-semibold mb-1" style="color:var(--qomer-text);">Sistema Activo (Permitir operaciones)</div>
                                    <div class="small" style="color:var(--qomer-text-muted);">
                                        Al desactivar, los usuarios no podrán realizar reciclajes ni canjes. Se mostrará modo mantenimiento en la app.
                                    </div>
                                </div>
                                <div class="ms-3">
                                    <div class="form-check form-switch mb-0">
                                        <input class="form-check-input" type="checkbox" id="systemActive"
                                               name="systemActive" value="1" role="switch"
                                               {{ old('systemActive', $settings['systemActive'] ?? true) ? 'checked' : '' }}
                                               style="width:3rem;height:1.5rem;cursor:pointer;">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-qomer px-5">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-2"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                                Guardar Configuración
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Gestión de Productos --}}
            <div class="card-stat p-0 overflow-hidden">
                <div class="px-4 py-3 d-flex justify-content-between align-items-center" style="border-bottom:1px solid var(--qomer-border);background:var(--qomer-surface-elevated);">
                    <span class="fw-semibold" style="color:var(--qomer-text);">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--qomer-cyan)" stroke-width="2" class="me-2"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M9 3v18M3 9h18"/></svg>
                        Gestión de Productos (Códigos de Barras)
                    </span>
                    <button class="btn btn-sm btn-qomer" data-bs-toggle="modal" data-bs-target="#createProductModal">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-1"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                        Añadir Producto
                    </button>
                </div>
                <div class="table-responsive">
                    <table class="table mb-0 align-middle">
                        <thead>
                            <tr style="background:var(--qomer-surface-elevated);border-bottom:1px solid var(--qomer-border);">
                                <th class="ps-4 py-3 text-xs fw-bold text-uppercase" style="color:var(--qomer-text-muted);letter-spacing:.08em;">Código</th>
                                <th class="py-3 text-xs fw-bold text-uppercase" style="color:var(--qomer-text-muted);letter-spacing:.08em;">Nombre</th>
                                <th class="py-3 text-xs fw-bold text-uppercase" style="color:var(--qomer-text-muted);letter-spacing:.08em;">Material</th>
                                <th class="py-3 text-xs fw-bold text-uppercase" style="color:var(--qomer-text-muted);letter-spacing:.08em;">Presentación</th>
                                <th class="py-3 text-xs fw-bold text-uppercase text-center" style="color:var(--qomer-text-muted);letter-spacing:.08em;">EcoPoints</th>
                                <th class="py-3 text-xs fw-bold text-uppercase text-center" style="color:var(--qomer-text-muted);letter-spacing:.08em;">Estado</th>
                                <th class="py-3 text-xs fw-bold text-uppercase text-end pe-4" style="color:var(--qomer-text-muted);letter-spacing:.08em;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($productos as $p)
                                <tr class="qomer-table-row" style="border-bottom:1px solid var(--qomer-border);">
                                    <td class="ps-4 py-3">
                                        <span class="font-mono small" style="color:var(--qomer-emerald);">{{ $p['codigoBarras'] ?? $p['id'] ?? 'N/A' }}</span>
                                    </td>
                                    <td class="py-3 fw-semibold" style="color:var(--qomer-text);">{{ $p['nombre'] ?? 'N/A' }}</td>
                                    <td class="py-3 small" style="color:var(--qomer-text-muted);">{{ $p['material'] ?? 'N/A' }}</td>
                                    <td class="py-3 small" style="color:var(--qomer-text-muted);">{{ $p['presentacion'] ?? 'N/A' }}</td>
                                    <td class="py-3 text-center fw-bold" style="color:var(--qomer-amber);">+{{ $p['ecoPoints'] ?? 0 }}</td>
                                    <td class="py-3 text-center">
                                        @if(isset($p['activo']) && $p['activo'])
                                            <span class="badge-emerald">Activo</span>
                                        @else
                                            <span class="badge" style="background:rgba(239,68,68,.15);color:#f87171;border:1px solid rgba(239,68,68,.3);">Inactivo</span>
                                        @endif
                                    </td>
                                    <td class="py-3 text-end pe-4">
                                        <div class="d-flex gap-2 justify-content-end">
                                            <button class="btn btn-sm" style="background:rgba(0,199,129,.12);color:var(--qomer-emerald);border:1px solid rgba(0,199,129,.3);border-radius:8px;"
                                                    data-bs-toggle="modal" data-bs-target="#editProductModal{{ $p['id'] }}" title="Editar">
                                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                            </button>
                                            <form action="{{ route('admin.products.destroy', $p['id']) }}" method="POST" class="d-inline"
                                                  onsubmit="return confirm('¿Estás seguro de eliminar este producto?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm" style="background:rgba(239,68,68,.12);color:#f87171;border:1px solid rgba(239,68,68,.3);border-radius:8px;" title="Eliminar">
                                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>

                                {{-- Modal Editar Producto --}}
                                <div class="modal fade" id="editProductModal{{ $p['id'] }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <form action="{{ route('admin.products.update', $p['id']) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-content" style="background:var(--qomer-surface);border:1px solid var(--qomer-border);border-radius:16px;">
                                                <div class="modal-header" style="border-bottom:1px solid var(--qomer-border);">
                                                    <h5 class="modal-title fw-bold" style="color:var(--qomer-text);">
                                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--qomer-emerald)" stroke-width="2" class="me-2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                                        Editar Producto
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" style="filter:var(--icon-filter);"></button>
                                                </div>
                                                <div class="modal-body p-4">
                                                    <div class="mb-3">
                                                        <label class="form-label small fw-semibold" style="color:var(--qomer-text-muted);">Código de Barras</label>
                                                        <input type="text" class="form-control qomer-input" value="{{ $p['codigoBarras'] ?? $p['id'] }}" disabled>
                                                        <div class="form-text" style="color:var(--qomer-text-muted);">El código de barras no se puede modificar.</div>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label small fw-semibold" style="color:var(--qomer-text-muted);">Nombre del Producto</label>
                                                        <input type="text" class="form-control qomer-input" name="nombre" value="{{ $p['nombre'] ?? '' }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label small fw-semibold" style="color:var(--qomer-text-muted);">Material</label>
                                                        <input type="text" class="form-control qomer-input" name="material" value="{{ $p['material'] ?? '' }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label small fw-semibold" style="color:var(--qomer-text-muted);">Presentación</label>
                                                        <input type="text" class="form-control qomer-input" name="presentacion" value="{{ $p['presentacion'] ?? '' }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label small fw-semibold" style="color:var(--qomer-text-muted);">EcoPoints a Asignar</label>
                                                        <input type="number" class="form-control qomer-input" name="ecoPoints" value="{{ $p['ecoPoints'] ?? 0 }}" required>
                                                    </div>
                                                    <div class="p-3 rounded-3" style="background:var(--qomer-surface-elevated);border:1px solid var(--qomer-border);">
                                                        <div class="form-check form-switch">
                                                            <input class="form-check-input" type="checkbox" name="activo" value="1"
                                                                   {{ (isset($p['activo']) && $p['activo']) ? 'checked' : '' }}>
                                                            <label class="form-check-label fw-semibold" style="color:var(--qomer-text);">Producto Activo</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer" style="border-top:1px solid var(--qomer-border);">
                                                    <button type="button" class="btn px-4" data-bs-dismiss="modal"
                                                            style="background:var(--qomer-surface-elevated);color:var(--qomer-text-muted);border:1px solid var(--qomer-border);border-radius:10px;">Cancelar</button>
                                                    <button type="submit" class="btn btn-qomer px-4">Guardar Cambios</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5">
                                        <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="var(--qomer-text-muted)" stroke-width="1" class="mb-3 d-block mx-auto opacity-50"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M9 3v18M3 9h18"/></svg>
                                        <p style="color:var(--qomer-text-muted);">No hay productos registrados.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Panel Lateral --}}
        <div class="col-lg-4">
            <div class="p-4 rounded-3 mb-4" style="background:linear-gradient(135deg,rgba(0,199,129,.12),rgba(6,182,212,.08));border:1px solid rgba(0,199,129,.3);">
                <div class="d-flex align-items-start gap-3">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="var(--qomer-emerald)" stroke-width="2" class="flex-shrink-0 mt-1"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/></svg>
                    <div>
                        <div class="fw-bold mb-2" style="color:var(--qomer-emerald);">Información Importante</div>
                        <p class="small mb-2" style="color:var(--qomer-text-muted);">
                            La configuración de esta pantalla afecta directamente la lógica de negocio en la aplicación móvil y máquinas de reciclaje en tiempo real.
                        </p>
                        <p class="small mb-0" style="color:var(--qomer-text-muted);">
                            Asegúrate de guardar los cambios para que tomen efecto global en la plataforma.
                        </p>
                    </div>
                </div>
            </div>

            <div class="card-stat p-4">
                <div class="text-xs fw-bold text-uppercase mb-3" style="color:var(--qomer-text-muted);letter-spacing:.08em;">Estado Actual del Sistema</div>
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="status-pulse"></span>
                    <span class="fw-semibold" style="color:var(--qomer-emerald);">Firestore Conectado</span>
                </div>
                <div class="small" style="color:var(--qomer-text-muted);">Sincronización en tiempo real activa con la app móvil y máquinas IoT.</div>
            </div>
        </div>
    </div>
</div>

{{-- Modal Crear Producto --}}
<div class="modal fade" id="createProductModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('admin.products.store') }}" method="POST">
            @csrf
            <div class="modal-content" style="background:var(--qomer-surface);border:1px solid var(--qomer-border);border-radius:16px;">
                <div class="modal-header" style="border-bottom:1px solid var(--qomer-border);">
                    <h5 class="modal-title fw-bold" style="color:var(--qomer-text);">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--qomer-emerald)" stroke-width="2" class="me-2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                        Nuevo Producto
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" style="filter:var(--icon-filter);"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold" style="color:var(--qomer-text-muted);">Código de Barras <span style="color:#f87171;">*</span></label>
                        <input type="text" class="form-control qomer-input" name="codigoBarras" required placeholder="Ej: 7771609000052">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold" style="color:var(--qomer-text-muted);">Nombre del Producto <span style="color:#f87171;">*</span></label>
                        <input type="text" class="form-control qomer-input" name="nombre" required placeholder="Ej: Vital">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold" style="color:var(--qomer-text-muted);">Material <span style="color:#f87171;">*</span></label>
                        <input type="text" class="form-control qomer-input" name="material" required placeholder="Ej: Botella PET">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold" style="color:var(--qomer-text-muted);">Presentación <span style="color:#f87171;">*</span></label>
                        <input type="text" class="form-control qomer-input" name="presentacion" required placeholder="Ej: 2 litros">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold" style="color:var(--qomer-text-muted);">EcoPoints a Asignar <span style="color:#f87171;">*</span></label>
                        <input type="number" class="form-control qomer-input" name="ecoPoints" required value="1" min="0">
                    </div>
                    <div class="p-3 rounded-3" style="background:var(--qomer-surface-elevated);border:1px solid var(--qomer-border);">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="activo" value="1" checked>
                            <label class="form-check-label fw-semibold" style="color:var(--qomer-text);">Producto Activo</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="border-top:1px solid var(--qomer-border);">
                    <button type="button" class="btn px-4" data-bs-dismiss="modal"
                            style="background:var(--qomer-surface-elevated);color:var(--qomer-text-muted);border:1px solid var(--qomer-border);border-radius:10px;">Cancelar</button>
                    <button type="submit" class="btn btn-qomer px-4">Crear Producto</button>
                </div>
            </div>
        </form>
    </div>
</div>

<style>
.qomer-table-row { transition: background .18s; }
.qomer-table-row:hover { background: rgba(0,199,129,.04) !important; }
.qomer-table-row:last-child { border-bottom: none !important; }
</style>
@endsection

@extends('layouts.admin')

@section('title', 'Editar Incentivo')

@section('content')
<div class="container-fluid px-0">

    {{-- Encabezado --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 fw-bold" style="color:var(--qomer-text);">
                <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="var(--qomer-amber)" stroke-width="2" class="me-2" style="vertical-align:-4px"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                Editar: {{ $incentive->title }}
            </h1>
            <p class="small mb-0" style="color:var(--qomer-text-muted);">Modifica datos, costo en EcoPoints o disponibilidad para la app móvil.</p>
        </div>
        <a href="{{ route('admin.incentives.index') }}" class="btn btn-sm px-3"
           style="background:var(--qomer-surface-elevated);color:var(--qomer-text-muted);border:1px solid var(--qomer-border);border-radius:10px;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-1"><path d="m15 18-6-6 6-6"/></svg>
            Volver al Catálogo
        </a>
    </div>

    @if ($errors->any())
        <div class="mb-4 p-3 rounded-3 d-flex align-items-start gap-3"
             style="background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.35);color:#f87171;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="flex-shrink-0 mt-1"><path d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
            <div>
                <div class="fw-bold mb-1">Corrige los siguientes errores:</div>
                <ul class="mb-0 small">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        </div>
    @endif

    <div class="row g-4">
        {{-- Formulario de Edición --}}
        <div class="col-lg-7">
            <div class="card-stat p-0 overflow-hidden">
                <div class="px-4 py-3 d-flex justify-content-between align-items-center" style="border-bottom:1px solid var(--qomer-border);background:var(--qomer-surface-elevated);">
                    <span class="fw-semibold" style="color:var(--qomer-text);">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--qomer-amber)" stroke-width="2" class="me-2"><circle cx="12" cy="12" r="3"/><path d="M19.07 4.93a10 10 0 0 1 0 14.14M4.93 4.93a10 10 0 0 0 0 14.14"/></svg>
                        Parámetros del Incentivo
                    </span>
                    <span class="font-mono small px-2 py-1 rounded" style="background:var(--qomer-surface);color:var(--qomer-text-muted);border:1px solid var(--qomer-border);">{{ $incentive->id }}</span>
                </div>
                <div class="p-4">
                    <form action="{{ route('admin.incentives.update', $incentive->id) }}" method="POST" id="incentiveEditForm">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label class="form-label small fw-semibold" style="color:var(--qomer-text-muted);">Título del Incentivo <span style="color:#f87171;">*</span></label>
                            <input type="text" name="title" id="inputTitle"
                                   class="form-control qomer-input @error('title') is-invalid @enderror"
                                   value="{{ old('title', $incentive->title) }}" required>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold" style="color:var(--qomer-text-muted);">Categoría <span style="color:#f87171;">*</span></label>
                                <select name="category" id="selectCategory"
                                        class="form-select qomer-input @error('category') is-invalid @enderror" required>
                                    @php $allCats = array_unique(array_merge($categories, [$incentive->category])); @endphp
                                    @foreach($allCats as $cat)
                                        <option value="{{ $cat }}" {{ old('category', $incentive->category) === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold" style="color:var(--qomer-text-muted);">EcoPoints Requeridos <span style="color:#f87171;">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text" style="background:var(--qomer-surface-elevated);border-color:var(--qomer-border);">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="var(--qomer-amber)" stroke="none"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                                    </span>
                                    <input type="number" name="ecoPointsRequired" id="inputPoints"
                                           class="form-control qomer-input @error('ecoPointsRequired') is-invalid @enderror"
                                           min="1" value="{{ old('ecoPointsRequired', $incentive->ecoPointsRequired) }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold" style="color:var(--qomer-text-muted);">Stock Disponible <span style="color:#f87171;">*</span></label>
                                <input type="number" name="availableQuantity" id="inputStock"
                                       class="form-control qomer-input @error('availableQuantity') is-invalid @enderror"
                                       min="0" value="{{ old('availableQuantity', $incentive->availableQuantity) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold" style="color:var(--qomer-text-muted);">ID del Documento</label>
                                <input type="text" class="form-control qomer-input" value="{{ $incentive->id }}" readonly
                                       style="opacity:.6;cursor:not-allowed;">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label small fw-semibold" style="color:var(--qomer-text-muted);">URL de Imagen</label>
                            <div class="input-group">
                                <span class="input-group-text" style="background:var(--qomer-surface-elevated);border-color:var(--qomer-border);color:var(--qomer-text-muted);">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>
                                </span>
                                <input type="url" name="imageUrl" id="inputImage"
                                       class="form-control qomer-input @error('imageUrl') is-invalid @enderror"
                                       placeholder="https://..." value="{{ old('imageUrl', $incentive->imageUrl) }}">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label small fw-semibold" style="color:var(--qomer-text-muted);">Descripción Detallada <span style="color:#f87171;">*</span></label>
                            <textarea name="description" id="inputDesc" rows="4"
                                      class="form-control qomer-input @error('description') is-invalid @enderror"
                                      required>{{ old('description', $incentive->description) }}</textarea>
                        </div>

                        <div class="p-3 mb-4 rounded-3 d-flex align-items-center justify-content-between" style="background:var(--qomer-surface-elevated);border:1px solid var(--qomer-border);">
                            <div>
                                <div class="fw-semibold small mb-1" style="color:var(--qomer-text);">Activo para canje en la app móvil</div>
                                <div class="small" style="color:var(--qomer-text-muted);">Los usuarios pueden ver y canjear este incentivo.</div>
                            </div>
                            <div class="form-check form-switch mb-0">
                                <input class="form-check-input" type="checkbox" name="isAvailable" id="switchAvailable"
                                       value="1" role="switch"
                                       {{ old('isAvailable', $incentive->isAvailable) ? 'checked' : '' }}
                                       style="width:3rem;height:1.5rem;cursor:pointer;">
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center pt-3" style="border-top:1px solid var(--qomer-border);">
                            <button type="button" class="btn btn-sm px-3"
                                    style="background:rgba(239,68,68,.1);color:#f87171;border:1px solid rgba(239,68,68,.3);border-radius:10px;"
                                    data-bs-toggle="modal" data-bs-target="#deleteModal">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-1"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>
                                Eliminar
                            </button>
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.incentives.index') }}"
                                   class="btn px-4" style="background:var(--qomer-surface-elevated);color:var(--qomer-text-muted);border:1px solid var(--qomer-border);border-radius:10px;">
                                    Cancelar
                                </a>
                                <button type="submit" class="btn btn-qomer px-5">
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-1"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                                    Guardar Cambios
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Vista Previa en Vivo --}}
        <div class="col-lg-5">
            <div class="sticky-top" style="top:2rem;">
                <div class="card-stat p-0 overflow-hidden">
                    <div class="px-4 py-3 d-flex justify-content-between align-items-center" style="border-bottom:1px solid var(--qomer-border);background:var(--qomer-surface-elevated);">
                        <span class="small fw-bold text-uppercase" style="color:var(--qomer-text-muted);letter-spacing:.07em;">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-1"><rect x="5" y="2" width="14" height="20" rx="2"/><line x1="12" y1="18" x2="12.01" y2="18"/></svg>
                            Vista Previa App Móvil
                        </span>
                        <span class="badge" style="background:rgba(0,199,129,.1);color:var(--qomer-emerald);border:1px solid rgba(0,199,129,.3);">En vivo</span>
                    </div>
                    <div class="p-4" style="background:var(--qomer-surface-elevated);">
                        <div class="mx-auto overflow-hidden" style="max-width:300px;background:var(--qomer-surface);border:1px solid var(--qomer-border);border-radius:16px;">
                            <div class="position-relative ratio ratio-16x9">
                                <img id="previewImage" src="{{ $incentive->imageUrl ?: 'https://placehold.co/600x400/141A17/00C781?text=Incentivo' }}"
                                     alt="Preview" style="object-fit:cover;width:100%;height:100%;">
                                <div class="position-absolute top-0 start-0 m-2">
                                    <span class="badge" id="previewCategory"
                                          style="background:rgba(15,20,17,.8);backdrop-filter:blur(8px);color:var(--qomer-text-muted);border:1px solid var(--qomer-border);">{{ $incentive->category }}</span>
                                </div>
                                <div class="position-absolute top-0 end-0 m-2">
                                    <span id="previewStatus" class="{{ $incentive->isAvailable ? 'badge-emerald' : 'badge' }}"
                                          style="{{ !$incentive->isAvailable ? 'background:rgba(100,116,139,.2);color:#94a3b8;border:1px solid rgba(100,116,139,.3);border-radius:6px;padding:4px 10px;font-size:12px;font-weight:600;' : '' }}">
                                        {{ $incentive->isAvailable ? 'Activo' : 'Pausado' }}
                                    </span>
                                </div>
                            </div>
                            <div class="p-3">
                                <h6 class="fw-bold mb-1" id="previewTitle" style="color:var(--qomer-text);">{{ $incentive->title }}</h6>
                                <p class="small mb-3" id="previewDesc" style="color:var(--qomer-text-muted);min-height:36px;">{{ $incentive->description }}</p>
                                <div class="p-2 rounded-3 mb-3 d-flex justify-content-between" style="background:var(--qomer-surface-elevated);border:1px solid var(--qomer-border);">
                                    <div>
                                        <div style="font-size:10px;color:var(--qomer-text-muted);font-weight:700;">PUNTOS</div>
                                        <div class="fw-bold" id="previewPoints" style="color:var(--qomer-amber);">{{ number_format($incentive->ecoPointsRequired) }} pts</div>
                                    </div>
                                    <div class="text-end">
                                        <div style="font-size:10px;color:var(--qomer-text-muted);font-weight:700;">STOCK</div>
                                        <div class="fw-semibold small" id="previewStock" style="color:var(--qomer-text);">{{ $incentive->availableQuantity }} uds</div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-qomer w-100 btn-sm disabled" style="opacity:.8;">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-1"><polyline points="20 12 20 22 4 22 4 12"/><rect x="2" y="7" width="20" height="5"/><path d="M12 22V7"/></svg>
                                    Canjear en App Móvil
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="px-4 py-2 text-center small" style="border-top:1px solid var(--qomer-border);color:var(--qomer-text-muted);">
                        Simulación de cómo verán los usuarios este incentivo en Android.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal Confirmar Eliminación --}}
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="background:var(--qomer-surface);border:1px solid rgba(239,68,68,.4);border-radius:16px;">
            <div class="modal-header" style="border-bottom:1px solid var(--qomer-border);">
                <h5 class="modal-title fw-bold" style="color:#f87171;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-2"><path d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/></svg>
                    Confirmar Eliminación
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" style="filter:var(--icon-filter);"></button>
            </div>
            <div class="modal-body" style="color:var(--qomer-text);">
                ¿Estás completamente seguro de eliminar el incentivo <strong style="color:var(--qomer-amber);">{{ $incentive->title }}</strong>?
                Los usuarios de la app móvil ya no podrán canjearlo.
            </div>
            <div class="modal-footer" style="border-top:1px solid var(--qomer-border);">
                <button type="button" class="btn px-4" data-bs-dismiss="modal"
                        style="background:var(--qomer-surface-elevated);color:var(--qomer-text-muted);border:1px solid var(--qomer-border);border-radius:10px;">Cancelar</button>
                <form action="{{ route('admin.incentives.destroy', $incentive->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn px-4"
                            style="background:rgba(239,68,68,.15);color:#f87171;border:1px solid rgba(239,68,68,.4);border-radius:10px;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-1"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>
                        Sí, Eliminar
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const titleInput     = document.getElementById('inputTitle');
    const categorySelect = document.getElementById('selectCategory');
    const pointsInput    = document.getElementById('inputPoints');
    const stockInput     = document.getElementById('inputStock');
    const imageInput     = document.getElementById('inputImage');
    const descInput      = document.getElementById('inputDesc');
    const switchAvail    = document.getElementById('switchAvailable');

    const previewTitle    = document.getElementById('previewTitle');
    const previewCategory = document.getElementById('previewCategory');
    const previewPoints   = document.getElementById('previewPoints');
    const previewStock    = document.getElementById('previewStock');
    const previewImage    = document.getElementById('previewImage');
    const previewDesc     = document.getElementById('previewDesc');
    const previewStatus   = document.getElementById('previewStatus');

    function updatePreview() {
        previewTitle.textContent    = titleInput.value.trim()  || 'Título del Incentivo';
        previewCategory.textContent = categorySelect.value     || 'General';
        previewPoints.textContent   = (pointsInput.value || 0) + ' pts';
        previewStock.textContent    = (stockInput.value  || 0) + ' uds';
        previewDesc.textContent     = descInput.value.trim()   || 'Descripción...';

        if (imageInput.value.trim()) previewImage.src = imageInput.value.trim();

        if (switchAvail.checked) {
            previewStatus.innerHTML = '<span class="status-pulse me-1"></span>Activo';
            previewStatus.className = 'badge-emerald';
            previewStatus.removeAttribute('style');
        } else {
            previewStatus.textContent = 'Pausado';
            previewStatus.className = 'badge';
            previewStatus.style = 'background:rgba(100,116,139,.2);color:#94a3b8;border:1px solid rgba(100,116,139,.3);border-radius:6px;padding:4px 10px;font-size:12px;font-weight:600;';
        }
    }

    [titleInput, pointsInput, stockInput, imageInput, descInput].forEach(el => el.addEventListener('input', updatePreview));
    [categorySelect, switchAvail].forEach(el => el.addEventListener('change', updatePreview));
});
</script>
@endpush
@endsection

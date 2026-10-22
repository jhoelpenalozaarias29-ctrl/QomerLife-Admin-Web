@extends('layouts.admin')

@section('title', 'Editar Incentivo')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 text-gray-800 fw-bold">
                <i class="bi bi-pencil-square me-2 text-primary"></i>Editar Incentivo: {{ $incentive->title }}
            </h1>
            <p class="text-muted mb-0 small">
                Modifica los datos, costo en EcoPoints o disponibilidad para la aplicación móvil.
            </p>
        </div>
        <div>
            <a href="{{ route('admin.incentives.index') }}" class="btn btn-outline-secondary shadow-sm">
                <i class="bi bi-arrow-left me-1"></i> Volver al Catálogo
            </a>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
            <h6 class="alert-heading fw-bold mb-1"><i class="bi bi-exclamation-triangle-fill me-1"></i> Corrige los siguientes errores:</h6>
            <ul class="mb-0 small">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-4">
        {{-- Formulario de Edición --}}
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0 fs-6 fw-bold text-dark">
                        <i class="bi bi-sliders me-1 text-primary"></i> Parámetros del Incentivo
                    </h5>
                    <span class="badge bg-light text-secondary border font-monospace">{{ $incentive->id }}</span>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('admin.incentives.update', $incentive->id) }}" method="POST" id="incentiveEditForm">
                        @csrf
                        @method('PUT')

                        {{-- Título --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold text-secondary small">Título del Incentivo <span class="text-danger">*</span></label>
                            <input type="text" name="title" id="inputTitle" class="form-control @error('title') is-invalid @enderror" 
                                   value="{{ old('title', $incentive->title) }}" required>
                        </div>

                        {{-- Categoría y EcoPoints --}}
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-secondary small">Categoría <span class="text-danger">*</span></label>
                                <select name="category" id="selectCategory" class="form-select @error('category') is-invalid @enderror" required>
                                    @php
                                        $allCats = array_unique(array_merge($categories, [$incentive->category]));
                                    @endphp
                                    @foreach($allCats as $cat)
                                        <option value="{{ $cat }}" {{ old('category', $incentive->category) === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-secondary small">EcoPoints Requeridos <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light text-success fw-bold"><i class="bi bi-stars"></i></span>
                                    <input type="number" name="ecoPointsRequired" id="inputPoints" class="form-control @error('ecoPointsRequired') is-invalid @enderror" 
                                           min="1" value="{{ old('ecoPointsRequired', $incentive->ecoPointsRequired) }}" required>
                                </div>
                            </div>
                        </div>

                        {{-- Stock --}}
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-secondary small">Stock / Cantidad Disponible <span class="text-danger">*</span></label>
                                <input type="number" name="availableQuantity" id="inputStock" class="form-control @error('availableQuantity') is-invalid @enderror" 
                                       min="0" value="{{ old('availableQuantity', $incentive->availableQuantity) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-secondary small">ID del Documento (Solo lectura)</label>
                                <input type="text" class="form-control bg-light font-monospace" value="{{ $incentive->id }}" readonly>
                            </div>
                        </div>

                        {{-- URL de Imagen --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold text-secondary small">URL de Imagen</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-link-45deg"></i></span>
                                <input type="url" name="imageUrl" id="inputImage" class="form-control @error('imageUrl') is-invalid @enderror" 
                                       placeholder="https://..." value="{{ old('imageUrl', $incentive->imageUrl) }}">
                            </div>
                        </div>

                        {{-- Descripción --}}
                        <div class="mb-4">
                            <label class="form-label fw-semibold text-secondary small">Descripción Detallada <span class="text-danger">*</span></label>
                            <textarea name="description" id="inputDesc" rows="4" class="form-control @error('description') is-invalid @enderror" required>{{ old('description', $incentive->description) }}</textarea>
                        </div>

                        {{-- Estado Activo --}}
                        <div class="form-check form-switch mb-4">
                            <input class="form-check-input" type="checkbox" name="isAvailable" id="switchAvailable" value="1" 
                                   {{ old('isAvailable', $incentive->isAvailable) ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold text-dark small" for="switchAvailable">
                                Activo para canje en la aplicación móvil
                            </label>
                        </div>

                        {{-- Botones de Acción --}}
                        <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                            <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                <i class="bi bi-trash me-1"></i> Eliminar Incentivo
                            </button>
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.incentives.index') }}" class="btn btn-outline-secondary">Cancelar</a>
                                <button type="submit" class="btn btn-primary px-4 shadow-sm">
                                    <i class="bi bi-check-circle me-1"></i> Guardar Cambios
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Vista Previa Interactiva --}}
        <div class="col-lg-5">
            <div class="sticky-top" style="top: 2rem;">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                        <span class="small fw-bold text-uppercase text-muted">
                            <i class="bi bi-phone me-1"></i> Vista Previa en App Móvil
                        </span>
                        <span class="badge bg-light text-secondary border">Simulación en vivo</span>
                    </div>
                    <div class="card-body p-4 bg-light text-center">
                        <div class="card border-0 shadow-sm mx-auto text-start overflow-hidden" style="max-width: 320px;">
                            <div class="position-relative ratio ratio-16x9 bg-secondary-subtle">
                                <img id="previewImage" src="{{ $incentive->imageUrl ?: 'https://placehold.co/600x400/e9ecef/495057?text=Incentivo' }}" 
                                     alt="Preview" class="object-fit-cover w-100 h-100">
                                <div class="position-absolute top-0 start-0 m-2">
                                    <span class="badge bg-dark bg-opacity-75" id="previewCategory">{{ $incentive->category }}</span>
                                </div>
                                <div class="position-absolute top-0 end-0 m-2">
                                    <span class="badge {{ $incentive->isAvailable ? 'bg-success' : 'bg-secondary' }}" id="previewStatus">
                                        {{ $incentive->isAvailable ? 'Disponible' : 'Pausado' }}
                                    </span>
                                </div>
                            </div>
                            <div class="card-body p-3">
                                <h6 class="fw-bold text-dark mb-1" id="previewTitle">{{ $incentive->title }}</h6>
                                <p class="text-muted small mb-3" id="previewDesc" style="min-height: 38px;">{{ $incentive->description }}</p>
                                
                                <div class="d-flex justify-content-between align-items-center bg-light p-2 rounded mb-3">
                                    <div>
                                        <div style="font-size: 10px;" class="text-muted fw-bold">PUNTOS</div>
                                        <div class="fw-bold text-success" id="previewPoints">{{ number_format($incentive->ecoPointsRequired) }} pts</div>
                                    </div>
                                    <div class="text-end">
                                        <div style="font-size: 10px;" class="text-muted fw-bold">DISPONIBLE</div>
                                        <div class="fw-semibold text-secondary small" id="previewStock">{{ $incentive->availableQuantity }} uds</div>
                                    </div>
                                </div>

                                <button type="button" class="btn btn-sm btn-success w-100 fw-bold disabled" style="opacity: 0.9;">
                                    <i class="bi bi-gift-fill me-1"></i> Canjear en App Móvil
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal Confirmar Eliminación --}}
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Confirmar Eliminación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                ¿Estás completamente seguro de eliminar el incentivo <strong>{{ $incentive->title }}</strong>? Los usuarios de la app móvil ya no podrán canjearlo.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <form action="{{ route('admin.incentives.destroy', $incentive->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Sí, Eliminar</button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const titleInput = document.getElementById('inputTitle');
    const categorySelect = document.getElementById('selectCategory');
    const pointsInput = document.getElementById('inputPoints');
    const stockInput = document.getElementById('inputStock');
    const imageInput = document.getElementById('inputImage');
    const descInput = document.getElementById('inputDesc');
    const switchAvail = document.getElementById('switchAvailable');

    const previewTitle = document.getElementById('previewTitle');
    const previewCategory = document.getElementById('previewCategory');
    const previewPoints = document.getElementById('previewPoints');
    const previewStock = document.getElementById('previewStock');
    const previewImage = document.getElementById('previewImage');
    const previewDesc = document.getElementById('previewDesc');
    const previewStatus = document.getElementById('previewStatus');

    function updatePreview() {
        previewTitle.textContent = titleInput.value.trim() || 'Título del Incentivo';
        previewCategory.textContent = categorySelect.value || 'General';
        previewPoints.textContent = (pointsInput.value || 0) + ' pts';
        previewStock.textContent = (stockInput.value || 0) + ' uds';
        previewDesc.textContent = descInput.value.trim() || 'Descripción del incentivo...';

        if (imageInput.value.trim()) {
            previewImage.src = imageInput.value.trim();
        }

        if (switchAvail.checked) {
            previewStatus.className = 'badge bg-success';
            previewStatus.textContent = 'Disponible';
        } else {
            previewStatus.className = 'badge bg-secondary';
            previewStatus.textContent = 'Pausado';
        }
    }

    titleInput.addEventListener('input', updatePreview);
    categorySelect.addEventListener('change', updatePreview);
    pointsInput.addEventListener('input', updatePreview);
    stockInput.addEventListener('input', updatePreview);
    imageInput.addEventListener('input', updatePreview);
    descInput.addEventListener('input', updatePreview);
    switchAvail.addEventListener('change', updatePreview);
});
</script>
@endpush
@endsection

@extends('layouts.admin')

@section('title', 'Nuevo Incentivo')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 text-gray-800 fw-bold">
                <i class="bi bi-plus-circle me-2 text-primary"></i>Nuevo Incentivo / Recompensa
            </h1>
            <p class="text-muted mb-0 small">
                Define un nuevo premio que estará disponible de inmediato en la aplicación móvil de Q'omer Life.
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
        {{-- Formulario de Creación --}}
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3 border-bottom">
                    <h5 class="card-title mb-0 fs-6 fw-bold text-dark">
                        <i class="bi bi-pencil-square me-1 text-primary"></i> Datos del Incentivo
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('admin.incentives.store') }}" method="POST" id="incentiveForm">
                        @csrf

                        {{-- Título --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold text-secondary small">Título del Incentivo <span class="text-danger">*</span></label>
                            <input type="text" name="title" id="inputTitle" class="form-control @error('title') is-invalid @enderror" 
                                   placeholder="Ej: Café Universitario, Termo Ecológico..." value="{{ old('title') }}" required>
                            <div class="form-text">Nombre claro y atractivo que verá el estudiante o usuario.</div>
                        </div>

                        {{-- Categoría y EcoPoints --}}
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-secondary small">Categoría <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <select name="category" id="selectCategory" class="form-select @error('category') is-invalid @enderror" required>
                                        @foreach($categories as $cat)
                                            <option value="{{ $cat }}" {{ old('category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-secondary small">EcoPoints Requeridos <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light text-success fw-bold"><i class="bi bi-stars"></i></span>
                                    <input type="number" name="ecoPointsRequired" id="inputPoints" class="form-control @error('ecoPointsRequired') is-invalid @enderror" 
                                           placeholder="Ej: 150" min="1" value="{{ old('ecoPointsRequired', 100) }}" required>
                                </div>
                            </div>
                        </div>

                        {{-- Stock y URL de Imagen --}}
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-secondary small">Stock / Cantidad Disponible <span class="text-danger">*</span></label>
                                <input type="number" name="availableQuantity" id="inputStock" class="form-control @error('availableQuantity') is-invalid @enderror" 
                                       placeholder="Ej: 50" min="0" value="{{ old('availableQuantity', 50) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-secondary small">ID Personalizado (Opcional)</label>
                                <input type="text" name="id" class="form-control" placeholder="Ej: inc_cafe_especial" value="{{ old('id') }}">
                                <div class="form-text">Si se deja vacío, se generará automáticamente.</div>
                            </div>
                        </div>

                        {{-- URL de Imagen --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold text-secondary small">URL de Imagen (Opcional)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-link-45deg"></i></span>
                                <input type="url" name="imageUrl" id="inputImage" class="form-control @error('imageUrl') is-invalid @enderror" 
                                       placeholder="https://images.unsplash.com/..." value="{{ old('imageUrl') }}">
                            </div>
                            <div class="form-text">Enlace directo a una imagen JPG o PNG en la web.</div>
                        </div>

                        {{-- Descripción --}}
                        <div class="mb-4">
                            <label class="form-label fw-semibold text-secondary small">Descripción Detallada <span class="text-danger">*</span></label>
                            <textarea name="description" id="inputDesc" rows="4" class="form-control @error('description') is-invalid @enderror" 
                                      placeholder="Describe en qué consiste el beneficio, dónde se reclama y condiciones de uso..." required>{{ old('description') }}</textarea>
                        </div>

                        {{-- Estado Activo --}}
                        <div class="form-check form-switch mb-4">
                            <input class="form-check-input" type="checkbox" name="isAvailable" id="switchAvailable" value="1" {{ old('isAvailable', '1') ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold text-dark small" for="switchAvailable">
                                Publicar inmediatamente (Disponible para canje en la app)
                            </label>
                        </div>

                        {{-- Botones de Envío --}}
                        <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                            <a href="{{ route('admin.incentives.index') }}" class="btn btn-outline-secondary">Cancelar</a>
                            <button type="submit" class="btn btn-primary px-4 shadow-sm">
                                <i class="bi bi-check-circle me-1"></i> Guardar y Publicar
                            </button>
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
                        {{-- Tarjeta simulación móvil --}}
                        <div class="card border-0 shadow-sm mx-auto text-start overflow-hidden" style="max-width: 320px;">
                            <div class="position-relative ratio ratio-16x9 bg-secondary-subtle">
                                <img id="previewImage" src="https://placehold.co/600x400/e9ecef/495057?text=Vista+Previa" 
                                     alt="Preview" class="object-fit-cover w-100 h-100">
                                <div class="position-absolute top-0 start-0 m-2">
                                    <span class="badge bg-dark bg-opacity-75" id="previewCategory">Cafetería</span>
                                </div>
                                <div class="position-absolute top-0 end-0 m-2">
                                    <span class="badge bg-success" id="previewStatus">Disponible</span>
                                </div>
                            </div>
                            <div class="card-body p-3">
                                <h6 class="fw-bold text-dark mb-1" id="previewTitle">Título del Incentivo</h6>
                                <p class="text-muted small mb-3" id="previewDesc" style="min-height: 38px;">Descripción del incentivo...</p>
                                
                                <div class="d-flex justify-content-between align-items-center bg-light p-2 rounded mb-3">
                                    <div>
                                        <div style="font-size: 10px;" class="text-muted fw-bold">PUNTOS</div>
                                        <div class="fw-bold text-success" id="previewPoints">100 pts</div>
                                    </div>
                                    <div class="text-end">
                                        <div style="font-size: 10px;" class="text-muted fw-bold">DISPONIBLE</div>
                                        <div class="fw-semibold text-secondary small" id="previewStock">50 uds</div>
                                    </div>
                                </div>

                                <button type="button" class="btn btn-sm btn-success w-100 fw-bold disabled" style="opacity: 0.9;">
                                    <i class="bi bi-gift-fill me-1"></i> Canjear en App Móvil
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-white border-top small text-muted text-center py-2">
                        Esta tarjeta refleja cómo verán los usuarios este incentivo en la pantalla de canjes de Android.
                    </div>
                </div>
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
        } else {
            previewImage.src = 'https://placehold.co/600x400/e9ecef/495057?text=' + encodeURIComponent(titleInput.value.trim() || 'Vista Previa');
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

    updatePreview();
});
</script>
@endpush
@endsection

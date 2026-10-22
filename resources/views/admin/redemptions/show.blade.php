@extends('layouts.admin')

@section('title', 'Detalle de Canje')

@section('content')
<div class="container-fluid px-0">

    {{-- Encabezado --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 fw-bold" style="color:var(--qomer-text);">
                <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="var(--qomer-cyan)" stroke-width="2" class="me-2" style="vertical-align:-4px"><polyline points="20 12 20 22 4 22 4 12"/><rect x="2" y="7" width="20" height="5"/><path d="M12 22V7"/></svg>
                Detalle de Canje
            </h1>
            <p class="small mb-0" style="color:var(--qomer-text-muted);">Información completa y gestión del estado de entrega del incentivo.</p>
        </div>
        <a href="{{ route('admin.redemptions.index') }}" class="btn btn-sm px-3"
           style="background:var(--qomer-surface-elevated);color:var(--qomer-text-muted);border:1px solid var(--qomer-border);border-radius:10px;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-1"><path d="m15 18-6-6 6-6"/></svg>
            Volver a Canjes
        </a>
    </div>

    @if(session('success'))
        <div class="alert mb-4 p-3 rounded-3 d-flex align-items-center gap-3"
             style="background:rgba(0,199,129,.1);border:1px solid rgba(0,199,129,.35);color:var(--qomer-emerald);">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
            <span class="fw-semibold">{{ session('success') }}</span>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" style="filter:invert(.6);"></button>
        </div>
    @endif

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card-stat p-0 overflow-hidden h-100">
                <div class="px-4 py-3 d-flex align-items-center justify-content-between" style="border-bottom:1px solid var(--qomer-border);background:var(--qomer-surface-elevated);">
                    <div class="d-flex align-items-center">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--qomer-cyan)" stroke-width="2" class="me-2"><polyline points="20 12 20 22 4 22 4 12"/><rect x="2" y="7" width="20" height="5"/><path d="M12 22V7"/></svg>
                        <span class="fw-semibold" style="color:var(--qomer-text);">Información del Canje</span>
                    </div>
                    <span class="font-mono small px-2 py-1 rounded" style="background:var(--qomer-surface);color:var(--qomer-text-muted);border:1px solid var(--qomer-border);">
                        {{ $redemption['id'] ?? 'N/A' }}
                    </span>
                </div>
                <div class="p-4">
                    <dl class="row mb-0 g-3">
                        <dt class="col-sm-4 py-2" style="color:var(--qomer-text-muted);">ID de Canje</dt>
                        <dd class="col-sm-8 py-2 font-mono small" style="color:var(--qomer-text);">{{ $redemption['id'] ?? 'N/A' }}</dd>

                        <dt class="col-sm-4 py-2" style="color:var(--qomer-text-muted);">Usuario ID</dt>
                        <dd class="col-sm-8 py-2 font-mono small" style="color:var(--qomer-cyan);">{{ $redemption['userId'] ?? 'N/A' }}</dd>

                        <dt class="col-sm-4 py-2" style="color:var(--qomer-text-muted);">Reward / Producto</dt>
                        <dd class="col-sm-8 py-2 fw-semibold" style="color:var(--qomer-text);">{{ $redemption['rewardName'] ?? $redemption['rewardId'] ?? 'N/A' }}</dd>

                        <dt class="col-sm-4 py-2" style="color:var(--qomer-text-muted);">EcoPoints Gastados</dt>
                        <dd class="col-sm-8 py-2 fw-bold font-mono fs-5" style="color:#f87171;">-{{ number_format($redemption['ecoPoints'] ?? ($redemption['pointsCost'] ?? 0)) }} pts</dd>

                        <dt class="col-sm-4 py-2" style="color:var(--qomer-text-muted);">Fecha de Solicitud</dt>
                        <dd class="col-sm-8 py-2" style="color:var(--qomer-text);">
                            @if(isset($redemption['createdAt']))
                                {{ is_numeric($redemption['createdAt']) ? date('d/m/Y H:i:s', (int)$redemption['createdAt']) : \Carbon\Carbon::parse($redemption['createdAt'])->format('d/m/Y H:i:s') }}
                            @else
                                <span style="color:var(--qomer-text-muted);">N/A</span>
                            @endif
                        </dd>

                        <dt class="col-sm-4 py-2" style="color:var(--qomer-text-muted);">Estado Actual</dt>
                        <dd class="col-sm-8 py-2">
                            @php $status = strtoupper($redemption['status'] ?? 'PENDING'); @endphp
                            @if($status === 'PENDING')
                                <span class="badge-amber"><i class="bi bi-hourglass-split me-1"></i>Pendiente</span>
                            @elseif($status === 'APPROVED')
                                <span class="badge-emerald"><i class="bi bi-check-circle me-1"></i>Aprobado</span>
                            @elseif($status === 'REJECTED')
                                <span class="badge" style="background:rgba(239,68,68,.15);color:#f87171;border:1px solid rgba(239,68,68,.3);"><i class="bi bi-x-circle me-1"></i>Rechazado</span>
                            @else
                                <span class="badge" style="background:rgba(100,116,139,.15);color:#94a3b8;border:1px solid rgba(100,116,139,.3);">{{ $status }}</span>
                            @endif
                        </dd>
                    </dl>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card-stat p-0 overflow-hidden">
                <div class="px-4 py-3" style="border-bottom:1px solid var(--qomer-border);background:var(--qomer-surface-elevated);">
                    <span class="fw-semibold" style="color:var(--qomer-text);">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--qomer-emerald)" stroke-width="2" class="me-2"><circle cx="12" cy="12" r="3"/><path d="M19.07 4.93a10 10 0 0 1 0 14.14M4.93 4.93a10 10 0 0 0 0 14.14"/></svg>
                        Gestionar Estado
                    </span>
                </div>
                <div class="p-4">
                    <form action="{{ route('admin.redemptions.updateStatus', $redemption['id'] ?? '') }}" method="POST">
                        @csrf
                        @method('PATCH')

                        <div class="mb-4">
                            <label for="status" class="form-label small fw-semibold" style="color:var(--qomer-text-muted);">Actualizar Estado</label>
                            <select name="status" id="status" class="form-select qomer-input" required>
                                <option value="PENDING"  {{ ($redemption['status'] ?? '') == 'PENDING'  ? 'selected' : '' }}>PENDIENTE</option>
                                <option value="APPROVED" {{ ($redemption['status'] ?? '') == 'APPROVED' ? 'selected' : '' }}>APROBAR Y ENTREGAR</option>
                                <option value="REJECTED" {{ ($redemption['status'] ?? '') == 'REJECTED' ? 'selected' : '' }}>RECHAZAR</option>
                            </select>
                            <div class="form-text mt-2 small" style="color:var(--qomer-text-muted);">
                                Al rechazar un canje, se informará al usuario en la aplicación móvil de Q'omer Life.
                            </div>
                        </div>

                        <button type="submit" class="btn btn-qomer w-100 py-2">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-2"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/></svg>
                            Guardar Cambios
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

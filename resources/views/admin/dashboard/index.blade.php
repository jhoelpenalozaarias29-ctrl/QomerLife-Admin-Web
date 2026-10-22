@extends('layouts.admin')

@section('title', 'Dashboard Ejecutivo')

@section('content')
<div class="container-fluid px-0">

    {{-- Banner de Bienvenida con Elemento 3D Ligero (Three.js) --}}
    <div class="card border-0 shadow-sm mb-4 overflow-hidden position-relative" 
         style="background: linear-gradient(135deg, rgba(20, 26, 23, 0.95) 0%, rgba(27, 36, 31, 0.85) 100%); border: 1px solid var(--qomer-border) !important;">
        <div class="card-body p-4 position-relative z-1">
            <div class="row align-items-center">
                <div class="col-lg-8 col-md-7">
                    <div class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill mb-3" 
                         style="background: rgba(0, 199, 129, 0.12); border: 1px solid rgba(0, 199, 129, 0.3);">
                        <i class="bi bi-shield-check text-success" style="color: #00C781 !important;"></i>
                        <span class="small fw-semibold" style="font-size: 0.75rem; color: #00C781; letter-spacing: 0.05em;">
                            PLATAFORMA OFICIAL Q'OMER LIFE · FASE 0
                        </span>
                    </div>
                    <h2 class="h3 fw-bold mb-2 text-white font-display">
                        Panel de Control y Monitoreo Ambiental
                    </h2>
                    <p class="text-light text-opacity-75 mb-3 small" style="max-width: 620px;">
                        Supervisión integral de las máquinas recicladoras inteligentes, emisión de EcoPoints en tiempo real y canje de beneficios universitarios.
                    </p>
                    <div class="d-flex flex-wrap gap-2 text-muted small">
                        <span class="badge bg-dark bg-opacity-50 border border-secondary border-opacity-25 px-3 py-2 text-light">
                            <i class="bi bi-calendar3 me-1 text-success"></i> {{ date('d/m/Y') }}
                        </span>
                        <span class="badge bg-dark bg-opacity-50 border border-secondary border-opacity-25 px-3 py-2 text-light">
                            <i class="bi bi-clock me-1 text-info"></i> Actualización en <span id="refresh-countdown" class="fw-bold text-success">60</span>s
                        </span>
                    </div>
                </div>

                {{-- Canvas 3D Decorativo Ligero (< 30 KB) --}}
                <div class="col-lg-4 col-md-5 d-none d-md-flex justify-content-center align-items-center position-relative">
                    <div id="threeDContainer" style="width: 180px; height: 180px; position: relative;">
                        <canvas id="threeCanvas" style="width: 100%; height: 100%; outline: none;"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- Luz de fondo ambiental --}}
        <div class="position-absolute end-0 top-0 bottom-0 w-50 pointer-events-none" 
             style="background: radial-gradient(circle at 80% 50%, rgba(0, 199, 129, 0.15) 0%, transparent 65%); z-index: 0;"></div>
    </div>

    {{-- Las 4 Tarjetas de Estadísticas Principales --}}
    <div class="row g-4 mb-4" id="kpiStatsContainer">
        {{-- Usuarios Totales --}}
        <div class="col-sm-6 col-xl-3">
            <div class="card card-stat h-100 p-3 shadow-sm">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <span class="text-uppercase small fw-bold text-muted" style="font-size: 0.72rem; letter-spacing: 0.08em;">
                        Usuarios Totales
                    </span>
                    <div class="p-2 rounded-3" style="background: rgba(0, 199, 129, 0.12); color: #00C781;">
                        <i class="bi bi-people-fill fs-5"></i>
                    </div>
                </div>
                <div class="d-flex align-items-baseline gap-2 mt-auto">
                    <h3 class="display-6 fw-bold mb-0 font-display stat-number" style="color: var(--qomer-text-primary);" data-target="{{ $totalUsers ?? 0 }}">
                        0
                    </h3>
                    <span class="small text-success fw-semibold" style="font-size: 0.78rem;">
                        <i class="bi bi-arrow-up-short"></i>Registrados
                    </span>
                </div>
                <div class="small text-muted mt-2 pt-2 border-top" style="border-color: var(--qomer-border-subtle) !important;">
                    <a href="{{ route('admin.users.index') }}" class="text-decoration-none small text-muted d-flex justify-content-between align-items-center">
                        <span>Gestionar usuarios</span>
                        <i class="bi bi-chevron-right" style="font-size: 0.75rem;"></i>
                    </a>
                </div>
            </div>
        </div>

        {{-- Transacciones --}}
        <div class="col-sm-6 col-xl-3">
            <div class="card card-stat h-100 p-3 shadow-sm">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <span class="text-uppercase small fw-bold text-muted" style="font-size: 0.72rem; letter-spacing: 0.08em;">
                        Transacciones
                    </span>
                    <div class="p-2 rounded-3" style="background: rgba(6, 182, 212, 0.12); color: #06B6D4;">
                        <i class="bi bi-recycle fs-5"></i>
                    </div>
                </div>
                <div class="d-flex align-items-baseline gap-2 mt-auto">
                    <h3 class="display-6 fw-bold mb-0 font-display stat-number" style="color: var(--qomer-text-primary);" data-target="{{ $totalTransactions ?? 0 }}">
                        0
                    </h3>
                    <span class="small text-info fw-semibold" style="font-size: 0.78rem;">
                        <i class="bi bi-activity"></i>Depósitos
                    </span>
                </div>
                <div class="small text-muted mt-2 pt-2 border-top" style="border-color: var(--qomer-border-subtle) !important;">
                    <a href="{{ route('admin.transactions.index') }}" class="text-decoration-none small text-muted d-flex justify-content-between align-items-center">
                        <span>Ver historial completo</span>
                        <i class="bi bi-chevron-right" style="font-size: 0.75rem;"></i>
                    </a>
                </div>
            </div>
        </div>

        {{-- EcoPoints Entregados --}}
        <div class="col-sm-6 col-xl-3">
            <div class="card card-stat h-100 p-3 shadow-sm">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <span class="text-uppercase small fw-bold text-muted" style="font-size: 0.72rem; letter-spacing: 0.08em;">
                        EcoPoints Entregados
                    </span>
                    <div class="p-2 rounded-3" style="background: rgba(245, 158, 11, 0.12); color: #F59E0B;">
                        <i class="bi bi-stars fs-5"></i>
                    </div>
                </div>
                <div class="d-flex align-items-baseline gap-2 mt-auto">
                    <h3 class="display-6 fw-bold mb-0 font-display stat-number" style="color: var(--qomer-text-primary);" data-target="{{ $totalEcoPoints ?? 0 }}">
                        0
                    </h3>
                    <span class="small text-warning fw-semibold" style="font-size: 0.78rem;">
                        pts emitidos
                    </span>
                </div>
                <div class="small text-muted mt-2 pt-2 border-top" style="border-color: var(--qomer-border-subtle) !important;">
                    <a href="{{ route('admin.reports.index', ['type' => 'transactions']) }}" class="text-decoration-none small text-muted d-flex justify-content-between align-items-center">
                        <span>Balance y reportes</span>
                        <i class="bi bi-chevron-right" style="font-size: 0.75rem;"></i>
                    </a>
                </div>
            </div>
        </div>

        {{-- Canjes Realizados --}}
        <div class="col-sm-6 col-xl-3">
            <div class="card card-stat h-100 p-3 shadow-sm">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <span class="text-uppercase small fw-bold text-muted" style="font-size: 0.72rem; letter-spacing: 0.08em;">
                        Canjes Realizados
                    </span>
                    <div class="p-2 rounded-3" style="background: rgba(168, 85, 247, 0.12); color: #A855F7;">
                        <i class="bi bi-gift-fill fs-5"></i>
                    </div>
                </div>
                <div class="d-flex align-items-baseline gap-2 mt-auto">
                    <h3 class="display-6 fw-bold mb-0 font-display stat-number" style="color: var(--qomer-text-primary);" data-target="{{ $totalRedemptions ?? 0 }}">
                        0
                    </h3>
                    <span class="small fw-semibold" style="font-size: 0.78rem; color: #A855F7;">
                        Recompensas
                    </span>
                </div>
                <div class="small text-muted mt-2 pt-2 border-top" style="border-color: var(--qomer-border-subtle) !important;">
                    <a href="{{ route('admin.redemptions.index') }}" class="text-decoration-none small text-muted d-flex justify-content-between align-items-center">
                        <span>Validar entregas</span>
                        <i class="bi bi-chevron-right" style="font-size: 0.75rem;"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Tablas de Actividad Reciente --}}
    <div class="row g-4 mb-4">
        {{-- Transacciones Recientes --}}
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-transparent py-3 border-bottom d-flex justify-content-between align-items-center" 
                     style="border-color: var(--qomer-border-subtle) !important;">
                    <div class="d-flex align-items-center gap-2">
                        <div class="rounded p-1" style="background: rgba(0, 199, 129, 0.15); color: #00C781;">
                            <i class="bi bi-arrow-left-right"></i>
                        </div>
                        <h5 class="mb-0 fs-6 fw-bold text-white">Transacciones Recientes</h5>
                    </div>
                    <a href="{{ route('admin.transactions.index') }}" class="btn btn-sm btn-outline-secondary py-1 px-3 rounded-pill" style="font-size: 0.8rem;">
                        Ver todas <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead>
                                <tr>
                                    <th class="ps-3">Token</th>
                                    <th>Máquina</th>
                                    <th>Material</th>
                                    <th class="text-center">PET</th>
                                    <th class="text-center">Alum.</th>
                                    <th class="text-center">EcoPoints</th>
                                    <th class="pe-3">Fecha</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentTransactions ?? [] as $tx)
                                    <tr>
                                        <td class="ps-3">
                                            <div class="d-flex align-items-center gap-1">
                                                <span class="font-mono text-muted small" title="{{ $tx['token'] ?? $tx['id'] ?? '' }}">
                                                    {{ \Illuminate\Support\Str::limit($tx['token'] ?? $tx['id'] ?? '', 8) }}
                                                </span>
                                                <button class="btn btn-link text-secondary p-0 copy-btn" 
                                                        onclick="copyToClipboard('{{ $tx['token'] ?? $tx['id'] ?? '' }}', this)" 
                                                        title="Copiar token">
                                                    <i class="bi bi-copy" style="font-size: 0.75rem;"></i>
                                                </button>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-dark bg-opacity-50 text-light border border-secondary border-opacity-25 px-2 py-1" style="font-size: 0.72rem;">
                                                {{ $tx['machineId'] ?? 'Cajero-01' }}
                                            </span>
                                        </td>
                                        <td>
                                            @php $mat = strtoupper($tx['material'] ?? 'PET'); @endphp
                                            @if($mat === 'PET')
                                                <span class="badge badge-emerald px-2 py-1">PET</span>
                                            @elseif($mat === 'ALUMINIO' || $mat === 'ALU')
                                                <span class="badge badge-cyan px-2 py-1">Alum.</span>
                                            @else
                                                <span class="badge bg-secondary px-2 py-1">{{ $mat }}</span>
                                            @endif
                                        </td>
                                        <td class="text-center fw-semibold text-secondary">
                                            {{ $tx['plasticBottlesCount'] ?? ($tx['bottleCount'] ?? 0) }}
                                        </td>
                                        <td class="text-center fw-semibold text-secondary">
                                            {{ $tx['aluminumCansCount'] ?? ($tx['canCount'] ?? 0) }}
                                        </td>
                                        <td class="text-center">
                                            <span class="badge badge-amber px-2 py-1">
                                                +{{ number_format($tx['ecoPoints'] ?? 0) }}
                                            </span>
                                        </td>
                                        <td class="pe-3 small text-muted">
                                            @php
                                                $fecha = $tx['fechaCreacion'] ?? $tx['createdAt'] ?? null;
                                                if ($fecha && is_object($fecha) && method_exists($fecha, 'get')) {
                                                    echo $fecha->get()->format('d/m/Y H:i');
                                                } elseif ($fecha instanceof \DateTimeInterface) {
                                                    echo $fecha->format('d/m/Y H:i');
                                                } elseif (is_numeric($fecha)) {
                                                    echo date('d/m/Y H:i', (int)$fecha);
                                                } elseif ($fecha) {
                                                    echo date('d/m/Y H:i', strtotime((string)$fecha));
                                                } else {
                                                    echo 'N/A';
                                                }
                                            @endphp
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-5 text-muted">
                                            <i class="bi bi-inbox fs-2 d-block mb-2 opacity-50"></i>
                                            Sin transacciones registradas actualmente.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Canjes Recientes --}}
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-transparent py-3 border-bottom d-flex justify-content-between align-items-center" 
                     style="border-color: var(--qomer-border-subtle) !important;">
                    <div class="d-flex align-items-center gap-2">
                        <div class="rounded p-1" style="background: rgba(168, 85, 247, 0.15); color: #A855F7;">
                            <i class="bi bi-gift"></i>
                        </div>
                        <h5 class="mb-0 fs-6 fw-bold text-white">Canjes Recientes</h5>
                    </div>
                    <a href="{{ route('admin.redemptions.index') }}" class="btn btn-sm btn-outline-secondary py-1 px-3 rounded-pill" style="font-size: 0.8rem;">
                        Ver todos <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead>
                                <tr>
                                    <th class="ps-3">Incentivo</th>
                                    <th class="text-center">Puntos</th>
                                    <th>Código</th>
                                    <th class="pe-3">Fecha</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentRedemptions ?? [] as $redemption)
                                    <tr>
                                        <td class="ps-3">
                                            <span class="small fw-semibold text-truncate d-inline-block" style="max-width: 150px;" 
                                                  title="{{ $redemption['incentiveTitle'] ?? ($redemption['rewardName'] ?? 'Incentivo') }}">
                                                {{ $redemption['incentiveTitle'] ?? ($redemption['rewardName'] ?? 'Incentivo') }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-danger-subtle text-danger px-2 py-1 fw-bold">
                                                -{{ number_format($redemption['pointsSpent'] ?? ($redemption['ecoPoints'] ?? 0)) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="font-mono text-muted small bg-light px-2 py-1 rounded border">
                                                {{ $redemption['redemptionCode'] ?? ($redemption['id'] ?? '—') }}
                                            </span>
                                        </td>
                                        <td class="pe-3 small text-muted">
                                            @php
                                                $ts = $redemption['timestamp'] ?? $redemption['createdAt'] ?? null;
                                                if ($ts && is_object($ts) && method_exists($ts, 'get')) {
                                                    echo $ts->get()->format('d/m/Y H:i');
                                                } elseif ($ts instanceof \DateTimeInterface) {
                                                    echo $ts->format('d/m/Y H:i');
                                                } elseif (is_numeric($ts)) {
                                                    echo date('d/m/Y H:i', (int)($ts > 9999999999 ? $ts / 1000 : $ts));
                                                } elseif ($ts) {
                                                    echo date('d/m/Y H:i', strtotime((string)$ts));
                                                } else {
                                                    echo 'N/A';
                                                }
                                            @endphp
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-5 text-muted">
                                            <i class="bi bi-gift fs-2 d-block mb-2 opacity-50"></i>
                                            Sin canjes registrados aún.
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

    {{-- Estadísticas de Reciclaje y Distribución de Materiales --}}
    <div class="row g-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h5 class="fw-bold mb-1 font-display text-white">Impacto y Recolección de Materiales</h5>
                        <p class="text-muted small mb-0">Balance consolidado de reciclaje capturado por el hardware Q'omer Life.</p>
                    </div>
                    <span class="badge badge-emerald px-3 py-2 rounded-pill">
                        <i class="bi bi-shield-check me-1"></i> Auditoría en Tiempo Real
                    </span>
                </div>

                {{-- 3 Tarjetas de Materiales --}}
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <div class="p-3 rounded-3 h-100" style="background: rgba(0, 199, 129, 0.08); border: 1px solid rgba(0, 199, 129, 0.2);">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <i class="bi bi-droplet-fill text-success fs-4" style="color: #00C781 !important;"></i>
                                <span class="small fw-bold text-uppercase" style="color: #00C781; letter-spacing: 0.05em;">Botellas PET</span>
                            </div>
                            <div class="fs-2 fw-bold text-white font-display mb-1">
                                {{ number_format($totalBottles ?? 0) }} <span class="fs-6 text-muted fw-normal">unidades</span>
                            </div>
                            <div class="small text-muted">Envases plásticos procesados</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-3 rounded-3 h-100" style="background: rgba(6, 182, 212, 0.08); border: 1px solid rgba(6, 182, 212, 0.2);">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <i class="bi bi-circle-fill text-info fs-4" style="color: #06B6D4 !important;"></i>
                                <span class="small fw-bold text-uppercase" style="color: #06B6D4; letter-spacing: 0.05em;">Latas de Aluminio</span>
                            </div>
                            <div class="fs-2 fw-bold text-white font-display mb-1">
                                {{ number_format($totalCans ?? 0) }} <span class="fs-6 text-muted fw-normal">unidades</span>
                            </div>
                            <div class="small text-muted">Contenedores metálicos reciclados</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-3 rounded-3 h-100" style="background: rgba(245, 158, 11, 0.08); border: 1px solid rgba(245, 158, 11, 0.2);">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <i class="bi bi-lightning-charge-fill text-warning fs-4" style="color: #F59E0B !important;"></i>
                                <span class="small fw-bold text-uppercase" style="color: #F59E0B; letter-spacing: 0.05em;">EcoPoints Emitidos</span>
                            </div>
                            <div class="fs-2 fw-bold text-white font-display mb-1">
                                {{ number_format($totalEcoPoints ?? 0) }} <span class="fs-6 text-muted fw-normal">pts</span>
                            </div>
                            <div class="small text-muted">Créditos ecológicos en circulación</div>
                        </div>
                    </div>
                </div>

                {{-- Barra de Ratio Visual de Materiales --}}
                @php
                    $totalItems = ($totalBottles ?? 0) + ($totalCans ?? 0);
                    $petPercent = $totalItems > 0 ? round((($totalBottles ?? 0) / $totalItems) * 100) : 50;
                    $canPercent = $totalItems > 0 ? (100 - $petPercent) : 50;
                @endphp
                <div class="pt-3 border-top" style="border-color: var(--qomer-border-subtle) !important;">
                    <div class="d-flex justify-content-between align-items-center small mb-2">
                        <span class="fw-semibold text-secondary">Distribución de Material Recolectado</span>
                        <span class="text-muted">PET: <strong class="text-success">{{ $petPercent }}%</strong> | Aluminio: <strong class="text-info">{{ $canPercent }}%</strong></span>
                    </div>
                    <div class="progress" style="height: 10px; background-color: var(--qomer-surface-elevated); border-radius: 6px; overflow: hidden;">
                        <div class="progress-bar" role="progressbar" style="width: {{ $petPercent }}%; background: linear-gradient(90deg, #00C781, #10B981);" aria-valuenow="{{ $petPercent }}" aria-valuemin="0" aria-valuemax="100"></div>
                        <div class="progress-bar" role="progressbar" style="width: {{ $canPercent }}%; background: linear-gradient(90deg, #06B6D4, #3B82F6);" aria-valuenow="{{ $canPercent }}" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Scripts de Animación GSAP y Elemento 3D Three.js --}}
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // 1. Contador automático de actualización (60s)
    let seconds = 60;
    const el = document.getElementById('refresh-countdown');
    setInterval(() => {
        seconds--;
        if (el) el.textContent = seconds;
        if (seconds <= 0) window.location.reload();
    }, 1000);

    // 2. Animación suave de números con GSAP
    if (typeof gsap !== 'undefined') {
        gsap.from('#kpiStatsContainer .card', {
            duration: 0.8,
            y: 20,
            opacity: 0,
            stagger: 0.12,
            ease: 'power3.out'
        });

        document.querySelectorAll('.stat-number').forEach(stat => {
            const targetVal = parseFloat(stat.getAttribute('data-target')) || 0;
            const obj = { val: 0 };
            gsap.to(obj, {
                val: targetVal,
                duration: 1.6,
                ease: 'power2.out',
                onUpdate: () => {
                    stat.textContent = Math.round(obj.val).toLocaleString('es-ES');
                }
            });
        });
    }

    // 3. Elemento 3D Ligero con Three.js (< 30 KB en memoria)
    initThreeDPrism();
});

// Función de copia al portapapeles con feedback visual
function copyToClipboard(text, btn) {
    if (!text) return;
    navigator.clipboard.writeText(text).then(() => {
        const icon = btn.querySelector('i');
        if (icon) {
            icon.className = 'bi bi-check2 text-success';
            setTimeout(() => {
                icon.className = 'bi bi-copy';
            }, 1800);
        }
    });
}

// Inicialización del Prisma 3D de Reciclaje
function initThreeDPrism() {
    const canvas = document.getElementById('threeCanvas');
    const container = document.getElementById('threeDContainer');
    if (!canvas || !container || typeof THREE === 'undefined') return;

    // Respetar preferencia de usuario de movimiento reducido
    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    if (prefersReducedMotion) return;

    try {
        const scene = new THREE.Scene();
        const width = container.clientWidth || 180;
        const height = container.clientHeight || 180;

        const camera = new THREE.PerspectiveCamera(45, width / height, 0.1, 100);
        camera.position.z = 3.6;

        const renderer = new THREE.WebGLRenderer({ canvas: canvas, alpha: true, antialias: true });
        renderer.setSize(width, height);
        renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));

        // Geometría: Icosaedro holográfico elegante
        const geometry = new THREE.IcosahedronGeometry(1.2, 0);

        // Material con alambre esmeralda resplandeciente
        const wireframeMaterial = new THREE.MeshBasicMaterial({
            color: 0x00C781,
            wireframe: true,
            transparent: true,
            opacity: 0.75
        });

        // Núcleo translúcido
        const coreMaterial = new THREE.MeshBasicMaterial({
            color: 0x06B6D4,
            transparent: true,
            opacity: 0.15
        });

        const meshWire = new THREE.Mesh(geometry, wireframeMaterial);
        const meshCore = new THREE.Mesh(geometry, coreMaterial);
        meshCore.scale.set(0.9, 0.9, 0.9);

        const group = new THREE.Group();
        group.add(meshWire);
        group.add(meshCore);
        scene.add(group);

        let animationFrameId;
        function animate() {
            animationFrameId = requestAnimationFrame(animate);
            group.rotation.x += 0.006;
            group.rotation.y += 0.009;
            renderer.render(scene, camera);
        }
        animate();

        // Parar renderizado si el contenedor no está visible para ahorrar 100% de CPU
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    if (!animationFrameId) animate();
                } else {
                    cancelAnimationFrame(animationFrameId);
                    animationFrameId = null;
                }
            });
        });
        observer.observe(container);

        // Interactividad suave con el ratón
        container.addEventListener('mousemove', (e) => {
            const rect = container.getBoundingClientRect();
            const x = ((e.clientX - rect.left) / rect.width) * 2 - 1;
            const y = -((e.clientY - rect.top) / rect.height) * 2 + 1;
            gsap.to(group.rotation, { x: y * 0.4, y: x * 0.4, duration: 0.5, ease: 'power1.out' });
        });

    } catch (e) {
        console.warn('Three.js no soportado o deshabilitado en este entorno:', e);
    }
}
</script>
@endpush
@endsection

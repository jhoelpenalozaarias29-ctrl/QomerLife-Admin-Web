<nav class="navbar qomer-navbar px-3 px-lg-4 py-2">
    <div class="d-flex align-items-center gap-3">
        {{-- Botón menú móvil --}}
        <button id="openSidebarBtn" class="btn btn-outline-secondary d-lg-none p-1 px-2" type="button" aria-label="Abrir menú">
            <i class="bi bi-list fs-4"></i>
        </button>

        {{-- Estado en Vivo del Sistema --}}
        <div class="d-none d-md-flex align-items-center gap-2 px-3 py-1 rounded-pill" 
             style="background: rgba(0, 199, 129, 0.08); border: 1px solid rgba(0, 199, 129, 0.2);">
            <span class="status-pulse"></span>
            <span class="small fw-semibold" style="font-size: 0.75rem; color: var(--qomer-emerald); letter-spacing: 0.04em;">
                SISTEMA OPERATIVO · FIRESTORE CONECTADO
            </span>
        </div>
    </div>

    <div class="d-flex align-items-center gap-3 ms-auto">
        {{-- Selector de Modo Oscuro / Claro --}}
        <button id="theme-toggle" class="btn btn-sm d-flex align-items-center justify-content-center p-2 rounded-circle" 
                style="width: 38px; height: 38px; background: var(--qomer-surface-elevated); border: 1px solid var(--qomer-border-subtle);"
                title="Cambiar entre modo oscuro y claro" aria-label="Cambiar tema">
            <i id="theme-icon" class="bi bi-moon-stars-fill text-warning fs-6"></i>
        </button>

        {{-- Menú de Usuario --}}
        <div class="dropdown">
            <button class="btn btn-sm d-flex align-items-center gap-2 py-1 px-2 rounded-pill" 
                    style="background: var(--qomer-surface-elevated); border: 1px solid var(--qomer-border-subtle); color: var(--qomer-text-primary);"
                    data-bs-toggle="dropdown" aria-expanded="false">
                <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold text-dark" 
                     style="width: 28px; height: 28px; background: #00C781; font-size: 0.75rem;">
                    {{ strtoupper(substr(auth()->user()->name ?? 'AD', 0, 2)) }}
                </div>
                <span class="small fw-semibold d-none d-sm-inline">{{ auth()->user()->name ?? 'Administrador' }}</span>
                <i class="bi bi-chevron-down text-muted small"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2 p-2" 
                style="background: var(--qomer-surface); border: 1px solid var(--qomer-border) !important; border-radius: 0.75rem;">
                <li class="px-2 py-1 mb-1 border-bottom" style="border-color: var(--qomer-border-subtle) !important;">
                    <div class="fw-bold small" style="color: var(--qomer-text-primary);">{{ auth()->user()->name ?? 'Administrador' }}</div>
                    <div class="text-muted" style="font-size: 0.72rem;">{{ auth()->user()->email ?? 'admin@qomer.com' }}</div>
                </li>
                <li>
                    <a class="dropdown-item rounded-2 py-2 small" href="{{ route('admin.settings.index') }}" style="color: var(--qomer-text-primary);">
                        <i class="bi bi-sliders me-2 text-primary"></i>Configuración del Sistema
                    </a>
                </li>
                <li>
                    <a class="dropdown-item rounded-2 py-2 small" href="{{ route('admin.reports.index') }}" style="color: var(--qomer-text-primary);">
                        <i class="bi bi-file-earmark-bar-graph me-2 text-info"></i>Centro de Reportes
                    </a>
                </li>
                <li><hr class="dropdown-divider" style="border-color: var(--qomer-border-subtle);"></li>
                <li>
                    <form method="POST" action="{{ route('admin.logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item rounded-2 py-2 small text-danger">
                            <i class="bi bi-box-arrow-right me-2"></i>Cerrar Sesión Segura
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</nav>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('qomerSidebar');
    const openBtn = document.getElementById('openSidebarBtn');
    const closeBtn = document.getElementById('closeSidebarBtn');

    openBtn?.addEventListener('click', () => {
        sidebar?.classList.add('show');
    });

    closeBtn?.addEventListener('click', () => {
        sidebar?.classList.remove('show');
    });

    // Cerrar sidebar al hacer clic fuera en pantallas móviles
    document.addEventListener('click', (e) => {
        if (window.innerWidth < 992 && sidebar && sidebar.classList.contains('show')) {
            if (!sidebar.contains(e.target) && !openBtn?.contains(e.target)) {
                sidebar.classList.remove('show');
            }
        }
    });
});
</script>

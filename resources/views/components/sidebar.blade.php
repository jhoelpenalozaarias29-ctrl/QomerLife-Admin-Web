<aside id="qomerSidebar" class="qomer-sidebar d-flex flex-column p-3 position-fixed">
    {{-- Logotipo y Marca --}}
    <div class="d-flex align-items-center justify-content-between mb-4 px-2 pt-1">
        <a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center gap-2 text-decoration-none">
            <div class="rounded-3 d-flex align-items-center justify-content-center p-2" 
                 style="background: linear-gradient(135deg, rgba(0, 199, 129, 0.25) 0%, rgba(6, 182, 212, 0.2) 100%); border: 1px solid rgba(0, 199, 129, 0.4); box-shadow: 0 0 15px rgba(0, 199, 129, 0.2);">
                <i class="bi bi-recycle fs-4 text-success" style="color: #00C781 !important;"></i>
            </div>
            <div>
                <span class="fs-5 fw-bold brand-title d-block lh-1">Q'omer Life</span>
                <span class="text-muted fw-semibold" style="font-size: 0.68rem; letter-spacing: 0.08em; text-transform: uppercase;">Admin Console</span>
            </div>
        </a>
        <button id="closeSidebarBtn" class="btn btn-sm btn-link text-secondary d-lg-none p-0 text-decoration-none" type="button">
            <i class="bi bi-x-lg fs-5"></i>
        </button>
    </div>

    {{-- Navegación Principal --}}
    <div class="px-2 mb-2 text-uppercase text-muted fw-bold" style="font-size: 0.68rem; letter-spacing: 0.1em;">
        Menú de Control
    </div>
    <nav class="nav flex-column gap-1 flex-grow-1">
        <a href="{{ route('admin.dashboard') }}"
           class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="bi bi-grid-1x2 me-2"></i> Dashboard
        </a>
        <a href="{{ route('admin.users.index') }}" 
           class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
            <i class="bi bi-people me-2"></i> Usuarios
        </a>
        <a href="{{ route('admin.transactions.index') }}" 
           class="nav-link {{ request()->routeIs('admin.transactions.*') ? 'active' : '' }}">
            <i class="bi bi-arrow-left-right me-2"></i> Transacciones
        </a>
        <a href="{{ route('admin.redemptions.index') }}" 
           class="nav-link {{ request()->routeIs('admin.redemptions.*') ? 'active' : '' }}">
            <i class="bi bi-gift me-2"></i> Canjes
        </a>
        <a href="{{ route('admin.incentives.index') }}" 
           class="nav-link {{ request()->routeIs('admin.incentives.*') ? 'active' : '' }}">
            <i class="bi bi-award me-2"></i> Incentivos
        </a>
        <a href="{{ route('admin.reports.index') }}" 
           class="nav-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
            <i class="bi bi-file-earmark-bar-graph me-2"></i> Reportes
        </a>
        <a href="{{ route('admin.audit.index') }}" 
           class="nav-link {{ request()->routeIs('admin.audit.*') ? 'active' : '' }}">
            <i class="bi bi-shield-check me-2"></i> Auditoría
        </a>
        <a href="{{ route('admin.settings.index') }}" 
           class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
            <i class="bi bi-sliders me-2"></i> Configuración
        </a>
    </nav>

    {{-- Perfil del Administrador en la parte inferior --}}
    <div class="pt-3 mt-auto border-top" style="border-color: var(--qomer-border-subtle) !important;">
        <div class="d-flex align-items-center justify-content-between p-2 rounded-3" 
             style="background: var(--qomer-surface-elevated); border: 1px solid var(--qomer-border-subtle);">
            <div class="d-flex align-items-center gap-2 overflow-hidden">
                <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold text-dark flex-shrink-0" 
                     style="width: 34px; height: 34px; background: #00C781; font-size: 0.8rem; box-shadow: 0 0 10px rgba(0, 199, 129, 0.4);">
                    {{ strtoupper(substr(auth()->user()->name ?? 'AD', 0, 2)) }}
                </div>
                <div class="text-truncate">
                    <span class="d-block fw-semibold text-truncate small" style="color: var(--qomer-text-primary);" title="{{ auth()->user()->name ?? 'Administrador' }}">
                        {{ auth()->user()->name ?? 'Administrador' }}
                    </span>
                    <span class="badge badge-emerald py-0 px-1" style="font-size: 0.65rem;">
                        {{ auth()->user()->role ?? 'ADMIN' }}
                    </span>
                </div>
            </div>
            <form method="POST" action="{{ route('admin.logout') }}" class="m-0">
                @csrf
                <button type="submit" class="btn btn-sm btn-link text-danger p-1" title="Cerrar sesión">
                    <i class="bi bi-box-arrow-right fs-6"></i>
                </button>
            </form>
        </div>
    </div>
</aside>

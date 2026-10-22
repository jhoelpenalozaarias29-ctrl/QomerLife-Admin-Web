<aside class="qomer-sidebar bg-body border-end position-fixed d-none d-lg-flex flex-column p-3">
    <a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center mb-4 text-decoration-none">
        <i class="bi bi-recycle fs-3 text-success me-2"></i>
        <span class="fs-5 fw-semibold">Q'omer Life</span>
    </a>

    <nav class="nav flex-column gap-1">
        <a href="{{ route('admin.dashboard') }}"
           class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2 me-2"></i> Dashboard
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
            <i class="bi bi-file-earmark-text me-2"></i> Reportes
        </a>
        <a href="{{ route('admin.audit.index') }}" 
           class="nav-link {{ request()->routeIs('admin.audit.*') ? 'active' : '' }}">
            <i class="bi bi-shield-check me-2"></i> Auditoría
        </a>
        <a href="{{ route('admin.settings.index') }}" 
           class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
            <i class="bi bi-gear me-2"></i> Configuración
        </a>
    </nav>

    <div class="mt-auto pt-3 border-top small text-secondary">
        v1.0 · Fase 0
    </div>
</aside>

<nav class="navbar bg-body border-bottom px-4 py-2">
    <div class="d-flex align-items-center gap-2">
        <button class="btn btn-outline-secondary d-lg-none" type="button">
            <i class="bi bi-list"></i>
        </button>
    </div>

    <div class="d-flex align-items-center gap-3 ms-auto">
        <button id="theme-toggle" class="btn btn-outline-secondary btn-sm" title="Cambiar tema">
            <i class="bi bi-moon-stars"></i>
        </button>

        <div class="dropdown">
            <button class="btn btn-light border d-flex align-items-center gap-2" data-bs-toggle="dropdown">
                <i class="bi bi-person-circle fs-5"></i>
                <span class="small">{{ auth()->user()->name ?? 'Administrador' }}</span>
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="#"><i class="bi bi-person me-2"></i>Mi perfil</a></li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <form method="POST" action="{{ route('admin.logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item text-danger">
                            <i class="bi bi-box-arrow-right me-2"></i>Cerrar sesión
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</nav>

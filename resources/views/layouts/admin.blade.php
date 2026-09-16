<!DOCTYPE html>
<html lang="es" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Panel') · Q'omer Life Admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    @stack('styles')
    <style>
        body { background-color: var(--bs-tertiary-bg); }
        .qomer-sidebar { width: 260px; min-height: 100vh; }
        .qomer-sidebar .nav-link { color: var(--bs-secondary-color); border-radius: .5rem; }
        .qomer-sidebar .nav-link.active,
        .qomer-sidebar .nav-link:hover { background-color: var(--bs-primary-bg-subtle); color: var(--bs-primary); }
        .qomer-main { margin-left: 260px; }
        @media (max-width: 991.98px) { .qomer-main { margin-left: 0; } }
    </style>
</head>
<body>
    <div class="d-flex">
        <x-sidebar />

        <div class="qomer-main flex-grow-1">
            <x-navbar />

            <main class="p-4">
                <x-breadcrumbs :items="$breadcrumbs ?? []" />

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <x-toast />

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
    <script>
        // Dark mode persistente vía cookie (sin localStorage por consistencia
        // con SSR; se puede leer también en el backend si se necesita).
        const themeToggle = document.getElementById('theme-toggle');
        const root = document.documentElement;
        const saved = document.cookie.split('; ').find(r => r.startsWith('qomer_theme='));
        if (saved) root.setAttribute('data-bs-theme', saved.split('=')[1]);

        themeToggle?.addEventListener('click', () => {
            const next = root.getAttribute('data-bs-theme') === 'dark' ? 'light' : 'dark';
            root.setAttribute('data-bs-theme', next);
            document.cookie = `qomer_theme=${next}; path=/; max-age=31536000`;
        });
    </script>
    @stack('scripts')
</body>
</html>

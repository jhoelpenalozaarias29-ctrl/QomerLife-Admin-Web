<!DOCTYPE html>
<html lang="es" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Panel') · Q'omer Life Admin</title>

    {{-- Tipografías Modernas --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Outfit:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">

    {{-- Bootstrap 5.3 & Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    {{-- GSAP & Three.js CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/gsap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/three@0.160.0/build/three.min.js"></script>

    @stack('styles')

    {{-- Sistema de Diseño Cinematográfico Q'omer Life --}}
    <style>
        :root {
            --qomer-emerald: #00C781;
            --qomer-emerald-rgb: 0, 199, 129;
            --qomer-emerald-dark: #15966A;
            --qomer-amber: #F59E0B;
            --qomer-amber-rgb: 245, 158, 11;
            --qomer-cyan: #06B6D4;
            --qomer-cyan-rgb: 6, 182, 212;
            --font-main: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            --font-display: 'Outfit', sans-serif;
            --font-mono: 'JetBrains Mono', monospace;
        }

        /* --- MODO OSCURO (PREDETERMINADO CINEMATOGRÁFICO) --- */
        [data-bs-theme="dark"] {
            --qomer-bg: #0B0F0D;
            --qomer-surface: #141A17;
            --qomer-surface-elevated: #1B241F;
            --qomer-surface-hover: #222E27;
            --qomer-border: rgba(0, 199, 129, 0.14);
            --qomer-border-subtle: rgba(255, 255, 255, 0.06);
            --qomer-text-primary: #F4F7F5;
            --qomer-text-secondary: #94A3B8;
            --qomer-text-muted: #64748B;
            --qomer-glow: 0 0 24px rgba(0, 199, 129, 0.15);
            --bs-body-bg: var(--qomer-bg);
            --bs-body-color: var(--qomer-text-primary);
            --bs-tertiary-bg: var(--qomer-bg);
            --bs-secondary-bg: var(--qomer-surface);
            --bs-border-color: var(--qomer-border-subtle);
        }

        /* --- MODO CLARO (NÍTIDO Y ACCESIBLE) --- */
        [data-bs-theme="light"] {
            --qomer-bg: #F4F7F5;
            --qomer-surface: #FFFFFF;
            --qomer-surface-elevated: #F8FAF9;
            --qomer-surface-hover: #EDF3F0;
            --qomer-border: rgba(0, 199, 129, 0.22);
            --qomer-border-subtle: rgba(0, 0, 0, 0.08);
            --qomer-text-primary: #0F172A;
            --qomer-text-secondary: #475569;
            --qomer-text-muted: #64748B;
            --qomer-glow: 0 4px 20px rgba(0, 199, 129, 0.12);
            --bs-body-bg: var(--qomer-bg);
            --bs-body-color: var(--qomer-text-primary);
            --bs-tertiary-bg: var(--qomer-bg);
            --bs-secondary-bg: var(--qomer-surface);
            --bs-border-color: var(--qomer-border-subtle);
        }

        body {
            font-family: var(--font-main);
            background-color: var(--qomer-bg);
            color: var(--qomer-text-primary);
            min-height: 100vh;
            overflow-x: hidden;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        h1, h2, h3, h4, h5, h6, .font-display {
            font-family: var(--font-display);
            letter-spacing: -0.02em;
        }

        .font-mono {
            font-family: var(--font-mono);
        }

        /* --- SIDEBAR REDISEÑADO --- */
        .qomer-sidebar {
            width: 270px;
            height: 100vh;
            background: var(--qomer-surface);
            border-right: 1px solid var(--qomer-border-subtle) !important;
            z-index: 1030;
            transition: all 0.3s ease;
            position: fixed;
            top: 0;
            left: 0;
            overflow-y: auto;
        }

        .qomer-sidebar .brand-title {
            font-family: var(--font-display);
            font-weight: 800;
            letter-spacing: -0.03em;
            background: linear-gradient(135deg, #00C781 0%, #06B6D4 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .qomer-sidebar .nav-link {
            color: var(--qomer-text-secondary);
            font-weight: 500;
            font-size: 0.92rem;
            padding: 0.7rem 1rem;
            border-radius: 0.65rem;
            position: relative;
            display: flex;
            align-items: center;
            transition: all 0.22s ease;
        }

        .qomer-sidebar .nav-link i {
            font-size: 1.15rem;
            transition: transform 0.22s ease, color 0.22s ease;
        }

        .qomer-sidebar .nav-link:hover {
            color: var(--qomer-emerald);
            background-color: var(--qomer-surface-hover);
            transform: translateX(3px);
        }

        .qomer-sidebar .nav-link:hover i {
            transform: scale(1.1);
            color: var(--qomer-emerald);
        }

        .qomer-sidebar .nav-link.active {
            color: var(--qomer-emerald);
            background: rgba(0, 199, 129, 0.12);
            font-weight: 600;
            border: 1px solid rgba(0, 199, 129, 0.25);
            box-shadow: 0 0 15px rgba(0, 199, 129, 0.08);
        }

        .qomer-sidebar .nav-link.active::before {
            content: '';
            position: absolute;
            left: -12px;
            top: 25%;
            bottom: 25%;
            width: 4px;
            border-radius: 4px;
            background: var(--qomer-emerald);
            box-shadow: 0 0 8px var(--qomer-emerald);
        }

        .qomer-main {
            margin-left: 270px;
            width: calc(100% - 270px);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            transition: margin-left 0.3s ease, width 0.3s ease;
        }

        @media (max-width: 991.98px) {
            .qomer-sidebar {
                transform: translateX(-100%);
            }
            .qomer-sidebar.show {
                transform: translateX(0);
            }
            .qomer-main {
                margin-left: 0 !important;
                width: 100% !important;
            }
        }

        /* --- NAVBAR GLASSMORPHIC --- */
        .qomer-navbar {
            background: rgba(20, 26, 23, 0.82) !important;
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            border-bottom: 1px solid var(--qomer-border-subtle);
            position: sticky;
            top: 0;
            z-index: 1020;
        }

        [data-bs-theme="light"] .qomer-navbar {
            background: rgba(255, 255, 255, 0.88) !important;
        }

        /* --- TARJETAS & SUPERFICIES ELEVADAS --- */
        .card {
            background-color: var(--qomer-surface);
            border: 1px solid var(--qomer-border-subtle);
            border-radius: 1rem;
            color: var(--qomer-text-primary);
            transition: transform 0.25s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.25s ease, border-color 0.25s ease;
        }

        .card:hover {
            border-color: var(--qomer-border);
        }

        .card-stat {
            position: relative;
            overflow: hidden;
            border: 1px solid var(--qomer-border-subtle);
        }

        .card-stat::after {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 120px;
            height: 120px;
            background: radial-gradient(circle, rgba(0, 199, 129, 0.08) 0%, transparent 70%);
            border-radius: 50%;
            pointer-events: none;
        }

        .card-stat:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 28px rgba(0, 0, 0, 0.25);
            border-color: rgba(0, 199, 129, 0.35);
        }

        /* --- TABLAS MODERNAS --- */
        .table {
            --bs-table-bg: transparent;
            --bs-table-color: var(--qomer-text-primary);
            --bs-table-hover-bg: var(--qomer-surface-hover);
            border-color: var(--qomer-border-subtle);
        }

        .table thead th {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            font-weight: 700;
            color: var(--qomer-text-secondary);
            background-color: var(--qomer-surface-elevated) !important;
            border-bottom: 1px solid var(--qomer-border-subtle);
            padding: 0.85rem 1rem;
        }

        .table tbody td {
            padding: 0.85rem 1rem;
            border-bottom: 1px solid var(--qomer-border-subtle);
            vertical-align: middle;
        }

        /* --- BADGES ELEGANTE --- */
        .badge-emerald {
            background-color: rgba(0, 199, 129, 0.15);
            color: #00C781;
            border: 1px solid rgba(0, 199, 129, 0.3);
            font-weight: 600;
        }

        .badge-cyan {
            background-color: rgba(6, 182, 212, 0.15);
            color: #06B6D4;
            border: 1px solid rgba(6, 182, 212, 0.3);
            font-weight: 600;
        }

        .badge-amber {
            background-color: rgba(245, 158, 11, 0.15);
            color: #F59E0B;
            border: 1px solid rgba(245, 158, 11, 0.3);
            font-weight: 600;
        }

        /* --- BOTONES CON MICRO-INTERACCIÓN --- */
        .btn-primary {
            background: linear-gradient(135deg, #00C781 0%, #15966A 100%);
            border: none;
            color: #0B0F0D;
            font-weight: 600;
            box-shadow: 0 4px 14px rgba(0, 199, 129, 0.3);
            transition: all 0.22s ease;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #05e294 0%, #19aa78 100%);
            color: #0B0F0D;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 199, 129, 0.45);
        }

        .btn-outline-primary {
            color: var(--qomer-emerald);
            border-color: rgba(0, 199, 129, 0.4);
        }

        .btn-outline-primary:hover {
            background-color: var(--qomer-emerald);
            border-color: var(--qomer-emerald);
            color: #0B0F0D;
        }

        /* --- CONTROLES DE FORMULARIO --- */
        .form-control, .form-select, .qomer-input {
            background-color: var(--qomer-surface-elevated);
            border-color: var(--qomer-border-subtle);
            color: var(--qomer-text-primary);
            border-radius: 0.65rem;
        }

        .form-control:focus, .form-select:focus, .qomer-input:focus {
            background-color: var(--qomer-surface-elevated);
            border-color: var(--qomer-emerald);
            color: var(--qomer-text-primary);
            box-shadow: 0 0 0 0.25rem rgba(0, 199, 129, 0.2);
        }

        .btn-qomer {
            background: linear-gradient(135deg, #00C781 0%, #15966A 100%);
            border: none;
            color: #0B0F0D;
            font-weight: 600;
            border-radius: 10px;
            box-shadow: 0 4px 14px rgba(0, 199, 129, 0.3);
            transition: all 0.22s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .btn-qomer:hover {
            background: linear-gradient(135deg, #05e294 0%, #19aa78 100%);
            color: #0B0F0D;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 199, 129, 0.45);
        }

        [data-bs-theme="dark"] {
            --icon-filter: invert(1);
        }

        [data-bs-theme="light"] {
            --icon-filter: invert(0);
        }

        /* --- PULSE EN LÍNEA --- */
        .status-pulse {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background-color: var(--qomer-emerald);
            box-shadow: 0 0 0 0 rgba(0, 199, 129, 0.7);
            animation: pulse-green 2s infinite;
        }

        @keyframes pulse-green {
            0% {
                transform: scale(0.95);
                box-shadow: 0 0 0 0 rgba(0, 199, 129, 0.7);
            }
            70% {
                transform: scale(1);
                box-shadow: 0 0 0 8px rgba(0, 199, 129, 0);
            }
            100% {
                transform: scale(0.95);
                box-shadow: 0 0 0 0 rgba(0, 199, 129, 0);
            }
        }

        /* --- REDUCED MOTION --- */
        @media (prefers-reduced-motion: reduce) {
            * {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
            }
        }
    </style>
</head>
<body>
    <div class="d-flex">
        {{-- Menú Lateral --}}
        <x-sidebar />

        {{-- Contenedor Principal --}}
        <div class="qomer-main flex-grow-1">
            {{-- Barra Superior --}}
            <x-navbar />

            {{-- Contenido de Página --}}
            <main class="p-4 flex-grow-1">
                <x-breadcrumbs :items="$breadcrumbs ?? []" />

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" 
                         style="background: rgba(0, 199, 129, 0.15); border-left: 4px solid #00C781 !important; color: var(--qomer-text-primary);">
                        <i class="bi bi-check-circle-fill me-2 text-success"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert"
                         style="background: rgba(239, 68, 68, 0.15); border-left: 4px solid #EF4444 !important; color: var(--qomer-text-primary);">
                        <i class="bi bi-exclamation-triangle-fill me-2 text-danger"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @yield('content')
            </main>

            {{-- Footer Elegante --}}
            <footer class="px-4 py-3 border-top small text-muted d-flex justify-content-between align-items-center" 
                    style="border-color: var(--qomer-border-subtle) !important; background: var(--qomer-surface);">
                <div>
                    <span class="fw-semibold brand-title">Q'omer Life</span> · Sistema Inteligente de Reciclaje
                </div>
                <div>
                    Panel Administrativo v1.0 · Fase 0
                </div>
            </footer>
        </div>
    </div>

    <x-toast />

    {{-- Bootstrap JS & Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>

    {{-- Script de Tema Dinámico sin Recargar Pantalla --}}
    <script>
        (function() {
            const root = document.documentElement;
            const themeBtn = document.getElementById('theme-toggle');
            const themeIcon = document.getElementById('theme-icon');

            // Leer tema guardado (default dark)
            function getPreferredTheme() {
                const cookieVal = document.cookie.split('; ').find(r => r.startsWith('qomer_theme='));
                if (cookieVal) return cookieVal.split('=')[1];
                const localVal = localStorage.getItem('qomer_theme');
                if (localVal) return localVal;
                return 'dark'; // Predeterminado cinematográfico
            }

            function applyTheme(theme) {
                root.setAttribute('data-bs-theme', theme);
                document.cookie = `qomer_theme=${theme}; path=/; max-age=31536000; SameSite=Lax`;
                localStorage.setItem('qomer_theme', theme);

                if (themeIcon) {
                    if (theme === 'dark') {
                        themeIcon.className = 'bi bi-moon-stars-fill text-warning';
                    } else {
                        themeIcon.className = 'bi bi-sun-fill text-warning';
                    }
                }

                // Despachar evento para componentes como Three.js o Chart.js
                window.dispatchEvent(new CustomEvent('qomer-theme-changed', { detail: { theme } }));
            }

            // Aplicar inmediatamente al cargar
            const initialTheme = getPreferredTheme();
            applyTheme(initialTheme);

            // Toggle interactivo sin recargar
            themeBtn?.addEventListener('click', (e) => {
                e.preventDefault();
                const current = root.getAttribute('data-bs-theme');
                const next = current === 'dark' ? 'light' : 'dark';

                // Animación de rotación en el icono
                if (typeof gsap !== 'undefined' && themeIcon) {
                    gsap.fromTo(themeIcon, { rotate: 0, scale: 0.6 }, { rotate: 360, scale: 1, duration: 0.45, ease: 'back.out(1.7)' });
                }

                applyTheme(next);
            });
        })();
    </script>

    @stack('scripts')
</body>
</html>

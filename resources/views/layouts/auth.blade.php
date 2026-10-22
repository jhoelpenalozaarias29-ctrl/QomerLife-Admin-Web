<!DOCTYPE html>
<html lang="es" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Acceso') · Q'omer Life Admin</title>

    {{-- Tipografía --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Outfit:wght@600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    
    <style>
        :root {
            --qomer-bg: #0B0F0D;
            --qomer-surface: #141A17;
            --qomer-border: rgba(0, 199, 129, 0.2);
            --qomer-emerald: #00C781;
        }

        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: var(--qomer-bg);
            background-image: 
                radial-gradient(at 0% 0%, rgba(0, 199, 129, 0.12) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(6, 182, 212, 0.1) 0px, transparent 50%);
            color: #F4F7F5;
            padding: 1.5rem;
        }

        .auth-card {
            width: 100%;
            max-width: 420px;
        }

        .auth-card .card {
            background-color: var(--qomer-surface);
            border: 1px solid var(--qomer-border);
            border-radius: 1.25rem;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(12px);
        }

        .form-control {
            background-color: #1B241F;
            border-color: rgba(255, 255, 255, 0.1);
            color: #F4F7F5;
            border-radius: 0.65rem;
            padding: 0.75rem 1rem;
        }

        .form-control:focus {
            background-color: #1B241F;
            border-color: var(--qomer-emerald);
            color: #F4F7F5;
            box-shadow: 0 0 0 0.25rem rgba(0, 199, 129, 0.2);
        }

        .btn-qomer {
            background: linear-gradient(135deg, #00C781 0%, #15966A 100%);
            border: none;
            color: #0B0F0D;
            font-weight: 700;
            border-radius: 0.65rem;
            padding: 0.8rem 1rem;
            box-shadow: 0 4px 15px rgba(0, 199, 129, 0.35);
            transition: all 0.22s ease;
        }

        .btn-qomer:hover {
            background: linear-gradient(135deg, #05e294 0%, #19aa78 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 22px rgba(0, 199, 129, 0.5);
            color: #0B0F0D;
        }
    </style>
</head>
<body>
    <div class="auth-card">
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

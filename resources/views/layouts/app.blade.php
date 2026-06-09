<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <link rel="shortcut icon" href="{{ asset('image/logo.png') }}" />
    <title>@yield('title', 'Panda Naicha') — Sistema de Gestión</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --bg:        #D1DDDB;
            --surface:   #e8efee;
            --card:      #ffffff;
            --border:    #b8ccca;
            --accent:    #1D6A96;
            --accent-h:  #155578;
            --success:   #2d8a5e;
            --warning:   #c97c10;
            --danger:    #c0392b;
            --text:      #283B42;
            --muted:     #5d7d84;
            --sidebar-w: 240px;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            display: flex;
        }

        /* ── Sidebar ─────────────────────────────── */
        .sidebar {
            width: var(--sidebar-w);
            background: var(--accent);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0; left: 0; bottom: 0;
            z-index: 100;
            overflow-y: auto;
        }

        .sidebar-logo {
            padding: 1.5rem 1.25rem;
            border-bottom: 1px solid rgba(255,255,255,.15);
            display: flex;
            align-items: center;
            gap: .75rem;
        }

        .sidebar-logo .logo-icon {
            width: 46px;
            height: 46px;
            object-fit: contain;
            flex-shrink: 0;
            border-radius: 50%;
            background: var(--bg);
            padding: 4px;
        }

        .sidebar-logo .logo-text {
            font-family: 'DM Mono', monospace;
            font-size: .85rem;
            font-weight: 500;
            color: #fff;
            line-height: 1.3;
        }

        .sidebar-logo .logo-sub {
            font-size: .7rem;
            color: rgba(255,255,255,.6);
            font-family: 'DM Sans', sans-serif;
        }

        .sidebar-nav {
            flex: 1;
            padding: 1rem 0;
        }

        .nav-section-label {
            font-size: .65rem;
            font-weight: 600;
            letter-spacing: .1em;
            text-transform: uppercase;
            color: rgba(255,255,255,.45);
            padding: .75rem 1.25rem .25rem;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: .75rem;
            padding: .6rem 1.25rem;
            color: rgba(255,255,255,.7);
            text-decoration: none;
            font-size: .875rem;
            font-weight: 500;
            border-left: 3px solid transparent;
            transition: all .15s;
        }

        .nav-item:hover,
        .nav-item.active {
            color: #fff;
            background: rgba(255,255,255,.12);
            border-left-color: var(--bg);
        }

        .nav-item .nav-icon { font-size: 1rem; width: 1.25rem; text-align: center; }

        .sidebar-user {
            padding: 1rem 1.25rem;
            border-top: 1px solid rgba(255,255,255,.15);
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: .75rem;
            margin-bottom: .75rem;
        }

        .user-avatar {
            width: 2.25rem;
            height: 2.25rem;
            border-radius: 50%;
            background: var(--bg);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: .8rem;
            font-weight: 700;
            color: var(--accent);
            flex-shrink: 0;
        }

        .user-name { font-size: .85rem; font-weight: 600; color: #fff; }
        .user-role { font-size: .7rem; color: rgba(255,255,255,.55); }

        .btn-logout {
            display: flex;
            align-items: center;
            gap: .5rem;
            width: 100%;
            padding: .5rem .75rem;
            background: rgba(255,255,255,.1);
            border: 1px solid rgba(255,255,255,.2);
            border-radius: 8px;
            color: rgba(255,255,255,.85);
            font-size: .8rem;
            font-weight: 500;
            cursor: pointer;
            text-decoration: none;
            transition: all .15s;
            font-family: inherit;
        }

        .btn-logout:hover {
            background: rgba(255,255,255,.2);
            color: #fff;
        }

        /* ── Main ────────────────────────────────── */
        .main {
            margin-left: var(--sidebar-w);
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .topbar {
            background: var(--card);
            border-bottom: 1px solid var(--border);
            padding: 1rem 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 50;
            box-shadow: 0 1px 6px rgba(40,59,66,.06);
        }

        .page-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--text);
        }

        .page-subtitle {
            font-size: .75rem;
            color: var(--muted);
            margin-top: .1rem;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .badge-role {
            font-family: 'DM Mono', monospace;
            font-size: .65rem;
            padding: .25rem .75rem;
            border-radius: 20px;
            background: rgba(29,106,150,.1);
            color: var(--accent);
            border: 1px solid rgba(29,106,150,.25);
            text-transform: uppercase;
            letter-spacing: .05em;
        }

        .content {
            padding: 2rem;
            flex: 1;
        }

        /* ── Cards ───────────────────────────────── */
        .card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 1px 4px rgba(40,59,66,.06);
        }

        .card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.25rem;
        }

        .card-title {
            font-size: 1rem;
            font-weight: 600;
            color: var(--text);
        }

        .card-subtitle {
            font-size: .75rem;
            color: var(--muted);
        }

        /* ── Stats Grid ──────────────────────────── */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .stat-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 1.25rem;
            box-shadow: 0 1px 4px rgba(40,59,66,.06);
        }

        .stat-label {
            font-size: .7rem;
            text-transform: uppercase;
            letter-spacing: .08em;
            color: var(--muted);
            font-weight: 600;
        }

        .stat-value {
            font-family: 'DM Mono', monospace;
            font-size: 1.75rem;
            font-weight: 500;
            margin: .5rem 0 .25rem;
            color: var(--text);
        }

        .stat-note {
            font-size: .72rem;
            color: var(--muted);
        }

        .stat-icon {
            font-size: 1.4rem;
            color: var(--accent);
            margin-bottom: .5rem;
        }

        /* ── Buttons ─────────────────────────────── */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            padding: .6rem 1.25rem;
            border-radius: 8px;
            font-size: .875rem;
            font-weight: 500;
            cursor: pointer;
            border: none;
            text-decoration: none;
            transition: all .15s;
            font-family: inherit;
        }

        .btn-primary { background: var(--accent); color: #fff; }
        .btn-primary:hover { background: var(--accent-h); color: #fff; }

        .btn-success {
            background: rgba(45,138,94,.12);
            color: var(--success);
            border: 1px solid rgba(45,138,94,.25);
        }
        .btn-success:hover { background: rgba(45,138,94,.22); color: var(--success); }

        .btn-danger {
            background: rgba(192,57,43,.1);
            color: var(--danger);
            border: 1px solid rgba(192,57,43,.2);
        }
        .btn-danger:hover { background: rgba(192,57,43,.2); color: var(--danger); }

        .btn-ghost {
            background: transparent;
            color: var(--muted);
            border: 1px solid var(--border);
        }
        .btn-ghost:hover { color: var(--text); border-color: var(--muted); }

        .btn-sm { padding: .4rem .9rem; font-size: .8rem; }

        /* ── Forms ───────────────────────────────── */
        .form-group { margin-bottom: 1.25rem; }

        label {
            display: block;
            font-size: .78rem;
            font-weight: 600;
            color: var(--muted);
            margin-bottom: .4rem;
            text-transform: uppercase;
            letter-spacing: .05em;
        }

        input[type=text],
        input[type=email],
        input[type=password],
        input[type=number],
        select,
        textarea {
            width: 100%;
            padding: .65rem 1rem;
            background: #fff;
            border: 1.5px solid var(--border);
            border-radius: 8px;
            color: var(--text);
            font-size: .9rem;
            font-family: inherit;
            transition: border .15s;
        }

        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(29,106,150,.12);
        }

        .form-error {
            font-size: .75rem;
            color: var(--danger);
            margin-top: .35rem;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        /* ── Table ───────────────────────────────── */
        .table-wrap { overflow-x: auto; }

        table { width: 100%; border-collapse: collapse; }

        th {
            text-align: left;
            font-size: .68rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .08em;
            color: var(--muted);
            padding: .75rem 1rem;
            border-bottom: 1px solid var(--border);
            background: var(--surface);
        }

        td {
            padding: .85rem 1rem;
            border-bottom: 1px solid rgba(184,204,202,.5);
            font-size: .875rem;
            color: var(--text);
        }

        tr:last-child td { border-bottom: none; }
        tr:hover td { background: rgba(133,184,203,.06); }

        /* ── Badges ──────────────────────────────── */
        .badge {
            display: inline-block;
            font-size: .68rem;
            font-weight: 600;
            padding: .2rem .65rem;
            border-radius: 20px;
            font-family: 'DM Mono', monospace;
        }

        .badge-success { background: rgba(45,138,94,.12);  color: var(--success); }
        .badge-danger  { background: rgba(192,57,43,.12);  color: var(--danger); }
        .badge-warning { background: rgba(201,124,16,.12); color: var(--warning); }
        .badge-info    { background: rgba(29,106,150,.12); color: var(--accent); }
        .badge-gray    { background: rgba(93,125,132,.1);  color: var(--muted); }

        /* ── Alerts ──────────────────────────────── */
        .alert {
            padding: .875rem 1.25rem;
            border-radius: 10px;
            font-size: .875rem;
            margin-bottom: 1.25rem;
            display: flex;
            align-items: center;
            gap: .75rem;
        }

        .alert-success { background: rgba(45,138,94,.1);  border: 1px solid rgba(45,138,94,.25);  color: #1e6640; }
        .alert-danger  { background: rgba(192,57,43,.08); border: 1px solid rgba(192,57,43,.2);   color: #a0291e; }
        .alert-warning { background: rgba(201,124,16,.1); border: 1px solid rgba(201,124,16,.25); color: #8a5a0a; }

        /* ── Utils ───────────────────────────────── */
        .mono { font-family: 'DM Mono', monospace; }
        .text-muted   { color: var(--muted); }
        .text-success { color: var(--success); }
        .text-danger  { color: var(--danger); }
        .text-warning { color: var(--warning); }
        .text-accent  { color: var(--accent); }
        .flex         { display: flex; }
        .items-center { align-items: center; }
        .gap-2  { gap: .5rem; }
        .gap-3  { gap: .75rem; }
        .mb-4   { margin-bottom: 1rem; }
        .mb-6   { margin-bottom: 1.5rem; }
        .mt-4   { margin-top: 1rem; }
        .grid   { display: grid; }
        .grid-2 { grid-template-columns: 1fr 1fr; gap: 1rem; }
        .grid-3 { grid-template-columns: 1fr 1fr 1fr; gap: 1rem; }
        .w-full      { width: 100%; }
        .text-right  { text-align: right; }
        .text-center { text-align: center; }
        .font-bold   { font-weight: 700; }
        .text-lg { font-size: 1.125rem; }
        .text-sm { font-size: .875rem; }
        .text-xs { font-size: .75rem; }
        .divider { border: none; border-top: 1px solid var(--border); margin: 1.25rem 0; }

        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .main { margin-left: 0; }
            .form-row { grid-template-columns: 1fr; }
        }

        @media (max-width: 480px) {
            .content { padding: 1.25rem; }
            .topbar  { padding: .85rem 1.25rem; }
        }
    </style>
    @yield('styles')

    <style>
    @media print {
        .sidebar, .topbar, .btn, button, form, .nav-item, .nav-section-label,
        [onclick], input[type="date"], input[type="submit"] {
            display: none !important;
        }
        body {
            background: #ffffff !important;
            color: #111111 !important;
            font-family: Arial, sans-serif !important;
            font-size: 11pt !important;
            margin: 0 !important;
            padding: 0 !important;
        }
        .main, .content, main {
            margin-left: 0 !important;
            padding: 1cm 1.5cm !important;
            width: 100% !important;
            max-width: 100% !important;
        }
        .card {
            background: #ffffff !important;
            border: 1px solid #cccccc !important;
            border-radius: 0 !important;
            box-shadow: none !important;
            margin-bottom: 1rem !important;
            break-inside: avoid !important;
            page-break-inside: avoid !important;
        }
        .card-title    { color: #111111 !important; font-size: 12pt !important; font-weight: 700 !important; }
        .card-subtitle { color: #555555 !important; font-size: 9pt !important; }
        .stats-grid {
            display: grid !important;
            grid-template-columns: repeat(3, 1fr) !important;
            gap: .5rem !important;
            margin-bottom: 1rem !important;
        }
        .stat-card {
            background: #f5f5f5 !important;
            border: 1px solid #cccccc !important;
            border-radius: 0 !important;
            padding: .6rem !important;
            break-inside: avoid !important;
        }
        .stat-label { color: #555555 !important; font-size: 7pt !important; text-transform: uppercase !important; }
        .stat-value { color: #111111 !important; font-size: 14pt !important; font-weight: 700 !important; }
        .stat-note  { color: #777777 !important; font-size: 7pt !important; }
        table { width: 100% !important; border-collapse: collapse !important; font-size: 9pt !important; }
        thead tr { background: #eeeeee !important; border-bottom: 2px solid #999999 !important; }
        th { color: #333333 !important; font-weight: 700 !important; padding: .4rem .5rem !important; font-size: 8pt !important; text-transform: uppercase !important; }
        td { color: #111111 !important; padding: .35rem .5rem !important; border-bottom: 1px solid #e0e0e0 !important; }
        tfoot tr { background: #f0f0f0 !important; border-top: 2px solid #999999 !important; font-weight: 700 !important; }
        .badge, span[class*="badge"] { border: 1px solid #999999 !important; background: #ffffff !important; color: #111111 !important; padding: .1rem .4rem !important; font-size: 8pt !important; }
        .badge-success { border-color: #16a34a !important; color: #16a34a !important; }
        .badge-warning { border-color: #d97706 !important; color: #d97706 !important; }
        .badge-danger  { border-color: #dc2626 !important; color: #dc2626 !important; }
        .badge-info    { border-color: #2563eb !important; color: #2563eb !important; }
        .text-success, .text-accent { color: #16a34a !important; }
        .text-danger  { color: #dc2626 !important; }
        .text-warning { color: #d97706 !important; }
        .text-muted   { color: #666666 !important; }
        .mono { font-family: 'Courier New', monospace !important; }
        .print-header { display: block !important; text-align: center !important; margin-bottom: 1.5rem !important; padding-bottom: .75rem !important; border-bottom: 2px solid #333333 !important; }
        .print-header h1 { font-size: 16pt !important; font-weight: 700 !important; color: #111111 !important; margin: 0 0 .25rem !important; }
        .print-header p  { font-size: 9pt !important; color: #555555 !important; margin: 0 !important; }
        [style*="display:none"] { display: block !important; }
        .grid-2 { display: block !important; }
        .grid-2 > * { width: 100% !important; margin-bottom: .5rem !important; }
        h2, h3, .card-title { page-break-after: avoid !important; }
    }
    </style>
</head>
<body>

{{-- Sidebar --}}
<aside class="sidebar">
    <div class="sidebar-logo">
        <img src="{{ asset('image/logo.png') }}" alt="Logo" class="logo-icon"/>
        <div>
            <div class="logo-text">Panda Naicha</div>
            <div class="logo-sub">Sistema de Gestión</div>
        </div>
    </div>

    <nav class="sidebar-nav">
        @yield('sidebar-nav')
    </nav>

    <div class="sidebar-user">
        <div class="user-info">
            <div class="user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</div>
            <div>
                <div class="user-name">{{ auth()->user()->name }}</div>
                <div class="user-role">{{ auth()->user()->role->name }}</div>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn-logout">
                <i class="bi bi-box-arrow-left"></i>
                Cerrar sesión
            </button>
        </form>
    </div>
</aside>

{{-- Main content --}}
<div class="main">
    <header class="topbar">
        <div>
            <div class="page-title">@yield('page-title', 'Dashboard')</div>
            <div class="page-subtitle">@yield('page-subtitle', '')</div>
        </div>
        <div class="topbar-right">
            <span class="badge-role">{{ auth()->user()->role->slug }}</span>
            @yield('topbar-actions')
        </div>
    </header>

    <div class="content">
        @if(session('success'))
            <div class="alert alert-success">
                <i class="bi bi-check-circle-fill"></i>
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-circle-fill"></i>
                {{ session('error') }}
            </div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-circle-fill" style="flex-shrink:0;margin-top:2px;"></i>
                <div>
                    @foreach($errors->all() as $e)
                        <div>{{ $e }}</div>
                    @endforeach
                </div>
            </div>
        @endif

        @yield('content')
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

@yield('scripts')
@stack('scripts')

<script>
// ── Seguridad: bloquear acceso con botón "atrás" tras cerrar sesión ──
(function() {
    if (typeof window.history.pushState === 'function') {
        window.history.pushState({ authenticated: true }, '');
    }
    window.addEventListener('pageshow', function(event) {
        if (event.persisted) {
            fetch(window.location.href, { method: 'HEAD', credentials: 'same-origin' })
                .then(res => {
                    if (res.redirected || res.url.includes('login')) {
                        window.location.replace('/login');
                    }
                })
                .catch(() => window.location.replace('/login'));
        }
    });
})();
</script>
</body>
</html>
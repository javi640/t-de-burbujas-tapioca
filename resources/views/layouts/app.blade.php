<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">

    <title>@yield('title', 'Panda Naicha') — Sistema de Gestión</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --bg:        #0b0f1a;
            --surface:   #131929;
            --card:      #1a2235;
            --border:    #2a3548;
            --accent:    #4f8ef7;
            --accent-h:  #3a7de8;
            --success:   #22c55e;
            --warning:   #f59e0b;
            --danger:    #ef4444;
            --text:      #f0f4ff;
            --muted:     #8b9abf;
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
            background: var(--surface);
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0; left: 0; bottom: 0;
            z-index: 100;
            overflow-y: auto;
        }

        .sidebar-logo {
            padding: 1.5rem 1.25rem;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: .75rem;
        }

        .sidebar-logo .logo-icon {
            width: 50px;
            height: 50px;
            object-fit: contain;
            flex-shrink: 0;
        }

        .sidebar-logo .logo-text {
            font-family: 'DM Mono', monospace;
            font-size: .85rem;
            font-weight: 500;
            color: var(--text);
            line-height: 1.3;
        }

        .sidebar-logo .logo-sub {
            font-size: .7rem;
            color: var(--muted);
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
            color: var(--muted);
            padding: .75rem 1.25rem .25rem;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: .75rem;
            padding: .6rem 1.25rem;
            color: var(--muted);
            text-decoration: none;
            font-size: .875rem;
            font-weight: 500;
            border-left: 3px solid transparent;
            transition: all .15s;
        }

        .nav-item:hover,
        .nav-item.active {
            color: var(--text);
            background: rgba(79,142,247,.08);
            border-left-color: var(--accent);
        }

        .nav-item .nav-icon { font-size: 1rem; width: 1.25rem; text-align: center; }

        .sidebar-user {
            padding: 1rem 1.25rem;
            border-top: 1px solid var(--border);
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
            background: var(--accent);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: .8rem;
            font-weight: 700;
            color: #fff;
            flex-shrink: 0;
        }

        .user-name { font-size: .85rem; font-weight: 600; }
        .user-role { font-size: .7rem; color: var(--muted); }

        .btn-logout {
            display: flex;
            align-items: center;
            gap: .5rem;
            width: 100%;
            padding: .5rem .75rem;
            background: rgba(239,68,68,.1);
            border: 1px solid rgba(239,68,68,.2);
            border-radius: 8px;
            color: var(--danger);
            font-size: .8rem;
            font-weight: 500;
            cursor: pointer;
            text-decoration: none;
            transition: all .15s;
            font-family: inherit;
        }

        .btn-logout:hover {
            background: rgba(239,68,68,.2);
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
            background: var(--surface);
            border-bottom: 1px solid var(--border);
            padding: 1rem 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 50;
        }

        .page-title {
            font-size: 1.1rem;
            font-weight: 600;
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
            background: rgba(79,142,247,.12);
            color: var(--accent);
            border: 1px solid rgba(79,142,247,.2);
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
            font-size: 1.5rem;
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

        .btn-primary {
            background: var(--accent);
            color: #fff;
        }
        .btn-primary:hover { background: var(--accent-h); }

        .btn-success {
            background: rgba(34,197,94,.15);
            color: var(--success);
            border: 1px solid rgba(34,197,94,.25);
        }
        .btn-success:hover { background: rgba(34,197,94,.25); }

        .btn-danger {
            background: rgba(239,68,68,.1);
            color: var(--danger);
            border: 1px solid rgba(239,68,68,.2);
        }
        .btn-danger:hover { background: rgba(239,68,68,.2); }

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
            font-size: .8rem;
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
            background: var(--bg);
            border: 1px solid var(--border);
            border-radius: 8px;
            color: var(--text);
            font-size: .9rem;
            font-family: inherit;
            transition: border .15s;
        }

        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(79,142,247,.12);
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
        }

        td {
            padding: .85rem 1rem;
            border-bottom: 1px solid rgba(42,53,72,.6);
            font-size: .875rem;
        }

        tr:last-child td { border-bottom: none; }
        tr:hover td { background: rgba(255,255,255,.015); }

        /* ── Badges ──────────────────────────────── */
        .badge {
            display: inline-block;
            font-size: .68rem;
            font-weight: 600;
            padding: .2rem .65rem;
            border-radius: 20px;
            font-family: 'DM Mono', monospace;
        }

        .badge-success { background: rgba(34,197,94,.12); color: var(--success); }
        .badge-danger  { background: rgba(239,68,68,.12); color: var(--danger); }
        .badge-warning { background: rgba(245,158,11,.12); color: var(--warning); }
        .badge-info    { background: rgba(79,142,247,.12); color: var(--accent); }
        .badge-gray    { background: rgba(139,154,191,.1); color: var(--muted); }

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

        .alert-success { background: rgba(34,197,94,.1); border: 1px solid rgba(34,197,94,.2); color: #4ade80; }
        .alert-danger  { background: rgba(239,68,68,.1);  border: 1px solid rgba(239,68,68,.2);  color: #f87171; }
        .alert-warning { background: rgba(245,158,11,.1); border: 1px solid rgba(245,158,11,.2); color: #fbbf24; }

        /* ── Utils ───────────────────────────────── */
        .mono { font-family: 'DM Mono', monospace; }
        .text-muted { color: var(--muted); }
        .text-success { color: var(--success); }
        .text-danger  { color: var(--danger); }
        .text-warning { color: var(--warning); }
        .text-accent  { color: var(--accent); }
        .flex { display: flex; }
        .items-center { align-items: center; }
        .gap-2 { gap: .5rem; }
        .gap-3 { gap: .75rem; }
        .mb-4 { margin-bottom: 1rem; }
        .mb-6 { margin-bottom: 1.5rem; }
        .mt-4 { margin-top: 1rem; }
        .grid { display: grid; }
        .grid-2 { grid-template-columns: 1fr 1fr; gap: 1rem; }
        .grid-3 { grid-template-columns: 1fr 1fr 1fr; gap: 1rem; }
        .w-full { width: 100%; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .font-bold { font-weight: 700; }
        .text-lg { font-size: 1.125rem; }
        .text-sm { font-size: .875rem; }
        .text-xs { font-size: .75rem; }
        .divider { border: none; border-top: 1px solid var(--border); margin: 1.25rem 0; }

        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .main { margin-left: 0; }
            .form-row { grid-template-columns: 1fr; }
            .sidebar-logo { padding: 1.25rem 1rem; }
            .sidebar-logo .logo-icon { width: 40px; height: 40px; }
            .sidebar-logo .logo-text { font-size: .75rem; }
            .sidebar-logo .logo-sub { font-size: .65rem; }
        }

        @media (max-width: 480px) {
            .sidebar-logo { padding: 1rem 0.75rem; gap: 0.5rem; }
            .sidebar-logo .logo-icon { width: 35px; height: 35px; }
            .sidebar-logo .logo-text { font-size: .7rem; }
            .sidebar-logo .logo-sub { font-size: .6rem; }
        }
    </style>
    @yield('styles')
</head>
<body>

{{-- Sidebar --}}
<aside class="sidebar">
    <div class="sidebar-logo">
        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="logo-icon"/>
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
                <span>⬡</span> Cerrar sesión
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
            <div class="alert alert-success">✓ {{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">✕ {{ session('error') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger">
                <div>
                    @foreach($errors->all() as $e)
                        <div>✕ {{ $e }}</div>
                    @endforeach
                </div>
            </div>
        @endif

        @yield('content')
    </div>
</div>

@yield('scripts')
@stack('scripts')
</body>
</html>
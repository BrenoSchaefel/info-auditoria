<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dashboard') — {{ config('app.name') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700&display=swap" rel="stylesheet">

    <style>
        *, *::before, *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        :root {
            --primary:       #2563eb;
            --primary-hover: #1d4ed8;
            --primary-light: #eff6ff;
            --sidebar-w:     260px;
            --topbar-h:      64px;
            --text:          #0f172a;
            --text-muted:    #64748b;
            --text-light:    #94a3b8;
            --border:        #e2e8f0;
            --bg:            #f8fafc;
            --surface:       #ffffff;
            --sidebar-bg:    #0f172a;
            --sidebar-text:  #94a3b8;
            --sidebar-hover: rgba(255,255,255,.06);
            --sidebar-active:#1e293b;
            --radius:        12px;
            --radius-sm:     8px;
            --shadow-sm:     0 1px 3px 0 rgb(0 0 0 / .07);
            --shadow-md:     0 4px 6px -1px rgb(0 0 0 / .07), 0 2px 4px -2px rgb(0 0 0 / .07);
            --transition:    .18s ease;
        }

        html, body { height: 100%; }

        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background: var(--bg);
            color: var(--text);
            -webkit-font-smoothing: antialiased;
            display: flex;
        }

        /* ─── Sidebar ─────────────────────────────────────── */
        .sidebar {
            position: fixed;
            top: 0; left: 0;
            width: var(--sidebar-w);
            height: 100vh;
            background: var(--sidebar-bg);
            display: flex;
            flex-direction: column;
            z-index: 100;
            transition: transform var(--transition);
            overflow: hidden;
        }

        .sidebar-logo {
            display: flex;
            align-items: center;
            gap: .75rem;
            padding: 0 1.25rem;
            height: var(--topbar-h);
            border-bottom: 1px solid rgba(255,255,255,.07);
            text-decoration: none;
            flex-shrink: 0;
        }

        .sidebar-logo-icon {
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, var(--primary) 0%, #6366f1 100%);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            box-shadow: 0 2px 8px rgba(37,99,235,.4);
        }

        .sidebar-logo-icon svg {
            width: 18px; height: 18px;
            stroke: #fff; fill: none;
            stroke-width: 2.2;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .sidebar-logo-name {
            font-size: .82rem;
            font-weight: 700;
            color: #f1f5f9;
            line-height: 1.2;
            letter-spacing: -.01em;
        }

        .sidebar-logo-name small {
            display: block;
            font-size: .68rem;
            font-weight: 400;
            color: var(--sidebar-text);
            letter-spacing: 0;
        }

        /* Nav */
        .sidebar-nav {
            flex: 1;
            overflow-y: auto;
            padding: 1rem 0;
            scrollbar-width: thin;
            scrollbar-color: rgba(255,255,255,.1) transparent;
        }

        .sidebar-section {
            margin-bottom: .25rem;
        }

        .sidebar-section-label {
            font-size: .65rem;
            font-weight: 600;
            letter-spacing: .08em;
            text-transform: uppercase;
            color: rgba(148,163,184,.5);
            padding: .75rem 1.25rem .35rem;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: .7rem;
            padding: .55rem 1.25rem;
            margin: 0 .5rem;
            border-radius: var(--radius-sm);
            color: var(--sidebar-text);
            text-decoration: none;
            font-size: .85rem;
            font-weight: 500;
            cursor: pointer;
            transition: background var(--transition), color var(--transition);
            position: relative;
        }

        .nav-item:hover {
            background: var(--sidebar-hover);
            color: #e2e8f0;
        }

        .nav-item.active {
            background: var(--sidebar-active);
            color: #f1f5f9;
        }

        .nav-item.active::before {
            content: '';
            position: absolute;
            left: -8px; top: 25%; bottom: 25%;
            width: 3px;
            background: var(--primary);
            border-radius: 0 3px 3px 0;
        }

        .nav-item svg {
            width: 17px; height: 17px;
            flex-shrink: 0;
            stroke: currentColor; fill: none;
            stroke-width: 1.8;
            stroke-linecap: round;
            stroke-linejoin: round;
            opacity: .8;
        }

        .nav-item.active svg { opacity: 1; }

        .nav-badge {
            margin-left: auto;
            font-size: .67rem;
            font-weight: 600;
            background: rgba(37,99,235,.3);
            color: #93c5fd;
            padding: .15rem .45rem;
            border-radius: 999px;
            line-height: 1.4;
        }

        /* Sidebar footer / user */
        .sidebar-footer {
            border-top: 1px solid rgba(255,255,255,.07);
            padding: 1rem;
            flex-shrink: 0;
        }

        .sidebar-user {
            display: flex;
            align-items: center;
            gap: .65rem;
            padding: .5rem .6rem;
            border-radius: var(--radius-sm);
            cursor: pointer;
            transition: background var(--transition);
            position: relative;
        }

        .sidebar-user:hover { background: var(--sidebar-hover); }

        .sidebar-user-avatar {
            width: 34px; height: 34px;
            border-radius: 50%;
            background: linear-gradient(135deg, #6366f1, #2563eb);
            display: flex; align-items: center; justify-content: center;
            font-size: .8rem;
            font-weight: 700;
            color: #fff;
            flex-shrink: 0;
        }

        .sidebar-user-info {
            flex: 1;
            min-width: 0;
        }

        .sidebar-user-name {
            font-size: .82rem;
            font-weight: 600;
            color: #e2e8f0;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .sidebar-user-role {
            font-size: .7rem;
            color: var(--sidebar-text);
        }

        .sidebar-user-menu {
            position: absolute;
            bottom: calc(100% + .5rem);
            left: 0; right: 0;
            background: #1e293b;
            border: 1px solid rgba(255,255,255,.08);
            border-radius: var(--radius-sm);
            padding: .4rem;
            display: none;
            box-shadow: 0 -8px 24px rgba(0,0,0,.3);
        }

        .sidebar-user-menu.open { display: block; }

        .sidebar-user-menu a,
        .sidebar-user-menu button {
            display: flex;
            align-items: center;
            gap: .55rem;
            width: 100%;
            padding: .55rem .7rem;
            border-radius: 6px;
            font-size: .82rem;
            font-weight: 500;
            color: var(--sidebar-text);
            text-decoration: none;
            background: none;
            border: none;
            cursor: pointer;
            font-family: inherit;
            transition: background var(--transition), color var(--transition);
        }

        .sidebar-user-menu a:hover,
        .sidebar-user-menu button:hover {
            background: rgba(255,255,255,.06);
            color: #e2e8f0;
        }

        .sidebar-user-menu svg {
            width: 15px; height: 15px;
            stroke: currentColor; fill: none;
            stroke-width: 1.8;
            stroke-linecap: round; stroke-linejoin: round;
        }

        .menu-divider {
            height: 1px;
            background: rgba(255,255,255,.07);
            margin: .35rem 0;
        }

        /* ─── Main area ───────────────────────────────────── */
        .main-wrap {
            margin-left: var(--sidebar-w);
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            min-width: 0;
        }

        /* ─── Topbar ──────────────────────────────────────── */
        .topbar {
            position: sticky;
            top: 0;
            z-index: 50;
            height: var(--topbar-h);
            background: var(--surface);
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            padding: 0 1.75rem;
            gap: 1rem;
        }

        .topbar-hamburger {
            display: none;
            background: none;
            border: none;
            cursor: pointer;
            padding: .35rem;
            border-radius: 6px;
            color: var(--text-muted);
            transition: background var(--transition);
        }

        .topbar-hamburger:hover { background: var(--bg); }

        .topbar-hamburger svg {
            width: 20px; height: 20px;
            stroke: currentColor; fill: none;
            stroke-width: 2;
            stroke-linecap: round; stroke-linejoin: round;
        }

        .topbar-breadcrumb {
            display: flex;
            align-items: center;
            gap: .4rem;
            font-size: .82rem;
            color: var(--text-muted);
            flex: 1;
        }

        .topbar-breadcrumb .crumb-current {
            color: var(--text);
            font-weight: 600;
        }

        .topbar-breadcrumb svg {
            width: 14px; height: 14px;
            stroke: currentColor; fill: none;
            stroke-width: 2;
            stroke-linecap: round; stroke-linejoin: round;
            opacity: .5;
        }

        .topbar-actions {
            display: flex;
            align-items: center;
            gap: .5rem;
        }

        .topbar-icon-btn {
            width: 36px; height: 36px;
            border-radius: var(--radius-sm);
            display: flex; align-items: center; justify-content: center;
            background: none;
            border: 1px solid var(--border);
            cursor: pointer;
            color: var(--text-muted);
            transition: background var(--transition), color var(--transition);
            position: relative;
        }

        .topbar-icon-btn:hover {
            background: var(--bg);
            color: var(--text);
        }

        .topbar-icon-btn svg {
            width: 17px; height: 17px;
            stroke: currentColor; fill: none;
            stroke-width: 1.8;
            stroke-linecap: round; stroke-linejoin: round;
        }

        .topbar-notif-dot {
            position: absolute;
            top: 6px; right: 6px;
            width: 7px; height: 7px;
            border-radius: 50%;
            background: var(--primary);
            border: 1.5px solid var(--surface);
        }

        /* ─── Page content ────────────────────────────────── */
        .page-content {
            flex: 1;
            padding: 1.75rem;
        }

        .page-header {
            margin-bottom: 1.5rem;
        }

        .page-title {
            font-size: 1.35rem;
            font-weight: 700;
            letter-spacing: -.03em;
            color: var(--text);
        }

        .page-subtitle {
            font-size: .85rem;
            color: var(--text-muted);
            margin-top: .2rem;
        }

        /* ─── Cards utilitários ──────────────────────────── */
        .card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            box-shadow: var(--shadow-sm);
        }

        .card-body {
            padding: 1.25rem 1.5rem;
        }

        /* ─── Responsivo ──────────────────────────────────── */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.open {
                transform: translateX(0);
                box-shadow: 4px 0 24px rgba(0,0,0,.3);
            }

            .main-wrap {
                margin-left: 0;
            }

            .topbar-hamburger {
                display: flex;
            }

            .page-content {
                padding: 1.25rem 1rem;
            }
        }

        /* ─── Overlay mobile ─────────────────────────────── */
        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,.5);
            z-index: 99;
        }

        .sidebar-overlay.open { display: block; }
    </style>

    @stack('styles')
</head>
<body>

    {{-- Sidebar --}}
    <aside class="sidebar" id="sidebar">

        <a href="{{ url('/') }}" class="sidebar-logo">
            <div class="sidebar-logo-icon">
                <svg viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
            </div>
            <div class="sidebar-logo-name">
                {{ config('app.name') }}
                <small>Sistema de Auditoria</small>
            </div>
        </a>

        <nav class="sidebar-nav">

            <div class="sidebar-section">
                <span class="sidebar-section-label">Geral</span>

                <a href="{{ route('home') }}" class="nav-item {{ request()->routeIs('home') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
                    Visão Geral
                </a>
            </div>

            <div class="sidebar-section">
                <span class="sidebar-section-label">Consultas</span>

                <a href="{{ route('consulta.ncm.index') }}" class="nav-item {{ request()->routeIs('consulta.ncm.*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    Consulta NCM
                </a>
            </div>

            <div class="sidebar-section">
                <span class="sidebar-section-label">Dados</span>

                <a href="{{ route('importacao.index') }}" class="nav-item {{ request()->routeIs('importacao.*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24"><polyline points="8 17 12 21 16 17"/><line x1="12" y1="12" x2="12" y2="21"/><path d="M20.88 18.09A5 5 0 0 0 18 9h-1.26A8 8 0 1 0 3 16.29"/></svg>
                    Importação
                </a>
            </div>

        </nav>

        <div class="sidebar-footer">
            <div class="sidebar-user" id="userMenuToggle">
                <div class="sidebar-user-menu" id="userMenu">
                    <a href="#">
                        <svg viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        Perfil
                    </a>
                    <div class="menu-divider"></div>
                    <form method="POST" action="{{ route('logout') }}" style="display:contents">
                        @csrf
                        <button type="submit">
                            <svg viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                            Sair
                        </button>
                    </form>
                </div>

                <div class="sidebar-user-avatar">
                    {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
                </div>
                <div class="sidebar-user-info">
                    <div class="sidebar-user-name">{{ Auth::user()->name ?? 'Usuário' }}</div>
                    <div class="sidebar-user-role">Auditor</div>
                </div>
                <svg style="width:14px;height:14px;stroke:#64748b;fill:none;stroke-width:2;flex-shrink:0" viewBox="0 0 24 24"><polyline points="18 15 12 9 6 15"/></svg>
            </div>
        </div>

    </aside>

    {{-- Overlay mobile --}}
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    {{-- Main --}}
    <div class="main-wrap">

        <header class="topbar">
            <button class="topbar-hamburger" id="hamburgerBtn" aria-label="Abrir menu">
                <svg viewBox="0 0 24 24"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
            </button>

            <div class="topbar-breadcrumb">
                <span>{{ config('app.name') }}</span>
                <svg viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"/></svg>
                <span class="crumb-current">@yield('title', 'Dashboard')</span>
            </div>

            <div class="topbar-actions">
                <button class="topbar-icon-btn" title="Notificações">
                    <svg viewBox="0 0 24 24"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
                    <span class="topbar-notif-dot"></span>
                </button>
            </div>
        </header>

        <main class="page-content">
            @yield('content')
        </main>

    </div>

    <script>
        // Sidebar mobile toggle
        var sidebar  = document.getElementById('sidebar');
        var overlay  = document.getElementById('sidebarOverlay');
        var hamburger = document.getElementById('hamburgerBtn');

        function openSidebar() {
            sidebar.classList.add('open');
            overlay.classList.add('open');
        }

        function closeSidebar() {
            sidebar.classList.remove('open');
            overlay.classList.remove('open');
        }

        hamburger.addEventListener('click', openSidebar);
        overlay.addEventListener('click', closeSidebar);

        // User menu toggle
        var userToggle = document.getElementById('userMenuToggle');
        var userMenu   = document.getElementById('userMenu');

        userToggle.addEventListener('click', function(e) {
            e.stopPropagation();
            userMenu.classList.toggle('open');
        });

        document.addEventListener('click', function() {
            userMenu.classList.remove('open');
        });
    </script>

    @stack('scripts')
</body>
</html>

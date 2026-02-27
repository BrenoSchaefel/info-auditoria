<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name')) — {{ config('app.name') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700&display=swap" rel="stylesheet">

    <style>
        *, *::before, *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        :root {
            --primary:        #2563eb;
            --primary-hover:  #1d4ed8;
            --primary-light:  #eff6ff;
            --danger:         #ef4444;
            --danger-light:   #fef2f2;
            --success:        #22c55e;
            --success-light:  #f0fdf4;
            --text:           #0f172a;
            --text-muted:     #64748b;
            --border:         #e2e8f0;
            --bg:             #f8fafc;
            --card-bg:        #ffffff;
            --shadow-sm:      0 1px 3px 0 rgb(0 0 0 / .1), 0 1px 2px -1px rgb(0 0 0 / .1);
            --shadow-md:      0 4px 6px -1px rgb(0 0 0 / .1), 0 2px 4px -2px rgb(0 0 0 / .1);
            --shadow-lg:      0 10px 15px -3px rgb(0 0 0 / .1), 0 4px 6px -4px rgb(0 0 0 / .1);
            --radius:         14px;
            --radius-sm:      8px;
            --transition:     .2s ease;
        }

        html, body {
            height: 100%;
        }

        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background-color: var(--bg);
            color: var(--text);
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            -webkit-font-smoothing: antialiased;
        }

        /* ─── Background decorativo ─────────────────────────── */
        .auth-bg {
            position: fixed;
            inset: 0;
            z-index: 0;
            overflow: hidden;
            pointer-events: none;
        }

        .auth-bg::before {
            content: '';
            position: absolute;
            width: 600px;
            height: 600px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(37,99,235,.12) 0%, transparent 70%);
            top: -200px;
            right: -150px;
        }

        .auth-bg::after {
            content: '';
            position: absolute;
            width: 500px;
            height: 500px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(99,102,241,.08) 0%, transparent 70%);
            bottom: -150px;
            left: -100px;
        }

        /* ─── Layout ─────────────────────────────────────────── */
        .auth-wrapper {
            position: relative;
            z-index: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 2rem 1rem;
        }

        /* ─── Logo / Branding ────────────────────────────────── */
        .auth-brand {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 2rem;
            text-decoration: none;
            gap: .5rem;
        }

        .auth-brand-icon {
            width: 52px;
            height: 52px;
            background: linear-gradient(135deg, var(--primary) 0%, #6366f1 100%);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 14px rgba(37,99,235,.35);
        }

        .auth-brand-icon svg {
            width: 28px;
            height: 28px;
            color: #fff;
            fill: none;
            stroke: currentColor;
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .auth-brand-name {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--text);
            letter-spacing: -.02em;
        }

        /* ─── Card ───────────────────────────────────────────── */
        .auth-card {
            background: var(--card-bg);
            border-radius: var(--radius);
            box-shadow: var(--shadow-lg);
            border: 1px solid var(--border);
            width: 100%;
            max-width: 440px;
            padding: 2.25rem 2.5rem;
            animation: cardIn .35s cubic-bezier(.16,1,.3,1);
        }

        @keyframes cardIn {
            from { opacity: 0; transform: translateY(16px) scale(.98); }
            to   { opacity: 1; transform: translateY(0) scale(1); }
        }

        .auth-card-title {
            font-size: 1.5rem;
            font-weight: 700;
            letter-spacing: -.03em;
            color: var(--text);
            margin-bottom: .35rem;
        }

        .auth-card-subtitle {
            font-size: .9rem;
            color: var(--text-muted);
            margin-bottom: 1.75rem;
        }

        /* ─── Formulário ─────────────────────────────────────── */
        .form-group {
            margin-bottom: 1.1rem;
        }

        .form-label {
            display: block;
            font-size: .82rem;
            font-weight: 600;
            color: var(--text);
            margin-bottom: .4rem;
            letter-spacing: .01em;
        }

        .form-control {
            width: 100%;
            padding: .65rem .9rem;
            font-size: .92rem;
            font-family: inherit;
            color: var(--text);
            background: var(--bg);
            border: 1.5px solid var(--border);
            border-radius: var(--radius-sm);
            outline: none;
            transition: border-color var(--transition), box-shadow var(--transition), background var(--transition);
            appearance: none;
        }

        .form-control:focus {
            border-color: var(--primary);
            background: #fff;
            box-shadow: 0 0 0 3px rgba(37,99,235,.15);
        }

        .form-control.is-invalid {
            border-color: var(--danger);
            background: var(--danger-light);
        }

        .form-control.is-invalid:focus {
            box-shadow: 0 0 0 3px rgba(239,68,68,.15);
        }

        .invalid-feedback {
            display: block;
            font-size: .78rem;
            color: var(--danger);
            margin-top: .3rem;
        }

        /* ─── Input com ícone ────────────────────────────────── */
        .input-wrapper {
            position: relative;
        }

        .input-icon {
            position: absolute;
            top: 50%;
            left: .75rem;
            transform: translateY(-50%);
            width: 16px;
            height: 16px;
            color: var(--text-muted);
            pointer-events: none;
            flex-shrink: 0;
        }

        .input-icon svg {
            width: 16px;
            height: 16px;
            stroke: currentColor;
            fill: none;
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .input-wrapper .form-control {
            padding-left: 2.4rem;
        }

        .input-toggle-password {
            position: absolute;
            top: 50%;
            right: .75rem;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: var(--text-muted);
            padding: 0;
            display: flex;
            align-items: center;
            transition: color var(--transition);
        }

        .input-toggle-password:hover { color: var(--primary); }

        .input-toggle-password svg {
            width: 16px;
            height: 16px;
            stroke: currentColor;
            fill: none;
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .input-wrapper .form-control.has-toggle {
            padding-right: 2.4rem;
        }

        /* ─── Checkbox ───────────────────────────────────────── */
        .form-check {
            display: flex;
            align-items: center;
            gap: .5rem;
            cursor: pointer;
            user-select: none;
        }

        .form-check-input {
            width: 16px;
            height: 16px;
            min-width: 16px;
            border: 1.5px solid var(--border);
            border-radius: 4px;
            background: var(--bg);
            appearance: none;
            cursor: pointer;
            transition: background var(--transition), border-color var(--transition);
            position: relative;
        }

        .form-check-input:checked {
            background: var(--primary);
            border-color: var(--primary);
        }

        .form-check-input:checked::after {
            content: '';
            position: absolute;
            top: 1px; left: 4px;
            width: 5px; height: 9px;
            border: 2px solid #fff;
            border-top: none;
            border-left: none;
            transform: rotate(45deg);
        }

        .form-check-label {
            font-size: .85rem;
            color: var(--text-muted);
        }

        /* ─── Botões ─────────────────────────────────────────── */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: .45rem;
            padding: .7rem 1.25rem;
            font-size: .9rem;
            font-weight: 600;
            font-family: inherit;
            border: none;
            border-radius: var(--radius-sm);
            cursor: pointer;
            text-decoration: none;
            transition: background var(--transition), transform var(--transition), box-shadow var(--transition);
            white-space: nowrap;
        }

        .btn:active { transform: scale(.97); }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary) 0%, #4f46e5 100%);
            color: #fff;
            box-shadow: 0 2px 8px rgba(37,99,235,.3);
            width: 100%;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, var(--primary-hover) 0%, #4338ca 100%);
            box-shadow: 0 4px 12px rgba(37,99,235,.4);
        }

        .btn-primary:disabled {
            opacity: .65;
            cursor: not-allowed;
            transform: none;
        }

        .btn-outline {
            background: transparent;
            color: var(--text-muted);
            border: 1.5px solid var(--border);
        }

        .btn-outline:hover {
            background: var(--bg);
            color: var(--text);
            border-color: #cbd5e1;
        }

        /* ─── Alertas ────────────────────────────────────────── */
        .alert {
            display: flex;
            align-items: flex-start;
            gap: .6rem;
            padding: .8rem 1rem;
            border-radius: var(--radius-sm);
            font-size: .85rem;
            margin-bottom: 1.25rem;
        }

        .alert svg {
            width: 16px;
            height: 16px;
            flex-shrink: 0;
            margin-top: 1px;
            stroke: currentColor;
            fill: none;
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .alert-success {
            background: var(--success-light);
            color: #166534;
            border: 1px solid #bbf7d0;
        }

        .alert-danger {
            background: var(--danger-light);
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        /* ─── Divisor / Links ─────────────────────────────────── */
        .auth-divider {
            display: flex;
            align-items: center;
            gap: .75rem;
            margin: 1.25rem 0;
            color: var(--text-muted);
            font-size: .8rem;
        }

        .auth-divider::before,
        .auth-divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--border);
        }

        .auth-link {
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
            font-size: .85rem;
            transition: color var(--transition);
        }

        .auth-link:hover { color: var(--primary-hover); text-decoration: underline; }

        .auth-footer-text {
            text-align: center;
            margin-top: 1.5rem;
            font-size: .85rem;
            color: var(--text-muted);
        }

        .form-row-inline {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.4rem;
        }

        /* ─── Loading spinner no botão ───────────────────────── */
        .btn-spinner {
            display: none;
            width: 16px;
            height: 16px;
            border: 2px solid rgba(255,255,255,.4);
            border-top-color: #fff;
            border-radius: 50%;
            animation: spin .6s linear infinite;
        }

        @keyframes spin { to { transform: rotate(360deg); } }

        .btn.loading .btn-spinner { display: block; }
        .btn.loading .btn-label  { display: none; }

        /* ─── Indicador força da senha ───────────────────────── */
        .password-strength {
            margin-top: .5rem;
        }

        .password-strength-bar {
            display: flex;
            gap: 4px;
            margin-bottom: .3rem;
        }

        .strength-segment {
            flex: 1;
            height: 4px;
            border-radius: 2px;
            background: var(--border);
            transition: background .3s ease;
        }

        .strength-segment.active-weak   { background: var(--danger); }
        .strength-segment.active-fair   { background: #f59e0b; }
        .strength-segment.active-good   { background: #3b82f6; }
        .strength-segment.active-strong { background: var(--success); }

        .password-strength-label {
            font-size: .75rem;
            color: var(--text-muted);
        }

        /* ─── Info box ───────────────────────────────────────── */
        .info-box {
            background: var(--primary-light);
            border: 1px solid #bfdbfe;
            border-radius: var(--radius-sm);
            padding: 1rem;
            font-size: .85rem;
            color: #1e40af;
            margin-bottom: 1.25rem;
        }

        /* ─── Responsivo ─────────────────────────────────────── */
        @media (max-width: 480px) {
            .auth-card {
                padding: 1.75rem 1.5rem;
            }
        }
    </style>

    @stack('styles')
</head>
<body>
    <div class="auth-bg"></div>

    <div class="auth-wrapper">
        <a href="{{ url('/') }}" class="auth-brand">
            <div class="auth-brand-icon">
                <svg viewBox="0 0 24 24">
                    <path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
            </div>
            <span class="auth-brand-name">{{ config('app.name') }}</span>
        </a>

        @yield('content')
    </div>

    <script>
        // Toggle visibilidade da senha
        function initPasswordToggles() {
            document.querySelectorAll('.input-toggle-password').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    var input = this.closest('.input-wrapper').querySelector('input');
                    var isText = input.type === 'text';
                    input.type = isText ? 'password' : 'text';

                    this.querySelector('.icon-eye').style.display     = isText ? 'block' : 'none';
                    this.querySelector('.icon-eye-off').style.display = isText ? 'none'  : 'block';
                });
            });
        }

        // Loading state nos formulários
        function initFormLoading() {
            document.querySelectorAll('form').forEach(function(form) {
                form.addEventListener('submit', function() {
                    var btn = this.querySelector('[data-loading]');
                    if (btn) btn.classList.add('loading');
                });
            });
        }

        // Indicador de força da senha
        function initPasswordStrength() {
            var input = document.getElementById('password-strength-input');
            if (!input) return;

            var segments = document.querySelectorAll('.strength-segment');
            var label    = document.querySelector('.password-strength-label');

            var levels = [
                { max: 0,  text: '',          cls: '' },
                { max: 1,  text: 'Muito fraca', cls: 'active-weak' },
                { max: 2,  text: 'Fraca',       cls: 'active-weak' },
                { max: 3,  text: 'Razoável',    cls: 'active-fair' },
                { max: 4,  text: 'Boa',         cls: 'active-good' },
                { max: 5,  text: 'Forte',       cls: 'active-strong' },
            ];

            function getScore(val) {
                var score = 0;
                if (val.length >= 8)  score++;
                if (val.length >= 12) score++;
                if (/[A-Z]/.test(val)) score++;
                if (/[0-9]/.test(val)) score++;
                if (/[^A-Za-z0-9]/.test(val)) score++;
                return score;
            }

            input.addEventListener('input', function() {
                var score = this.value ? getScore(this.value) : 0;
                var level = levels[score] || levels[levels.length - 1];

                segments.forEach(function(seg, i) {
                    seg.className = 'strength-segment';
                    if (i < score) seg.classList.add(level.cls);
                });

                if (label) label.textContent = level.text;
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            initPasswordToggles();
            initFormLoading();
            initPasswordStrength();
        });
    </script>

    @stack('scripts')
</body>
</html>

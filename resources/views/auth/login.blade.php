<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate, max-age=0">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title>Panda Naicha — Login</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        :root {
            --c-fog:   #D1DDDB;
            --c-sky:   #85B8CB;
            --c-ocean: #1D6A96;
            --c-deep:  #283B42;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--c-deep);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .login-wrapper {
            width: 100%;
            max-width: 400px;
            padding: 1rem;
        }

        /* ── Logo area ─────────────────────────────── */
        .logo-area {
            text-align: center;
            margin-bottom: 1.8rem;
        }

        .logo-area .logo-icon {
            width: 100px;
            height: 100px;
            object-fit: contain;
            display: block;
            margin: 0 auto .75rem;
            border-radius: 50%;
            background: #fff;
            padding: 10px;
            box-shadow: 0 6px 24px rgba(0,0,0,.25);
        }

        .logo-area h1 {
            color: var(--c-fog);
            font-size: 1.4rem;
            font-weight: 700;
            margin-bottom: .2rem;
        }

        .logo-area p {
            color: var(--c-sky);
            font-size: .82rem;
            font-weight: 300;
            letter-spacing: .04em;
        }

        /* ── Card ──────────────────────────────────── */
        .card {
            background: var(--c-fog);
            padding: 2rem;
            border-radius: 16px;
            border: 1px solid rgba(133,184,203,.25);
            box-shadow: 0 20px 50px rgba(0,0,0,.3);
        }

        /* ── Form elements ─────────────────────────── */
        .form-group { margin-bottom: 1.2rem; }

        label {
            display: block;
            color: var(--c-deep);
            font-size: .72rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .07em;
            margin-bottom: .4rem;
        }

        input {
            width: 100%;
            padding: .7rem 1rem;
            background: #fff;
            border: 1.5px solid rgba(133,184,203,.4);
            border-radius: 9px;
            color: var(--c-deep);
            font-family: 'DM Sans', sans-serif;
            font-size: .93rem;
            transition: border-color .15s, box-shadow .15s;
        }

        input::placeholder { color: #9ab5ba; }

        input:focus {
            outline: none;
            border-color: var(--c-ocean);
            box-shadow: 0 0 0 3px rgba(29,106,150,.15);
        }

        input.input-error { border-color: #d05252; }

        /* ── Remember / forgot ─────────────────────── */
        .remember-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
        }

        .remember-row label {
            display: flex;
            align-items: center;
            gap: .4rem;
            text-transform: none;
            font-size: .8rem;
            color: #4a6068;
            cursor: pointer;
            margin-bottom: 0;
            font-weight: 400;
        }

        .remember-row input[type=checkbox] {
            width: 16px;
            height: 16px;
            accent-color: var(--c-ocean);
            cursor: pointer;
        }

        .forgot-link {
            color: var(--c-ocean);
            font-size: .8rem;
            text-decoration: none;
            font-weight: 500;
        }

        .forgot-link:hover { text-decoration: underline; color: var(--c-deep); }

        /* ── Submit button ─────────────────────────── */
        .btn-submit {
            width: 100%;
            padding: .8rem;
            background: var(--c-ocean);
            color: #fff;
            border: none;
            border-radius: 9px;
            font-family: 'DM Sans', sans-serif;
            font-size: .97rem;
            font-weight: 600;
            cursor: pointer;
            transition: background .15s, transform .1s, box-shadow .15s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: .5rem;
        }

        .btn-submit:hover {
            background: var(--c-deep);
            box-shadow: 0 6px 18px rgba(40,59,66,.3);
        }

        .btn-submit:active { transform: scale(.98); }

        .btn-submit:disabled {
            background: var(--c-sky);
            color: rgba(255,255,255,.6);
            cursor: not-allowed;
            box-shadow: none;
        }

        /* ── Alerts ────────────────────────────────── */
        .alert-error {
            background: rgba(208,82,82,.1);
            border: 1px solid rgba(208,82,82,.25);
            color: #b03a3a;
            border-radius: 9px;
            padding: .75rem 1rem;
            font-size: .84rem;
            margin-bottom: 1.2rem;
            display: flex;
            align-items: flex-start;
            gap: .5rem;
        }

        .alert-success {
            background: rgba(29,106,150,.1);
            border: 1px solid rgba(29,106,150,.25);
            color: var(--c-ocean);
            border-radius: 9px;
            padding: .75rem 1rem;
            font-size: .84rem;
            margin-bottom: 1.2rem;
            display: flex;
            align-items: center;
            gap: .5rem;
        }

        /* ── Password wrapper ──────────────────────── */
        .password-wrapper { position: relative; }

        .toggle-pwd {
            position: absolute;
            right: 11px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: var(--c-sky);
            font-size: 1rem;
            padding: 0;
            line-height: 1;
            transition: color .15s;
        }

        .toggle-pwd:hover { color: var(--c-ocean); }

        /* ── Spinner ───────────────────────────────── */
        .spinner {
            display: none;
            width: 18px;
            height: 18px;
            border: 2px solid rgba(255,255,255,.35);
            border-top-color: #fff;
            border-radius: 50%;
            animation: spin .65s linear infinite;
        }

        @keyframes spin { to { transform: rotate(360deg); } }

        /* ── Back to home ──────────────────────────── */
        .btn-back-home {
            position: fixed;
            top: 20px;
            left: 20px;
            display: inline-flex;
            align-items: center;
            gap: .45rem;
            background: rgba(255,255,255,.08);
            color: var(--c-fog);
            text-decoration: none;
            padding: .55rem 1.1rem;
            border-radius: 24px;
            font-size: .85rem;
            font-weight: 500;
            border: 1px solid rgba(133,184,203,.25);
            transition: all .2s;
            z-index: 10;
            backdrop-filter: blur(6px);
        }

        .btn-back-home:hover {
            background: var(--c-ocean);
            color: #fff;
            border-color: var(--c-ocean);
            transform: translateX(-2px);
        }

        .btn-back-home i { font-size: 1rem; }

        /* ── Responsive ────────────────────────────── */
        @media (max-width: 768px) {
            .login-wrapper { max-width: 350px; padding: .8rem; }
            .logo-area .logo-icon { width: 80px; height: 80px; }
            .logo-area h1 { font-size: 1.2rem; }
            .card { padding: 1.5rem; }
        }

        @media (max-width: 480px) {
            .login-wrapper { max-width: 100%; padding: .6rem; }
            .logo-area .logo-icon { width: 65px; height: 65px; }
            .logo-area h1 { font-size: 1rem; }
            .card { padding: 1.25rem; }
        }
    </style>
</head>
<body>
    <a href="{{ route('home') }}" class="btn-back-home">
        <i class="bi bi-arrow-left"></i> Volver al inicio
    </a>

    <div class="login-wrapper">

        <div class="logo-area">
            <img src="{{ asset('image/logo.png') }}" alt="Logo" class="logo-icon"/>
            <h1>Panda Naicha</h1>
            <p>Sistema de Gestión de Ventas</p>
        </div>

        <div class="card">

            @if ($errors->any())
                <div class="alert-error">
                    <i class="bi bi-exclamation-circle-fill" style="flex-shrink:0;margin-top:1px;"></i>
                    <div>
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                </div>
            @endif

            @if (session('success'))
                <div class="alert-success">
                    <i class="bi bi-check-circle-fill" style="flex-shrink:0;"></i>
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" id="loginForm" novalidate>
                @csrf

                <div class="form-group">
                    <label for="username">Usuario</label>
                    <input
                        type="text"
                        name="username"
                        id="username"
                        value="{{ old('username') }}"
                        placeholder="Tu nombre de usuario"
                        required
                        autofocus
                        autocomplete="username"
                        class="{{ $errors->any() ? 'input-error' : '' }}"
                    >
                </div>

                <div class="form-group">
                    <label for="password">Contraseña</label>
                    <div class="password-wrapper">
                        <input
                            type="password"
                            name="password"
                            id="password"
                            placeholder="Tu contraseña"
                            required
                            autocomplete="current-password"
                            style="padding-right: 2.4rem;"
                            class="{{ $errors->any() ? 'input-error' : '' }}"
                        >
                        <button type="button" class="toggle-pwd" id="toggleBtn" title="Mostrar/ocultar contraseña" aria-label="Mostrar contraseña">
                            <i class="bi bi-eye" id="eyeIcon"></i>
                        </button>
                    </div>
                </div>

                <div class="remember-row">
                    <label>
                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                        Mantener sesión iniciada
                    </label>
                    <a href="{{ route('password.request') }}" class="forgot-link">
                        ¿Olvidaste tu contraseña?
                    </a>
                </div>

                <button type="submit" class="btn-submit" id="submitBtn">
                    <span id="btnText">Ingresar al sistema</span>
                    <div class="spinner" id="spinner"></div>
                </button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        if (window.history && window.history.replaceState) {
            window.history.replaceState(null, document.title, window.location.href);
        }

        window.addEventListener('popstate', function () {
            window.location.replace('{{ route('login') }}');
        });

        document.getElementById('toggleBtn').addEventListener('click', function () {
            const input = document.getElementById('password');
            const icon  = document.getElementById('eyeIcon');
            if (input.type === 'password') {
                input.type = 'text';
                icon.className = 'bi bi-eye-slash';
            } else {
                input.type = 'password';
                icon.className = 'bi bi-eye';
            }
        });

        document.getElementById('loginForm').addEventListener('submit', function (e) {
            const username = document.getElementById('username').value.trim();
            const password = document.getElementById('password').value;

            if (!username || !password) {
                e.preventDefault();
                return;
            }

            const btn     = document.getElementById('submitBtn');
            const btnText = document.getElementById('btnText');
            const spinner = document.getElementById('spinner');

            btn.disabled          = true;
            btnText.textContent   = 'Verificando...';
            spinner.style.display = 'block';

            setTimeout(function () {
                btn.disabled          = false;
                btnText.textContent   = 'Ingresar al sistema';
                spinner.style.display = 'none';
            }, 3000);
        });
    </script>
</body>
</html>
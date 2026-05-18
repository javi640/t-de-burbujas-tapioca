<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- Evitar que el navegador guarde esta página en caché --}}
    <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate, max-age=0">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title>Panda Naicha — Login</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Segoe UI', sans-serif;
            background: #0b0f1a;
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

        .logo-area {
            text-align: center;
            margin-bottom: 2rem;
        }

        .logo-area .logo-icon {
            width: 100px;
            height: 100px;
            object-fit: contain;
            display: block;
            margin: 0 auto .5rem;
        }

        .logo-area h1 {
            color: #f0f4ff;
            font-size: 1.4rem;
            font-weight: 700;
        }

        .logo-area p {
            color: #8b9abf;
            font-size: .85rem;
            margin-top: .25rem;
        }

        .card {
            background: #1a2235;
            padding: 2rem;
            border-radius: 16px;
            border: 1px solid #2a3548;
            box-shadow: 0 20px 60px rgba(0,0,0,.4);
        }

        .form-group {
            margin-bottom: 1.25rem;
        }

        label {
            display: block;
            color: #8b9abf;
            font-size: .75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .06em;
            margin-bottom: .4rem;
        }

        input {
            width: 100%;
            padding: .7rem 1rem;
            background: #0b0f1a;
            border: 1px solid #2a3548;
            border-radius: 8px;
            color: #f0f4ff;
            font-size: .95rem;
            transition: border .15s, box-shadow .15s;
        }

        input:focus {
            outline: none;
            border-color: #4f8ef7;
            box-shadow: 0 0 0 3px rgba(79,142,247,.12);
        }

        input.input-error {
            border-color: #ef4444;
        }

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
            color: #8b9abf;
            cursor: pointer;
            margin-bottom: 0;
        }

        .remember-row input[type=checkbox] {
            width: 16px;
            height: 16px;
            accent-color: #4f8ef7;
            cursor: pointer;
        }

        .forgot-link {
            color: #4f8ef7;
            font-size: .8rem;
            text-decoration: none;
        }

        .forgot-link:hover { text-decoration: underline; }

        .btn-submit {
            width: 100%;
            padding: .8rem;
            background: #4f8ef7;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            cursor: pointer;
            font-weight: 600;
            font-family: inherit;
            transition: background .15s, transform .1s;
        }

        .btn-submit:hover { background: #3a7de8; }
        .btn-submit:active { transform: scale(.98); }

        .btn-submit:disabled {
            background: #2a3548;
            color: #8b9abf;
            cursor: not-allowed;
        }

        .alert-error {
            background: rgba(239,68,68,.1);
            border: 1px solid rgba(239,68,68,.2);
            color: #f87171;
            border-radius: 8px;
            padding: .75rem 1rem;
            font-size: .85rem;
            margin-bottom: 1.25rem;
            display: flex;
            align-items: flex-start;
            gap: .5rem;
        }

        .alert-success {
            background: rgba(34,197,94,.1);
            border: 1px solid rgba(34,197,94,.2);
            color: #4ade80;
            border-radius: 8px;
            padding: .75rem 1rem;
            font-size: .85rem;
            margin-bottom: 1.25rem;
        }

        .divider {
            border: none;
            border-top: 1px solid #2a3548;
            margin: 1.5rem 0;
        }

        .password-wrapper {
            position: relative;
        }

        .toggle-pwd {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: #8b9abf;
            font-size: .9rem;
            padding: 0;
        }

        .toggle-pwd:hover { color: #f0f4ff; }

        .spinner {
            display: none;
            width: 18px;
            height: 18px;
            border: 2px solid rgba(255,255,255,.3);
            border-top-color: #fff;
            border-radius: 50%;
            animation: spin .7s linear infinite;
            margin: 0 auto;
        }

        @keyframes spin { to { transform: rotate(360deg); } }

        /* ── Media Queries para Responsividad ─── */
        @media (max-width: 768px) {
            .login-wrapper { max-width: 350px; padding: .8rem; }
            .logo-area { margin-bottom: 1.5rem; }
            .logo-area .logo-icon { width: 80px; height: 80px; }
            .logo-area h1 { font-size: 1.2rem; }
            .logo-area p { font-size: .8rem; }
            .card { padding: 1.5rem; }
        }

        @media (max-width: 480px) {
            .login-wrapper { max-width: 100%; padding: .6rem; }
            .logo-area { margin-bottom: 1.25rem; }
            .logo-area .logo-icon { width: 60px; height: 60px; }
            .logo-area h1 { font-size: 1rem; }
            .logo-area p { font-size: .75rem; }
            .card { padding: 1.25rem; }
            .form-group { margin-bottom: 1rem; }
            label { font-size: .7rem; }
            input { padding: .6rem .8rem; font-size: .9rem; }
            .btn-submit { padding: .7rem; font-size: .95rem; }
        }
    </style>
</head>
<body>
    <div class="login-wrapper">
        <div class="logo-area">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="logo-icon"/>
            <h1>Panda Naicha</h1>
            <p>Sistema de Gestión de Ventas</p>
        </div>

        <div class="card">
            {{-- Mensajes de error --}}
            @if ($errors->any())
                <div class="alert-error">
                    <span>✕</span>
                    <div>
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Mensaje de éxito (ej: contraseña restablecida) --}}
            @if (session('success'))
                <div class="alert-success">✓ {{ session('success') }}</div>
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
                            class="{{ $errors->any() ? 'input-error' : '' }}"
                        >
                        <button type="button" class="toggle-pwd" onclick="togglePwd()" id="toggleBtn" title="Mostrar/ocultar contraseña">
                            👁
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

    <script>
        // ── Prevenir regreso con botón Atrás después del logout ──────
        // Reemplaza la entrada del historial actual para que al presionar
        // "atrás" desde cualquier página autenticada no pueda regresar.
        if (window.history && window.history.replaceState) {
            window.history.replaceState(null, document.title, window.location.href);
        }

        // Detecta si el usuario intenta navegar hacia atrás y lo
        // redirige de vuelta al login.
        window.addEventListener('popstate', function () {
            window.location.replace('{{ route('login') }}');
        });

        // ── Toggle visibilidad de contraseña ─────────────────────────
        function togglePwd() {
            const input = document.getElementById('password');
            const btn   = document.getElementById('toggleBtn');
            input.type  = input.type === 'password' ? 'text' : 'password';
            btn.textContent = input.type === 'password' ? '👁' : '🙈';
        }

        // ── Spinner al enviar el formulario ──────────────────────────
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

            btn.disabled        = true;
            btnText.textContent = 'Verificando...';
            spinner.style.display = 'block';

            // Re-habilitar si el servidor devuelve error (3 segundos)
            setTimeout(() => {
                btn.disabled        = false;
                btnText.textContent = 'Ingresar al sistema';
                spinner.style.display = 'none';
            }, 3000);
        });
    </script>
</body>
</html>
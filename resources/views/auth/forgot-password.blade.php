<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña — Panda Naicha</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
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
            color: var(--c-deep);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .card {
            background: var(--c-fog);
            padding: 2.5rem;
            border-radius: 16px;
            width: 100%;
            max-width: 420px;
            border: 1px solid rgba(133,184,203,.25);
            box-shadow: 0 20px 50px rgba(0,0,0,.3);
        }

        .logo-icon {
            width: 100px;
            height: 100px;
            object-fit: contain;
            display: block;
            margin: 0 auto 1rem;
            border-radius: 50%;
            background: #fff;
            padding: 10px;
            box-shadow: 0 4px 16px rgba(0,0,0,.12);
        }

        h1 {
            font-size: 1.4rem;
            font-weight: 700;
            text-align: center;
            margin-bottom: .5rem;
            color: var(--c-deep);
        }

        p {
            color: var(--c-ocean);
            text-align: center;
            font-size: .875rem;
            margin-bottom: 1.75rem;
            line-height: 1.6;
            font-weight: 400;
        }

        label {
            display: block;
            font-size: .72rem;
            font-weight: 600;
            color: var(--c-deep);
            margin-bottom: .4rem;
            text-transform: uppercase;
            letter-spacing: .06em;
        }

        input {
            width: 100%;
            padding: .7rem 1rem;
            background: #fff;
            border: 1.5px solid rgba(133,184,203,.4);
            border-radius: 9px;
            color: var(--c-deep);
            font-size: .9rem;
            font-family: inherit;
            transition: border .15s, box-shadow .15s;
            margin-bottom: 1.25rem;
        }

        input::placeholder { color: #9ab5ba; }

        input:focus {
            outline: none;
            border-color: var(--c-ocean);
            box-shadow: 0 0 0 3px rgba(29,106,150,.15);
        }

        .btn {
            width: 100%;
            padding: .78rem;
            background: var(--c-ocean);
            color: #fff;
            border: none;
            border-radius: 9px;
            font-size: .97rem;
            cursor: pointer;
            font-weight: 600;
            font-family: inherit;
            transition: background .15s, box-shadow .15s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: .5rem;
        }

        .btn:hover {
            background: var(--c-deep);
            box-shadow: 0 6px 18px rgba(40,59,66,.25);
        }

        .back {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: .35rem;
            margin-top: 1.25rem;
            color: #5d7d84;
            font-size: .85rem;
            text-decoration: none;
            transition: color .15s;
        }

        .back:hover { color: var(--c-ocean); }

        .alert {
            padding: .8rem 1rem;
            border-radius: 9px;
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
        }

        .alert-error {
            background: rgba(192,57,43,.08);
            border: 1px solid rgba(192,57,43,.2);
            color: #a0291e;
        }

        @media (max-width: 768px) {
            .card { padding: 2rem; max-width: 350px; }
            .logo-icon { width: 80px; height: 80px; }
            h1 { font-size: 1.2rem; }
            p { font-size: .8rem; margin-bottom: 1.5rem; }
        }

        @media (max-width: 480px) {
            .card { padding: 1.5rem; max-width: 100%; margin: .75rem; }
            .logo-icon { width: 65px; height: 65px; }
            h1 { font-size: 1rem; }
            p { font-size: .78rem; margin-bottom: 1.25rem; }
        }
    </style>
</head>
<body>
    <div class="card">
        <img src="{{ asset('image/logo.png') }}" alt="Logo" class="logo-icon"/>
        <h1>Recuperar Contraseña</h1>
        <p>Ingresa tu email y te enviaremos un enlace para restablecer tu contraseña.</p>

        @if(session('status'))
            <div class="alert alert-success">
                <i class="bi bi-check-circle-fill" style="flex-shrink:0;margin-top:1px;"></i>
                <span>{{ session('status') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-error">
                <i class="bi bi-exclamation-circle-fill" style="flex-shrink:0;margin-top:1px;"></i>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <label>Correo Electrónico</label>
            <input type="email" name="email" value="{{ old('email') }}" placeholder="tucorreo@ejemplo.com" required autofocus>
            <button type="submit" class="btn">
                <i class="bi bi-send"></i>
                Enviar Enlace de Recuperación
            </button>
        </form>

        <a href="{{ route('login') }}" class="back">
            <i class="bi bi-arrow-left"></i>
            Volver al login
        </a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
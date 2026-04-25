<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña — Panda Naicha</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
    <style>
        *{margin:0;padding:0;box-sizing:border-box}
        body{font-family:'DM Sans',sans-serif;background:#0b0f1a;color:#f0f4ff;min-height:100vh;display:flex;align-items:center;justify-content:center}
        .card{background:#1a2235;padding:2.5rem;border-radius:16px;width:100%;max-width:420px;border:1px solid #2a3548;box-shadow:0 20px 60px rgba(0,0,0,.5)}
        h1{font-size:1.4rem;font-weight:700;text-align:center;margin-bottom:.5rem}
        p{color:#8b9abf;text-align:center;font-size:.875rem;margin-bottom:1.75rem;line-height:1.6}
        label{display:block;font-size:.75rem;font-weight:600;color:#8b9abf;margin-bottom:.4rem;text-transform:uppercase;letter-spacing:.05em}
        input{width:100%;padding:.7rem 1rem;background:#0b0f1a;border:1px solid #2a3548;border-radius:8px;color:#f0f4ff;font-size:.9rem;font-family:inherit;transition:border .15s;margin-bottom:1.25rem}
        input:focus{outline:none;border-color:#4f8ef7;box-shadow:0 0 0 3px rgba(79,142,247,.12)}
        .btn{width:100%;padding:.75rem;background:#4f8ef7;color:#fff;border:none;border-radius:8px;font-size:1rem;cursor:pointer;font-weight:600;font-family:inherit;transition:background .15s}
        .btn:hover{background:#3a7de8}
        .back{display:block;text-align:center;margin-top:1.25rem;color:#8b9abf;font-size:.85rem;text-decoration:none}
        .back:hover{color:#f0f4ff}
        .alert{padding:.875rem 1rem;border-radius:8px;font-size:.875rem;margin-bottom:1.25rem}
        .alert-success{background:rgba(34,197,94,.1);border:1px solid rgba(34,197,94,.2);color:#4ade80}
        .alert-error{background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.2);color:#f87171}
        .logo{text-align:center;font-size:2.5rem;margin-bottom:1rem}
    </style>
</head>
<body>
    <div class="card">
        <div class="logo">🐼</div>
        <h1>Recuperar Contraseña</h1>
        <p>Ingresa tu email y te enviaremos un enlace para restablecer tu contraseña.</p>

        @if(session('status'))
            <div class="alert alert-success">✓ {{ session('status') }}</div>
        @endif

        @if($errors->any())
            <div class="alert alert-error">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <label>Correo Electrónico</label>
            <input type="email" name="email" value="{{ old('email') }}" placeholder="tucorreo@ejemplo.com" required autofocus>
            <button type="submit" class="btn">Enviar Enlace de Recuperación</button>
        </form>

        <a href="{{ route('login') }}" class="back">← Volver al login</a>
    </div>
</body>
</html>
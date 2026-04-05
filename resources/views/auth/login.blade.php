<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panda Naicha — Login</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: sans-serif;
            background: #0f172a;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .card {
            background: #1e293b;
            padding: 2rem;
            border-radius: 12px;
            width: 100%;
            max-width: 380px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.4);
        }
        h1 { color: #f8fafc; text-align: center; margin-bottom: 0.5rem; }
        p  { color: #94a3b8; text-align: center; margin-bottom: 1.5rem; font-size: 0.9rem; }
        label { display: block; color: #cbd5e1; font-size: 0.85rem; margin-bottom: 0.3rem; }
        input {
            width: 100%;
            padding: 0.65rem 1rem;
            background: #0f172a;
            border: 1px solid #334155;
            border-radius: 8px;
            color: #f1f5f9;
            font-size: 1rem;
            margin-bottom: 1rem;
        }
        input:focus { outline: none; border-color: #6366f1; }
        button {
            width: 100%;
            padding: 0.75rem;
            background: #6366f1;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            cursor: pointer;
            font-weight: 600;
        }
        button:hover { background: #4f46e5; }
        .error {
            background: #450a0a;
            color: #fca5a5;
            padding: 0.75rem 1rem;
            border-radius: 8px;
            font-size: 0.85rem;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <div class="card">
        <h1>🐼 Panda Naicha</h1>
        <p>Sistema de Gestión de Ventas</p>

        @if ($errors->any())
            <div class="error">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <label>Usuario</label>
            <input type="text" name="username"
                   value="{{ old('username') }}"
                   placeholder="Ingresa tu usuario" required autofocus>

            <label>Contraseña</label>
            <input type="password" name="password"
                   placeholder="Ingresa tu contraseña" required>

            <button type="submit">Ingresar</button>
        </form>
    </div>
</body>
</html>
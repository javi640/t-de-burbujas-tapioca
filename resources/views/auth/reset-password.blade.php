<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva Contraseña — Panda Naicha</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background: #0b0f1a;
            color: #f0f4ff;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .card {
            background: #1a2235;
            padding: 2.5rem;
            border-radius: 16px;
            width: 100%;
            max-width: 420px;
            border: 1px solid #2a3548;
            box-shadow: 0 20px 60px rgba(0, 0, 0, .5);
        }

        h1 {
            font-size: 1.4rem;
            font-weight: 700;
            text-align: center;
            margin-bottom: .5rem;
        }

        p {
            color: #8b9abf;
            text-align: center;
            font-size: .875rem;
            margin-bottom: 1.75rem;
        }

        label {
            display: block;
            font-size: .75rem;
            font-weight: 600;
            color: #8b9abf;
            margin-bottom: .4rem;
            text-transform: uppercase;
            letter-spacing: .05em;
        }

        input {
            width: 100%;
            padding: .7rem 1rem;
            background: #0b0f1a;
            border: 1px solid #2a3548;
            border-radius: 8px;
            color: #f0f4ff;
            font-size: .9rem;
            font-family: inherit;
            transition: border .15s;
            margin-bottom: 1.25rem;
        }

        input:focus {
            outline: none;
            border-color: #4f8ef7;
            box-shadow: 0 0 0 3px rgba(79, 142, 247, .12);
        }

        .btn {
            width: 100%;
            padding: .75rem;
            background: #4f8ef7;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            cursor: pointer;
            font-weight: 600;
            font-family: inherit;
        }

        .btn:hover {
            background: #3a7de8;
        }

        .err {
            font-size: .75rem;
            color: #f87171;
            margin-top: -.75rem;
            margin-bottom: .75rem;
        }

        .logo {
            text-align: center;
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="logo">🐼</div>
        <h1>Nueva Contraseña</h1>
        <p>Ingresa y confirma tu nueva contraseña.</p>

        <form method="POST" action="{{ route('password.update') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <label>Email</label>
            <input type="email" name="email" value="{{ old('email', $email ?? '') }}" required>
            @error('email')<div class="err">{{ $message }}</div>@enderror

            <label>Nueva Contraseña</label>
            <input type="password" name="password" required>
            @error('password')<div class="err">{{ $message }}</div>@enderror

            <label>Confirmar Contraseña</label>
            <input type="password" name="password_confirmation" required>

            <button type="submit" class="btn">Restablecer Contraseña</button>
        </form>
    </div>
</body>
</html>
@extends('layouts.app')
@section('title', 'Nuevo Usuario')

@section('sidebar-nav')
    <span class="nav-section-label">Principal</span>
    <a href="{{ route('admin.dashboard') }}" class="nav-item">
        <span class="nav-icon" style="display:inline-flex;align-items:center;"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg></span> Dashboard
    </a>

    <span class="nav-section-label">Gestión</span>
    <a href="{{ route('admin.users.index') }}" class="nav-item active">
        <span class="nav-icon" style="display:inline-flex;align-items:center;"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg></span> Usuarios
    </a>
    <a href="{{ route('admin.sales.index') }}" class="nav-item">
        <span class="nav-icon" style="display:inline-flex;align-items:center;"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg></span> Historial Ventas
    </a>
    <a href="{{ route('admin.shifts.index') }}" class="nav-item">
        <span class="nav-icon" style="display:inline-flex;align-items:center;"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg></span> Turnos
    </a>
    <span class="nav-section-label">Reportes</span>
    <a href="{{ route('admin.reports.daily') }}" class="nav-item">
        <span class="nav-icon" style="display:inline-flex;align-items:center;"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg></span> Cierre Diario
    </a>
    <a href="{{ route('admin.audit.index') }}" class="nav-item">
        <span class="nav-icon" style="display:inline-flex;align-items:center;"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg></span> Auditoría
    </a>
@endsection

@section('page-title', 'Nuevo Usuario')
@section('page-subtitle', 'Agregar personal al sistema')

@section('content')
<div class="card" style="max-width: 640px;">

    <form
        method="POST"
        action="{{ route('admin.users.store') }}"
        id="createUserForm"
        novalidate
    >
        @csrf

        {{-- Nombre y Usuario --}}
        <div class="form-row">
            <div class="form-group">
                <label>Nombre Completo</label>
                <input
                    type="text"
                    name="name"
                    value="{{ old('name') }}"
                    placeholder="Ej: Juan Pérez"
                    required
                    maxlength="100"
                >

                @error('name')
                    <div class="form-error">✕ {{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label>
                    Nombre de Usuario
                    <span class="text-muted" style="font-size:.75rem;">
                        (solo letras, números y _)
                    </span>
                </label>

                <input
                    type="text"
                    name="username"
                    value="{{ old('username') }}"
                    placeholder="Ej: cajero2"
                    required
                    maxlength="50"
                    pattern="[a-zA-Z0-9_]+"
                    title="Solo letras, números y guión bajo"
                >

                @error('username')
                    <div class="form-error">✕ {{ $message }}</div>
                @enderror

                <div
                    class="text-xs text-muted"
                    style="margin-top: .3rem;"
                >
                    Solo letras, números y _ (sin espacios)
                </div>
            </div>
        </div>

        {{-- Email --}}
        <div class="form-group">
            <label>Correo Gmail</label>

            <div style="position: relative;">
                <input
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    placeholder="ejemplo@gmail.com"
                    required
                    id="emailInput"
                >

                <span
                    id="emailIndicator"
                    style="position:absolute; right:12px; top:50%; transform:translateY(-50%); font-size:1.1rem; display:none;"
                ></span>
            </div>

            @error('email')
                <div class="form-error">✕ {{ $message }}</div>
            @enderror

            <div
                class="text-xs text-muted"
                style="margin-top: .3rem;"
            >
                Requerido: debe ser una cuenta @gmail.com
            </div>
        </div>

        {{-- Contraseñas --}}
        <div class="form-row">
            <div class="form-group">
                <label>Contraseña</label>

                <div style="position: relative;">
                    <input
                        type="password"
                        name="password"
                        id="password"
                        placeholder="Mínimo 8 caracteres"
                        required
                        minlength="8"
                    >

                    <button
                        type="button"
                        onclick="togglePassword('password')"
                        style="position:absolute;right:10px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:var(--muted);font-size:.9rem;"
                    >
                        👁
                    </button>
                </div>

                @error('password')
                    <div class="form-error">✕ {{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label>Confirmar Contraseña</label>

                <div style="position: relative;">
                    <input
                        type="password"
                        name="password_confirmation"
                        id="password_confirmation"
                        placeholder="Repite la contraseña"
                        required
                        minlength="8"
                    >

                    <button
                        type="button"
                        onclick="togglePassword('password_confirmation')"
                        style="position:absolute;right:10px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:var(--muted);font-size:.9rem;"
                    >
                        👁
                    </button>
                </div>

                <div
                    id="passwordMatchMsg"
                    style="font-size:.75rem; margin-top:.3rem;"
                ></div>
            </div>
        </div>

        {{-- Indicador de fortaleza de contraseña --}}
        <div style="margin-top:-.75rem; margin-bottom:1rem;">
            <div
                style="display:flex; gap:4px; margin-bottom:.25rem;"
                id="strengthBars"
            >
                <div
                    id="bar1"
                    style="height:4px; flex:1; border-radius:2px; background:var(--border);"
                ></div>

                <div
                    id="bar2"
                    style="height:4px; flex:1; border-radius:2px; background:var(--border);"
                ></div>

                <div
                    id="bar3"
                    style="height:4px; flex:1; border-radius:2px; background:var(--border);"
                ></div>

                <div
                    id="bar4"
                    style="height:4px; flex:1; border-radius:2px; background:var(--border);"
                ></div>
            </div>

            <div
                class="text-xs text-muted"
                id="strengthLabel"
            ></div>
        </div>

        {{-- Rol y Sucursal --}}
        <div class="form-row">
            <div class="form-group">
                <label>Rol</label>

                <select name="role_id" required>
                    <option value="">— Seleccionar rol —</option>

                    @foreach($roles as $role)
                        <option
                            value="{{ $role->id }}"
                            {{ old('role_id') == $role->id ? 'selected' : '' }}
                        >
                            {{ $role->name }}
                        </option>
                    @endforeach
                </select>

                @error('role_id')
                    <div class="form-error">✕ {{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label>Sucursal</label>

                <select name="branch_id" required>
                    <option value="">— Seleccionar sucursal —</option>

                    @foreach($branches as $branch)
                        <option
                            value="{{ $branch->id }}"
                            {{ old('branch_id') == $branch->id ? 'selected' : '' }}
                        >
                            {{ $branch->name }}
                        </option>
                    @endforeach
                </select>

                @error('branch_id')
                    <div class="form-error">✕ {{ $message }}</div>
                @enderror
            </div>
        </div>

        <hr class="divider">

        <div class="flex gap-3">
            <button
                type="submit"
                class="btn btn-primary"
                id="submitBtn"
            >
                Crear Usuario
            </button>

            <a
                href="{{ route('admin.users.index') }}"
                class="btn btn-ghost"
            >
                Cancelar
            </a>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
// ── Validación Gmail en tiempo real ─────────────────────────
const emailInput = document.getElementById('emailInput');
const emailIndicator = document.getElementById('emailIndicator');

emailInput.addEventListener('input', function () {
    const value = this.value.trim();

    if (!value) {
        emailIndicator.style.display = 'none';
        this.style.borderColor = 'var(--border)';
        return;
    }

    const isGmail = /^[^\s@]+@gmail\.com$/i.test(value);

    emailIndicator.style.display = 'block';

    if (isGmail) {
        emailIndicator.textContent = '✓';
        emailIndicator.style.color = 'var(--success)';
        this.style.borderColor = 'var(--success)';
    } else {
        emailIndicator.textContent = '✕';
        emailIndicator.style.color = 'var(--danger)';
        this.style.borderColor = 'var(--danger)';
    }
});

// ── Fortaleza de contraseña ──────────────────────────────────
const passwordInput = document.getElementById('password');
const bars = [
    document.getElementById('bar1'),
    document.getElementById('bar2'),
    document.getElementById('bar3'),
    document.getElementById('bar4')
];
const strengthLabel = document.getElementById('strengthLabel');

passwordInput.addEventListener('input', function () {
    const val = this.value;
    let score = 0;

    if (val.length >= 8) score++;
    if (/[A-Z]/.test(val)) score++;
    if (/[0-9]/.test(val)) score++;
    if (/[^a-zA-Z0-9]/.test(val)) score++;

    const colors = [
        'var(--danger)',
        '#f59e0b',
        '#3b82f6',
        'var(--success)'
    ];

    const labels = [
        '',
        'Débil',
        'Regular',
        'Buena',
        'Muy segura'
    ];

    bars.forEach((bar, i) => {
        bar.style.background =
            i < score
                ? colors[score - 1]
                : 'var(--border)';
    });

    strengthLabel.textContent =
        val.length ? labels[score] : '';

    strengthLabel.style.color =
        score > 0
            ? colors[score - 1]
            : 'var(--muted)';
});

// ── Validación de coincidencia de contraseñas ────────────────
const confirmInput = document.getElementById('password_confirmation');
const matchMsg = document.getElementById('passwordMatchMsg');

function checkPasswordMatch() {
    if (!confirmInput.value) {
        matchMsg.textContent = '';
        return;
    }

    if (passwordInput.value === confirmInput.value) {
        matchMsg.textContent = '✓ Las contraseñas coinciden';
        matchMsg.style.color = 'var(--success)';
        confirmInput.style.borderColor = 'var(--success)';
    } else {
        matchMsg.textContent = '✕ Las contraseñas no coinciden';
        matchMsg.style.color = 'var(--danger)';
        confirmInput.style.borderColor = 'var(--danger)';
    }
}

confirmInput.addEventListener('input', checkPasswordMatch);
passwordInput.addEventListener('input', checkPasswordMatch);

// ── Toggle visibilidad contraseña ────────────────────────────
function togglePassword(id) {
    const input = document.getElementById(id);
    input.type =
        input.type === 'password'
            ? 'text'
            : 'password';
}

// ── Validación del formulario antes de enviar ────────────────
document
    .getElementById('createUserForm')
    .addEventListener('submit', function (e) {
        const emailVal = emailInput.value.trim();
        const isGmail = /^[^\s@]+@gmail\.com$/i.test(emailVal);

        if (!isGmail) {
            e.preventDefault();
            emailInput.focus();
            emailInput.style.borderColor = 'var(--danger)';

            alert(
                '❌ El correo debe ser una cuenta de Gmail (@gmail.com)'
            );
            return;
        }

        if (passwordInput.value !== confirmInput.value) {
            e.preventDefault();
            confirmInput.focus();

            alert(
                '❌ Las contraseñas no coinciden'
            );
            return;
        }

        if (passwordInput.value.length < 8) {
            e.preventDefault();
            passwordInput.focus();

            alert(
                '❌ La contraseña debe tener al menos 8 caracteres'
            );
            return;
        }
    });
</script>
@endsection
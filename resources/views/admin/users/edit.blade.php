@extends('layouts.app')
@section('title', 'Editar Usuario')

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

@section('page-title', 'Editar Usuario')

@section('page-subtitle')
    Editando: <span class="mono" style="color:var(--accent)">{{ $user->username }}</span>
@endsection

@section('content')
<div style="max-width: 640px;">
    <div class="card">
        <div class="card-header">
            <div class="card-title">Datos del usuario</div>
            <span class="{{ $user->is_active ? 'badge badge-success' : 'badge badge-danger' }}">
                {{ $user->is_active ? 'Activo' : 'Inactivo' }}
            </span>
        </div>

        <form method="POST" action="{{ route('admin.users.update', $user) }}" id="editUserForm" novalidate>
            @csrf @method('PUT')

            {{-- Nombre y Usuario --}}
            <div class="form-row">
                <div class="form-group">
                    <label>Nombre Completo</label>
                    <input
                        type="text"
                        name="name"
                        value="{{ old('name', $user->name) }}"
                        required
                        maxlength="100"
                        pattern="[A-Za-zÀ-ÿ\s]+"
                        title="Solo letras y espacios"
                    >
                    @error('name')
                        <div class="form-error">✕ {{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label>Nombre de Usuario</label>
                    <input
                        type="text"
                        name="username"
                        value="{{ old('username', $user->username) }}"
                        required
                        maxlength="50"
                        pattern="[a-zA-Z0-9_]+"
                    >
                    @error('username')
                        <div class="form-error">✕ {{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- Email --}}
            <div class="form-group">
                <label>Correo Gmail</label>
                <div style="position: relative;">
                    <input
                        type="email"
                        name="email"
                        value="{{ old('email', $user->email) }}"
                        required
                        id="emailInput"
                        placeholder="ejemplo@gmail.com"
                    >
                    <span id="emailIndicator" style="position:absolute; right:12px; top:50%; transform:translateY(-50%); font-size:1.1rem;"></span>
                </div>
                @error('email')
                    <div class="form-error">✕ {{ $message }}</div>
                @enderror
                <div class="text-xs text-muted" style="margin-top:.3rem;">Requerido: debe ser una cuenta @gmail.com</div>
            </div>

            {{-- Contraseñas --}}
            <div style="background: rgba(79,142,247,.04); border: 1px solid rgba(79,142,247,.15); border-radius: 8px; padding: 1rem; margin-bottom: 1.25rem;">
                <div class="text-xs text-muted" style="margin-bottom: .75rem; font-weight:600; text-transform:uppercase; letter-spacing:.05em;">
                    🔒 Cambiar Contraseña (opcional)
                </div>
                <div class="form-row" style="margin-bottom:0;">
                    <div class="form-group" style="margin-bottom:0;">
                        <label>Nueva Contraseña</label>
                        <div style="position: relative;">
                            <input
                                type="password"
                                name="password"
                                id="password"
                                placeholder="Dejar vacío para no cambiar"
                                minlength="8"
                            >
                            <button type="button" onclick="togglePassword('password')" style="position:absolute;right:10px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:var(--muted);">
                                👁
                            </button>
                        </div>
                        @error('password')
                            <div class="form-error">✕ {{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group" style="margin-bottom:0;">
                        <label>Confirmar Nueva Contraseña</label>
                        <div style="position: relative;">
                            <input
                                type="password"
                                name="password_confirmation"
                                id="password_confirmation"
                                placeholder="Repite la contraseña"
                                minlength="8"
                            >
                            <button type="button" onclick="togglePassword('password_confirmation')" style="position:absolute;right:10px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:var(--muted);">
                                👁
                            </button>
                        </div>
                        <div id="passwordMatchMsg" style="font-size:.75rem; margin-top:.3rem;"></div>
                    </div>
                </div>
            </div>

            {{-- Rol y Sucursal --}}
            <div class="form-row">
                <div class="form-group">
                    <label>Rol</label>
                    <select name="role_id" required>
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}" {{ $user->role_id == $role->id ? 'selected' : '' }}>
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
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}" {{ $user->branch_id == $branch->id ? 'selected' : '' }}>
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
                <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-ghost">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
// ── Email indicator al cargar ────────────────────────────────
const emailInput     = document.getElementById('emailInput');
const emailIndicator = document.getElementById('emailIndicator');

function validateEmail(value) {
    const isGmail = /^(?=[^@]*[a-zA-Z])[^\s@]+@gmail\.com$/i.test(value.trim());
    emailIndicator.style.display = value ? 'block' : 'none';
    if (isGmail) {
        emailIndicator.textContent = '✓';
        emailIndicator.style.color = 'var(--success)';
        emailInput.style.borderColor = 'var(--success)';
    } else {
        emailIndicator.textContent = '✕';
        emailIndicator.style.color = 'var(--danger)';
        emailInput.style.borderColor = 'var(--danger)';
    }
}

validateEmail(emailInput.value);
emailInput.addEventListener('input', () => validateEmail(emailInput.value));

// ── Toggle password ──────────────────────────────────────────
function togglePassword(id) {
    const input = document.getElementById(id);
    input.type = input.type === 'password' ? 'text' : 'password';
}

// ── Coincidencia contraseñas ─────────────────────────────────
const passwordInput = document.getElementById('password');
const confirmInput  = document.getElementById('password_confirmation');
const matchMsg      = document.getElementById('passwordMatchMsg');

function checkMatch() {
    if (!confirmInput.value && !passwordInput.value) { matchMsg.textContent = ''; return; }
    if (!confirmInput.value) { matchMsg.textContent = ''; return; }
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

confirmInput.addEventListener('input', checkMatch);
passwordInput.addEventListener('input', checkMatch);

// ── Validación antes de enviar ───────────────────────────────
document.getElementById('editUserForm').addEventListener('submit', function (e) {
    const nameInput = document.querySelector('[name="name"]');
    const nameVal = nameInput.value.trim();
    if (!/^[A-Za-zÀ-ÿ\s]+$/.test(nameVal)) {
        e.preventDefault();
        nameInput.focus();
        alert('❌ El nombre solo puede contener letras y espacios');
        return;
    }

    const emailVal = emailInput.value.trim();
    if (!/^(?=[^@]*[a-zA-Z])[^\s@]+@gmail\.com$/i.test(emailVal)) {
        e.preventDefault();
        emailInput.focus();
        alert('❌ El correo debe ser @gmail.com y contener letras (no solo números)');
        return;
    }

    if (passwordInput.value || confirmInput.value) {
        if (passwordInput.value.length < 8) {
            e.preventDefault();
            passwordInput.focus();
            alert('❌ La contraseña debe tener al menos 8 caracteres');
            return;
        }
        if (passwordInput.value !== confirmInput.value) {
            e.preventDefault();
            confirmInput.focus();
            alert('❌ Las contraseñas no coinciden');
            return;
        }
    }
});
</script>
@endsection
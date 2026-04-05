@extends('layouts.app')
@section('title', 'Nuevo Usuario')

@section('sidebar-nav')
    <span class="nav-section-label">Principal</span>
    <a href="{{ route('admin.dashboard') }}" class="nav-item"><span class="nav-icon">⬡</span> Dashboard</a>
    <span class="nav-section-label">Gestión</span>
    <a href="{{ route('admin.users.index') }}" class="nav-item active"><span class="nav-icon">👥</span> Usuarios</a>
    <a href="{{ route('admin.sales.index') }}" class="nav-item"><span class="nav-icon">🛒</span> Historial Ventas</a>
    <a href="{{ route('admin.shifts.index') }}" class="nav-item"><span class="nav-icon">⏱</span> Turnos</a>
@endsection

@section('page-title', 'Nuevo Usuario')
@section('page-subtitle', 'Agregar personal al sistema')

@section('content')
<div class="card" style="max-width: 600px;">
    <form method="POST" action="{{ route('admin.users.store') }}">
        @csrf
        <div class="form-row">
            <div class="form-group">
                <label>Nombre Completo</label>
                <input type="text" name="name" value="{{ old('name') }}" placeholder="Ej: Juan Pérez" required>
                @error('name')<div class="form-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label>Usuario</label>
                <input type="text" name="username" value="{{ old('username') }}" placeholder="Ej: cajero2" required>
                @error('username')<div class="form-error">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Email (para recuperación)</label>
                <input type="email" name="email" value="{{ old('email') }}" placeholder="correo@ejemplo.com" required>
                @error('email')<div class="form-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label>Contraseña</label>
                <input type="password" name="password" placeholder="Mínimo 8 caracteres" required>
                @error('password')<div class="form-error">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Rol</label>
                <select name="role_id" required>
                    <option value="">— Seleccionar rol —</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                            {{ $role->name }}
                        </option>
                    @endforeach
                </select>
                @error('role_id')<div class="form-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label>Sucursal</label>
                <select name="branch_id" required>
                    <option value="">— Seleccionar sucursal —</option>
                    @foreach($branches as $branch)
                        <option value="{{ $branch->id }}" {{ old('branch_id') == $branch->id ? 'selected' : '' }}>
                            {{ $branch->name }}
                        </option>
                    @endforeach
                </select>
                @error('branch_id')<div class="form-error">{{ $message }}</div>@enderror
            </div>
        </div>

        <hr class="divider">
        <div class="flex gap-3">
            <button type="submit" class="btn btn-primary">Crear Usuario</button>
            <a href="{{ route('admin.users.index') }}" class="btn btn-ghost">Cancelar</a>
        </div>
    </form>
</div>
@endsection
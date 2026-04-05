@extends('layouts.app')
@section('title', 'Editar Usuario')

@section('sidebar-nav')
    <span class="nav-section-label">Principal</span>
    <a href="{{ route('admin.dashboard') }}" class="nav-item"><span class="nav-icon">⬡</span> Dashboard</a>
    <span class="nav-section-label">Gestión</span>
    <a href="{{ route('admin.users.index') }}" class="nav-item active"><span class="nav-icon">👥</span> Usuarios</a>
    <a href="{{ route('admin.sales.index') }}" class="nav-item"><span class="nav-icon">🛒</span> Historial Ventas</a>
    <a href="{{ route('admin.shifts.index') }}" class="nav-item"><span class="nav-icon">⏱</span> Turnos</a>
@endsection

@section('page-title', 'Editar Usuario')
@section('page-subtitle', '{{ $user->username }}')

@section('content')
<div style="max-width: 600px;">
    <div class="card mb-4">
        <div class="card-header">
            <div class="card-title">Datos del usuario</div>
        </div>
        <form method="POST" action="{{ route('admin.users.update', $user) }}">
            @csrf @method('PUT')
            <div class="form-row">
                <div class="form-group">
                    <label>Nombre Completo</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required>
                    @error('name')<div class="form-error">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label>Usuario</label>
                    <input type="text" name="username" value="{{ old('username', $user->username) }}" required>
                    @error('username')<div class="form-error">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required>
                    @error('email')<div class="form-error">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label>Nueva Contraseña <span class="text-muted">(dejar vacío para no cambiar)</span></label>
                    <input type="password" name="password" placeholder="Nueva contraseña...">
                    @error('password')<div class="form-error">{{ $message }}</div>@enderror
                </div>
            </div>
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
@extends('layouts.app')
@section('title', 'Gestión de Usuarios')

@section('sidebar-nav')
    <span class="nav-section-label">Principal</span>
    <a href="{{ route('admin.dashboard') }}" class="nav-item">
        <i class="bi bi-speedometer2"></i> Dashboard
    </a>
    <span class="nav-section-label">Gestión</span>
    <a href="{{ route('admin.users.index') }}" class="nav-item active">
        <i class="bi bi-people-fill"></i> Usuarios
    </a>
    <a href="{{ route('admin.sales.index') }}" class="nav-item">
        <i class="bi bi-bag-check"></i> Historial Ventas
    </a>
    <a href="{{ route('admin.shifts.index') }}" class="nav-item">
        <i class="bi bi-clock-history"></i> Turnos
    </a>
@endsection

@section('page-title', 'Usuarios y Roles')
@section('page-subtitle', 'Gestión del personal del sistema')

@section('topbar-actions')
    <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm">+ Nuevo Usuario</a>
@endsection

@section('content')
<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Usuario</th>
                    <th>Nombre</th>
                    <th>Rol</th>
                    <th>Sucursal</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr>
                        <td class="mono text-muted text-xs">{{ $user->id }}</td>
                        <td>
                            <div class="flex items-center gap-2">
                                <div class="user-avatar" style="width:1.75rem; height:1.75rem; font-size:.7rem;">
                                    {{ strtoupper(substr($user->name, 0, 2)) }}
                                </div>
                                <span class="mono text-sm">{{ $user->username }}</span>
                            </div>
                        </td>
                        <td>{{ $user->name }}</td>
                        <td>
                            <span class="{{ $user->role->slug === 'admin' ? 'badge badge-warning' : 'badge badge-info' }}">
                                {{ $user->role->name }}
                            </span>
                        </td>
                        <td class="text-sm text-muted">{{ $user->branch->name ?? '—' }}</td>
                        <td>
                            <span class="{{ $user->is_active ? 'badge badge-success' : 'badge badge-danger' }}">
                                {{ $user->is_active ? 'Activo' : 'Inactivo' }}
                            </span>
                        </td>
                        <td>
                            <div class="flex gap-2">
                                <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-ghost btn-sm">Editar</a>
                                @if(auth()->id() !== $user->id)
                                    <form method="POST" action="{{ route('admin.users.toggle', $user) }}">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="btn btn-sm {{ $user->is_active ? 'btn-danger' : 'btn-success' }}">
                                            {{ $user->is_active ? 'Desactivar' : 'Activar' }}
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted" style="padding: 3rem;">
                            No hay usuarios registrados
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
@extends('layouts.app')
@section('title', 'Turnos')

@section('sidebar-nav')
    <span class="nav-section-label">Principal</span>
    <a href="{{ route('admin.dashboard') }}" class="nav-item"><span class="nav-icon">⬡</span> Dashboard</a>
    <span class="nav-section-label">Gestión</span>
    <a href="{{ route('admin.users.index') }}" class="nav-item"><span class="nav-icon">👥</span> Usuarios</a>
    <a href="{{ route('admin.sales.index') }}" class="nav-item"><span class="nav-icon">🛒</span> Historial Ventas</a>
    <a href="{{ route('admin.shifts.index') }}" class="nav-item active"><span class="nav-icon">⏱</span> Turnos</a>
@endsection

@section('page-title', 'Turnos')
@section('page-subtitle', 'Registro de turnos del personal')

@section('content')
<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Cajero</th>
                    <th>Apertura</th>
                    <th>Cierre</th>
                    <th>Ef. Inicial</th>
                    <th>Ef. Declarado</th>
                    <th>Diferencia</th>
                    <th>Estado</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($shifts as $shift)
                    <tr>
                        <td>
                            <div class="flex items-center gap-2">
                                <div class="user-avatar" style="width:1.75rem;height:1.75rem;font-size:.7rem;">
                                    {{ strtoupper(substr($shift->user->name, 0, 2)) }}
                                </div>
                                {{ $shift->user->name }}
                            </div>
                        </td>
                        <td class="mono text-xs">{{ $shift->start_time->format('d/m H:i') }}</td>
                        <td class="mono text-xs">{{ $shift->end_time ? $shift->end_time->format('d/m H:i') : '—' }}</td>
                        <td class="mono">Bs {{ number_format($shift->initial_cash, 2) }}</td>
                        <td class="mono">{{ $shift->reported_cash ? 'Bs '.number_format($shift->reported_cash, 2) : '—' }}</td>
                        <td class="mono {{ $shift->cash_difference < 0 ? 'text-danger' : ($shift->cash_difference > 0 ? 'text-warning' : 'text-success') }}">
                            {{ $shift->cash_difference !== null ? 'Bs '.number_format($shift->cash_difference, 2) : '—' }}
                        </td>
                        <td>
                            <span class="{{ $shift->status === 'OPEN' ? 'badge badge-success' : 'badge badge-gray' }}">
                                {{ $shift->status === 'OPEN' ? 'Abierto' : 'Cerrado' }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('admin.shifts.show', $shift) }}" class="btn btn-ghost btn-sm">Ver</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted" style="padding: 3rem;">No hay turnos</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="margin-top:1rem;">{{ $shifts->links() }}</div>
</div>
@endsection
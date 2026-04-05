@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('sidebar-nav')
    <span class="nav-section-label">Principal</span>
    <a href="{{ route('admin.dashboard') }}" class="nav-item active">
        <span class="nav-icon">⬡</span> Dashboard
    </a>

    <span class="nav-section-label">Gestión</span>
    <a href="{{ route('admin.users.index') }}" class="nav-item">
        <span class="nav-icon">👥</span> Usuarios
    </a>
    <a href="{{ route('admin.sales.index') }}" class="nav-item">
        <span class="nav-icon">🛒</span> Historial Ventas
    </a>
    <a href="{{ route('admin.shifts.index') }}" class="nav-item">
        <span class="nav-icon">⏱</span> Turnos
    </a>
@endsection

@section('page-title', 'Dashboard')
@section('page-subtitle', 'Resumen general del día')

@section('topbar-actions')
    <span class="text-xs text-muted mono">{{ now()->format('d/m/Y H:i') }}</span>
@endsection

@section('content')
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon">⏱</div>
        <div class="stat-label">Turnos Abiertos</div>
        <div class="stat-value">{{ $openShifts }}</div>
        <div class="stat-note">en este momento</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">🛒</div>
        <div class="stat-label">Ventas Hoy</div>
        <div class="stat-value">{{ $todaySalesCount }}</div>
        <div class="stat-note">transacciones</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">💵</div>
        <div class="stat-label">Total Efectivo</div>
        <div class="stat-value mono">Bs {{ number_format($todayCash, 2) }}</div>
        <div class="stat-note">recaudado hoy</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">📱</div>
        <div class="stat-label">Total QR</div>
        <div class="stat-value mono">Bs {{ number_format($todayQr, 2) }}</div>
        <div class="stat-note">recaudado hoy</div>
    </div>
</div>

<div class="grid grid-2">
    {{-- Turnos activos --}}
    <div class="card">
        <div class="card-header">
            <div>
                <div class="card-title">Turnos Activos</div>
                <div class="card-subtitle">Cajeros trabajando ahora</div>
            </div>
            <a href="{{ route('admin.shifts.index') }}" class="btn btn-ghost btn-sm">Ver todos</a>
        </div>
        @forelse($activeShifts as $shift)
            <div class="flex items-center gap-3 mb-4" style="padding: .75rem; background: rgba(79,142,247,.05); border-radius: 8px; border: 1px solid var(--border);">
                <div class="user-avatar" style="background: var(--success);">{{ strtoupper(substr($shift->user->name, 0, 2)) }}</div>
                <div style="flex: 1">
                    <div class="text-sm font-bold">{{ $shift->user->name }}</div>
                    <div class="text-xs text-muted">Desde {{ $shift->start_time->format('H:i') }}</div>
                </div>
                <div class="text-right">
                    <div class="mono text-sm text-success">Bs {{ number_format($shift->sales()->where('status','COMPLETED')->sum('total_amount'), 2) }}</div>
                    <div class="text-xs text-muted">vendido</div>
                </div>
            </div>
        @empty
            <div class="text-center text-muted" style="padding: 2rem 0;">
                <div style="font-size: 2rem; margin-bottom: .5rem;">⏸</div>
                <div>No hay turnos abiertos</div>
            </div>
        @endforelse
    </div>

    {{-- Últimas ventas --}}
    <div class="card">
        <div class="card-header">
            <div>
                <div class="card-title">Últimas Ventas</div>
                <div class="card-subtitle">Transacciones recientes</div>
            </div>
            <a href="{{ route('admin.sales.index') }}" class="btn btn-ghost btn-sm">Ver todas</a>
        </div>
        @forelse($recentSales as $sale)
            <div class="flex items-center gap-3" style="padding: .6rem 0; border-bottom: 1px solid rgba(42,53,72,.5);">
                <span class="{{ $sale->payment_method === 'CASH' ? 'badge badge-success' : 'badge badge-info' }}">
                    {{ $sale->payment_method === 'CASH' ? 'EFEC' : 'QR' }}
                </span>
                <div style="flex: 1">
                    <div class="text-sm">{{ $sale->shift->user->name }}</div>
                    <div class="text-xs text-muted">{{ $sale->sale_time->format('H:i') }}</div>
                </div>
                <div class="mono text-sm {{ $sale->status === 'VOIDED' ? 'text-danger' : '' }}">
                    {{ $sale->status === 'VOIDED' ? '—' : 'Bs ' . number_format($sale->total_amount, 2) }}
                </div>
            </div>
        @empty
            <div class="text-center text-muted" style="padding: 2rem 0;">Sin ventas hoy</div>
        @endforelse
    </div>
</div>
@endsection
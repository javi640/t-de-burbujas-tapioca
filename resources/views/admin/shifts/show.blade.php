@extends('layouts.app')
@section('title', 'Detalle de Turno')

@section('sidebar-nav')
    <span class="nav-section-label">Principal</span>
    <a href="{{ route('admin.dashboard') }}" class="nav-item">
        <span class="nav-icon" style="display:inline-flex;align-items:center;"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg></span> Dashboard
    </a>
    <span class="nav-section-label">Gestión</span>
    <a href="{{ route('admin.users.index') }}" class="nav-item">
        <span class="nav-icon" style="display:inline-flex;align-items:center;"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg></span> Usuarios
    </a>
    <a href="{{ route('admin.sales.index') }}" class="nav-item">
        <span class="nav-icon" style="display:inline-flex;align-items:center;"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg></span> Historial Ventas
    </a>
    <a href="{{ route('admin.shifts.index') }}" class="nav-item active">
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

@section('page-title', 'Detalle de Turno')
@section('page-subtitle')
    {{ $shift->user->name }} · {{ $shift->start_time->format('d/m/Y') }}
@endsection

@section('topbar-actions')
    <a href="{{ route('admin.shifts.index') }}" class="btn btn-ghost btn-sm">← Volver</a>
@endsection

@section('content')

{{-- ── Stats principales ───────────────────────────────────── --}}
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon">👤</div>
        <div class="stat-label">Cajero</div>
        <div class="stat-value" style="font-size: 1rem;">{{ $shift->user->name }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">🔓</div>
        <div class="stat-label">Abierto por</div>
        <div class="stat-value" style="font-size: 1rem;">{{ $shift->openedBy?->name ?? '—' }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">💵</div>
        <div class="stat-label">Efectivo Inicial</div>
        <div class="stat-value mono">Bs {{ number_format($shift->initial_cash, 2) }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">🛒</div>
        <div class="stat-label">Total Ventas</div>
        <div class="stat-value">{{ $shift->sales->count() }}</div>
    </div>
</div>

{{-- ── Panel de asistencia ─────────────────────────────────── --}}
<div class="card mb-4">
    <div class="card-header">
        <div>
            <div class="card-title">🕐 Control de Asistencia</div>
            <div class="card-subtitle">Registro de puntualidad del cajero</div>
        </div>
        {{-- Badge de estado --}}
        @if($shift->attendance_status === 'PUNTUAL')
            <span class="badge badge-success" style="font-size: .85rem; padding: .4rem .9rem;">✓ Puntual</span>
        @elseif($shift->attendance_status === 'TARDANZA')
            <span class="badge badge-danger" style="font-size: .85rem; padding: .4rem .9rem;">⚠ Tardanza</span>
        @else
            <span class="badge badge-gray" style="font-size: .85rem; padding: .4rem .9rem;">⏳ Pendiente</span>
        @endif
    </div>

    <div class="grid grid-2" style="gap: 1rem; margin-top: .5rem;">
        <div style="display: flex; flex-direction: column; gap: .75rem;">

            <div class="flex items-center gap-3" style="padding: .6rem 0; border-bottom: 1px solid rgba(42,53,72,.5);">
                <div class="text-muted" style="width: 160px; font-size: .85rem;">Hora programada</div>
                <div class="mono font-bold">
                    {{ $shift->scheduled_start ? $shift->scheduled_start->setTimezone('America/La_Paz')->format('H:i') : '—' }}
                </div>
            </div>

            <div class="flex items-center gap-3" style="padding: .6rem 0; border-bottom: 1px solid rgba(42,53,72,.5);">
                <div class="text-muted" style="width: 160px; font-size: .85rem;">Tolerancia</div>
                <div class="mono font-bold">{{ $shift->tolerance_minutes }} min</div>
            </div>

            <div class="flex items-center gap-3" style="padding: .6rem 0; border-bottom: 1px solid rgba(42,53,72,.5);">
                <div class="text-muted" style="width: 160px; font-size: .85rem;">Límite sin tardanza</div>
                <div class="mono font-bold" style="color: var(--warning);">
                    {{ $shift->attendanceDeadline() ? $shift->attendanceDeadline()->setTimezone('America/La_Paz')->format('H:i') : '—' }}
                </div>
            </div>

        </div>

        <div style="display: flex; flex-direction: column; gap: .75rem;">

            <div class="flex items-center gap-3" style="padding: .6rem 0; border-bottom: 1px solid rgba(42,53,72,.5);">
                <div class="text-muted" style="width: 160px; font-size: .85rem;">Login del cajero</div>
                <div class="mono font-bold {{ $shift->attendance_status === 'TARDANZA' ? 'text-danger' : 'text-success' }}">
                    {{ $shift->cajero_login_time ? $shift->cajero_login_time->setTimezone('America/La_Paz')->format('H:i:s') : '— aún no ingresó' }}
                </div>
            </div>

            @if($shift->attendance_status === 'TARDANZA')
            <div class="flex items-center gap-3" style="padding: .6rem 0; border-bottom: 1px solid rgba(42,53,72,.5);">
                <div class="text-muted" style="width: 160px; font-size: .85rem;">Minutos de retraso</div>
                <div class="mono font-bold text-danger">{{ $shift->minutesLate() }} min tarde</div>
            </div>
            @endif

            <div class="flex items-center gap-3" style="padding: .6rem 0; border-bottom: 1px solid rgba(42,53,72,.5);">
                <div class="text-muted" style="width: 160px; font-size: .85rem;">Turno abierto a las</div>
                <div class="mono font-bold">
                    {{ $shift->start_time->setTimezone('America/La_Paz')->format('H:i:s') }}
                </div>
            </div>

        </div>
    </div>
</div>

{{-- ── Arqueo (si ya está cerrado) ────────────────────────── --}}
@if($shift->status === 'CLOSED')
<div class="card mb-4">
    <div class="card-header">
        <div class="card-title">💰 Arqueo de Cierre</div>
        @if($shift->inconsistency_class === 'SIN_INCONSISTENCIA')
            <span class="badge badge-success">✓ Sin inconsistencia</span>
        @elseif($shift->inconsistency_class === 'INCONSISTENCIA_LEVE')
            <span class="badge badge-warning">⚠ Leve</span>
        @elseif($shift->inconsistency_class === 'INCONSISTENCIA_CRITICA')
            <span class="badge badge-danger">✕ Crítica</span>
        @endif
    </div>
    <div class="grid grid-2" style="gap: 1rem; margin-top: .5rem;">
        <div>
            <div class="text-muted text-xs">Efectivo esperado</div>
            <div class="mono" style="font-size: 1.25rem;">Bs {{ number_format($shift->expectedCash(), 2) }}</div>
        </div>
        <div>
            <div class="text-muted text-xs">Efectivo declarado</div>
            <div class="mono" style="font-size: 1.25rem;">Bs {{ number_format($shift->reported_cash, 2) }}</div>
        </div>
        <div>
            <div class="text-muted text-xs">Diferencia</div>
            <div class="mono {{ $shift->cash_difference < 0 ? 'text-danger' : ($shift->cash_difference > 0 ? 'text-warning' : 'text-success') }}" style="font-size: 1.25rem;">
                Bs {{ number_format($shift->cash_difference, 2) }}
            </div>
        </div>
        <div>
            <div class="text-muted text-xs">Total QR</div>
            <div class="mono text-accent" style="font-size: 1.25rem;">Bs {{ number_format($shift->totalQr(), 2) }}</div>
        </div>
    </div>
</div>
@endif

{{-- ── Ventas del turno ────────────────────────────────────── --}}
<div class="card">
    <div class="card-header">
        <div class="card-title">🛒 Ventas del Turno</div>
        <div class="text-muted text-sm">{{ $shift->sales->count() }} transacciones</div>
    </div>
    @if($shift->sales->count())
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Hora</th>
                    <th>Productos</th>
                    <th>Método</th>
                    <th>Total</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                @foreach($shift->sales as $sale)
                <tr>
                    <td class="mono text-xs">{{ $sale->sale_time->format('H:i') }}</td>
                    <td class="text-xs text-muted">
                        {{ $sale->details->map(fn($d) => $d->quantity.'× '.$d->product->name)->join(', ') }}
                    </td>
                    <td>
                        <span class="{{ $sale->payment_method === 'CASH' ? 'badge badge-success' : 'badge badge-info' }}">
                            {{ $sale->payment_method === 'CASH' ? 'Efectivo' : 'QR' }}
                        </span>
                    </td>
                    <td class="mono">Bs {{ number_format($sale->total_amount, 2) }}</td>
                    <td>
                        <span class="{{ $sale->status === 'COMPLETED' ? 'badge badge-success' : 'badge badge-danger' }}">
                            {{ $sale->status === 'COMPLETED' ? 'OK' : 'Anulada' }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
        <div class="text-muted text-center" style="padding: 2rem 0;">Sin ventas en este turno</div>
    @endif
</div>

@endsection
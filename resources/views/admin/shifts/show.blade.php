@extends('layouts.app')
@section('title', 'Detalle de Turno')


@section('sidebar-nav')
    <span class="nav-section-label">Principal</span>
    <a href="{{ route('admin.dashboard') }}" class="nav-item">
        <span class="nav-icon"><i class="bi bi-grid-fill"></i></span> Dashboard
    </a>
    <span class="nav-section-label">Gestión</span>
    <a href="{{ route('admin.users.index') }}" class="nav-item">
        <span class="nav-icon"><i class="bi bi-people-fill"></i></span> Usuarios
    </a>
    <a href="{{ route('admin.sales.index') }}" class="nav-item">
        <span class="nav-icon"><i class="bi bi-receipt"></i></span> Historial Ventas
    </a>
    <a href="{{ route('admin.shifts.index') }}" class="nav-item active">
        <span class="nav-icon"><i class="bi bi-clock-fill"></i></span> Turnos
    </a>
    <span class="nav-section-label">Reportes</span>
    <a href="{{ route('admin.reports.daily') }}" class="nav-item">
        <span class="nav-icon"><i class="bi bi-file-earmark-text"></i></span> Cierre Diario
    </a>
    <a href="{{ route('admin.audit.index') }}" class="nav-item">
        <span class="nav-icon"><i class="bi bi-search"></i></span> Auditoría
    </a>
@endsection

@section('page-title', 'Detalle de Turno')
@section('page-subtitle')
    {{ $shift->user->name }} · {{ $shift->start_time->format('d/m/Y') }}
@endsection

@section('topbar-actions')
    <a href="{{ route('admin.shifts.index') }}" class="btn btn-ghost btn-sm"><i class="bi bi-arrow-left"></i> Volver</a>
@endsection

@section('content')

{{-- ── Stats principales ───────────────────────────────────── --}}
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon"><i class="bi bi-person-fill"></i></div>
        <div class="stat-label">Cajero</div>
        <div class="stat-value" style="font-size: 1rem;">{{ $shift->user->name }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon"><i class="bi bi-unlock-fill"></i></div>
        <div class="stat-label">Abierto por</div>
        <div class="stat-value" style="font-size: 1rem;">{{ $shift->openedBy?->name ?? '—' }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon"><i class="bi bi-cash-stack"></i></div>
        <div class="stat-label">Efectivo Inicial</div>
        <div class="stat-value mono">Bs {{ number_format($shift->initial_cash, 2) }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon"><i class="bi bi-cart3"></i></div>
        <div class="stat-label">Total Ventas</div>
        <div class="stat-value">{{ $shift->sales->count() }}</div>
    </div>
</div>

{{-- ── Panel de asistencia ─────────────────────────────────── --}}
<div class="card mb-4">
    <div class="card-header">
        <div>
            <div class="card-title"><i class="bi bi-clock"></i> Control de Asistencia</div>
            <div class="card-subtitle">Registro de puntualidad del cajero</div>
        </div>
        {{-- Badge de estado --}}
        @if($shift->attendance_status === 'PUNTUAL')
            <span class="badge badge-success" style="font-size: .85rem; padding: .4rem .9rem;"><i class="bi bi-check-circle-fill"></i> Puntual</span>
        @elseif($shift->attendance_status === 'TARDANZA')
            <span class="badge badge-danger" style="font-size: .85rem; padding: .4rem .9rem;"><i class="bi bi-exclamation-triangle-fill"></i> Tardanza</span>
        @else
            <span class="badge badge-gray" style="font-size: .85rem; padding: .4rem .9rem;">⏳ Pendiente</span>
        @endif
    </div>

    <div class="grid grid-2" style="gap: 1rem; margin-top: .5rem;">
        <div style="display: flex; flex-direction: column; gap: .75rem;">

            <div class="flex items-center gap-3" style="padding: .6rem 0; border-bottom: 1px solid rgba(184,204,202,.3);">
                <div class="text-muted" style="width: 160px; font-size: .85rem;">Hora programada</div>
                <div class="mono font-bold">
                    {{ $shift->scheduled_start ? $shift->scheduled_start->setTimezone('America/La_Paz')->format('H:i') : '—' }}
                </div>
            </div>

            <div class="flex items-center gap-3" style="padding: .6rem 0; border-bottom: 1px solid rgba(184,204,202,.3);">
                <div class="text-muted" style="width: 160px; font-size: .85rem;">Tolerancia</div>
                <div class="mono font-bold">{{ $shift->tolerance_minutes }} min</div>
            </div>

            <div class="flex items-center gap-3" style="padding: .6rem 0; border-bottom: 1px solid rgba(184,204,202,.3);">
                <div class="text-muted" style="width: 160px; font-size: .85rem;">Límite sin tardanza</div>
                <div class="mono font-bold" style="color: var(--warning);">
                    {{ $shift->attendanceDeadline() ? $shift->attendanceDeadline()->setTimezone('America/La_Paz')->format('H:i') : '—' }}
                </div>
            </div>

        </div>

        <div style="display: flex; flex-direction: column; gap: .75rem;">

            <div class="flex items-center gap-3" style="padding: .6rem 0; border-bottom: 1px solid rgba(184,204,202,.3);">
                <div class="text-muted" style="width: 160px; font-size: .85rem;">Login del cajero</div>
                <div class="mono font-bold {{ $shift->attendance_status === 'TARDANZA' ? 'text-danger' : 'text-success' }}">
                    {{ $shift->cajero_login_time ? $shift->cajero_login_time->setTimezone('America/La_Paz')->format('H:i:s') : '— aún no ingresó' }}
                </div>
            </div>

            @if($shift->attendance_status === 'TARDANZA')
            <div class="flex items-center gap-3" style="padding: .6rem 0; border-bottom: 1px solid rgba(184,204,202,.3);">
                <div class="text-muted" style="width: 160px; font-size: .85rem;">Minutos de retraso</div>
                <div class="mono font-bold text-danger">{{ $shift->minutesLate() }} min tarde</div>
            </div>
            @endif

            <div class="flex items-center gap-3" style="padding: .6rem 0; border-bottom: 1px solid rgba(184,204,202,.3);">
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
        <div class="card-title"><i class="bi bi-currency-dollar"></i> Arqueo de Cierre</div>
        @if($shift->inconsistency_class === 'SIN_INCONSISTENCIA')
            <span class="badge badge-success"><i class="bi bi-check-circle-fill"></i> Sin inconsistencia</span>
        @elseif($shift->inconsistency_class === 'INCONSISTENCIA_LEVE')
            <span class="badge badge-warning"><i class="bi bi-exclamation-triangle-fill"></i> Leve</span>
        @elseif($shift->inconsistency_class === 'INCONSISTENCIA_CRITICA')
            <span class="badge badge-danger"><i class="bi bi-exclamation-circle-fill"></i> Crítica</span>
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
        <div class="card-title"><i class="bi bi-cart3"></i> Ventas del Turno</div>
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
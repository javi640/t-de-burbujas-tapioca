@extends('layouts.app')
@section('title', 'Detalle de Turno')

@section('sidebar-nav')
    <span class="nav-section-label">Principal</span>
    <a href="{{ route('admin.dashboard') }}" class="nav-item"><span class="nav-icon">⬡</span> Dashboard</a>
    <span class="nav-section-label">Gestión</span>
    <a href="{{ route('admin.users.index') }}" class="nav-item"><span class="nav-icon">👥</span> Usuarios</a>
    <a href="{{ route('admin.sales.index') }}" class="nav-item"><span class="nav-icon">🛒</span> Historial Ventas</a>
    <a href="{{ route('admin.shifts.index') }}" class="nav-item active"><span class="nav-icon">⏱</span> Turnos</a>
@endsection

@section('page-title', 'Detalle del Turno')
@section('page-subtitle', 'Información completa del turno #' . $shift->id)

@section('topbar-actions')
    <a href="{{ route('admin.shifts.index') }}" class="btn btn-ghost">← Volver</a>
@endsection

@section('content')

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 2rem;">
    <!-- Info General -->
    <div class="card">
        <h3 style="margin-bottom: 1rem; font-size: 1rem; font-weight: 600;">📋 Información General</h3>
        <div style="display: grid; gap: .75rem; font-size: .9rem;">
            <div style="display: grid; grid-template-columns: 1fr 1fr;">
                <span style="color: #8b9abf;">Cajero:</span>
                <span style="font-weight: 500;">{{ $shift->user->name }}</span>
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr;">
                <span style="color: #8b9abf;">Estado:</span>
                <span>
                    <span class="{{ $shift->status === 'OPEN' ? 'badge badge-success' : 'badge badge-gray' }}">
                        {{ $shift->status === 'OPEN' ? 'Abierto' : 'Cerrado' }}
                    </span>
                </span>
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr;">
                <span style="color: #8b9abf;">Apertura:</span>
                <span class="mono">{{ $shift->start_time->format('d/m/Y H:i') }}</span>
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr;">
                <span style="color: #8b9abf;">Cierre:</span>
                <span class="mono">{{ $shift->end_time ? $shift->end_time->format('d/m/Y H:i') : '—' }}</span>
            </div>
        </div>
    </div>

    <!-- Caja -->
    <div class="card">
        <h3 style="margin-bottom: 1rem; font-size: 1rem; font-weight: 600;">💰 Efectivo</h3>
        <div style="display: grid; gap: .75rem; font-size: .9rem;">
            <div style="display: grid; grid-template-columns: 1fr 1fr;">
                <span style="color: #8b9abf;">Inicial:</span>
                <span class="mono">Bs {{ number_format($shift->initial_cash, 2) }}</span>
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr;">
                <span style="color: #8b9abf;">Declarado:</span>
                <span class="mono">Bs {{ $shift->reported_cash ? number_format($shift->reported_cash, 2) : '—' }}</span>
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr;">
                <span style="color: #8b9abf;">Esperado:</span>
                <span class="mono">Bs {{ number_format($shift->expectedCash(), 2) }}</span>
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr;">
                <span style="color: #8b9abf;">Diferencia:</span>
                <span class="mono {{ $shift->cash_difference < 0 ? 'text-danger' : ($shift->cash_difference > 0 ? 'text-warning' : 'text-success') }}">
                    Bs {{ $shift->cash_difference !== null ? number_format($shift->cash_difference, 2) : '—' }}
                </span>
            </div>
        </div>
    </div>
</div>

<!-- Resumen de Ventas -->
<div class="card" style="margin-bottom: 2rem;">
    <h3 style="margin-bottom: 1rem; font-size: 1rem; font-weight: 600;">🛒 Resumen de Ventas</h3>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 1rem;">
        <div style="background: #131929; padding: 1rem; border-radius: 8px; border: 1px solid #2a3548;">
            <div style="font-size: .8rem; color: #8b9abf; margin-bottom: .3rem;">Total Ventas</div>
            <div class="mono" style="font-size: 1.3rem; font-weight: 600;">
                Bs {{ number_format($shift->sales->where('status', 'COMPLETED')->sum('total_amount'), 2) }}
            </div>
        </div>
        <div style="background: #131929; padding: 1rem; border-radius: 8px; border: 1px solid #2a3548;">
            <div style="font-size: .8rem; color: #8b9abf; margin-bottom: .3rem;">Efectivo (CASH)</div>
            <div class="mono" style="font-size: 1.3rem; font-weight: 600;">
                Bs {{ number_format($shift->sales->where('payment_method', 'CASH')->where('status', 'COMPLETED')->sum('total_amount'), 2) }}
            </div>
        </div>
        <div style="background: #131929; padding: 1rem; border-radius: 8px; border: 1px solid #2a3548;">
            <div style="font-size: .8rem; color: #8b9abf; margin-bottom: .3rem;">QR</div>
            <div class="mono" style="font-size: 1.3rem; font-weight: 600;">
                Bs {{ number_format($shift->totalQr(), 2) }}
            </div>
        </div>
        <div style="background: #131929; padding: 1rem; border-radius: 8px; border: 1px solid #2a3548;">
            <div style="font-size: .8rem; color: #8b9abf; margin-bottom: .3rem;">Cantidad de Ventas</div>
            <div class="mono" style="font-size: 1.3rem; font-weight: 600;">
                {{ $shift->sales->where('status', 'COMPLETED')->count() }}
            </div>
        </div>
    </div>
</div>

<!-- Movimientos de Caja -->
@if($shift->cashMovements->count() > 0)
<div class="card" style="margin-bottom: 2rem;">
    <h3 style="margin-bottom: 1rem; font-size: 1rem; font-weight: 600;">📝 Movimientos de Caja</h3>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Tipo</th>
                    <th>Categoría</th>
                    <th>Monto</th>
                    <th>Nota</th>
                    <th>Hora</th>
                </tr>
            </thead>
            <tbody>
                @foreach($shift->cashMovements as $movement)
                    <tr>
                        <td>
                            <span class="badge {{ $movement->movement_type === 'INCOME' ? 'badge-success' : 'badge-danger' }}">
                                {{ $movement->movement_type === 'INCOME' ? '➕ Ingreso' : '➖ Egreso' }}
                            </span>
                        </td>
                        <td>{{ $movement->category ?? '—' }}</td>
                        <td class="mono">Bs {{ number_format($movement->amount, 2) }}</td>
                        <td>{{ substr($movement->notes, 0, 30) }}{{ strlen($movement->notes) > 30 ? '...' : '' }}</td>
                        <td class="mono text-xs">{{ $movement->created_at->format('H:i') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

<!-- Stock Inicial -->
@if($shift->stock->count() > 0)
<div class="card">
    <h3 style="margin-bottom: 1rem; font-size: 1rem; font-weight: 600;">📦 Stock Inicial del Turno</h3>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Cantidad</th>
                </tr>
            </thead>
            <tbody>
                @foreach($shift->stock as $item)
                    <tr>
                        <td>{{ $item->product->name ?? 'Producto desconocido' }}</td>
                        <td class="mono">{{ $item->quantity }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

@endsection

@section('styles')
<style>
    .badge {
        display: inline-block;
        padding: .25rem .6rem;
        border-radius: 4px;
        font-size: .75rem;
        font-weight: 600;
    }
    .badge-success {
        background: rgba(34, 197, 94, .15);
        color: #4ade80;
        border: 1px solid rgba(34, 197, 94, .3);
    }
    .badge-danger {
        background: rgba(239, 68, 68, .15);
        color: #f87171;
        border: 1px solid rgba(239, 68, 68, .3);
    }
    .badge-gray {
        background: rgba(139, 154, 191, .15);
        color: #cbd5e1;
        border: 1px solid rgba(139, 154, 191, .3);
    }
    .text-danger { color: #ef4444; }
    .text-warning { color: #f59e0b; }
    .text-success { color: #22c55e; }
    .text-muted { color: #8b9abf; }
    .mono { font-family: 'DM Mono', monospace; }
    .text-xs { font-size: .75rem; }
    
    @media (max-width: 768px) {
        div[style*="grid-template-columns: 1fr 1fr"] {
            grid-template-columns: 1fr !important;
        }
        div[style*="grid-template-columns: repeat(auto-fit"] {
            grid-template-columns: 1fr !important;
        }
    }
</style>
@endsection

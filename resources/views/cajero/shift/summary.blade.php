@extends('layouts.app')
@section('title', 'Resumen de Turno')

@section('sidebar-nav')
    <span class="nav-section-label">Mi Turno</span>
    @if(auth()->user()->isAdmin())
        <a href="{{ route('admin.shifts.index') }}" class="nav-item">
            <span class="nav-icon" style="display:inline-flex;align-items:center;"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg></span> Ver Turnos
        </a>
    @else
        <a href="{{ route('cajero.shift.waiting') }}" class="nav-item">
            <span class="nav-icon" style="display:inline-flex;align-items:center;"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg></span> Mi Turno
        </a>
    @endif
@endsection

@section('page-title', 'Resumen de Turno')
@section('page-subtitle')
    Turno cerrado · {{ $shift->start_time->format('d/m/Y') }}
@endsection

@section('content')
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-label">Efectivo Esperado</div>
        <div class="stat-value mono">Bs {{ number_format($shift->expectedCash(), 2) }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Efectivo Declarado</div>
        <div class="stat-value mono">Bs {{ number_format($shift->reported_cash, 2) }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Diferencia</div>
        <div class="stat-value mono {{ $shift->cash_difference < 0 ? 'text-danger' : ($shift->cash_difference > 0 ? 'text-warning' : 'text-success') }}">
            Bs {{ number_format($shift->cash_difference, 2) }}
        </div>
        <div class="stat-note">{{ $shift->cash_difference == 0 ? '✓ Cuadrado' : ($shift->cash_difference < 0 ? '⚠ Faltante' : '⚠ Sobrante') }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Total QR</div>
        <div class="stat-value mono text-accent">Bs {{ number_format($shift->totalQr(), 2) }}</div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div class="card-title">Ventas del Turno</div>
        <div class="text-muted text-sm">{{ $shift->sales->count() }} transacciones</div>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr><th>Hora</th><th>Productos</th><th>Método</th><th>Total</th><th>Estado</th></tr>
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
</div>


@endsection
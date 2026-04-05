@extends('layouts.app')
@section('title', 'Mi Turno')

@section('sidebar-nav')
    <span class="nav-section-label">Mi Turno</span>
    <a href="{{ route('cajero.shift.current') }}" class="nav-item active">
        <span class="nav-icon">⏱</span> Turno Actual
    </a>
    <a href="{{ route('cajero.sales.create') }}" class="nav-item">
        <span class="nav-icon">🛒</span> Nueva Venta
    </a>
    <a href="#movements" class="nav-item">
        <span class="nav-icon">💰</span> Movimientos
    </a>
@endsection

@section('page-title', 'Mi Turno Activo')
@section('page-subtitle', 'Iniciado a las {{ $shift->start_time->format("H:i") }} del {{ $shift->start_time->format("d/m/Y") }}')

@section('topbar-actions')
    <form method="POST" action="{{ route('cajero.shift.close') }}" id="closeForm">
        @csrf
        <button type="button" onclick="showCloseModal()" class="btn btn-danger btn-sm">🔒 Cerrar Turno</button>
    </form>
@endsection

@section('content')
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon">💵</div>
        <div class="stat-label">Efectivo Esperado</div>
        <div class="stat-value mono">Bs {{ number_format($expectedCash, 2) }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">📱</div>
        <div class="stat-label">Total QR</div>
        <div class="stat-value mono text-accent">Bs {{ number_format($totalQr, 2) }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">🛒</div>
        <div class="stat-label">Ventas del Turno</div>
        <div class="stat-value">{{ $shift->sales->count() }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">💸</div>
        <div class="stat-label">Efectivo Inicial</div>
        <div class="stat-value mono">Bs {{ number_format($shift->initial_cash, 2) }}</div>
    </div>
</div>

<div class="grid grid-2">
    {{-- Stock restante --}}
    <div class="card">
        <div class="card-header">
            <div class="card-title">📦 Stock Restante</div>
            <a href="{{ route('cajero.sales.create') }}" class="btn btn-primary btn-sm">+ Nueva Venta</a>
        </div>
        @foreach($shift->stock as $stock)
            <div class="flex items-center gap-3" style="padding: .6rem 0; border-bottom: 1px solid rgba(42,53,72,.5);">
                <div style="flex: 1;">
                    <div class="text-sm font-bold">{{ $stock->product->name }}</div>
                    <div class="text-xs text-muted">Inicial: {{ $stock->initial_quantity }}</div>
                </div>
                <div class="text-right">
                    <div class="mono font-bold {{ $stock->remainingQuantity() <= 2 ? 'text-warning' : 'text-success' }}">
                        {{ $stock->remainingQuantity() }}
                    </div>
                    <div class="text-xs text-muted">disponibles</div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Últimas ventas --}}
    <div class="card">
        <div class="card-header">
            <div class="card-title">🛒 Ventas del Turno</div>
        </div>
        @forelse($shift->sales->take(8) as $sale)
            <div class="flex items-center gap-3" style="padding: .5rem 0; border-bottom: 1px solid rgba(42,53,72,.4);">
                <span class="{{ $sale->payment_method === 'CASH' ? 'badge badge-success' : 'badge badge-info' }}">
                    {{ $sale->payment_method === 'CASH' ? 'EF' : 'QR' }}
                </span>
                <div style="flex: 1;" class="text-sm text-muted">{{ $sale->sale_time->format('H:i') }}</div>
                <div class="mono {{ $sale->status === 'VOIDED' ? 'text-danger' : '' }}">
                    Bs {{ number_format($sale->total_amount, 2) }}
                </div>
            </div>
        @empty
            <div class="text-muted text-center" style="padding: 2rem 0;">Sin ventas aún</div>
        @endforelse
    </div>
</div>

{{-- Movimientos extra --}}
<div class="card mt-4" id="movements">
    <div class="card-header">
        <div class="card-title">💰 Registrar Movimiento</div>
        <div class="card-subtitle">Ingresos o egresos extras de caja</div>
    </div>
    <form method="POST" action="{{ route('cajero.movements.store') }}">
        @csrf
        <div class="flex gap-3 items-center">
            <div class="form-group" style="margin:0; flex:1">
                <select name="movement_type">
                    <option value="INCOME">📈 Ingreso Extra</option>
                    <option value="EXPENSE">📉 Egreso / Gasto</option>
                </select>
            </div>
            <div class="form-group" style="margin:0; width:140px">
                <input type="number" name="amount" placeholder="Monto Bs" min="0.01" step="0.01">
            </div>
            <div class="form-group" style="margin:0; flex:2">
                <input type="text" name="description" placeholder="Descripción del movimiento">
            </div>
            <button type="submit" class="btn btn-primary" style="white-space:nowrap;">Registrar</button>
        </div>
    </form>
</div>

{{-- Modal de cierre de turno --}}
<div id="closeModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,.7); z-index:999; display:flex; align-items:center; justify-content:center;" hidden>
    <div class="card" style="max-width:440px; width:90%;">
        <div class="card-title mb-4">🔒 Cerrar Turno</div>
        <form method="POST" action="{{ route('cajero.shift.close') }}">
            @csrf
            <div class="form-group">
                <label>Efectivo Esperado (calculado)</label>
                <div class="mono" style="font-size: 1.5rem; padding: .5rem 0; color: var(--success);">
                    Bs {{ number_format($expectedCash, 2) }}
                </div>
            </div>
            <div class="form-group">
                <label>Efectivo Declarado (lo que contás físicamente)</label>
                <input type="number" name="reported_cash" min="0" step="0.01" placeholder="0.00"
                       style="font-family: 'DM Mono', monospace; font-size: 1.25rem;" required>
            </div>
            <div class="form-group">
                <label>Notas de cierre (opcional)</label>
                <textarea name="notes" rows="2" placeholder="Alguna observación..."></textarea>
            </div>
            <div class="flex gap-3">
                <button type="submit" class="btn btn-danger">Confirmar Cierre</button>
                <button type="button" onclick="hideCloseModal()" class="btn btn-ghost">Cancelar</button>
            </div>
        </form>
    </div>
</div>

@endsection

@section('scripts')
<script>
    const modal = document.getElementById('closeModal');
    function showCloseModal() { modal.hidden = false; modal.style.display = 'flex'; }
    function hideCloseModal() { modal.hidden = true; modal.style.display = 'none'; }
</script>
@endsection
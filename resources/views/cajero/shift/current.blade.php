@extends('layouts.app')
@section('title', 'Mi Turno')

@section('sidebar-nav')
    <span class="nav-section-label">Mi Turno</span>
    <a href="{{ route('cajero.shift.current') }}" class="nav-item active">
        <span class="nav-icon" style="display:inline-flex;align-items:center;"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg></span> Turno Actual
    </a>
    <a href="{{ route('cajero.sales.create') }}" class="nav-item">
        <span class="nav-icon" style="display:inline-flex;align-items:center;"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg></span> Nueva Venta
    </a>
    <a href="javascript:void(0)" onclick="document.getElementById('movements').scrollIntoView({behavior:'smooth'})" class="nav-item">
        <span class="nav-icon" style="display:inline-flex;align-items:center;"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg></span> Movimientos
    </a>
@endsection

@section('page-title', 'Mi Turno Activo')

{{-- ✅ FIX 1: usar bloque @section en lugar de string --}}
@section('page-subtitle')
    Iniciado a las {{ $shift->start_time->setTimezone('America/La_Paz')->format('H:i') }} del {{ $shift->start_time->setTimezone('America/La_Paz')->format('d/m/Y') }}
@endsection

@section('topbar-actions')
    <button type="button" onclick="showCloseModal()" class="btn btn-danger btn-sm">
        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline;vertical-align:middle;margin-right:4px;"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
        Cerrar Turno
    </button>
@endsection

@section('content')
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon"><svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="6" width="20" height="12" rx="2"/><path d="M12 12h.01"/><path d="M17 12h.01"/><path d="M7 12h.01"/></svg></div>
        <div class="stat-label">Efectivo Esperado</div>
        <div class="stat-value mono">Bs {{ number_format($expectedCash, 2) }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon"><svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="5" y="2" width="14" height="20" rx="2" ry="2"/><line x1="12" y1="18" x2="12.01" y2="18"/></svg></div>
        <div class="stat-label">Total QR</div>
        <div class="stat-value mono text-accent">Bs {{ number_format($totalQr, 2) }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon"><svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg></div>
        <div class="stat-label">Ventas del Turno</div>
        <div class="stat-value">{{ $shift->sales->count() }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon"><svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg></div>
        <div class="stat-label">Efectivo Inicial</div>
        <div class="stat-value mono">Bs {{ number_format($shift->initial_cash, 2) }}</div>
    </div>
</div>

<div class="grid grid-2">
    {{-- Stock restante --}}
    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline;vertical-align:middle;margin-right:5px;"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>
                Stock Restante
            </div>
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
            <div class="card-title">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline;vertical-align:middle;margin-right:5px;"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                Ventas del Turno
            </div>
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
        <div class="card-title">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline;vertical-align:middle;margin-right:5px;"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
            Registrar Movimiento
        </div>
        <div class="card-subtitle">Ingresos o egresos extras de caja</div>
    </div>
    <form method="POST" action="{{ route('cajero.movements.store') }}">
        @csrf
        <div class="flex gap-3 items-center">
            <div class="form-group" style="margin:0; flex:1">
                <select name="movement_type">
                    <option value="INCOME">Ingreso Extra</option>
                    <option value="EXPENSE">Egreso / Gasto</option>
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

<div id="closeModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,.7); z-index:999; align-items:center; justify-content:center;">
    <div class="card" style="max-width:440px; width:90%;">
        <div class="card-title mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline;vertical-align:middle;margin-right:5px;"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
            Cerrar Turno
        </div>
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
    function showCloseModal() { modal.style.display = 'flex'; }
    function hideCloseModal() { modal.style.display = 'none'; }
</script>
@endsection
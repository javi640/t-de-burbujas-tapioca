@extends('layouts.app')
@section('title', 'Movimientos de Caja')

@section('sidebar-nav')
    <span class="nav-section-label">Mi Turno</span>
    <a href="{{ route('cajero.shift.current') }}" class="nav-item">
        <span class="nav-icon"><i class="bi bi-clock"></i></span> Turno Actual
    </a>
    <a href="{{ route('cajero.sales.create') }}" class="nav-item">
        <span class="nav-icon"><i class="bi bi-cart3"></i></span> Nueva Venta
    </a>
    <a href="{{ route('cajero.movements.index') }}" class="nav-item active">
        <span class="nav-icon"><i class="bi bi-currency-dollar"></i></span> Movimientos
    </a>
@endsection

@section('page-title', 'Movimientos de Caja')
@section('page-subtitle', 'Ingresos y egresos extras registrados en el turno')

@section('topbar-actions')
    <a href="{{ route('cajero.shift.current') }}" class="btn btn-ghost btn-sm">← Volver al Turno</a>
@endsection

@section('content')

@php
    $totalIncome  = $shift->cashMovements->where('movement_type', 'INCOME')->sum('amount');
    $totalExpense = $shift->cashMovements->where('movement_type', 'EXPENSE')->sum('amount');
@endphp

{{-- Registrar movimiento --}}
<div class="card mb-4">
    <div class="card-header">
        <div class="card-title">
            <i class="bi bi-currency-dollar" style="vertical-align:middle; margin-right:5px;"></i>
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
                <input type="number" name="amount" placeholder="Monto Bs" min="0.01" step="0.01" required>
            </div>
            <div class="form-group" style="margin:0; flex:2">
                <input type="text" name="description" placeholder="Descripción del movimiento">
            </div>
            <button type="submit" class="btn btn-primary" style="white-space:nowrap;">Registrar</button>
        </div>
        @error('amount')
            <div class="form-error mt-2"><i class="bi bi-x-circle-fill"></i> {{ $message }}</div>
        @enderror
    </form>
</div>

{{-- Resumen --}}
<div class="stats-grid mb-4">
    <div class="stat-card">
        <div class="stat-icon"><i class="bi bi-arrow-down-circle"></i></div>
        <div class="stat-label">Total Ingresos Extra</div>
        <div class="stat-value mono text-success">Bs {{ number_format($totalIncome, 2) }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon"><i class="bi bi-arrow-up-circle"></i></div>
        <div class="stat-label">Total Egresos</div>
        <div class="stat-value mono text-danger">Bs {{ number_format($totalExpense, 2) }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon"><i class="bi bi-list-ol"></i></div>
        <div class="stat-label">Movimientos Registrados</div>
        <div class="stat-value">{{ $shift->cashMovements->count() }}</div>
    </div>
</div>

{{-- Listado de movimientos --}}
<div class="card">
    <div class="card-header">
        <div class="card-title">
            <i class="bi bi-clock-history" style="vertical-align:middle; margin-right:5px;"></i>
            Registro de Movimientos
        </div>
    </div>

    @forelse($shift->cashMovements as $movement)
        <div class="flex items-center gap-3" style="padding: .7rem 0; border-bottom: 1px solid rgba(184,204,202,.5);">
            <span class="{{ $movement->isIncome() ? 'badge badge-success' : 'badge badge-danger' }}">
                {{ $movement->isIncome() ? 'INGRESO' : 'EGRESO' }}
            </span>
            <div style="flex: 1;">
                <div class="text-sm font-bold">{{ $movement->description ?: 'Sin descripción' }}</div>
                <div class="text-xs text-muted">{{ $movement->created_at->setTimezone('America/La_Paz')->format('d/m/Y H:i') }}</div>
            </div>
            <div class="mono font-bold {{ $movement->isIncome() ? 'text-success' : 'text-danger' }}">
                {{ $movement->isIncome() ? '+' : '−' }} Bs {{ number_format($movement->amount, 2) }}
            </div>
        </div>
    @empty
        <div class="text-muted text-center" style="padding: 2rem 0;">Sin movimientos registrados aún</div>
    @endforelse
</div>

@endsection

@extends('layouts.app')
@section('title', 'Historial de Ventas')
 
@section('sidebar-nav')
    <span class="nav-section-label">Principal</span>
    <a href="{{ route('admin.dashboard') }}" class="nav-item"><span class="nav-icon">⬡</span> Dashboard</a>
    <span class="nav-section-label">Gestión</span>
    <a href="{{ route('admin.users.index') }}" class="nav-item"><span class="nav-icon">👥</span> Usuarios</a>
    <a href="{{ route('admin.sales.index') }}" class="nav-item active"><span class="nav-icon">🛒</span> Historial Ventas</a>
    <a href="{{ route('admin.shifts.index') }}" class="nav-item"><span class="nav-icon">⏱</span> Turnos</a>
@endsection
 
@section('page-title', 'Historial de Ventas')
@section('page-subtitle', 'Todas las transacciones registradas')
 
@section('content')
{{-- Filtros --}}
<div class="card mb-4">
    <form method="GET" action="{{ route('admin.sales.index') }}">
        <div class="flex gap-3 items-center">
            <div class="form-group" style="margin:0; flex:1">
                <input type="date" name="from" value="{{ request('from', today()->format('Y-m-d')) }}" style="padding: .5rem .75rem;">
            </div>
            <div class="form-group" style="margin:0; flex:1">
                <input type="date" name="to" value="{{ request('to', today()->format('Y-m-d')) }}" style="padding: .5rem .75rem;">
            </div>
            <div class="form-group" style="margin:0; flex:1">
                <select name="payment_method" style="padding: .5rem .75rem;">
                    <option value="">— Método de pago —</option>
                    <option value="CASH" {{ request('payment_method') === 'CASH' ? 'selected' : '' }}>Efectivo</option>
                    <option value="QR"   {{ request('payment_method') === 'QR'   ? 'selected' : '' }}>QR</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary" style="white-space:nowrap;">Filtrar</button>
            <a href="{{ route('admin.sales.index') }}" class="btn btn-ghost">Limpiar</a>
        </div>
    </form>
</div>
 
{{-- Totales del filtro --}}
<div class="stats-grid" style="grid-template-columns: repeat(3, 1fr);">
    <div class="stat-card">
        <div class="stat-label">Total Ventas</div>
        <div class="stat-value mono">{{ $sales->total() }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Total Efectivo</div>
        <div class="stat-value mono text-success">Bs {{ number_format($totalCash, 2) }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Total QR</div>
        <div class="stat-value mono text-accent">Bs {{ number_format($totalQr, 2) }}</div>
    </div>
</div>
 
<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Fecha/Hora</th>
                    <th>Cajero</th>
                    <th>Método</th>
                    <th>Productos</th>
                    <th>Total</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sales as $sale)
                    <tr>
                        <td class="mono text-xs text-muted">{{ $sale->id }}</td>
                        <td class="mono text-xs">{{ $sale->sale_time->format('d/m H:i') }}</td>
                        <td class="text-sm">{{ $sale->shift->user->name }}</td>
                        <td>
                            <span class="{{ $sale->payment_method === 'CASH' ? 'badge badge-success' : 'badge badge-info' }}">
                                {{ $sale->payment_method === 'CASH' ? 'Efectivo' : 'QR' }}
                            </span>
                        </td>
                        <td class="text-xs text-muted">
                            {{ $sale->details->map(fn($d) => $d->quantity.'× '.$d->product->name)->join(', ') }}
                        </td>
                        <td class="mono font-bold {{ $sale->status === 'VOIDED' ? 'text-danger' : 'text-success' }}">
                            Bs {{ number_format($sale->total_amount, 2) }}
                        </td>
                        <td>
                            <span class="{{ $sale->status === 'COMPLETED' ? 'badge badge-success' : 'badge badge-danger' }}">
                                {{ $sale->status === 'COMPLETED' ? 'OK' : 'Anulada' }}
                            </span>
                        </td>
                        <td>
                            @if($sale->status === 'COMPLETED')
                                <form method="POST" action="{{ route('admin.sales.void', $sale) }}"
                                      onsubmit="return confirm('¿Anular esta venta?')">
                                    @csrf
                                    <input type="hidden" name="void_reason" value="Anulada por administrador">
                                    <button type="submit" class="btn btn-danger btn-sm">Anular</button>
                                </form>
                            @else
                                <span class="text-xs text-muted">—</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted" style="padding: 3rem;">
                            No hay ventas en el período seleccionado
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="margin-top: 1rem;">{{ $sales->withQueryString()->links() }}</div>
</div>
@endsection
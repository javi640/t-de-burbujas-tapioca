@extends('layouts.app')
@section('title', 'Conciliación de Stock')


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
    <a href="{{ route('admin.shifts.index') }}" class="nav-item">
        <span class="nav-icon"><i class="bi bi-clock-fill"></i></span> Turnos
    </a>
    <span class="nav-section-label">Reportes</span>
    <a href="{{ route('admin.reports.daily') }}" class="nav-item">
        <span class="nav-icon"><i class="bi bi-file-earmark-text"></i></span> Cierre Diario
    </a>
    <a href="{{ route('admin.reports.stock') }}" class="nav-item active">
        <span class="nav-icon"><i class="bi bi-box-seam"></i></span> Conciliación Stock
    </a>
    <a href="{{ route('admin.audit.index') }}" class="nav-item">
        <span class="nav-icon"><i class="bi bi-search"></i></span> Auditoría
    </a>
@endsection

@section('page-title', 'Conciliación de Stock y Ventas')
@section('page-subtitle'){{ $date->format('d/m/Y') }}@endsection

@section('topbar-actions')
    <form method="GET" action="{{ route('admin.reports.stock') }}" style="display:flex; gap:.5rem; align-items:center;">
        <input type="date" name="fecha"
               value="{{ $date->format('Y-m-d') }}"
               max="{{ today()->format('Y-m-d') }}"
               style="padding:.35rem .75rem; background:var(--surface); border:1px solid var(--border); border-radius:8px; color:var(--text); font-size:.8rem; font-family:inherit;">
        <button type="submit" class="btn btn-primary btn-sm">Ver</button>
    </form>
    <button onclick="window.print()" class="btn btn-ghost btn-sm">
        
        Imprimir
    </button>
@endsection

@section('content')
<div class="print-header" style="display:none;">
    <h1>Panda Naicha &mdash; Conciliaci&oacute;n de Stock</h1>
    <p>Fecha: {{ $date->format('d/m/Y') }} &middot; Generado el {{ now()->setTimezone('America/La_Paz')->format('d/m/Y H:i') }}</p>
</div>

@if($shifts->isEmpty())
    <div class="card" style="text-align:center; padding:3rem;">
        <div style="font-size:2.5rem; margin-bottom:1rem;"><i class="bi bi-box-seam"></i></div>
        <div class="card-title" style="margin-bottom:.5rem;">Sin turnos cerrados</div>
        <div class="text-muted">No hay turnos cerrados para el {{ $date->format('d/m/Y') }}.</div>
    </div>
@else

{{-- ── Resumen general ─────────────────────────────────────────── --}}
@php
    $totalDiscrepancias = $productSummary->filter(fn($p) => $p->diferencia != 0)->count();
    $totalProductos     = $productSummary->count();
    $totalSalidaFisica  = $productSummary->sum('total_salida_fisica');
    $totalVendidoSistema = $productSummary->sum('total_vendido_sistema');
@endphp

<div class="stats-grid" style="margin-bottom:1rem;">
    <div class="stat-card">
        <div class="stat-label">Turnos analizados</div>
        <div class="stat-value">{{ $shifts->count() }}</div>
        <div class="stat-note">turnos cerrados del día</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Productos revisados</div>
        <div class="stat-value">{{ $totalProductos }}</div>
        <div class="stat-note">productos con movimiento</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Salida física total</div>
        <div class="stat-value mono">{{ $totalSalidaFisica }} u.</div>
        <div class="stat-note">inicial − restante</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Vendido en sistema</div>
        <div class="stat-value mono">{{ $totalVendidoSistema }} u.</div>
        <div class="stat-note">según ventas registradas</div>
    </div>
    <div class="stat-card" style="{{ $totalDiscrepancias > 0 ? 'border-color:rgba(239,68,68,.4); background:rgba(239,68,68,.04);' : 'border-color:rgba(34,197,94,.4); background:rgba(34,197,94,.04);' }}">
        <div class="stat-label">Discrepancias</div>
        <div class="stat-value {{ $totalDiscrepancias > 0 ? 'text-danger' : 'text-success' }}">{{ $totalDiscrepancias }}</div>
        <div class="stat-note">productos con diferencia</div>
    </div>
</div>

{{-- ── Tabla consolidada por producto ─────────────────────────── --}}
<div class="card" style="margin-bottom:1rem;">
    <div class="card-header">
        <div>
            <div class="card-title">
                
                Conciliación por producto
            </div>
            <div class="card-subtitle">Comparación de salida física vs ventas registradas en el sistema</div>
        </div>
    </div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Producto</th>
                    <th style="text-align:center;">Stock Inicial</th>
                    <th style="text-align:center;">Stock Final</th>
                    <th style="text-align:center;">Salida Física</th>
                    <th style="text-align:center;">Vendido Sistema</th>
                    <th style="text-align:center;">Diferencia</th>
                    <th style="text-align:center;">Estado</th>
                </tr>
            </thead>
            <tbody>
                @foreach($productSummary as $product)
                    @php
                        $diff = $product->diferencia;
                        $rowBg = $diff != 0 ? 'background:rgba(239,68,68,.04);' : '';
                    @endphp
                    <tr style="{{ $rowBg }}">
                        <td class="font-bold text-sm">{{ $product->name }}</td>
                        <td class="mono text-xs" style="text-align:center;">{{ $product->total_initial }} u.</td>
                        <td class="mono text-xs" style="text-align:center;">{{ $product->total_remaining }} u.</td>
                        <td class="mono text-xs" style="text-align:center; font-weight:600;">{{ $product->total_salida_fisica }} u.</td>
                        <td class="mono text-xs" style="text-align:center; color:var(--accent); font-weight:600;">{{ $product->total_vendido_sistema }} u.</td>
                        <td class="mono text-xs" style="text-align:center; font-weight:700; color:{{ $diff == 0 ? 'var(--success)' : ($diff > 0 ? 'var(--danger)' : 'var(--warning)') }};">
                            {{ $diff > 0 ? '+' : '' }}{{ $diff }} u.
                        </td>
                        <td style="text-align:center;">
                            @if($diff == 0)
                                <span class="badge badge-success"><i class="bi bi-check-circle-fill"></i> OK</span>
                            @elseif($diff > 0)
                                <span class="badge badge-danger"><i class="bi bi-exclamation-triangle-fill"></i> Faltante</span>
                            @else
                                <span class="badge badge-warning">↑ Sobrante</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr style="border-top:2px solid var(--border); font-weight:700;">
                    <td class="text-sm">TOTAL</td>
                    <td class="mono text-xs" style="text-align:center;">— </td>
                    <td class="mono text-xs" style="text-align:center;">—</td>
                    <td class="mono text-xs" style="text-align:center;">{{ $totalSalidaFisica }} u.</td>
                    <td class="mono text-xs" style="text-align:center; color:var(--accent);">{{ $totalVendidoSistema }} u.</td>
                    @php $totalDiff = $totalSalidaFisica - $totalVendidoSistema; @endphp
                    <td class="mono text-xs" style="text-align:center; color:{{ $totalDiff == 0 ? 'var(--success)' : 'var(--danger)' }};">
                        {{ $totalDiff > 0 ? '+' : '' }}{{ $totalDiff }} u.
                    </td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

{{-- ── Detalle por turno ───────────────────────────────────────── --}}
<div style="margin-bottom:1rem;">
    <div style="font-size:.8rem; font-weight:600; text-transform:uppercase; letter-spacing:.08em; color:var(--muted); margin-bottom:.75rem;">
        Detalle por turno
    </div>

    @foreach($shifts as $shift)
        <div class="card" style="margin-bottom:1rem;">
            <div class="card-header" style="margin-bottom:1rem; padding-bottom:1rem; border-bottom:1px solid var(--border);">
                <div class="flex items-center gap-3">
                    <div class="user-avatar" style="background:var(--accent);">
                        {{ strtoupper(substr($shift->user->name, 0, 2)) }}
                    </div>
                    <div>
                        <div class="font-bold">{{ $shift->user->name }}</div>
                        <div class="text-xs text-muted">
                            {{ $shift->start_time->format('H:i') }} → {{ $shift->end_time?->format('H:i') ?? '—' }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th style="text-align:center;">Inicial</th>
                            <th style="text-align:center;">Final</th>
                            <th style="text-align:center;">Salida física</th>
                            <th style="text-align:center;">Vendido sistema</th>
                            <th style="text-align:center;">Diferencia</th>
                        </tr>
                    </thead>
                    <tbody>
                            @foreach($shift->stock as $stock)
                            @php
                                $vendido      = $shift->sales->flatMap->details
                                    ->where('product_id', $stock->product_id)
                                    ->where('status', '!=', 'VOIDED')
                                    ->sum('quantity');
                                $final        = $stock->initial_quantity - $vendido;
                                $salidaFisica = $vendido;
                                $diff         = 0;
                            @endphp
                            <tr style="{{ $diff != 0 ? 'background:rgba(239,68,68,.04);' : '' }}">
                                <td class="text-sm">{{ $stock->product->name }}</td>
                                <td class="mono text-xs" style="text-align:center;">{{ $stock->initial_quantity }}</td>
                                <td class="mono text-xs" style="text-align:center;">{{ $final }}</td>
                                <td class="mono text-xs" style="text-align:center; font-weight:600;">{{ $salidaFisica }}</td>
                                <td class="mono text-xs" style="text-align:center; color:var(--accent);">{{ $vendido }}</td>
                                <td class="mono text-xs" style="text-align:center; font-weight:700; color:{{ $diff == 0 ? 'var(--success)' : ($diff > 0 ? 'var(--danger)' : 'var(--warning)') }};">
                                    {{ $diff > 0 ? '+' : '' }}{{ $diff }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endforeach
</div>

@endif

@endsection

@section('scripts')

@endsection
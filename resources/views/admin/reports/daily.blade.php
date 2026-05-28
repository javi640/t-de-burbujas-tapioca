@extends('layouts.app')
@section('title', 'Reporte de Cierre Diario')


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
    <a href="{{ route('admin.reports.daily') }}" class="nav-item active">
        <span class="nav-icon"><i class="bi bi-file-earmark-text"></i></span> Cierre Diario
    </a>
    <a href="{{ route('admin.audit.index') }}" class="nav-item">
        <span class="nav-icon"><i class="bi bi-search"></i></span> Auditoría
    </a>
@endsection

@section('page-title', 'Reporte de Cierre Diario')

@section('page-subtitle')
    {{ $date->format('d/m/Y') }}
@endsection

@section('topbar-actions')
    {{-- Selector de modo Cierre/Stock --}}
    <div style="display:flex; background:var(--surface); border:1px solid var(--border); border-radius:8px; overflow:hidden;">
        <a href="{{ route('admin.reports.daily', array_merge(request()->query(), ['modo' => 'cierre'])) }}"
           class="btn btn-sm {{ $mode === 'cierre' ? 'btn-primary' : 'btn-ghost' }}"
           style="border-radius:0; border:none; padding:.35rem .75rem;">
            Cierre Diario
        </a>
        <a href="{{ route('admin.reports.daily', array_merge(request()->query(), ['modo' => 'stock'])) }}"
           class="btn btn-sm {{ $mode === 'stock' ? 'btn-primary' : 'btn-ghost' }}"
           style="border-radius:0; border:none; padding:.35rem .75rem;">
            Conciliación
        </a>
    </div>
    {{-- Selector de fecha --}}
    <form method="GET" action="{{ route('admin.reports.daily') }}" style="display:flex; gap:.5rem; align-items:center;">
        <input type="hidden" name="modo" value="{{ $mode }}">
        <input type="date" name="fecha"
               value="{{ $date->format('Y-m-d') }}"
               max="{{ today()->format('Y-m-d') }}"
               style="padding:.35rem .75rem; background:var(--surface); border:1px solid var(--border); border-radius:8px; color:var(--text); font-size:.8rem; font-family:inherit;">
        <button type="submit" class="btn btn-primary btn-sm">Ver</button>
    </form>
    {{-- Botón imprimir --}}
    <button onclick="window.print()" class="btn btn-ghost btn-sm"><i class="bi bi-printer"></i> Imprimir</button>
@endsection

@section('content')
<div class="print-header" style="display:none;">
    <h1>Panda Naicha &mdash; Reporte de Cierre Diario</h1>
    <p>Fecha: {{ $date->format('d/m/Y') }} &middot; Generado el {{ now()->setTimezone('America/La_Paz')->format('d/m/Y H:i') }}</p>
</div>

@if($shifts->isEmpty())
    {{-- ── Sin datos para la fecha ──────────────────────────────── --}}
    <div class="card" style="text-align:center; padding:3rem;">
        <div style="font-size:2.5rem; margin-bottom:1rem;"><i class="bi bi-clipboard"></i></div>
        <div class="card-title" style="margin-bottom:.5rem;">Sin turnos cerrados</div>
        <div class="text-muted">No hay turnos cerrados registrados para el {{ $date->format('d/m/Y') }}.</div>
        <div class="text-muted text-sm" style="margin-top:.5rem;">Seleccioná otra fecha en el selector de arriba.</div>
    </div>
@else

@if($mode === 'cierre')
{{-- ── 1. Métricas del día ──────────────────────────────────────── --}}
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-label">Turnos cerrados</div>
        <div class="stat-value">{{ $shifts->count() }}</div>
        <div class="stat-note">{{ $totalSales }} ventas · {{ $totalVoided }} anuladas</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Total Efectivo</div>
        <div class="stat-value mono">Bs {{ number_format($totalCash, 2) }}</div>
        <div class="stat-note">recaudado en efectivo</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Total QR</div>
        <div class="stat-value mono text-accent">Bs {{ number_format($totalQr, 2) }}</div>
        <div class="stat-note">recaudado por QR</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Egresos del día</div>
        <div class="stat-value mono text-danger">Bs {{ number_format($totalExpenses, 2) }}</div>
        <div class="stat-note">gastos operativos</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Ingresos extra</div>
        <div class="stat-value mono text-warning">Bs {{ number_format($totalIncome, 2) }}</div>
        <div class="stat-note">ingresos extraordinarios</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Neto del día</div>
        <div class="stat-value mono text-success">Bs {{ number_format($netRevenue, 2) }}</div>
        <div class="stat-note">efectivo + QR − egresos + ingresos</div>
    </div>
</div>

{{-- ── 2. Resumen del árbol de decisiones ──────────────────────── --}}
<div class="card" style="margin-bottom:1rem;">
    <div class="card-header">
        <div>
            <div class="card-title">Resultado del árbol de decisiones</div>
            <div class="card-subtitle">Clasificación automática de inconsistencias por turno</div>
        </div>
        {{-- Leyenda de umbrales --}}
        <div style="font-size:.72rem; color:var(--muted); text-align:right; line-height:1.8;">
            <div>Umbral leve: diferencia ≤ Bs 20.00</div>
            <div>Umbral crítico: diferencia > Bs 20.00</div>
        </div>
    </div>

    {{-- Contadores de clasificación --}}
    <div style="display:grid; grid-template-columns:repeat(3,1fr); gap:1rem; margin-bottom:1.5rem;">
        <div style="text-align:center; padding:1rem; background:rgba(34,197,94,.08); border:1px solid rgba(34,197,94,.2); border-radius:10px;">
            <div style="font-size:1.75rem; font-weight:700; color:var(--success); font-family:'DM Mono',monospace;">{{ $decisionSummary['ok'] }}</div>
            <div style="font-size:.75rem; color:var(--success); font-weight:600; margin-top:.25rem;"><i class="bi bi-check-circle-fill"></i> Sin inconsistencia</div>
            <div style="font-size:.68rem; color:var(--muted); margin-top:.15rem;">Diferencia = Bs 0.00</div>
        </div>
        <div style="text-align:center; padding:1rem; background:rgba(245,158,11,.08); border:1px solid rgba(245,158,11,.2); border-radius:10px;">
            <div style="font-size:1.75rem; font-weight:700; color:var(--warning); font-family:'DM Mono',monospace;">{{ $decisionSummary['leve'] }}</div>
            <div style="font-size:.75rem; color:var(--warning); font-weight:600; margin-top:.25rem;"><i class="bi bi-exclamation-triangle-fill"></i> Inconsistencia leve</div>
            <div style="font-size:.68rem; color:var(--muted); margin-top:.15rem;">Diferencia ≤ Bs 20.00</div>
        </div>
        <div style="text-align:center; padding:1rem; background:rgba(239,68,68,.08); border:1px solid rgba(239,68,68,.2); border-radius:10px;">
            <div style="font-size:1.75rem; font-weight:700; color:var(--danger); font-family:'DM Mono',monospace;">{{ $decisionSummary['critica'] }}</div>
            <div style="font-size:.75rem; color:var(--danger); font-weight:600; margin-top:.25rem;"><i class="bi bi-x-circle-fill"></i> Inconsistencia crítica</div>
            <div style="font-size:.68rem; color:var(--muted); margin-top:.15rem;">Diferencia > Bs 20.00</div>
        </div>
    </div>

    {{-- Diagrama del árbol de decisiones (visual) --}}
    <div style="font-family:'DM Mono',monospace; font-size:.78rem; color:var(--text); line-height:2;">
        <div style="color:var(--accent);">[NODO RAÍZ] ¿Hay diferencia en el arqueo?</div>
            <div style="padding-left:1.5rem;">
                <span style="color:var(--success);">├── NO</span>
                <span style="color:var(--muted);"> → </span>
                <span style="background:rgba(34,197,94,.15); color:var(--success); padding:.1rem .5rem; border-radius:4px; font-size:.72rem;"><i class="bi bi-check-circle-fill"></i> SIN INCONSISTENCIA</span>
            </div>
            <div style="padding-left:1.5rem;">
                <span style="color:var(--danger);">└── SÍ</span>
                <span style="color:var(--muted);"> → [V1] ¿La diferencia es leve (≤ Bs 20)?</span>
            </div>
            <div style="padding-left:3.5rem;">
                <span style="color:var(--success);">├── SÍ (leve)</span>
                <span style="color:var(--muted);"> → [V2] ¿El cajero es reincidente?</span>
            </div>
            <div style="padding-left:5.5rem;">
                <span style="color:var(--danger);">├── SÍ</span>
                <span style="color:var(--muted);"> → </span>
                <span style="background:rgba(239,68,68,.15); color:var(--danger); padding:.1rem .5rem; border-radius:4px; font-size:.72rem;"><i class="bi bi-x-circle-fill"></i> INCONSISTENCIA CRÍTICA</span>
            </div>
            <div style="padding-left:5.5rem;">
                <span style="color:var(--warning);">└── NO</span>
                <span style="color:var(--muted);"> → </span>
                <span style="background:rgba(245,158,11,.15); color:var(--warning); padding:.1rem .5rem; border-radius:4px; font-size:.72rem;"><i class="bi bi-exclamation-triangle-fill"></i> INCONSISTENCIA LEVE</span>
            </div>
            <div style="padding-left:3.5rem;">
                <span style="color:var(--warning);">└── NO (alta > Bs 20)</span>
                <span style="color:var(--muted);"> → [V3] ¿Es faltante?</span>
            </div>
            <div style="padding-left:5.5rem;">
                <span style="color:var(--success);">├── SÍ (faltante)</span>
                <span style="color:var(--muted);"> → [V4] ¿Ventas QR explican el faltante?</span>
            </div>
            <div style="padding-left:7.5rem;">
                <span style="color:var(--warning);">├── SÍ</span>
                <span style="color:var(--muted);"> → </span>
                <span style="background:rgba(245,158,11,.15); color:var(--warning); padding:.1rem .5rem; border-radius:4px; font-size:.72rem;"><i class="bi bi-exclamation-triangle-fill"></i> INCONSISTENCIA LEVE</span>
            </div>
            <div style="padding-left:7.5rem;">
                <span style="color:var(--danger);">└── NO</span>
                <span style="color:var(--muted);"> → </span>
                <span style="background:rgba(239,68,68,.15); color:var(--danger); padding:.1rem .5rem; border-radius:4px; font-size:.72rem;"><i class="bi bi-x-circle-fill"></i> INCONSISTENCIA CRÍTICA</span>
            </div>
            <div style="padding-left:5.5rem;">
                <span style="color:var(--warning);">└── NO (sobrante)</span>
                <span style="color:var(--muted);"> → [V5] ¿Registró egresos?</span>
            </div>
    </div>
</div>

{{-- ── 3. Detalle por turno con resultado del árbol ────────────── --}}
<div style="margin-bottom:1rem;">
    <div style="font-size:.8rem; font-weight:600; text-transform:uppercase; letter-spacing:.08em; color:var(--muted); margin-bottom:.75rem;">
        Detalle por turno
    </div>

    @foreach($shiftsWithDecision as $row)
        @php
            $shift    = $row['shift'];
            $decision = $row['decision'];
            $expected = $row['expected'];
            $reported = $row['reported'];

            $borderColor = match($decision['color']) {
                'success' => 'rgba(34,197,94,.4)',
                'warning' => 'rgba(245,158,11,.4)',
                'danger'  => 'rgba(239,68,68,.4)',
            };
            $bgColor = match($decision['color']) {
                'success' => 'rgba(34,197,94,.04)',
                'warning' => 'rgba(245,158,11,.04)',
                'danger'  => 'rgba(239,68,68,.04)',
            };

            $shiftSalesCash = $shift->sales->where('status','COMPLETED')->where('payment_method','CASH')->sum('total_amount');
            $shiftSalesQr   = $shift->sales->where('status','COMPLETED')->where('payment_method','QR')->sum('total_amount');
            $shiftExpenses  = $shift->cashMovements->where('movement_type','EXPENSE')->sum('amount');
            $shiftIncomes   = $shift->cashMovements->where('movement_type','INCOME')->sum('amount');
            $shiftSalesCount = $shift->sales->where('status','COMPLETED')->count();
        @endphp

        <div class="card" style="margin-bottom:1rem; border-color:{{ $borderColor }}; background:{{ $bgColor }};">

            {{-- Cabecera del turno --}}
            <div class="flex items-center gap-3" style="margin-bottom:1rem; padding-bottom:1rem; border-bottom:1px solid var(--border);">
                <div class="user-avatar" style="background:var(--accent);">
                    {{ strtoupper(substr($shift->user->name, 0, 2)) }}
                </div>
                <div style="flex:1;">
                    <div class="font-bold">{{ $shift->user->name }}</div>
                    <div class="text-xs text-muted">
                        {{ $shift->start_time->format('H:i') }} → {{ $shift->end_time?->format('H:i') ?? '—' }}
                        · {{ (int) $shift->start_time->diffInMinutes($shift->end_time) }} min
                    </div>
                </div>
                {{-- Badge de resultado del árbol --}}
                <div style="text-align:right;">
                    <span style="
                        display:inline-block;
                        padding:.3rem .8rem;
                        border-radius:20px;
                        font-size:.72rem;
                        font-weight:700;
                        font-family:'DM Mono',monospace;
                        background: {{ match($decision['color']) {
                            'success' => 'rgba(34,197,94,.15)',
                            'warning' => 'rgba(245,158,11,.15)',
                            'danger'  => 'rgba(239,68,68,.15)',
                        } }};
                        color: var(--{{ $decision['color'] }});
                        border: 1px solid {{ $borderColor }};
                    ">
                        {{ $decision['icon'] }} {{ $decision['label'] }}
                    </span>
                </div>
            </div>

            <div class="grid grid-2" style="gap:1rem;">

                {{-- Columna izquierda: arqueo numérico --}}
                <div>
                    <div style="font-size:.68rem; font-weight:600; text-transform:uppercase; letter-spacing:.08em; color:var(--muted); margin-bottom:.75rem;">Arqueo de caja</div>
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:.5rem; margin-bottom:.75rem;">
                        <div style="background:rgba(184,204,202,.15); border-radius:8px; padding:.75rem;">
                            <div style="font-size:.65rem; color:var(--muted); margin-bottom:.25rem;">Ef. inicial</div>
                            <div class="mono" style="font-size:.9rem;">Bs {{ number_format($shift->initial_cash, 2) }}</div>
                        </div>
                        <div style="background:rgba(184,204,202,.15); border-radius:8px; padding:.75rem;">
                            <div style="font-size:.65rem; color:var(--muted); margin-bottom:.25rem;">Ventas efectivo</div>
                            <div class="mono" style="font-size:.9rem;">Bs {{ number_format($shiftSalesCash, 2) }}</div>
                        </div>
                        <div style="background:rgba(184,204,202,.15); border-radius:8px; padding:.75rem;">
                            <div style="font-size:.65rem; color:var(--muted); margin-bottom:.25rem;">Egresos</div>
                            <div class="mono text-danger" style="font-size:.9rem;">− Bs {{ number_format($shiftExpenses, 2) }}</div>
                        </div>
                        <div style="background:rgba(184,204,202,.15); border-radius:8px; padding:.75rem;">
                            <div style="font-size:.65rem; color:var(--muted); margin-bottom:.25rem;">Ing. extra</div>
                            <div class="mono text-warning" style="font-size:.9rem;">+ Bs {{ number_format($shiftIncomes, 2) }}</div>
                        </div>
                    </div>
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:.5rem;">
                        <div style="background:rgba(184,204,202,.2); border-radius:8px; padding:.75rem; border:1px solid rgba(79,142,247,.2);">
                            <div style="font-size:.65rem; color:var(--muted); margin-bottom:.25rem;">Esperado</div>
                            <div class="mono text-accent" style="font-size:1.1rem; font-weight:700;">Bs {{ number_format($expected, 2) }}</div>
                        </div>
                        <div style="background:rgba(184,204,202,.2); border-radius:8px; padding:.75rem; border:1px solid {{ $borderColor }};">
                            <div style="font-size:.65rem; color:var(--muted); margin-bottom:.25rem;">Declarado</div>
                            <div class="mono" style="font-size:1.1rem; font-weight:700; color:var(--{{ $decision['color'] }});">Bs {{ number_format($reported, 2) }}</div>
                        </div>
                    </div>
                    {{-- Diferencia destacada --}}
                    <div style="margin-top:.5rem; padding:.6rem .75rem; border-radius:8px; background:rgba(184,204,202,.15); border:1px solid {{ $borderColor }}; display:flex; justify-content:space-between; align-items:center;">
                        <span style="font-size:.75rem; color:var(--muted);">Diferencia ({{ $decision['type'] }})</span>
                        <span class="mono" style="font-size:1rem; font-weight:700; color:var(--{{ $decision['color'] }});">
                            Bs {{ number_format($shift->cash_difference, 2) }}
                        </span>
                    </div>
                </div>

                {{-- Columna derecha: resultado del árbol + recomendación --}}
                <div>
                    <div style="font-size:.68rem; font-weight:600; text-transform:uppercase; letter-spacing:.08em; color:var(--muted); margin-bottom:.75rem;">Decisión del árbol</div>

                    {{-- Recorrido del árbol para este turno --}}
                    <div style="background:rgba(184,204,202,.2); border-radius:8px; padding:.85rem; margin-bottom:.75rem; font-family:'DM Mono',monospace; font-size:.72rem; line-height:1.9;">
                        @foreach($decision['decision_path'] as $pregunta => $respuesta)
                            <div>
                                <span style="color:var(--muted);">{{ $pregunta }}</span>
                            </div>
                            <div style="padding-left:1rem; margin-bottom:.2rem;">
                                <span style="color:var(--{{ $decision['color'] }});">→ {{ $respuesta }}</span>
                            </div>
                        @endforeach
                        <div style="border-top:1px solid var(--border); margin-top:.4rem; padding-top:.4rem;">
                            <span style="color:var(--{{ $decision['color'] }}); font-weight:700;">
                                {{ $decision['icon'] }} {{ strtoupper($decision['label']) }}
                            </span>
                        </div>
                    </div>

                    {{-- Descripción --}}
                    <div style="font-size:.78rem; color:var(--muted); line-height:1.6; margin-bottom:.75rem;">
                        {{ $decision['description'] }}
                    </div>

                    {{-- Recomendación --}}
                    <div style="
                        padding:.75rem;
                        border-radius:8px;
                        border-left:3px solid var(--{{ $decision['color'] }});
                        background:rgba(184,204,202,.15);
                        font-size:.75rem;
                        line-height:1.6;
                        color:var(--text);
                    ">
                        <div style="font-size:.65rem; font-weight:700; text-transform:uppercase; letter-spacing:.06em; color:var(--{{ $decision['color'] }}); margin-bottom:.35rem;">
                            Recomendación
                        </div>
                        {{ $decision['recommendation'] }}
                    </div>

                    {{-- Mini stats ventas --}}
                    <div class="flex gap-3" style="margin-top:.75rem; font-size:.72rem; color:var(--muted);">
                        <span><i class="bi bi-cart3"></i> {{ $shiftSalesCount }} ventas</span>
                        <span><i class="bi bi-qr-code-scan"></i> Bs {{ number_format($shiftSalesQr, 2) }} QR</span>
                    </div>
                    @if($shift->notes)
                        <div style="margin-top:.5rem; font-size:.72rem; color:var(--muted); font-style:italic;">
                            <i class="bi bi-pencil-square"></i> "{{ $shift->notes }}"
                        </div>
                    @endif
                </div>
            </div>

            {{-- Ventas del turno (colapsable) --}}
            @if($shift->sales->count() > 0)
                <div style="margin-top:1rem; border-top:1px solid var(--border); padding-top:1rem;">
                    <button onclick="toggleDetail('sales-{{ $shift->id }}')"
                            style="font-size:.75rem; color:var(--accent); background:none; border:none; cursor:pointer; padding:0; font-family:inherit;">
                        ▼ Ver {{ $shift->sales->count() }} transacciones del turno
                    </button>
                    <div id="sales-{{ $shift->id }}" style="display:none; margin-top:.75rem; overflow-x:auto;">
                        <table style="width:100%; border-collapse:collapse; font-size:.8rem;">
                            <thead>
                                <tr>
                                    <th style="text-align:left; padding:.5rem .75rem; border-bottom:1px solid var(--border); color:var(--muted); font-size:.65rem; text-transform:uppercase; letter-spacing:.06em;">Hora</th>
                                    <th style="text-align:left; padding:.5rem .75rem; border-bottom:1px solid var(--border); color:var(--muted); font-size:.65rem; text-transform:uppercase; letter-spacing:.06em;">Productos</th>
                                    <th style="text-align:left; padding:.5rem .75rem; border-bottom:1px solid var(--border); color:var(--muted); font-size:.65rem; text-transform:uppercase; letter-spacing:.06em;">Método</th>
                                    <th style="text-align:right; padding:.5rem .75rem; border-bottom:1px solid var(--border); color:var(--muted); font-size:.65rem; text-transform:uppercase; letter-spacing:.06em;">Total</th>
                                    <th style="text-align:left; padding:.5rem .75rem; border-bottom:1px solid var(--border); color:var(--muted); font-size:.65rem; text-transform:uppercase; letter-spacing:.06em;">Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($shift->sales as $sale)
                                    <tr style="border-bottom:1px solid rgba(184,204,202,.5);">
                                        <td style="padding:.5rem .75rem;" class="mono text-xs">{{ $sale->sale_time->format('H:i') }}</td>
                                        <td style="padding:.5rem .75rem;" class="text-xs text-muted">
                                            {{ $sale->details->map(fn($d) => $d->quantity.'× '.$d->product->name)->join(', ') }}
                                        </td>
                                        <td style="padding:.5rem .75rem;">
                                            <span class="{{ $sale->payment_method === 'CASH' ? 'badge badge-success' : 'badge badge-info' }}">
                                                {{ $sale->payment_method === 'CASH' ? 'Efectivo' : 'QR' }}
                                            </span>
                                        </td>
                                        <td style="padding:.5rem .75rem; text-align:right;" class="mono {{ $sale->status === 'VOIDED' ? 'text-danger' : '' }}">
                                            Bs {{ number_format($sale->total_amount, 2) }}
                                        </td>
                                        <td style="padding:.5rem .75rem;">
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
            @endif
        </div>
    @endforeach
</div>

{{-- ── 4. Productos más vendidos del día ───────────────────────── --}}
    @if($topProducts->isNotEmpty())
    <div class="card" style="margin-bottom:1rem;">
        <div class="card-header">
            <div class="card-title">Productos vendidos en el día</div>
            <div class="card-subtitle">Consolidado de todos los turnos</div>
        </div>
        @php $maxUnits = $topProducts->max('units_sold') ?: 1; @endphp
        @foreach($topProducts as $i => $product)
            @php $pct = round(($product->units_sold / $maxUnits) * 100); @endphp
            <div style="margin-bottom:.85rem;">
                <div class="flex items-center gap-2" style="margin-bottom:.3rem;">
                    <span class="mono" style="font-size:.7rem; width:1.4rem; height:1.4rem; display:flex; align-items:center; justify-content:center; background:rgba(29,106,150,.12); border-radius:50%; color:var(--accent); font-weight:700; flex-shrink:0;">{{ $i+1 }}</span>
                    <span class="text-sm font-bold" style="flex:1;">{{ $product->name }}</span>
                    <span class="mono text-xs text-muted">{{ $product->units_sold }} u.</span>
                    <span class="mono text-sm text-success">Bs {{ number_format($product->revenue, 2) }}</span>
                </div>
                <div style="height:5px; background:var(--border); border-radius:3px; overflow:hidden; margin-left:1.85rem;">
                    <div style="height:100%; width:{{ $pct }}%; background:var(--success); border-radius:3px;"></div>
                </div>
            </div>
        @endforeach
    </div>
    @endif

@else {{-- modo stock --}}
{{-- MODO CONCILIACIÓN DE STOCK --}}
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
    </div>

    <div class="card" style="margin-bottom:1rem;">
        <div class="card-header">
            <div>
                <div class="card-title">Conciliación por producto</div>
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
                    @forelse($productSummary as $product)
                        <tr>
                            <td class="font-bold text-sm">{{ $product->name }}</td>
                            <td class="mono text-xs" style="text-align:center;">{{ $product->total_initial }} u.</td>
                            <td class="mono text-xs" style="text-align:center;">{{ $product->total_remaining }} u.</td>
                            <td class="mono text-xs" style="text-align:center; font-weight:600;">{{ $product->total_salida_fisica }} u.</td>
                            <td class="mono text-xs" style="text-align:center; color:var(--accent); font-weight:600;">{{ $product->total_vendido_sistema }} u.</td>
                            <td class="mono text-xs" style="text-align:center; font-weight:700; color:var(--success);">
                                0 u.
                            </td>
                            <td style="text-align:center;">
                                <span class="badge badge-success"><i class="bi bi-check-circle-fill"></i> OK</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted" style="padding:2rem;">No hay productos con movimiento</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

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
                                <th style="text-align:center;">Vendido sistema</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($shift->stock as $stock)
                                @php
                                    $vendido = $shift->sales->flatMap->details
                                        ->where('product_id', $stock->product_id)
                                        ->where('status', '!=', 'VOIDED')
                                        ->sum('quantity');
                                    $final = $stock->initial_quantity - $vendido;
                                @endphp
                                <tr>
                                    <td class="text-sm">{{ $stock->product->name }}</td>
                                    <td class="mono text-xs" style="text-align:center;">{{ $stock->initial_quantity }}</td>
                                    <td class="mono text-xs" style="text-align:center;">{{ $final }}</td>
                                    <td class="mono text-xs" style="text-align:center; color:var(--accent);">{{ $vendido }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endforeach
    </div>
@endif {{-- end mode --}}

@endif {{-- end @if($shifts->isEmpty()) --}}

@endsection

@section('scripts')
<script>
function toggleDetail(id) {
    const el = document.getElementById(id);
    const btn = el.previousElementSibling;
    if (el.style.display === 'none') {
        el.style.display = 'block';
        btn.textContent = btn.textContent.replace('▼', '▲');
    } else {
        el.style.display = 'none';
        btn.textContent = btn.textContent.replace('▲', '▼');
    }
}
</script>

{{-- Estilos para impresión --}}

@endsection
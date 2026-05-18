{{-- resources/views/admin/reports/daily.blade.php --}}
@extends('layouts.app')
@section('title', 'Reporte de Cierre Diario')

@section('sidebar-nav')
    <span class="nav-section-label">Principal</span>
    <a href="{{ route('admin.dashboard') }}" class="nav-item"><i class="bi bi-speedometer2"></i> Dashboard</a>
    <span class="nav-section-label">Gestión</span>
    <a href="{{ route('admin.users.index') }}" class="nav-item"><i class="bi bi-people-fill"></i> Usuarios</a>
    <a href="{{ route('admin.sales.index') }}" class="nav-item"><i class="bi bi-bag-check"></i> Historial Ventas</a>
    <a href="{{ route('admin.shifts.index') }}" class="nav-item"><i class="bi bi-clock-history"></i> Turnos</a>
    <span class="nav-section-label">Reportes</span>
    <a href="{{ route('admin.reports.daily') }}" class="nav-item active"><i class="bi bi-file-earmark"></i> Cierre Diario</a>
@endsection

@section('page-title', 'Reporte de Cierre Diario')

@section('page-subtitle')
    {{ $date->translatedFormat('l d \d\e F \d\e Y') }}
@endsection

@section('topbar-actions')
    {{-- Selector de fecha --}}
    <form method="GET" action="{{ route('admin.reports.daily') }}" style="display:flex; gap:.5rem; align-items:center;">
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

@if($shifts->isEmpty())
    {{-- ── Sin datos para la fecha ──────────────────────────────── --}}
    <div class="card" style="text-align:center; padding:3rem;">
        <div style="font-size:2.5rem; margin-bottom:1rem;"><i class="bi bi-file-earmark" style="font-size:2.5rem;"></i></div>
        <div class="card-title" style="margin-bottom:.5rem;">Sin turnos cerrados</div>
        <div class="text-muted">No hay turnos cerrados registrados para el {{ $date->format('d/m/Y') }}.</div>
        <div class="text-muted text-sm" style="margin-top:.5rem;">Seleccioná otra fecha en el selector de arriba.</div>
    </div>
@else

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
            <div style="font-size:.75rem; color:var(--success); font-weight:600; margin-top:.25rem;">✓ Sin inconsistencia</div>
            <div style="font-size:.68rem; color:var(--muted); margin-top:.15rem;">Diferencia = Bs 0.00</div>
        </div>
        <div style="text-align:center; padding:1rem; background:rgba(245,158,11,.08); border:1px solid rgba(245,158,11,.2); border-radius:10px;">
            <div style="font-size:1.75rem; font-weight:700; color:var(--warning); font-family:'DM Mono',monospace;">{{ $decisionSummary['leve'] }}</div>
            <div style="font-size:.75rem; color:var(--warning); font-weight:600; margin-top:.25rem;">⚠ Inconsistencia leve</div>
            <div style="font-size:.68rem; color:var(--muted); margin-top:.15rem;">Diferencia ≤ Bs 20.00</div>
        </div>
        <div style="text-align:center; padding:1rem; background:rgba(239,68,68,.08); border:1px solid rgba(239,68,68,.2); border-radius:10px;">
            <div style="font-size:1.75rem; font-weight:700; color:var(--danger); font-family:'DM Mono',monospace;">{{ $decisionSummary['critica'] }}</div>
            <div style="font-size:.75rem; color:var(--danger); font-weight:600; margin-top:.25rem;">✕ Inconsistencia crítica</div>
            <div style="font-size:.68rem; color:var(--muted); margin-top:.15rem;">Diferencia > Bs 20.00</div>
        </div>
    </div>

    {{-- Diagrama del árbol de decisiones (visual) --}}
    <div style="background:rgba(184,204,202,.2); border-radius:10px; padding:1.25rem; border:1px solid var(--border);">
        <div style="font-size:.7rem; font-weight:600; text-transform:uppercase; letter-spacing:.08em; color:var(--muted); margin-bottom:1rem;">
            Lógica del árbol aplicada
        </div>
        <div style="font-family:'DM Mono',monospace; font-size:.78rem; color:var(--text); line-height:2;">
            <div style="color:var(--accent);">[NODO RAÍZ]  ¿Existe diferencia entre efectivo esperado y declarado?</div>
            <div style="padding-left:1.5rem;">
                <span style="color:var(--success);">├── NO</span>
                <span style="color:var(--muted);"> → </span>
                <span style="background:rgba(34,197,94,.15); color:var(--success); padding:.1rem .5rem; border-radius:4px; font-size:.72rem;">✓ SIN INCONSISTENCIA</span>
                <span style="color:var(--muted); font-size:.68rem;"> (diferencia = Bs 0.00)</span>
            </div>
            <div style="padding-left:1.5rem;">
                <span style="color:var(--danger);">└── SÍ</span>
                <span style="color:var(--muted);"> → [NODO 2]  ¿La diferencia es negativa (faltante)?</span>
            </div>
            <div style="padding-left:3.5rem;">
                <span style="color:var(--success);">├── SÍ (faltante)</span>
                <span style="color:var(--muted);"> → [NODO 3a] ¿El faltante supera Bs {{ number_format(20, 2) }}?</span>
            </div>
            <div style="padding-left:5.5rem;">
                <span style="color:var(--danger);">├── SÍ</span>
                <span style="color:var(--muted);"> → </span>
                <span style="background:rgba(239,68,68,.15); color:var(--danger); padding:.1rem .5rem; border-radius:4px; font-size:.72rem;">✕ INCONSISTENCIA CRÍTICA</span>
            </div>
            <div style="padding-left:5.5rem;">
                <span style="color:var(--warning);">└── NO</span>
                <span style="color:var(--muted);"> → </span>
                <span style="background:rgba(245,158,11,.15); color:var(--warning); padding:.1rem .5rem; border-radius:4px; font-size:.72rem;">⚠ INCONSISTENCIA LEVE</span>
            </div>
            <div style="padding-left:3.5rem;">
                <span style="color:var(--warning);">└── NO (sobrante)</span>
                <span style="color:var(--muted);"> → [NODO 3b] ¿El sobrante supera Bs {{ number_format(20, 2) }}?</span>
            </div>
            <div style="padding-left:5.5rem;">
                <span style="color:var(--danger);">├── SÍ</span>
                <span style="color:var(--muted);"> → </span>
                <span style="background:rgba(239,68,68,.15); color:var(--danger); padding:.1rem .5rem; border-radius:4px; font-size:.72rem;">✕ INCONSISTENCIA CRÍTICA</span>
            </div>
            <div style="padding-left:5.5rem;">
                <span style="color:var(--warning);">└── NO</span>
                <span style="color:var(--muted);"> → </span>
                <span style="background:rgba(245,158,11,.15); color:var(--warning); padding:.1rem .5rem; border-radius:4px; font-size:.72rem;">⚠ INCONSISTENCIA LEVE</span>
            </div>
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
                        · {{ $shift->start_time->diffInMinutes($shift->end_time) }} min
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
                        <div style="background:rgba(184,204,202,.2); border-radius:8px; padding:.75rem; border:1px solid rgba(133,184,203,.3);">
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
                        <span>🛒 {{ $shiftSalesCount }} ventas</span>
                        <span>📱 Bs {{ number_format($shiftSalesQr, 2) }} QR</span>
                    </div>
                    @if($shift->notes)
                        <div style="margin-top:.5rem; font-size:.72rem; color:var(--muted); font-style:italic;">
                            📝 "{{ $shift->notes }}"
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
                                    <tr style="border-bottom:1px solid rgba(184,204,202,.4);">
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
                <span class="mono" style="font-size:.7rem; width:1.4rem; height:1.4rem; display:flex; align-items:center; justify-content:center; background:rgba(133,184,203,.15); border-radius:50%; color:var(--accent); font-weight:700; flex-shrink:0;">{{ $i+1 }}</span>
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
<style>
@media print {
    .sidebar, .topbar, button, form { display: none !important; }
    .main { margin-left: 0 !important; }
    .card { break-inside: avoid; border: 1px solid #ccc !important; background: white !important; color: black !important; }
    [style*="display:none"] { display: block !important; }
}
</style>
@endsection
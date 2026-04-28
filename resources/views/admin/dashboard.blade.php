@extends('layouts.app')
@section('title', 'Dashboard Admin')

@section('sidebar-nav')
    <span class="nav-section-label">Principal</span>
    <a href="{{ route('admin.dashboard') }}" class="nav-item active">
        <span class="nav-icon">⬡</span> Dashboard
    </a>
    <span class="nav-section-label">Gestión</span>
    <a href="{{ route('admin.users.index') }}" class="nav-item">
        <span class="nav-icon">👥</span> Usuarios
    </a>
    <a href="{{ route('admin.sales.index') }}" class="nav-item">
        <span class="nav-icon">🛒</span> Historial Ventas
    </a>
    <a href="{{ route('admin.shifts.index') }}" class="nav-item">
        <span class="nav-icon">⏱</span> Turnos
    </a>
    <span class="nav-section-label">Reportes</span>
    <a href="{{ route('admin.reports.daily') }}" class="nav-item">
        <span class="nav-icon">📋</span> Cierre Diario
    </a>
    <a href="{{ route('admin.audit.index') }}" class="nav-item">
        <span class="nav-icon">🔍</span> Auditoría
    </a>
@endsection

@section('page-title', 'Dashboard')

@section('page-subtitle')
    Resumen {{ $label }} · <span id="live-date"></span>
@endsection

@section('topbar-actions')
    <span id="live-clock" class="text-xs text-muted mono"></span>

    {{-- ── Selector Hoy / Esta semana ── --}}
    <div style="display:flex; gap:.35rem; background:rgba(42,53,72,.5); border:1px solid var(--border); border-radius:8px; padding:.25rem;">
        <a href="{{ route('admin.dashboard', ['modo' => 'hoy']) }}"
           style="padding:.3rem .75rem; border-radius:6px; font-size:.78rem; font-weight:600; text-decoration:none;
                  {{ $mode === 'hoy' ? 'background:var(--accent); color:#fff;' : 'color:var(--muted);' }}">
            Hoy
        </a>
        <a href="{{ route('admin.dashboard', ['modo' => 'semana']) }}"
           style="padding:.3rem .75rem; border-radius:6px; font-size:.78rem; font-weight:600; text-decoration:none;
                  {{ $mode === 'semana' ? 'background:var(--accent); color:#fff;' : 'color:var(--muted);' }}">
            Esta semana
        </a>
    </div>

    <button onclick="location.reload()" class="btn btn-ghost btn-sm" title="Actualizar">↻</button>
@endsection

@section('content')

{{-- ── 1. Tarjetas métricas ─────────────────────────────────────── --}}
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon">⏱</div>
        <div class="stat-label">Turnos Abiertos</div>
        <div class="stat-value">{{ $openShifts }}</div>
        <div class="stat-note">en este momento</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">🛒</div>
        <div class="stat-label">Ventas {{ ucfirst($label) }}</div>
        <div class="stat-value">{{ $todaySalesCount }}</div>
        <div class="stat-note">transacciones completadas</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">💵</div>
        <div class="stat-label">Total Efectivo</div>
        <div class="stat-value mono">Bs {{ number_format($todayCash, 2) }}</div>
        <div class="stat-note">recaudado {{ $label }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">📱</div>
        <div class="stat-label">Total QR</div>
        <div class="stat-value mono text-accent">Bs {{ number_format($todayQr, 2) }}</div>
        <div class="stat-note">recaudado {{ $label }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">💰</div>
        <div class="stat-label">Total General</div>
        <div class="stat-value mono text-success">Bs {{ number_format($todayTotal, 2) }}</div>
        <div class="stat-note">efectivo + QR</div>
    </div>
</div>

{{-- ── 2. Gráfico + Turnos activos ──────────────────────────────── --}}
<div class="grid grid-2" style="margin-bottom:1rem;">

    {{-- Gráfico de barras (por hora o por día) --}}
    <div class="card">
        <div class="card-header">
            <div>
                <div class="card-title">
                    {{ $chartMode === 'week' ? 'Ventas por día' : 'Ventas por hora' }}
                </div>
                <div class="card-subtitle">
                    {{ $chartMode === 'week' ? 'Total en Bs por día de la semana' : 'Total en Bs por franja horaria (hoy)' }}
                </div>
            </div>
        </div>

        @php
            $chartItems  = collect($hourlyData);
            $maxChart    = $chartItems->max('total');
            $maxChart    = $maxChart > 0 ? $maxChart : 1;
        @endphp

        <div style="display:flex; align-items:flex-end; gap:{{ $chartMode === 'week' ? '8px' : '4px' }}; height:140px; padding:0 .5rem;">
            @foreach($chartItems as $item)
                @php
                    $barH    = max(3, ($item['total'] / $maxChart) * 120);
                    $isNow   = $item['isToday'] ?? false;
                    $hasData = $item['total'] > 0;
                    $bgColor = $isNow
                        ? 'var(--accent)'
                        : ($hasData ? 'rgba(79,142,247,0.45)' : 'rgba(42,53,72,0.4)');
                @endphp
                <div style="flex:1; display:flex; flex-direction:column; align-items:center; gap:2px;"
                     title="{{ $item['label'] }} — Bs {{ number_format($item['total'], 2) }} ({{ $item['count'] }} ventas)">
                    @if($hasData)
                        <span style="font-size:.55rem; color:var(--muted); font-family:'DM Mono',monospace; white-space:nowrap;">
                            {{ number_format($item['total'], 0) }}
                        </span>
                    @else
                        <span style="font-size:.55rem; color:transparent;">0</span>
                    @endif
                    <div style="width:100%; height:{{ $barH }}px; background:{{ $bgColor }}; border-radius:3px 3px 0 0; margin-top:auto;"></div>
                    <span style="font-size:.6rem; color:{{ $isNow ? 'var(--accent)' : 'var(--muted)' }}; font-family:'DM Mono',monospace; font-weight:{{ $isNow ? '700' : '400' }};">
                        {{ $chartMode === 'week' ? $item['label'] : substr($item['label'], 0, 2) }}
                    </span>
                </div>
            @endforeach
        </div>

        @if($todaySalesCount === 0)
            <div class="text-center text-muted" style="padding:.5rem 0; font-size:.8rem;">
                Sin ventas {{ $label }}
            </div>
        @endif
    </div>

    {{-- Turnos activos (siempre en tiempo real) --}}
    <div class="card">
        <div class="card-header">
            <div>
                <div class="card-title">Turnos Activos</div>
                <div class="card-subtitle">Cajeros trabajando ahora</div>
            </div>
            <a href="{{ route('admin.shifts.index') }}" class="btn btn-ghost btn-sm">Ver todos</a>
        </div>

        @forelse($activeShifts as $shift)
            @php
                $shiftTotal = $shift->sales->where('status', 'COMPLETED')->sum('total_amount');
                $shiftCash  = $shift->sales->where('status', 'COMPLETED')->where('payment_method', 'CASH')->sum('total_amount');
                $shiftQr    = $shift->sales->where('status', 'COMPLETED')->where('payment_method', 'QR')->sum('total_amount');
                $shiftCount = $shift->sales->where('status', 'COMPLETED')->count();
                $duration   = $shift->start_time->diffForHumans(null, true);
            @endphp
            <div style="padding:.75rem; background:rgba(79,142,247,.05); border-radius:8px; border:1px solid var(--border); margin-bottom:.75rem;">
                <div class="flex items-center gap-3" style="margin-bottom:.5rem;">
                    <div class="user-avatar" style="background:var(--success);">
                        {{ strtoupper(substr($shift->user->name, 0, 2)) }}
                    </div>
                    <div style="flex:1">
                        <div class="text-sm font-bold">{{ $shift->user->name }}</div>
                        <div class="text-xs text-muted">Desde {{ $shift->start_time->format('H:i') }} · hace {{ $duration }}</div>
                    </div>
                    <div class="text-right">
                        <div class="mono text-sm text-success">Bs {{ number_format($shiftTotal, 2) }}</div>
                        <div class="text-xs text-muted">{{ $shiftCount }} venta{{ $shiftCount !== 1 ? 's' : '' }}</div>
                    </div>
                </div>
                <div class="flex gap-3" style="font-size:.72rem; color:var(--muted);">
                    <span>💵 Bs {{ number_format($shiftCash, 2) }}</span>
                    <span>📱 Bs {{ number_format($shiftQr, 2) }}</span>
                </div>
            </div>
        @empty
            <div class="text-center text-muted" style="padding:2rem 0;">
                <div style="font-size:2rem; margin-bottom:.5rem;">⏸</div>
                <div>No hay turnos abiertos</div>
            </div>
        @endforelse
    </div>
</div>

{{-- ── 3. Rendimiento cajeros + Top productos ───────────────────── --}}
<div class="grid grid-2" style="margin-bottom:1rem;">

    <div class="card">
        <div class="card-header">
            <div class="card-title">Rendimiento por cajero</div>
            <div class="card-subtitle">Totales {{ $label }}</div>
        </div>
        @if($salesByCashier->isEmpty())
            <div class="text-center text-muted" style="padding:2rem 0;">Sin ventas {{ $label }}</div>
        @else
            @php $maxCashier = $salesByCashier->max('grand_total') ?: 1; @endphp
            @foreach($salesByCashier as $row)
                @php $pct = round(($row->grand_total / $maxCashier) * 100); @endphp
                <div style="margin-bottom:1rem;">
                    <div class="flex items-center gap-2" style="margin-bottom:.3rem;">
                        <div class="user-avatar" style="width:1.75rem;height:1.75rem;font-size:.7rem;background:var(--accent);">
                            {{ strtoupper(substr($row->cashier_name, 0, 2)) }}
                        </div>
                        <span class="text-sm font-bold" style="flex:1;">{{ $row->cashier_name }}</span>
                        <span class="mono text-sm text-success">Bs {{ number_format($row->grand_total, 2) }}</span>
                    </div>
                    <div style="height:6px; background:var(--border); border-radius:3px; overflow:hidden;">
                        <div style="height:100%; width:{{ $pct }}%; background:var(--accent); border-radius:3px;"></div>
                    </div>
                    <div class="flex gap-3" style="font-size:.72rem; color:var(--muted); margin-top:.25rem;">
                        <span>💵 Bs {{ number_format($row->cash_total, 2) }}</span>
                        <span>📱 Bs {{ number_format($row->qr_total, 2) }}</span>
                        <span>{{ $row->sale_count }} ventas</span>
                    </div>
                </div>
            @endforeach
        @endif
    </div>

    <div class="card">
        <div class="card-header">
            <div class="card-title">Top productos</div>
            <div class="card-subtitle">Por unidades vendidas {{ $label }}</div>
        </div>
        @if($topProducts->isEmpty())
            <div class="text-center text-muted" style="padding:2rem 0;">Sin ventas {{ $label }}</div>
        @else
            @php $maxUnits = $topProducts->max('units_sold') ?: 1; @endphp
            @foreach($topProducts as $i => $product)
                @php $pct = round(($product->units_sold / $maxUnits) * 100); @endphp
                <div style="margin-bottom:.85rem;">
                    <div class="flex items-center gap-2" style="margin-bottom:.25rem;">
                        <span class="mono" style="font-size:.7rem; width:1.4rem; height:1.4rem; display:flex; align-items:center; justify-content:center; background:rgba(79,142,247,.12); border-radius:50%; color:var(--accent); font-weight:700; flex-shrink:0;">{{ $i + 1 }}</span>
                        <span class="text-sm font-bold" style="flex:1;">{{ $product->name }}</span>
                        <span class="mono text-xs text-muted">{{ $product->units_sold }} u.</span>
                        <span class="mono text-sm text-success">Bs {{ number_format($product->revenue, 2) }}</span>
                    </div>
                    <div style="height:5px; background:var(--border); border-radius:3px; overflow:hidden; margin-left:1.85rem;">
                        <div style="height:100%; width:{{ $pct }}%; background:var(--success); border-radius:3px;"></div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</div>

{{-- ── 4. Últimas transacciones ────────────────────────────────── --}}
<div class="card">
    <div class="card-header">
        <div>
            <div class="card-title">Últimas transacciones</div>
            <div class="card-subtitle">Las 10 ventas más recientes {{ $label }}</div>
        </div>
        <a href="{{ route('admin.sales.index') }}" class="btn btn-ghost btn-sm">Ver historial completo</a>
    </div>
    @if($recentSales->isEmpty())
        <div class="text-center text-muted" style="padding:2rem 0;">Sin ventas {{ $label }}</div>
    @else
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>{{ $isWeek ? 'Fecha' : 'Hora' }}</th>
                        <th>Cajero</th>
                        <th>Método</th>
                        <th>Total</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentSales as $sale)
                        <tr>
                            <td class="mono text-xs">
                                {{ $isWeek ? $sale->sale_time->format('d/m H:i') : $sale->sale_time->format('H:i') }}
                            </td>
                            <td class="text-sm">{{ $sale->shift->user->name }}</td>
                            <td>
                                <span class="{{ $sale->payment_method === 'CASH' ? 'badge badge-success' : 'badge badge-info' }}">
                                    {{ $sale->payment_method === 'CASH' ? 'Efectivo' : 'QR' }}
                                </span>
                            </td>
                            <td class="mono {{ $sale->status === 'VOIDED' ? 'text-danger' : 'text-success' }}">
                                Bs {{ number_format($sale->total_amount, 2) }}
                            </td>
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
    @endif
</div>

@endsection

@section('scripts')
<script>
    function updateClock() {
        const now = new Date();
        const pad = n => String(n).padStart(2, '0');
        const date = `${pad(now.getDate())}/${pad(now.getMonth()+1)}/${now.getFullYear()}`;
        const time = `${pad(now.getHours())}:${pad(now.getMinutes())}:${pad(now.getSeconds())}`;
        const clockEl = document.getElementById('live-clock');
        const dateEl  = document.getElementById('live-date');
        if (clockEl) clockEl.textContent = time;
        if (dateEl)  dateEl.textContent  = date;
    }
    updateClock();
    setInterval(updateClock, 1000);

    // Auto-refresh cada 60 segundos (solo en modo hoy)
    @if($mode === 'hoy')
    setTimeout(() => location.reload(), 60000);
    @endif
</script>
@endsection
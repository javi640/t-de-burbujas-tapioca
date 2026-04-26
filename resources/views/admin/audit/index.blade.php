@extends('layouts.app')
@section('title', 'Auditoría')

@section('sidebar-nav')
    <span class="nav-section-label">Principal</span>
    <a href="{{ route('admin.dashboard') }}" class="nav-item"><span class="nav-icon">⬡</span> Dashboard</a>
    <span class="nav-section-label">Gestión</span>
    <a href="{{ route('admin.users.index') }}" class="nav-item"><span class="nav-icon">👥</span> Usuarios</a>
    <a href="{{ route('admin.sales.index') }}" class="nav-item"><span class="nav-icon">🛒</span> Historial Ventas</a>
    <a href="{{ route('admin.shifts.index') }}" class="nav-item"><span class="nav-icon">⏱</span> Turnos</a>
    <span class="nav-section-label">Reportes</span>
    <a href="{{ route('admin.reports.daily') }}" class="nav-item"><span class="nav-icon">📋</span> Cierre Diario</a>
    <a href="{{ route('admin.audit.index') }}" class="nav-item active"><span class="nav-icon">🔍</span> Auditoría</a>
@endsection

@section('page-title', 'Auditoría')

@section('page-subtitle')
    Registro de movimientos por turno y acciones del sistema
@endsection

@section('topbar-actions')
    <button onclick="window.print()" class="btn btn-ghost btn-sm">🖨 Imprimir</button>
@endsection

@section('content')

{{-- ── Contadores por acción ────────────────────────────────── --}}
@php
    $actionLabels = [
        'login'       => ['Inicios de sesión', '🔑'],
        'logout'      => ['Cierres de sesión',  '🚪'],
        'open_shift'  => ['Aperturas de turno', '▶'],
        'close_shift' => ['Cierres de turno',   '■'],
        'void_sale'   => ['Anulaciones',         '⚠'],
    ];
    $actionColors = [
        'login'       => 'accent',
        'logout'      => 'muted',
        'open_shift'  => 'success',
        'close_shift' => 'warning',
        'void_sale'   => 'danger',
    ];
@endphp

<div class="stats-grid" style="grid-template-columns: repeat(5, 1fr); margin-bottom: 1rem;">
    @foreach($actionLabels as $key => [$label, $icon])
        <div class="stat-card" style="text-align:center;">
            <div style="font-size:1.3rem; margin-bottom:.25rem;">{{ $icon }}</div>
            <div class="stat-label" style="font-size:.68rem;">{{ $label }}</div>
            <div class="stat-value mono" style="font-size:1.4rem; color:var(--{{ $actionColors[$key] ?? 'text' }});">
                {{ $actionCounts[$key] ?? 0 }}
            </div>
        </div>
    @endforeach
</div>

{{-- ── Filtros ──────────────────────────────────────────────── --}}
<div class="card" style="margin-bottom:1rem;">
    <form method="GET" action="{{ route('admin.audit.index') }}">
        <div style="display:grid; grid-template-columns: 2fr 1.5fr 1fr 1fr auto auto; gap:.75rem; align-items:end;">

            <div class="form-group" style="margin:0;">
                <label style="font-size:.7rem; color:var(--muted); text-transform:uppercase; letter-spacing:.06em;">Usuario</label>
                <select name="user_id" style="padding:.4rem .75rem;">
                    <option value="">— Todos los usuarios —</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group" style="margin:0;">
                <label style="font-size:.7rem; color:var(--muted); text-transform:uppercase; letter-spacing:.06em;">Acción</label>
                <select name="action" style="padding:.4rem .75rem;">
                    <option value="">— Todas las acciones —</option>
                    @foreach($actionLabels as $key => [$label, $icon])
                        <option value="{{ $key }}" {{ request('action') === $key ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group" style="margin:0;">
                <label style="font-size:.7rem; color:var(--muted); text-transform:uppercase; letter-spacing:.06em;">Desde</label>
                <input type="date" name="from"
                       value="{{ request('from', today()->format('Y-m-d')) }}"
                       style="padding:.4rem .75rem;">
            </div>

            <div class="form-group" style="margin:0;">
                <label style="font-size:.7rem; color:var(--muted); text-transform:uppercase; letter-spacing:.06em;">Hasta</label>
                <input type="date" name="to"
                       value="{{ request('to', today()->format('Y-m-d')) }}"
                       style="padding:.4rem .75rem;">
            </div>

            <button type="submit" class="btn btn-primary btn-sm" style="align-self:end;">Filtrar</button>
            <a href="{{ route('admin.audit.index') }}" class="btn btn-ghost btn-sm" style="align-self:end;">Limpiar</a>
        </div>
    </form>
</div>

{{-- ── Tabla de registros ───────────────────────────────────── --}}
<div class="card">
    <div class="card-header">
        <div>
            <div class="card-title">Registros de auditoría</div>
            <div class="card-subtitle">{{ $logs->total() }} eventos encontrados</div>
        </div>
    </div>

    @if($logs->isEmpty())
        <div class="text-center text-muted" style="padding:3rem 0;">
            <div style="font-size:2rem; margin-bottom:.75rem;">🔍</div>
            <div>No hay registros para los filtros seleccionados</div>
        </div>
    @else
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Fecha y hora</th>
                        <th>Usuario</th>
                        <th>Acción</th>
                        <th>Referencia</th>
                        <th>IP</th>
                        <th>Detalle</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($logs as $log)
                        @php
                            $color = match($log->action) {
                                'open_shift'  => 'success',
                                'close_shift' => 'warning',
                                'void_sale'   => 'danger',
                                'login'       => 'info',
                                default       => 'gray',
                            };
                            $actionLabel = $actionLabels[$log->action][0] ?? $log->action;
                        @endphp
                        <tr>
                            {{-- Fecha/hora --}}
                            <td class="mono text-xs" style="white-space:nowrap;">
                                {{ $log->created_at->format('d/m/Y') }}
                                <span class="text-muted">{{ $log->created_at->format('H:i:s') }}</span>
                            </td>

                            {{-- Usuario --}}
                            <td>
                                @if($log->user)
                                    <div class="flex items-center gap-2">
                                        <div class="user-avatar" style="width:1.6rem;height:1.6rem;font-size:.65rem;">
                                            {{ strtoupper(substr($log->user->name, 0, 2)) }}
                                        </div>
                                        <span class="text-sm">{{ $log->user->name }}</span>
                                    </div>
                                @else
                                    <span class="text-muted text-xs">Sistema</span>
                                @endif
                            </td>

                            {{-- Acción con badge de color --}}
                            <td>
                                <span style="
                                    display:inline-block;
                                    padding:.2rem .6rem;
                                    border-radius:20px;
                                    font-size:.7rem;
                                    font-weight:600;
                                    background: var(--color-background-{{ $color }});
                                    color: var(--color-text-{{ $color }});
                                    border: 1px solid var(--color-border-{{ $color }});
                                    white-space:nowrap;
                                ">
                                    {{ $actionLabels[$log->action][1] ?? '' }} {{ $actionLabel }}
                                </span>
                            </td>

                            {{-- Modelo/referencia --}}
                            <td class="text-xs text-muted mono">
                                @if($log->model && $log->model_id)
                                    {{ $log->model }} #{{ $log->model_id }}
                                @else
                                    —
                                @endif
                            </td>

                            {{-- IP --}}
                            <td class="mono text-xs text-muted">
                                {{ $log->ip_address ?? '—' }}
                            </td>

                            {{-- Detalle expandible --}}
                            <td>
                                @if($log->old_values || $log->new_values)
                                    <button type="button"
                                            onclick="toggleDetail('detail-{{ $log->id }}')"
                                            class="btn btn-ghost btn-sm"
                                            style="font-size:.7rem; padding:.2rem .5rem;">
                                        Ver datos
                                    </button>
                                @else
                                    <span class="text-xs text-muted">—</span>
                                @endif
                            </td>
                        </tr>

                        {{-- Fila expandible con old/new values --}}
                        @if($log->old_values || $log->new_values)
                            <tr id="detail-{{ $log->id }}" style="display:none;">
                                <td colspan="6" style="padding:.5rem 1rem 1rem; background:rgba(10,15,30,.3);">
                                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem;">

                                        @if($log->old_values)
                                            <div>
                                                <div style="font-size:.65rem; font-weight:600; text-transform:uppercase; letter-spacing:.06em; color:var(--muted); margin-bottom:.4rem;">
                                                    Valores anteriores
                                                </div>
                                                <pre style="
                                                    font-family:'DM Mono',monospace;
                                                    font-size:.72rem;
                                                    background:rgba(239,68,68,.05);
                                                    border:1px solid rgba(239,68,68,.15);
                                                    border-radius:6px;
                                                    padding:.6rem .75rem;
                                                    color:var(--danger);
                                                    margin:0;
                                                    white-space:pre-wrap;
                                                    word-break:break-all;
                                                ">{{ json_encode($log->old_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                            </div>
                                        @endif

                                        @if($log->new_values)
                                            <div>
                                                <div style="font-size:.65rem; font-weight:600; text-transform:uppercase; letter-spacing:.06em; color:var(--muted); margin-bottom:.4rem;">
                                                    Valores nuevos / registrados
                                                </div>
                                                <pre style="
                                                    font-family:'DM Mono',monospace;
                                                    font-size:.72rem;
                                                    background:rgba(34,197,94,.05);
                                                    border:1px solid rgba(34,197,94,.15);
                                                    border-radius:6px;
                                                    padding:.6rem .75rem;
                                                    color:var(--success);
                                                    margin:0;
                                                    white-space:pre-wrap;
                                                    word-break:break-all;
                                                ">{{ json_encode($log->new_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                            </div>
                                        @endif

                                    </div>
                                </td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>

        <div style="margin-top:1rem;">{{ $logs->withQueryString()->links() }}</div>
    @endif
</div>

@endsection

@section('scripts')
<script>
function toggleDetail(id) {
    const row = document.getElementById(id);
    const btn = row.previousElementSibling.querySelector('button');
    const isHidden = row.style.display === 'none';
    row.style.display = isHidden ? 'table-row' : 'none';
    if (btn) btn.textContent = isHidden ? 'Ocultar' : 'Ver datos';
}
</script>
@endsection
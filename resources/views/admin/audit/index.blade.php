@extends('layouts.app')
@section('title', 'Auditoría')

@section('sidebar-nav')
    <span class="nav-section-label">Principal</span>
    <a href="{{ route('admin.dashboard') }}" class="nav-item">
        <span class="nav-icon" style="display:inline-flex;align-items:center;"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg></span> Dashboard
    </a>
    <span class="nav-section-label">Gestión</span>
    <a href="{{ route('admin.users.index') }}" class="nav-item">
        <span class="nav-icon" style="display:inline-flex;align-items:center;"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg></span> Usuarios
    </a>
    <a href="{{ route('admin.sales.index') }}" class="nav-item">
        <span class="nav-icon" style="display:inline-flex;align-items:center;"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg></span> Historial Ventas
    </a>
    <a href="{{ route('admin.shifts.index') }}" class="nav-item">
        <span class="nav-icon" style="display:inline-flex;align-items:center;"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg></span> Turnos
    </a>
    <span class="nav-section-label">Reportes</span>
    <a href="{{ route('admin.reports.daily') }}" class="nav-item">
        <span class="nav-icon" style="display:inline-flex;align-items:center;"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg></span> Cierre Diario
    </a>
    <a href="{{ route('admin.audit.index') }}" class="nav-item active">
        <span class="nav-icon" style="display:inline-flex;align-items:center;"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg></span> Auditoría
    </a>
@endsection

@section('page-title', 'Auditoría')

@section('page-subtitle')
    Registro de movimientos por turno y acciones del sistema
@endsection

@section('topbar-actions')
    <button onclick="window.print()" class="btn btn-ghost btn-sm">🖨 Imprimir</button>
@endsection

@section('content')

@php
    $actionLabels = [
        'login'       => ['Inicios de sesión',  'login'],
        'logout'      => ['Cierres de sesión',   'logout'],
        'open_shift'  => ['Aperturas de turno',  'open_shift'],
        'close_shift' => ['Cierres de turno',    'close_shift'],
        'void_sale'   => ['Anulaciones',          'void_sale'],
    ];
    $actionIcons = [
        'login'       => '<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color:var(--accent)"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>',
        'logout'      => '<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color:var(--muted)"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>',
        'open_shift'  => '<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color:var(--success)"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 9.9-1"/></svg>',
        'close_shift' => '<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color:var(--warning)"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>',
        'void_sale'   => '<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color:var(--danger)"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>',
    ];

    // Clases CSS predefinidas para cada acción — evita variables dentro de style=""
    $actionBadgeClass = [
        'login'       => 'badge-info',
        'logout'      => 'badge-gray',
        'open_shift'  => 'badge-success',
        'close_shift' => 'badge-warning',
        'void_sale'   => 'badge-danger',
    ];

    // Color del número en los contadores
    $actionCountStyle = [
        'login'       => 'color:var(--accent)',
        'logout'      => 'color:var(--muted)',
        'open_shift'  => 'color:var(--success)',
        'close_shift' => 'color:var(--warning)',
        'void_sale'   => 'color:var(--danger)',
    ];
@endphp

{{-- ── Contadores por acción ────────────────────────────────── --}}
<div class="stats-grid" style="grid-template-columns: repeat(5, 1fr); margin-bottom: 1rem;">
    @foreach($actionLabels as $key => [$label, $icon])
        <div class="stat-card" style="text-align:center;">
            <div style="display:flex;justify-content:center;margin-bottom:.5rem;">{!! $actionIcons[$key] ?? '' !!}</div>
            <div class="stat-label" style="font-size:.68rem;">{{ $label }}</div>
            {{-- Usamos variable PHP para el estilo, no interpolación dentro de style="" --}}
            <div class="stat-value mono" style="font-size:1.4rem; {{ $actionCountStyle[$key] ?? '' }}">
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
                            // Clase del badge — definida en PHP, no interpolada en style=""
                            $badgeClass  = $actionBadgeClass[$log->action] ?? 'badge-gray';
                            $actionLabel = $actionLabels[$log->action][0] ?? $log->action;
                            $actionIcon  = $actionLabels[$log->action][1] ?? '';
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

                            {{-- Acción con badge — usa clase CSS, no style con variable --}}
                            <td>
                                <span class="badge {{ $badgeClass }}" style="white-space:nowrap;">
                                    {{ $actionIcon }} {{ $actionLabel }}
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
                                                <pre style="font-family:'DM Mono',monospace; font-size:.72rem; background:rgba(239,68,68,.05); border:1px solid rgba(239,68,68,.15); border-radius:6px; padding:.6rem .75rem; color:var(--danger); margin:0; white-space:pre-wrap; word-break:break-all;">{{ json_encode($log->old_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                            </div>
                                        @endif

                                        @if($log->new_values)
                                            <div>
                                                <div style="font-size:.65rem; font-weight:600; text-transform:uppercase; letter-spacing:.06em; color:var(--muted); margin-bottom:.4rem;">
                                                    Valores nuevos / registrados
                                                </div>
                                                <pre style="font-family:'DM Mono',monospace; font-size:.72rem; background:rgba(34,197,94,.05); border:1px solid rgba(34,197,94,.15); border-radius:6px; padding:.6rem .75rem; color:var(--success); margin:0; white-space:pre-wrap; word-break:break-all;">{{ json_encode($log->new_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
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

        <div style="margin-top:1rem; display:flex; align-items:center; gap:.5rem; flex-wrap:wrap;">
            @if($logs->onFirstPage())
                <span style="padding:.35rem .75rem; border-radius:6px; background:var(--surface); border:1px solid var(--border); color:var(--muted); font-size:.8rem; cursor:not-allowed;">← Anterior</span>
            @else
                <a href="{{ $logs->withQueryString()->previousPageUrl() }}" style="padding:.35rem .75rem; border-radius:6px; background:var(--surface); border:1px solid var(--border); color:var(--text); font-size:.8rem; text-decoration:none;">← Anterior</a>
            @endif
            @foreach($logs->withQueryString()->getUrlRange(max(1, $logs->currentPage()-2), min($logs->lastPage(), $logs->currentPage()+2)) as $page => $url)
                @if($page == $logs->currentPage())
                    <span style="padding:.35rem .6rem; border-radius:6px; background:var(--accent); color:#fff; font-size:.8rem; font-weight:600; min-width:2rem; text-align:center;">{{ $page }}</span>
                @else
                    <a href="{{ $url }}" style="padding:.35rem .6rem; border-radius:6px; background:var(--surface); border:1px solid var(--border); color:var(--text); font-size:.8rem; text-decoration:none; min-width:2rem; text-align:center;">{{ $page }}</a>
                @endif
            @endforeach
            @if($logs->hasMorePages())
                <a href="{{ $logs->withQueryString()->nextPageUrl() }}" style="padding:.35rem .75rem; border-radius:6px; background:var(--surface); border:1px solid var(--border); color:var(--text); font-size:.8rem; text-decoration:none;">Siguiente →</a>
            @else
                <span style="padding:.35rem .75rem; border-radius:6px; background:var(--surface); border:1px solid var(--border); color:var(--muted); font-size:.8rem; cursor:not-allowed;">Siguiente →</span>
            @endif
            <span style="font-size:.75rem; color:var(--muted); margin-left:.5rem;">
                Mostrando {{ $logs->firstItem() }}–{{ $logs->lastItem() }} de {{ $logs->total() }} registros
            </span>
        </div>
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
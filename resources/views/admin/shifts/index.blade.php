@extends('layouts.app')
@section('title', 'Turnos')

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
    <a href="{{ route('admin.shifts.index') }}" class="nav-item active">
        <span class="nav-icon" style="display:inline-flex;align-items:center;"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg></span> Turnos
    </a>
    <span class="nav-section-label">Reportes</span>
    <a href="{{ route('admin.reports.daily') }}" class="nav-item">
        <span class="nav-icon" style="display:inline-flex;align-items:center;"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg></span> Cierre Diario
    </a>
    <a href="{{ route('admin.audit.index') }}" class="nav-item">
        <span class="nav-icon" style="display:inline-flex;align-items:center;"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg></span> Auditoría
    </a>
@endsection

@section('page-title', 'Turnos')
@section('page-subtitle', 'Registro de turnos del personal')

@section('topbar-actions')
    <a href="{{ route('admin.shifts.open') }}" class="btn btn-primary btn-sm">
        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 9.9-1"/></svg>
        Abrir Turno
    </a>
@endsection

@section('content')
<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Cajero</th>
                    <th>Apertura</th>
                    <th>Cierre</th>
                    <th>Ef. Inicial</th>
                    <th>Ef. Declarado</th>
                    <th>Diferencia</th>
                    <th>Estado</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($shifts as $shift)
                    <tr>
                        <td>
                            <div class="flex items-center gap-2">
                                <div class="user-avatar" style="width:1.75rem;height:1.75rem;font-size:.7rem;">
                                    {{ strtoupper(substr($shift->user->name, 0, 2)) }}
                                </div>
                                {{ $shift->user->name }}
                            </div>
                        </td>
                        <td class="mono text-xs">{{ $shift->start_time->format('d/m H:i') }}</td>
                        <td class="mono text-xs">{{ $shift->end_time ? $shift->end_time->format('d/m H:i') : '—' }}</td>
                        <td class="mono">Bs {{ number_format($shift->initial_cash, 2) }}</td>
                        <td class="mono">{{ $shift->reported_cash ? 'Bs '.number_format($shift->reported_cash, 2) : '—' }}</td>
                        <td class="mono {{ $shift->cash_difference < 0 ? 'text-danger' : ($shift->cash_difference > 0 ? 'text-warning' : 'text-success') }}">
                            {{ $shift->cash_difference !== null ? 'Bs '.number_format($shift->cash_difference, 2) : '—' }}
                        </td>
                        <td>
                            <span class="{{ $shift->status === 'OPEN' ? 'badge badge-success' : 'badge badge-gray' }}">
                                {{ $shift->status === 'OPEN' ? 'Abierto' : 'Cerrado' }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('admin.shifts.show', $shift) }}" class="btn btn-ghost btn-sm">Ver</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted" style="padding: 3rem;">No hay turnos</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="margin-top:1rem; display:flex; align-items:center; gap:.5rem; flex-wrap:wrap;">
        @if($shifts->onFirstPage())
            <span style="padding:.35rem .75rem; border-radius:6px; background:var(--surface); border:1px solid var(--border); color:var(--muted); font-size:.8rem; cursor:not-allowed;">← Anterior</span>
        @else
            <a href="{{ $shifts->withQueryString()->previousPageUrl() }}" style="padding:.35rem .75rem; border-radius:6px; background:var(--surface); border:1px solid var(--border); color:var(--text); font-size:.8rem; text-decoration:none;">← Anterior</a>
        @endif
        @foreach($shifts->withQueryString()->getUrlRange(max(1, $shifts->currentPage()-2), min($shifts->lastPage(), $shifts->currentPage()+2)) as $page => $url)
            @if($page == $shifts->currentPage())
                <span style="padding:.35rem .6rem; border-radius:6px; background:var(--accent); color:#fff; font-size:.8rem; font-weight:600; min-width:2rem; text-align:center;">{{ $page }}</span>
            @else
                <a href="{{ $url }}" style="padding:.35rem .6rem; border-radius:6px; background:var(--surface); border:1px solid var(--border); color:var(--text); font-size:.8rem; text-decoration:none; min-width:2rem; text-align:center;">{{ $page }}</a>
            @endif
        @endforeach
        @if($shifts->hasMorePages())
            <a href="{{ $shifts->withQueryString()->nextPageUrl() }}" style="padding:.35rem .75rem; border-radius:6px; background:var(--surface); border:1px solid var(--border); color:var(--text); font-size:.8rem; text-decoration:none;">Siguiente →</a>
        @else
            <span style="padding:.35rem .75rem; border-radius:6px; background:var(--surface); border:1px solid var(--border); color:var(--muted); font-size:.8rem; cursor:not-allowed;">Siguiente →</span>
        @endif
        <span style="font-size:.75rem; color:var(--muted); margin-left:.5rem;">
            Mostrando {{ $shifts->firstItem() }}–{{ $shifts->lastItem() }} de {{ $shifts->total() }} registros
        </span>
    </div>
</div>
@endsection
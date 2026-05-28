@extends('layouts.app')
@section('title', 'Historial de Ventas')

@section('sidebar-nav')
    <span class="nav-section-label">Principal</span>
    <a href="{{ route('admin.dashboard') }}" class="nav-item">
        <span class="nav-icon" style="display:inline-flex;align-items:center;"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg></span> Dashboard
    </a>
    <span class="nav-section-label">Gestión</span>
    <a href="{{ route('admin.users.index') }}" class="nav-item">
        <span class="nav-icon" style="display:inline-flex;align-items:center;"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg></span> Usuarios
    </a>
    <a href="{{ route('admin.sales.index') }}" class="nav-item active">
        <span class="nav-icon" style="display:inline-flex;align-items:center;"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg></span> Historial Ventas
    </a>
    <a href="{{ route('admin.shifts.index') }}" class="nav-item">
        <span class="nav-icon" style="display:inline-flex;align-items:center;"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg></span> Turnos
    </a>
    <span class="nav-section-label">Reportes</span>
    <a href="{{ route('admin.reports.daily') }}" class="nav-item">
        <span class="nav-icon" style="display:inline-flex;align-items:center;"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg></span> Cierre Diario
    </a>
    <a href="{{ route('admin.audit.index') }}" class="nav-item">
        <span class="nav-icon" style="display:inline-flex;align-items:center;"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg></span> Auditoría
    </a>
@endsection

@section('page-title', 'Historial de Ventas')
@section('page-subtitle', 'Todas las transacciones registradas')

@section('content')
<div class="print-header" style="display:none;">
    <h1>Panda Naicha &mdash; Historial de Ventas</h1>
    <p>Generado el {{ now()->setTimezone('America/La_Paz')->format('d/m/Y H:i') }} por {{ auth()->user()->name }}</p>
</div>
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

{{-- Totales --}}
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
                    <th>Anulada por</th>
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
                        <td class="text-xs text-muted">
                            @if($sale->status === 'VOIDED' && $sale->voidedBy)
                                {{ $sale->voidedBy->name }}
                            @else
                                —
                            @endif
                        </td>
                        <td>
                            @if($sale->status === 'COMPLETED')
                        <button
                            type="button"
                            class="btn btn-danger btn-sm"
                            data-id="{{ $sale->id }}"
                            data-cajero="{{ $sale->shift->user->name }}"
                            data-total="Bs {{ number_format($sale->total_amount, 2) }}"
                            onclick="openVoidModal(
                                this.dataset.id,
                                this.dataset.cajero,
                                this.dataset.total
                            )"
                        >
                            Anular
                        </button>
                            @else
                                <span class="text-xs text-muted">—</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted" style="padding: 3rem;">
                            No hay ventas en el período seleccionado
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="margin-top:1rem; display:flex; align-items:center; gap:.5rem; flex-wrap:wrap;">
        @if($sales->onFirstPage())
            <span style="padding:.35rem .75rem; border-radius:6px; background:var(--surface); border:1px solid var(--border); color:var(--muted); font-size:.8rem; cursor:not-allowed;">← Anterior</span>
        @else
            <a href="{{ $sales->withQueryString()->previousPageUrl() }}" style="padding:.35rem .75rem; border-radius:6px; background:var(--surface); border:1px solid var(--border); color:var(--text); font-size:.8rem; text-decoration:none;">← Anterior</a>
        @endif
        @foreach($sales->withQueryString()->getUrlRange(max(1, $sales->currentPage()-2), min($sales->lastPage(), $sales->currentPage()+2)) as $page => $url)
            @if($page == $sales->currentPage())
                <span style="padding:.35rem .6rem; border-radius:6px; background:var(--accent); color:#fff; font-size:.8rem; font-weight:600; min-width:2rem; text-align:center;">{{ $page }}</span>
            @else
                <a href="{{ $url }}" style="padding:.35rem .6rem; border-radius:6px; background:var(--surface); border:1px solid var(--border); color:var(--text); font-size:.8rem; text-decoration:none; min-width:2rem; text-align:center;">{{ $page }}</a>
            @endif
        @endforeach
        @if($sales->hasMorePages())
            <a href="{{ $sales->withQueryString()->nextPageUrl() }}" style="padding:.35rem .75rem; border-radius:6px; background:var(--surface); border:1px solid var(--border); color:var(--text); font-size:.8rem; text-decoration:none;">Siguiente →</a>
        @else
            <span style="padding:.35rem .75rem; border-radius:6px; background:var(--surface); border:1px solid var(--border); color:var(--muted); font-size:.8rem; cursor:not-allowed;">Siguiente →</span>
        @endif
        <span style="font-size:.75rem; color:var(--muted); margin-left:.5rem;">
            Mostrando {{ $sales->firstItem() }}–{{ $sales->lastItem() }} de {{ $sales->total() }} registros
        </span>
    </div>
</div>

{{-- Modal de anulación --}}
<div id="void-modal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,.65); z-index:999; align-items:center; justify-content:center;">
    <div style="background:var(--card); border:1px solid var(--border); border-radius:12px; padding:2rem; width:100%; max-width:440px; box-shadow:0 20px 60px rgba(0,0,0,.5);">
        <div style="font-size:1.1rem; font-weight:600; margin-bottom:.25rem;">⚠ Anular Venta</div>
        <div id="void-modal-info" style="font-size:.85rem; color:var(--muted); margin-bottom:1.25rem;"></div>

        <form id="void-form" method="POST">
            @csrf
            <div class="form-group">
                <label>Motivo de anulación <span style="color:var(--danger)">*</span></label>
                <textarea name="void_reason" id="void-reason" rows="3"
                    placeholder="Describe el motivo de la anulación..."
                    maxlength="500"
                    style="resize:vertical;"
                    required></textarea>
                <div id="void-reason-error" style="color:var(--danger); font-size:.8rem; margin-top:.25rem; display:none;">
                    El motivo es obligatorio (mínimo 5 caracteres).
                </div>
            </div>
            <div class="flex gap-3" style="margin-top:1rem;">
                <button type="button" onclick="closeVoidModal()" class="btn btn-ghost" style="flex:1;">Cancelar</button>
                <button type="button" onclick="submitVoid()" class="btn btn-danger" style="flex:1;">Confirmar Anulación</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function openVoidModal(saleId, cajero, total) {
        document.getElementById('void-form').action = `/admin/venta/${saleId}/anular`;
        document.getElementById('void-modal-info').textContent = `Cajero: ${cajero} · Total: ${total}`;
        document.getElementById('void-reason').value = '';
        document.getElementById('void-reason-error').style.display = 'none';
        const modal = document.getElementById('void-modal');
        modal.style.display = 'flex';
        setTimeout(() => document.getElementById('void-reason').focus(), 100);
    }

    function closeVoidModal() {
        document.getElementById('void-modal').style.display = 'none';
    }

    function submitVoid() {
        const reason = document.getElementById('void-reason').value.trim();
        if (reason.length < 5) {
            document.getElementById('void-reason-error').style.display = 'block';
            document.getElementById('void-reason').focus();
            return;
        }
        document.getElementById('void-reason-error').style.display = 'none';
        document.getElementById('void-form').submit();
    }

    document.getElementById('void-modal').addEventListener('click', function(e) {
        if (e.target === this) closeVoidModal();
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeVoidModal();
    });
</script>
@endpush
@endsection
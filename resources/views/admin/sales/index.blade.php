@extends('layouts.app')
@section('title', 'Historial de Ventas')

@section('sidebar-nav')
    <span class="nav-section-label">Principal</span>
    <a href="{{ route('admin.dashboard') }}" class="nav-item"><i class="bi bi-speedometer2"></i> Dashboard</a>
    <span class="nav-section-label">Gestión</span>
    <a href="{{ route('admin.users.index') }}" class="nav-item"><i class="bi bi-people-fill"></i> Usuarios</a>
    <a href="{{ route('admin.sales.index') }}" class="nav-item active"><i class="bi bi-bag-check"></i> Historial Ventas</a>
    <a href="{{ route('admin.shifts.index') }}" class="nav-item"><i class="bi bi-clock-history"></i> Turnos</a>
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
    <div style="margin-top: 1rem;">{{ $sales->withQueryString()->links() }}</div>
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
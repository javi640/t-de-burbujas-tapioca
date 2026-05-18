@extends('layouts.app')
@section('title', 'Abrir Turno')

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
        <span class="nav-icon" style="display:inline-flex;align-items:center;"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg></span> Cierre Diario
    </a>
    <a href="{{ route('admin.audit.index') }}" class="nav-item">
        <span class="nav-icon" style="display:inline-flex;align-items:center;"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg></span> Auditoría
    </a>
@endsection

@section('page-title', 'Abrir Turno')
@section('page-subtitle', 'Asigna y programa el turno de un cajero')

@section('topbar-actions')
    <a href="{{ route('admin.shifts.index') }}" class="btn btn-ghost btn-sm">← Volver a Turnos</a>
@endsection

@section('content')
<form method="POST" action="{{ route('admin.shifts.store') }}" id="openShiftForm">
    @csrf
    <div class="grid grid-2 mb-4">

        <div style="display: flex; flex-direction: column; gap: 1rem;">

            <div class="card">
                <div class="card-header">
                    <div>
                        <div class="card-title">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline;vertical-align:middle;margin-right:5px;"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                            Cajero
                        </div>
                        <div class="card-subtitle">Selecciona quién atenderá este turno</div>
                    </div>
                </div>

                <div class="form-group">
                    <label>Cajero asignado</label>
                    <select name="cajero_id" id="cajero_id" required>
                        <option value="">— Seleccionar cajero —</option>
                        @foreach($cajeros as $cajero)
                            <option
                                value="{{ $cajero->id }}"
                                {{ old('cajero_id') == $cajero->id ? 'selected' : '' }}
                                {{ in_array($cajero->id, $cajerosConTurnoAbierto) ? 'disabled' : '' }}
                            >
                                {{ $cajero->name }}
                                {{ in_array($cajero->id, $cajerosConTurnoAbierto) ? '(turno abierto)' : '' }}
                            </option>
                        @endforeach
                    </select>
                    @error('cajero_id')
                        <div class="form-error">✕ {{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <div>
                        <div class="card-title">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline;vertical-align:middle;margin-right:5px;"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                            Horario del Turno
                        </div>
                        <div class="card-subtitle">Define la hora de inicio y el margen de tolerancia</div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Hora programada</label>
                        <input
                            type="time"
                            name="scheduled_start"
                            value="{{ old('scheduled_start', now()->format('H:i')) }}"
                            required
                            style="font-family: 'DM Mono', monospace; font-size: 1.1rem; padding: .75rem;"
                        >
                        @error('scheduled_start')
                            <div class="form-error">✕ {{ $message }}</div>
                        @enderror
                        <div class="text-xs text-muted" style="margin-top: .3rem;">
                            Hora a la que el cajero debe iniciar sesión
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Tolerancia (minutos)</label>
                        <input
                            type="number"
                            name="tolerance_minutes"
                            value="{{ old('tolerance_minutes', 10) }}"
                            min="0"
                            max="120"
                            step="5"
                            style="font-family: 'DM Mono', monospace; font-size: 1.1rem; padding: .75rem;"
                        >
                        @error('tolerance_minutes')
                            <div class="form-error">✕ {{ $message }}</div>
                        @enderror
                        <div class="text-xs text-muted" style="margin-top: .3rem;">
                            Minutos de gracia antes de marcar tardanza
                        </div>
                    </div>
                </div>

                <div style="background: rgba(42,53,72,.4); border-radius: 8px; padding: .75rem 1rem; margin-top: .5rem;">
                    <div class="flex items-center gap-3">
                        <span style="display:inline-flex;align-items:center;"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#f59e0b" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg></span>
                        <div>
                            <div class="text-xs text-muted">Límite sin tardanza</div>
                            <div class="mono font-bold" id="deadlinePreview" style="color: var(--warning);">--:--</div>
                        </div>
                        <div style="margin-left: auto;">
                            <div class="text-xs text-muted">Si llega después de esta hora</div>
                            <div class="text-xs" style="color: var(--danger);">→ se marca como TARDANZA</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <div>
                        <div class="card-title">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline;vertical-align:middle;margin-right:5px;"><rect x="2" y="6" width="20" height="12" rx="2"/><path d="M12 12h.01"/><path d="M17 12h.01"/><path d="M7 12h.01"/></svg>
                            Efectivo en Caja
                        </div>
                        <div class="card-subtitle">Monto físico con el que inicia el turno</div>
                    </div>
                </div>

                <div class="form-group">
                    <label>Monto Inicial (Bs)</label>
                    <input
                        type="number"
                        name="initial_cash"
                        value="{{ old('initial_cash', 0) }}"
                        min="0"
                        step="0.01"
                        placeholder="0.00"
                        required
                        style="font-family: 'DM Mono', monospace; font-size: 1.5rem; padding: 1rem;"
                    >
                    @error('initial_cash')
                        <div class="form-error">✕ {{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Observaciones del turno (opcional)</label>
                    <textarea
                        name="notes"
                        rows="2"
                        placeholder="Ej: Turno mañana, revisión de stock pendiente..."
                    >{{ old('notes') }}</textarea>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <div>
                    <div class="card-title">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline;vertical-align:middle;margin-right:5px;"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>
                        Stock Inicial
                    </div>
                    <div class="card-subtitle">Unidades disponibles al inicio del turno</div>
                </div>
            </div>

            <div>
                @php
                    $toppingKeywords = ['boba', 'jelly', 'pearls', 'nata', 'crumbs', 'pudding'];
                    $isToppingFn = function($name) use ($toppingKeywords) {
                        foreach ($toppingKeywords as $kw) {
                            if (stripos($name, $kw) !== false) return true;
                        }
                        return false;
                    };
                    $mainProducts    = $products->filter(fn($p) => !$isToppingFn($p->name));
                    $toppingProducts = $products->filter(fn($p) =>  $isToppingFn($p->name));
                @endphp

                {{-- Bebidas --}}
                @foreach($mainProducts as $product)
                    <div class="flex items-center gap-3" style="padding: .6rem 0; border-bottom: 1px solid rgba(42,53,72,.5);">
                        <div style="flex: 1;">
                            <div class="text-sm font-bold">{{ $product->name }}</div>
                            <div class="text-xs text-muted mono">Bs {{ number_format($product->price, 2) }}</div>
                        </div>
                        <input
                            type="number"
                            name="stock[{{ $product->id }}]"
                            value="{{ old("stock.{$product->id}", 0) }}"
                            min="0"
                            step="1"
                            style="width: 80px; padding: .4rem .6rem; text-align: center;"
                        >
                    </div>
                @endforeach

                {{-- Separador toppings --}}
                @if($toppingProducts->isNotEmpty())
                    <div style="margin: 1rem 0 .5rem; padding-top: .75rem; border-top: 2px solid rgba(245,158,11,.3);">
                        <span class="text-xs" style="text-transform:uppercase; letter-spacing:.08em; font-weight:600; color:#f59e0b;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#f59e0b" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline;vertical-align:middle;margin-right:4px;"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/></svg>
                            Extras / Toppings
                        </span>
                    </div>
                    @foreach($toppingProducts as $product)
                        <div class="flex items-center gap-3" style="padding: .6rem .5rem; border-bottom: 1px solid rgba(245,158,11,.15); background: rgba(245,158,11,.03); border-radius: 4px;">
                            <div style="flex: 1;">
                                <div class="text-sm font-bold">{{ $product->name }}</div>
                                <div class="text-xs mono" style="color:#f59e0b;">Bs {{ number_format($product->price, 2) }}</div>
                            </div>
                            <input
                                type="number"
                                name="stock[{{ $product->id }}]"
                                value="{{ old("stock.{$product->id}", 0) }}"
                                min="0"
                                step="1"
                                style="width: 80px; padding: .4rem .6rem; text-align: center; border-color: rgba(245,158,11,.3);"
                            >
                        </div>
                    @endforeach
                @endif
            </div>

            @error('stock')
                <div class="form-error mt-4">✕ {{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="flex gap-3">
        <button type="submit" class="btn btn-primary" style="padding: .875rem 2rem; font-size: 1rem;">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline;vertical-align:middle;margin-right:6px;"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 9.9-1"/></svg>
            Abrir Turno
        </button>
        <a href="{{ route('admin.shifts.index') }}" class="btn btn-ghost">
            Cancelar
        </a>
    </div>
</form>
@endsection

@section('scripts')
<script>
    const scheduledInput = document.querySelector('[name="scheduled_start"]');
    const toleranceInput = document.querySelector('[name="tolerance_minutes"]');
    const deadlinePreview = document.getElementById('deadlinePreview');

    function updateDeadline() {
        const timeVal  = scheduledInput.value;
        const tolVal   = parseInt(toleranceInput.value) || 0;

        if (!timeVal) {
            deadlinePreview.textContent = '--:--';
            return;
        }

        const [h, m]  = timeVal.split(':').map(Number);
        const total   = h * 60 + m + tolVal;
        const dh      = String(Math.floor(total / 60) % 24).padStart(2, '0');
        const dm      = String(total % 60).padStart(2, '0');

        deadlinePreview.textContent = `${dh}:${dm}`;
    }

    scheduledInput.addEventListener('input', updateDeadline);
    toleranceInput.addEventListener('input', updateDeadline);
    updateDeadline();
</script>
@endsection
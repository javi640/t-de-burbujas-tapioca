@extends('layouts.app')
@section('title', 'Abrir Turno')


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
    <a href="{{ route('admin.shifts.index') }}" class="nav-item active">
        <span class="nav-icon"><i class="bi bi-clock-fill"></i></span> Turnos
    </a>
    <span class="nav-section-label">Reportes</span>
    <a href="{{ route('admin.reports.daily') }}" class="nav-item">
        <span class="nav-icon"><i class="bi bi-file-earmark-text"></i></span> Cierre Diario
    </a>
    <a href="{{ route('admin.audit.index') }}" class="nav-item">
        <span class="nav-icon"><i class="bi bi-search"></i></span> Auditoría
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
                            <i class="bi bi-clock" style="vertical-align:middle;margin-right:5px;"></i>
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
                            <div class="form-error"><i class="bi bi-exclamation-circle-fill"></i> {{ $message }}</div>
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
                            <div class="form-error"><i class="bi bi-exclamation-circle-fill"></i> {{ $message }}</div>
                        @enderror
                        <div class="text-xs text-muted" style="margin-top: .3rem;">
                            Minutos de gracia antes de marcar tardanza
                        </div>
                    </div>
                </div>

                <div style="background: rgba(42,53,72,.4); border-radius: 8px; padding: .75rem 1rem; margin-top: .5rem;">
                    <div class="flex items-center gap-3">
                        <span style="display:inline-flex;align-items:center;"><i class="bi bi-cash-stack" style="vertical-align:middle;margin-right:5px;"></i>
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
                        <div class="form-error"><i class="bi bi-exclamation-circle-fill"></i> {{ $message }}</div>
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
                    <div class="flex items-center gap-3" style="padding: .6rem 0; border-bottom: 1px solid rgba(184,204,202,.5);">
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
                <div class="form-error mt-4"><i class="bi bi-x-circle-fill"></i> {{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="flex gap-3">
        <button type="submit" class="btn btn-primary" style="padding: .875rem 2rem; font-size: 1rem;">
            
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
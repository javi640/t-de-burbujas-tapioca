@extends('layouts.app')
@section('title', 'Abrir Turno')

@section('sidebar-nav')
    <span class="nav-section-label">Mi Turno</span>
    <a href="{{ route('cajero.shift.open') }}" class="nav-item active">
        <span class="nav-icon"><i class="bi bi-unlock-fill"></i></span> Abrir Turno
    </a>
@endsection

@section('page-title', 'Apertura de Turno')
@section('page-subtitle', 'Registra el efectivo inicial y el stock del día')

@section('content')
<form method="POST" action="{{ route('cajero.shift.open') }}">
    @csrf
    <div class="grid grid-2 mb-4">
        {{-- Efectivo inicial --}}
        <div class="card">
            <div class="card-header">
                <div>
                    <div class="card-title">
                        <i class="bi bi-cash-stack" style="vertical-align:middle; margin-right:5px;"></i>
                        Efectivo en Caja
                    </div>
                    <div class="card-subtitle">Monto físico al iniciar el turno</div>
                </div>
            </div>
            <div class="form-group">
                <label>Monto Inicial (Bs)</label>
                <input type="number" name="initial_cash" value="{{ old('initial_cash', 0) }}"
                       min="0" step="0.01" placeholder="0.00" required
                       style="font-family: 'DM Mono', monospace; font-size: 1.5rem; padding: 1rem;">
                @error('initial_cash')<div class="form-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label>Observaciones (opcional)</label>
                <textarea name="notes" rows="2" placeholder="Alguna nota sobre el turno...">{{ old('notes') }}</textarea>
            </div>
        </div>

        {{-- Stock inicial --}}
        <div class="card">
            <div class="card-header">
                <div>
                    <div class="card-title">
                        <i class="bi bi-box-seam" style="vertical-align:middle; margin-right:5px;"></i>
                        Stock Inicial
                    </div>
                    <div class="card-subtitle">Unidades disponibles de cada producto</div>
                </div>
            </div>
            <div style="max-height: 360px; overflow-y: auto;">
                @foreach($products as $product)
                    <div class="flex items-center gap-3" style="padding: .6rem 0; border-bottom: 1px solid rgba(184,204,202,.5);">
                        <div style="flex: 1;">
                            <div class="text-sm font-bold">{{ $product->name }}</div>
                            <div class="text-xs text-muted mono">Bs {{ number_format($product->price, 2) }}</div>
                        </div>
                        <input type="number"
                               name="stock[{{ $product->id }}]"
                               value="{{ old("stock.{$product->id}", 0) }}"
                               min="0" step="1"
                               style="width: 80px; padding: .4rem .6rem; text-align: center;">
                    </div>
                @endforeach
            </div>
            @error('stock')<div class="form-error mt-4">{{ $message }}</div>@enderror
        </div>
    </div>

    <button type="submit" class="btn btn-primary" style="padding: .875rem 2rem; font-size: 1rem;">
        <i class="bi bi-unlock-fill"></i> Abrir Turno
    </button>
</form>
@endsection
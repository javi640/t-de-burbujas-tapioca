@extends('layouts.app')
@section('title', 'Nueva Venta')

@section('sidebar-nav')
    <span class="nav-section-label">Mi Turno</span>
    <a href="{{ route('cajero.shift.current') }}" class="nav-item">
        <span class="nav-icon" style="display:inline-flex;align-items:center;"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg></span> Turno Actual
    </a>
    <a href="{{ route('cajero.sales.create') }}" class="nav-item active">
        <span class="nav-icon" style="display:inline-flex;align-items:center;"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg></span> Nueva Venta
    </a>
    <a href="{{ route('cajero.shift.current') }}#movements" class="nav-item">
        <span class="nav-icon" style="display:inline-flex;align-items:center;"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg></span> Movimientos
    </a>
@endsection

@section('page-title', 'Registrar Venta')
@section('page-subtitle', 'Selecciona los productos y método de pago')

@section('content')
<div class="grid grid-2">
    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline;vertical-align:middle;margin-right:5px;"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>
                Productos Disponibles
            </div>
        </div>
        <div id="products-list">
            @forelse($stockItems as $stock)
                <div class="flex items-center gap-3"
                    style="padding: .75rem; margin-bottom: .5rem; background: rgba(79,142,247,.04); border-radius: 8px; border: 1px solid var(--border); cursor: pointer;"
                    onclick="addToCart({{ $stock->product?->id ?? 0 }}, {{ json_encode($stock->product?->name ?? 'Producto no encontrado') }}, {{ $stock->product?->price ?? 0 }}, {{ $stock->remainingQuantity() }})">
                    <div style="flex: 1;">
                        <div class="font-bold text-sm">{{ $stock->product->name }}</div>
                        <div class="text-xs text-muted">{{ $stock->remainingQuantity() }} disponibles</div>
                    </div>
                    <div class="mono text-accent font-bold">Bs {{ number_format($stock->product->price, 2) }}</div>
                    <span style="font-size: 1.25rem;">+</span>
                </div>
            @empty
                <div class="text-center text-muted" style="padding: 3rem 0;">
                    <div style="font-size: 2rem; margin-bottom: .5rem;">📭</div>
                    <div>No hay productos con stock disponible</div>
                </div>
            @endforelse

            @if($toppingItems->isNotEmpty())
                <div style="margin: 1rem 0 .5rem; padding-top: .75rem; border-top: 1px solid var(--border);">
                    <span class="text-xs text-muted" style="text-transform:uppercase; letter-spacing:.08em; font-weight:600;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline;vertical-align:middle;margin-right:4px;"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/></svg>
                        Extras / Toppings
                    </span>
                </div>
                @foreach($toppingItems as $stock)
                    <div class="flex items-center gap-3"
                        style="padding: .75rem; margin-bottom: .5rem; background: rgba(245,158,11,.04); border-radius: 8px; border: 1px solid rgba(245,158,11,.2); cursor: pointer;"
                        onclick="addToCart({{ $stock->product?->id ?? 0 }}, {{ json_encode($stock->product?->name ?? 'Producto no encontrado') }}, {{ $stock->product?->price ?? 0 }}, {{ $stock->remainingQuantity() }})">
                        <div style="flex: 1;">
                            <div class="font-bold text-sm">{{ $stock->product->name }}</div>
                            <div class="text-xs text-muted">{{ $stock->remainingQuantity() }} disponibles</div>
                        </div>
                        <div class="mono font-bold" style="color: #f59e0b;">Bs {{ number_format($stock->product->price, 2) }}</div>
                        <span style="font-size: 1.25rem;">+</span>
                    </div>
                @endforeach
            @endif
        </div>
    </div>

    <div class="card" style="position: sticky; top: 1rem;">
        <div class="card-header">
            <div class="card-title">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline;vertical-align:middle;margin-right:5px;"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                Carrito de Venta
            </div>
            <button type="button" onclick="clearCart()" class="btn btn-ghost btn-sm">Limpiar</button>
        </div>

        <div id="cart-items" style="min-height: 120px; margin-bottom: 1rem;">
            <div id="cart-empty" class="text-center text-muted" style="padding: 2rem 0;">
                Agrega productos desde la izquierda
            </div>
        </div>

        <hr class="divider">
        <div class="flex items-center" style="justify-content: space-between; margin-bottom: 1rem;">
            <span class="text-muted">Total</span>
            <span class="mono font-bold" style="font-size: 1.5rem;" id="cart-total">Bs 0.00</span>
        </div>

        <form method="POST" action="{{ route('cajero.sales.store') }}" id="saleForm">
            @csrf
            <div id="cart-inputs"></div>

            <div class="form-group">
                <label>Método de Pago</label>
                <div class="flex gap-3">
                    <label style="flex:1; text-transform:none; letter-spacing:0; cursor:pointer;">
                        <input type="radio" name="payment_method" value="CASH" checked style="width:auto; margin-right:.5rem;">
                        <span style="font-size:.9rem;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline;vertical-align:middle;margin-right:3px;"><rect x="2" y="6" width="20" height="12" rx="2"/><path d="M12 12h.01"/></svg>
                            Efectivo
                        </span>
                    </label>
                    <label style="flex:1; text-transform:none; letter-spacing:0; cursor:pointer;">
                        <input type="radio" name="payment_method" value="QR" style="width:auto; margin-right:.5rem;">
                        <span style="font-size:.9rem;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline;vertical-align:middle;margin-right:3px;"><rect x="5" y="2" width="14" height="20" rx="2" ry="2"/><line x1="12" y1="18" x2="12.01" y2="18"/></svg>
                            QR
                        </span>
                    </label>
                </div>
            </div>

            <button type="button" onclick="submitSale()" class="btn btn-primary w-full" style="padding: .875rem; font-size: 1rem;">
                ✓ Confirmar Venta
            </button>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
const cart = {};

function addToCart(id, name, price, maxQty) {
    if (cart[id]) {
        if (cart[id].qty >= maxQty) {
            alert('No hay más stock de ' + name);
            return;
        }
        cart[id].qty++;
    } else {
        cart[id] = { name, price, qty: 1, maxQty };
    }
    renderCart();
}

function removeFromCart(id) {
    delete cart[id];
    renderCart();
}

function changeQty(id, delta) {
    if (!cart[id]) return;
    cart[id].qty = Math.max(1, Math.min(cart[id].maxQty, cart[id].qty + delta));
    renderCart();
}

function clearCart() {
    Object.keys(cart).forEach(k => delete cart[k]);
    renderCart();
}

function renderCart() {
    const container = document.getElementById('cart-items');
    const inputs = document.getElementById('cart-inputs');
    const ids = Object.keys(cart);

    if (ids.length === 0) {
        container.innerHTML = '<div id="cart-empty" class="text-center text-muted" style="padding: 2rem 0;">Agrega productos desde la izquierda</div>';
        inputs.innerHTML = '';
        document.getElementById('cart-total').textContent = 'Bs 0.00';
        return;
    }

    let html = '';
    let inputsHtml = '';
    let total = 0;

    ids.forEach((id, i) => {
        const item = cart[id];
        const sub = item.price * item.qty;
        total += sub;
        html += `
            <div class="flex items-center gap-2" style="padding: .5rem 0; border-bottom: 1px solid rgba(42,53,72,.4);">
                <div style="flex:1">
                    <div class="text-sm font-bold">${item.name}</div>
                    <div class="text-xs text-muted mono">Bs ${item.price.toFixed(2)} c/u</div>
                </div>
                <button type="button" onclick="changeQty(${id}, -1)" style="background:var(--border); border:none; color:var(--text); width:28px; height:28px; border-radius:6px; cursor:pointer; font-size:1rem;">-</button>
                <span class="mono" style="width:28px; text-align:center;">${item.qty}</span>
                <button type="button" onclick="changeQty(${id}, 1)" style="background:var(--border); border:none; color:var(--text); width:28px; height:28px; border-radius:6px; cursor:pointer; font-size:1rem;">+</button>
                <span class="mono text-success" style="width:70px; text-align:right;">Bs ${sub.toFixed(2)}</span>
                <button type="button" onclick="removeFromCart(${id})" style="background:rgba(239,68,68,.15); border:none; color:var(--danger); width:28px; height:28px; border-radius:6px; cursor:pointer;">✕</button>
            </div>`;
        inputsHtml += `<input type="hidden" name="items[${i}][product_id]" value="${id}">`;
        inputsHtml += `<input type="hidden" name="items[${i}][quantity]" value="${item.qty}">`;
    });

    container.innerHTML = html;
    inputs.innerHTML = inputsHtml;
    document.getElementById('cart-total').textContent = 'Bs ' + total.toFixed(2);
}

function submitSale() {
    if (Object.keys(cart).length === 0) {
        alert('Agrega al menos un producto al carrito.');
        return;
    }
    renderCart();
    document.getElementById('saleForm').submit();
}
</script>
@endsection
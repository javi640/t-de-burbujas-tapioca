@extends('layouts.app')
@section('title', 'Nueva Venta')

@section('sidebar-nav')
    <span class="nav-section-label">Mi Turno</span>
    <a href="{{ route('cajero.shift.current') }}" class="nav-item">
        <span class="nav-icon">⏱</span> Turno Actual
    </a>
    <a href="{{ route('cajero.sales.create') }}" class="nav-item active">
        <span class="nav-icon">🛒</span> Nueva Venta
    </a>
    <a href="{{ route('cajero.shift.current') }}#movements" class="nav-item">
        <span class="nav-icon">💰</span> Movimientos
    </a>
@endsection

@section('page-title', 'Registrar Venta')
@section('page-subtitle', 'Selecciona los productos y método de pago')

@section('content')
<div class="grid grid-2">
    {{-- Productos disponibles --}}
    <div class="card">
        <div class="card-header">
            <div class="card-title">📦 Productos Disponibles</div>
        </div>
        <div id="products-list">
            @forelse($stockItems as $stock)
                <div class="flex items-center gap-3"
                     style="padding: .75rem; margin-bottom: .5rem; background: rgba(79,142,247,.04); border-radius: 8px; border: 1px solid var(--border); cursor: pointer;"
                     onclick="addToCart({{ $stock->product->id }}, {{ json_encode($stock->product->name) }}, {{ $stock->product->price }}, {{ $stock->remainingQuantity() }})">
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
        </div>
    </div>

    {{-- Carrito --}}
    <div class="card" style="position: sticky; top: 1rem;">
        <div class="card-header">
            <div class="card-title">🛒 Carrito de Venta</div>
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
                        <span style="font-size:.9rem;">💵 Efectivo</span>
                    </label>
                    <label style="flex:1; text-transform:none; letter-spacing:0; cursor:pointer;">
                        <input type="radio" name="payment_method" value="QR" style="width:auto; margin-right:.5rem;">
                        <span style="font-size:.9rem;">📱 QR</span>
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
    const empty = document.getElementById('cart-empty');
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
    renderCart(); // ensure inputs are updated
    document.getElementById('saleForm').submit();
}
</script>
@endsection
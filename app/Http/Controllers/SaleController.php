<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSaleRequest;
use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\ShiftStock;
use App\Models\AuditLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class SaleController extends Controller
{
    public function create(): View
    {
        /** @var \App\Models\User $user */
        $user  = Auth::user();
        $shift = $user->openShift()
            ->with('stock.product')
            ->firstOrFail();

        $stockItems = $shift->stock->filter(
            fn($s) => $s->remainingQuantity() > 0
        );

        return view('cajero.sales.create', compact('shift', 'stockItems'));
    }

    public function store(StoreSaleRequest $request): RedirectResponse
    {
        /** @var \App\Models\User $user */
        $user  = Auth::user();
        $shift = $user->openShift()->firstOrFail();

        DB::transaction(function () use ($request, $shift) {
            $total = 0;
            $items = $request->validated('items');

            foreach ($items as $item) {
                $shiftStock = ShiftStock::where('shift_id', $shift->id)
                    ->where('product_id', $item['product_id'])
                    ->lockForUpdate()
                    ->firstOrFail();

                if (! $shiftStock->hasStock($item['quantity'])) {
                    throw new \Exception(
                        "Stock insuficiente para: {$shiftStock->product->name}"
                    );
                }

                $total += $shiftStock->product->price * $item['quantity'];
            }

            $sale = Sale::create([
                'shift_id'       => $shift->id,
                'total_amount'   => $total,
                'payment_method' => $request->payment_method,
                'status'         => 'COMPLETED',
                'sale_time'      => now(),
            ]);

            foreach ($items as $item) {
                $shiftStock = ShiftStock::where('shift_id', $shift->id)
                    ->where('product_id', $item['product_id'])
                    ->first();

                SaleDetail::create([
                    'sale_id'    => $sale->id,
                    'product_id' => $item['product_id'],
                    'quantity'   => $item['quantity'],
                    'unit_price' => $shiftStock->product->price,
                    'subtotal'   => $shiftStock->product->price * $item['quantity'],
                ]);

                $shiftStock->increment('sold_quantity', $item['quantity']);
            }
        });

        return redirect()
            ->route('cajero.sales.create')
            ->with('success', 'Venta registrada correctamente.');
    }

    public function void(Sale $sale): RedirectResponse
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (! $user->isAdmin()) {
            abort(403, 'Solo el administrador puede anular ventas.');
        }

        if ($sale->isVoided()) {
            return back()->withErrors(['sale' => 'Esta venta ya fue anulada.']);
        }

        DB::transaction(function () use ($sale) {
            $sale->update([
                'status'      => 'VOIDED',
                'voided_by'   => Auth::id(),
                'void_reason' => request('void_reason'),
            ]);

            foreach ($sale->details as $detail) {
                ShiftStock::where('shift_id', $sale->shift_id)
                    ->where('product_id', $detail->product_id)
                    ->decrement('sold_quantity', $detail->quantity);
            }

            AuditLog::create([
                'user_id'    => Auth::id(),
                'action'     => 'void_sale',
                'model'      => 'Sale',
                'model_id'   => $sale->id,
                'new_values' => ['void_reason' => request('void_reason')],
                'ip_address' => request()->ip(),
            ]);
        });

        return back()->with('success', 'Venta anulada y stock restaurado.');
    }
}
<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSaleRequest;
use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\ShiftStock;
use App\Models\AuditLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
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

        $toppingKeywords = ['boba', 'jelly', 'pearls', 'nata', 'crumbs', 'pudding'];

        $isToppingFn = function (string $name) use ($toppingKeywords): bool {
            foreach ($toppingKeywords as $kw) {
                if (stripos($name, $kw) !== false) return true;
            }
            return false;
        };

        $allStock     = $shift->stock->filter(fn($s) => $s->remainingQuantity() > 0);
        $stockItems   = $allStock->filter(fn($s) => !$isToppingFn($s->product->name));
        $toppingItems = $allStock->filter(fn($s) =>  $isToppingFn($s->product->name));

        return view('cajero.sales.create', compact('shift', 'stockItems', 'toppingItems'));
    }

    public function store(StoreSaleRequest $request): RedirectResponse
    {
        /** @var \App\Models\User $user */
        $user  = Auth::user();
        $shift = $user->openShift()->firstOrFail();

        DB::transaction(function () use ($request, $shift) {
            $items = $request->validated('items');
            $productIds = collect($items)->pluck('product_id');

            $stocks = ShiftStock::where('shift_id', $shift->id)
                ->whereIn('product_id', $productIds)
                ->with('product')
                ->lockForUpdate()
                ->get()
                ->keyBy('product_id');

            $total = 0;
            foreach ($items as $item) {
                $ss = $stocks[$item['product_id']] ?? throw new \Exception('Stock no encontrado');

                if (! $ss->hasStock($item['quantity'])) {
                    throw new \Exception(
                        "Stock insuficiente para: {$ss->product->name}"
                    );
                }

                $total += $ss->product->price * $item['quantity'];
            }

            $sale = Sale::create([
                'shift_id'       => $shift->id,
                'total_amount'   => $total,
                'payment_method' => $request->payment_method,
                'status'         => 'COMPLETED',
                'sale_time'      => now(),
            ]);

            foreach ($items as $item) {
                $ss = $stocks[$item['product_id']];

                SaleDetail::create([
                    'sale_id'    => $sale->id,
                    'product_id' => $item['product_id'],
                    'quantity'   => $item['quantity'],
                    'unit_price' => $ss->product->price,
                    'subtotal'   => $ss->product->price * $item['quantity'],
                ]);

                $ss->increment('sold_quantity', $item['quantity']);
            }
        });

        $this->clearDashboardCache();

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

        request()->validate([
            'void_reason' => ['required', 'string', 'min:5', 'max:500'],
        ], [
            'void_reason.required' => 'El motivo de anulación es obligatorio.',
            'void_reason.min'      => 'El motivo debe tener al menos 5 caracteres.',
            'void_reason.max'      => 'El motivo no puede superar los 500 caracteres.',
        ]);

        $sale->loadMissing('details');

        DB::transaction(function () use ($sale) {
            $sale->update([
                'status'      => 'VOIDED',
                'voided_by'   => Auth::id(),
                'void_reason' => request('void_reason'),
            ]);

            $sale->details->groupBy('product_id')->each(function ($details, $productId) use ($sale) {
                $qty = $details->sum('quantity');
                ShiftStock::where('shift_id', $sale->shift_id)
                    ->where('product_id', $productId)
                    ->decrement('sold_quantity', $qty);
            });

            AuditLog::create([
                'user_id'    => Auth::id(),
                'action'     => 'void_sale',
                'model'      => 'Sale',
                'model_id'   => $sale->id,
                'new_values' => ['void_reason' => request('void_reason')],
                'ip_address' => request()->ip(),
            ]);
        });

        $this->clearDashboardCache();

        return back()->with('success', 'Venta anulada y stock restaurado.');
    }

    private function clearDashboardCache(): void
    {
        Cache::forget('dashboard.active_shifts');
        Cache::forget('dashboard.period.hoy.' . today()->format('Y-m-d') . '.' . today()->format('Y-m-d'));
        Cache::forget('dashboard.period.semana.' . now()->startOfWeek()->format('Y-m-d') . '.' . now()->endOfWeek()->format('Y-m-d'));
    }
}
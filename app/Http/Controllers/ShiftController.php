<?php

namespace App\Http\Controllers;

use App\Http\Requests\OpenShiftRequest;
use App\Http\Requests\CloseShiftRequest;
use App\Models\Shift;
use App\Models\Product;
use App\Models\ShiftStock;
use App\Models\AuditLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ShiftController extends Controller
{
    public function showOpen(): View|RedirectResponse
    {
        $user = $this->authUser();

        if ($user->hasOpenShift()) {
            return redirect()->route('cajero.shift.current');
        }

        $products = Product::where('branch_id', $user->branch_id)
            ->active()
            ->orderBy('name')
            ->get();

        return view('cajero.shift.open', compact('products'));
    }

    public function open(OpenShiftRequest $request): RedirectResponse
    {
        $user = $this->authUser();

        if ($user->hasOpenShift()) {
            return back()->withErrors(['shift' => 'Ya tienes un turno abierto.']);
        }

        DB::transaction(function () use ($request, $user) {
            $shift = Shift::create([
                'user_id'      => $user->id,
                'status'       => 'OPEN',
                'initial_cash' => $request->initial_cash,
                'notes'        => $request->notes,
            ]);

            foreach ($request->stock as $productId => $quantity) {
                if ($quantity > 0) {
                    ShiftStock::create([
                        'shift_id'         => $shift->id,
                        'product_id'       => $productId,
                        'initial_quantity' => $quantity,
                        'sold_quantity'    => 0,
                    ]);
                }
            }

            AuditLog::create([
                'user_id'    => $user->id,
                'action'     => 'open_shift',
                'model'      => 'Shift',
                'model_id'   => $shift->id,
                'new_values' => ['initial_cash' => $request->initial_cash],
                'ip_address' => request()->ip(),
            ]);
        });

        return redirect()
            ->route('cajero.shift.current')
            ->with('success', 'Turno abierto correctamente.');
    }

    public function current(): View
    {
        $user = $this->authUser();
        $shift = $user->openShift()
            ->with(['stock.product', 'sales' => fn($q) => $q->completed()])
            ->firstOrFail();

        $expectedCash = $shift->expectedCash();
        $totalQr      = $shift->totalQr();

        return view('cajero.shift.current', compact('shift', 'expectedCash', 'totalQr'));
    }

    public function close(CloseShiftRequest $request): RedirectResponse
    {
        $user = $this->authUser();
        $shift = $user->openShift()->firstOrFail();

        DB::transaction(function () use ($request, $shift, $user) {
            $expectedCash = $shift->expectedCash();
            $reportedCash = $request->reported_cash;
            $difference   = $reportedCash - $expectedCash;

            $shift->update([
                'status'          => 'CLOSED',
                'end_time'        => now(),
                'reported_cash'   => $reportedCash,
                'cash_difference' => $difference,
                'notes'           => $request->notes,
            ]);

            AuditLog::create([
                'user_id'    => $user->id,
                'action'     => 'close_shift',
                'model'      => 'Shift',
                'model_id'   => $shift->id,
                'old_values' => ['expected_cash' => $expectedCash],
                'new_values' => [
                    'reported_cash'   => $reportedCash,
                    'cash_difference' => $difference,
                ],
                'ip_address' => request()->ip(),
            ]);
        });

        return redirect()
            ->route('cajero.shift.summary', $shift->id)
            ->with('success', 'Turno cerrado. Resumen disponible.');
    }

    public function summary(Shift $shift): View
    {
        $user = $this->authUser();

        if ($user->isCajero() && $shift->user_id !== $user->id) {
            abort(403);
        }

        $shift->load(['user', 'sales.details.product', 'stock.product', 'cashMovements']);

        return view('cajero.shift.summary', compact('shift'));
    }
}
<?php

namespace App\Http\Controllers;

use App\Services\DecisionTreeService;
use App\Http\Requests\CloseShiftRequest;
use App\Models\Shift;
use App\Models\AuditLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ShiftController extends Controller
{
    // ── Vista principal del turno activo ─────────────────────────

    public function current(): View
    {
        $user  = $this->authUser();
        $shift = $user->openShift()
            ->with(['stock.product', 'sales' => fn($q) => $q->completed(), 'openedBy'])
            ->firstOrFail();

        $expectedCash = $shift->expectedCash();
        $totalQr      = $shift->totalQr();

        return view('cajero.shift.current', compact('shift', 'expectedCash', 'totalQr'));
    }

    // ── Pantalla de espera (sin turno asignado aún) ──────────────

    /**
     * El cajero ve esta pantalla cuando inicia sesión pero
     * el admin todavía no le ha abierto un turno.
     */
    public function waiting(): View
    {
        $user = $this->authUser();

        // Si ya tiene turno, redirigir al módulo de ventas
        if ($user->hasOpenShift()) {
            return redirect()->route('cajero.shift.current');
        }

        return view('cajero.shift.waiting');
    }

    // ── Cierre de turno (sigue siendo responsabilidad del cajero) ─

    public function close(CloseShiftRequest $request): RedirectResponse
    {
        $user  = $this->authUser();
        $shift = $user->openShift()->firstOrFail();

        DB::transaction(function () use ($request, $shift, $user) {
            $expectedCash = $shift->expectedCash();
            $reportedCash = $request->reported_cash;
            $difference   = $reportedCash - $expectedCash;

            // Árbol de decisiones multivariable (aporte académico)
            $treeResult = app(DecisionTreeService::class)
                ->evaluate($expectedCash, $reportedCash, $shift);

            $shift->update([
                'status'              => 'CLOSED',
                'end_time'            => now(),
                'reported_cash'       => $reportedCash,
                'cash_difference'     => $difference,
                'inconsistency_class' => $treeResult['classification'],
                'notes'               => $request->notes,
            ]);

            AuditLog::create([
                'user_id'    => $user->id,
                'action'     => 'close_shift',
                'model'      => 'Shift',
                'model_id'   => $shift->id,
                'old_values' => ['expected_cash' => $expectedCash],
                'new_values' => [
                    'reported_cash'       => $reportedCash,
                    'cash_difference'     => $difference,
                    'inconsistency_class' => $treeResult['classification'],
                ],
                'ip_address' => request()->ip(),
            ]);
        });

        // Limpiar caché del dashboard
        Cache::forget('dashboard.period.hoy.' . today()->format('Y-m-d') . '.' . today()->format('Y-m-d'));
        Cache::forget('dashboard.period.semana.' . now()->startOfWeek()->format('Y-m-d') . '.' . now()->endOfWeek()->format('Y-m-d'));
        Cache::forget('dashboard.active_shifts');

        return redirect()
            ->route('cajero.shift.summary', $shift->id)
            ->with('success', 'Turno cerrado. Resumen disponible.');
    }

    // ── Resumen del turno cerrado ────────────────────────────────

    public function summary(Shift $shift): View
    {
        $user = $this->authUser();

        if ($user->isCajero() && $shift->user_id !== $user->id) {
            abort(403);
        }

        $shift->load(['user', 'openedBy', 'sales.details.product', 'stock.product', 'cashMovements']);

        return view('cajero.shift.summary', compact('shift'));
    }
}
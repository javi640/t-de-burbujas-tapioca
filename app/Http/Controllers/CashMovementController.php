<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCashMovementRequest;
use App\Models\AuditLog;
use App\Models\CashMovement;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class CashMovementController extends Controller
{
    public function store(StoreCashMovementRequest $request): RedirectResponse
    {
        $user  = Auth::user();
        $shift = $user->openShift()->firstOrFail();

        $movement = CashMovement::create([
            'shift_id'      => $shift->id,
            'created_by'    => $user->id,
            'movement_type' => $request->movement_type,
            'amount'        => $request->amount,
            'description'   => $request->description,
        ]);

        // ── Registrar en auditoría
        AuditLog::create([
            'user_id'    => $user->id,
            'action'     => 'cash_movement',
            'model'      => 'Shift',
            'model_id'   => $shift->id,
            'old_values' => null,
            'new_values' => [
                'movement_type' => $movement->movement_type,
                'amount'        => $movement->amount,
                'description'   => $movement->description,
            ],
            'ip_address' => $request->ip(),
            'created_at' => now(),
        ]);

        return back()->with('success', 'Movimiento registrado.');
    }
}
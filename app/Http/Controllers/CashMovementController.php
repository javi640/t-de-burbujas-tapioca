<?php

namespace App\Http\Controllers;

 
use App\Http\Requests\StoreCashMovementRequest;
use App\Models\CashMovement;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
 
class CashMovementController extends Controller
{
    public function store(StoreCashMovementRequest $request): RedirectResponse
    {
        $shift = Auth::user()->openShift()->firstOrFail();
 
        CashMovement::create([
            'shift_id'      => $shift->id,
            'created_by'    => Auth::id(),
            'movement_type' => $request->movement_type,
            'amount'        => $request->amount,
            'description'   => $request->description,
        ]);
 
        return back()->with('success', 'Movimiento registrado.');
    }
}
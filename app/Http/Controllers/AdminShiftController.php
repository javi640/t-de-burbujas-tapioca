<?php

namespace App\Http\Controllers;
 
use App\Models\Shift;
use Illuminate\View\View;
 
class AdminShiftController extends Controller
{
    public function index(): View
    {
        $shifts = Shift::with('user')
            ->latest('start_time')
            ->paginate(20);
 
        return view('admin.shifts.index', compact('shifts'));
    }
 
    public function show(Shift $shift): View
    {
        $shift->load(['user', 'sales.details.product', 'stock.product', 'cashMovements']);
        return view('cajero.shift.summary', compact('shift'));
    }
}
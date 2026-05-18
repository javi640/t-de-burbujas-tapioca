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
        // Carga las relaciones de forma más eficiente
        $shift->load([
            'user',
            'sales' => function ($query) {
                $query->with('details.product', 'voidedBy')
                    ->orderBy('created_at', 'desc')
                    ->limit(100); // Limita a evitar timeout
            },
            'stock.product',
            'cashMovements' => function ($query) {
                $query->orderBy('created_at', 'desc')
                    ->limit(100);
            },
        ]);

        return view('admin.shifts.show', compact('shift'));
    }
}
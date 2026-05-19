<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminSaleController extends Controller
{
    public function index(Request $request): View
    {
        $query = Sale::with(['shift.user', 'details.product', 'voidedBy'])
            ->latest('sale_time');

        if ($request->filled('from')) {
            $query->whereDate('sale_time', '>=', $request->from);
        }
        if ($request->filled('to')) {
            $query->whereDate('sale_time', '<=', $request->to);
        }
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        $totals = (clone $query)
            ->where('status', 'COMPLETED')
            ->selectRaw("
                SUM(CASE WHEN payment_method='CASH' THEN total_amount ELSE 0 END) as cash_total,
                SUM(CASE WHEN payment_method='QR' THEN total_amount ELSE 0 END) as qr_total
            ")
            ->first();

        $totalCash = $totals->cash_total ?? 0;
        $totalQr   = $totals->qr_total ?? 0;

        $sales = $query->paginate(25);

        return view('admin.sales.index', compact('sales', 'totalCash', 'totalQr'));
    }
}
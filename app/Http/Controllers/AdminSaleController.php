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

        $baseQuery = clone $query;
        $totalCash = (clone $baseQuery)->where('payment_method', 'CASH')->where('status', 'COMPLETED')->sum('total_amount');
        $totalQr   = (clone $baseQuery)->where('payment_method', 'QR')->where('status', 'COMPLETED')->sum('total_amount');

        $sales = $query->paginate(25);

        return view('admin.sales.index', compact('sales', 'totalCash', 'totalQr'));
    }
}
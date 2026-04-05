<?php

namespace App\Http\Controllers;
 
use App\Models\Shift;
use App\Models\Sale;
use Illuminate\View\View;
 
class AdminDashboardController extends Controller
{
    public function index(): View
    {
        $openShifts      = Shift::where('status', 'OPEN')->count();
        $activeShifts    = Shift::where('status', 'OPEN')->with('user', 'sales')->get();
        $todaySalesCount = Sale::whereDate('sale_time', today())->where('status', 'COMPLETED')->count();
        $todayCash       = Sale::whereDate('sale_time', today())
                               ->where('status', 'COMPLETED')
                               ->where('payment_method', 'CASH')
                               ->sum('total_amount');
        $todayQr         = Sale::whereDate('sale_time', today())
                               ->where('status', 'COMPLETED')
                               ->where('payment_method', 'QR')
                               ->sum('total_amount');
        $recentSales     = Sale::with('shift.user')
                               ->whereDate('sale_time', today())
                               ->latest('sale_time')
                               ->take(10)
                               ->get();
 
        return view('admin.dashboard', compact(
            'openShifts', 'activeShifts', 'todaySalesCount',
            'todayCash', 'todayQr', 'recentSales'
        ));
    }
}
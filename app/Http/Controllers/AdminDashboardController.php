<?php

namespace App\Http\Controllers;

use App\Models\Shift;
use App\Models\Sale;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    public function index(): View
    {
        // ── Métricas generales del día ───────────────────────────────
        $openShifts      = Shift::where('status', 'OPEN')->count();
        $activeShifts    = Shift::where('status', 'OPEN')
                                ->with('user', 'sales')
                                ->get();

        $todaySalesCount = Sale::whereDate('sale_time', today())
                               ->where('status', 'COMPLETED')
                               ->count();

        $todayCash = Sale::whereDate('sale_time', today())
                         ->where('status', 'COMPLETED')
                         ->where('payment_method', 'CASH')
                         ->sum('total_amount');

        $todayQr = Sale::whereDate('sale_time', today())
                       ->where('status', 'COMPLETED')
                       ->where('payment_method', 'QR')
                       ->sum('total_amount');

        $todayTotal = $todayCash + $todayQr;

        // ── Últimas ventas ───────────────────────────────────────────
        $recentSales = Sale::with('shift.user')
                           ->whereDate('sale_time', today())
                           ->latest('sale_time')
                           ->take(10)
                           ->get();

        // ── Ventas por cajero (hoy) ──────────────────────────────────
        // Muestra cuánto vendió cada cajero hoy, separado por método de pago
        $salesByCashier = Sale::whereDate('sale_time', today())
            ->where('status', 'COMPLETED')
            ->join('shifts', 'sales.shift_id', '=', 'shifts.id')
            ->join('users', 'shifts.user_id', '=', 'users.id')
            ->select(
                'users.name as cashier_name',
                DB::raw('SUM(CASE WHEN sales.payment_method = "CASH" THEN sales.total_amount ELSE 0 END) as cash_total'),
                DB::raw('SUM(CASE WHEN sales.payment_method = "QR"   THEN sales.total_amount ELSE 0 END) as qr_total'),
                DB::raw('SUM(sales.total_amount) as grand_total'),
                DB::raw('COUNT(*) as sale_count')
            )
            ->groupBy('users.id', 'users.name')
            ->orderByDesc('grand_total')
            ->get();

        // ── Ventas por hora (para el gráfico de barras) ──────────────
        // Agrupa las ventas de hoy en bloques de 1 hora (00:00 – 23:00)
        $salesByHour = Sale::whereDate('sale_time', today())
            ->where('status', 'COMPLETED')
            ->select(
                DB::raw('HOUR(sale_time) as hour'),
                DB::raw('SUM(total_amount) as total'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy(DB::raw('HOUR(sale_time)'))
            ->orderBy('hour')
            ->get()
            ->keyBy('hour');   // índice por hora para acceso rápido en la vista

        // Construir array de 24 horas completo (horas sin ventas = 0)
        $hourlyData = [];
        for ($h = 0; $h < 24; $h++) {
            $hourlyData[] = [
                'hour'  => str_pad($h, 2, '0', STR_PAD_LEFT) . ':00',
                'total' => isset($salesByHour[$h]) ? (float) $salesByHour[$h]->total : 0,
                'count' => isset($salesByHour[$h]) ? (int)   $salesByHour[$h]->count : 0,
            ];
        }

        // ── Productos más vendidos hoy ────────────────────────────────
        $topProducts = DB::table('sale_details')
            ->join('sales',    'sale_details.sale_id',    '=', 'sales.id')
            ->join('products', 'sale_details.product_id', '=', 'products.id')
            ->whereDate('sales.sale_time', today())
            ->where('sales.status', 'COMPLETED')
            ->select(
                'products.name',
                DB::raw('SUM(sale_details.quantity) as units_sold'),
                DB::raw('SUM(sale_details.quantity * sale_details.unit_price) as revenue')
            )
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('units_sold')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'openShifts',
            'activeShifts',
            'todaySalesCount',
            'todayCash',
            'todayQr',
            'todayTotal',
            'recentSales',
            'salesByCashier',
            'hourlyData',
            'topProducts'
        ));
    }
}
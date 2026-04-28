<?php

namespace App\Http\Controllers;

use App\Models\Shift;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    public function index(Request $request): View
    {
        // ── Modo: 'hoy' o 'semana' (selector del admin) ─────────
        $mode = $request->get('modo', 'hoy');
        $isWeek = $mode === 'semana';

        // Rango de fechas según el modo
        $dateFrom = $isWeek ? now()->startOfWeek() : today();
        $dateTo   = $isWeek ? now()->endOfWeek()   : today();
        $label    = $isWeek ? 'esta semana' : 'hoy';

        // ── Turnos abiertos (siempre en tiempo real) ─────────────
        $openShifts  = Shift::where('status', 'OPEN')->count();
        $activeShifts = Shift::where('status', 'OPEN')
            ->with('user', 'sales')
            ->get();

        // ── Métricas del período ─────────────────────────────────
        $periodSalesCount = Sale::whereBetween('sale_time', [$dateFrom, $dateTo])
            ->where('sales.status', 'COMPLETED')
            ->count();

        $periodCash = Sale::whereBetween('sale_time', [$dateFrom, $dateTo])
            ->where('sales.status', 'COMPLETED')
            ->where('payment_method', 'CASH')
            ->sum('total_amount');

        $periodQr = Sale::whereBetween('sale_time', [$dateFrom, $dateTo])
            ->where('sales.status', 'COMPLETED')
            ->where('payment_method', 'QR')
            ->sum('total_amount');

        $periodTotal = $periodCash + $periodQr;

        // Mantener nombres compatibles con la vista
        $todaySalesCount = $periodSalesCount;
        $todayCash       = $periodCash;
        $todayQr         = $periodQr;
        $todayTotal      = $periodTotal;

        // ── Últimas ventas (siempre las 10 más recientes) ────────
        $recentSales = Sale::with('shift.user')
            ->whereBetween('sale_time', [$dateFrom, $dateTo])
            ->where('sales.status', 'COMPLETED')
            ->latest('sale_time')
            ->take(10)
            ->get();

        // ── Ventas por cajero del período ────────────────────────
        $salesByCashier = Sale::whereBetween('sale_time', [$dateFrom, $dateTo])
            ->where('sales.status', 'COMPLETED')
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

        // ── Gráfico: por hora (hoy) o por día (semana) ───────────
        if ($isWeek) {
            // Ventas agrupadas por día de la semana (lun-dom)
            $salesByDay = Sale::whereBetween('sale_time', [$dateFrom, $dateTo])
                ->where('sales.status', 'COMPLETED')
                ->select(
                    DB::raw('DAYOFWEEK(sale_time) as dow'),  // 1=Dom, 2=Lun ... 7=Sab
                    DB::raw('DATE(sale_time) as sale_date'),
                    DB::raw('SUM(total_amount) as total'),
                    DB::raw('COUNT(*) as count')
                )
                ->groupBy(DB::raw('DATE(sale_time)'), DB::raw('DAYOFWEEK(sale_time)'))
                ->orderBy(DB::raw('DATE(sale_time)'))
                ->get()
                ->keyBy('sale_date');

            $days = ['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'];
            $chartData = [];
            for ($d = 0; $d < 7; $d++) {
                $date    = now()->startOfWeek()->addDays($d)->format('Y-m-d');
                $dayName = $days[$d];
                $chartData[] = [
                    'label' => $dayName,
                    'date'  => $date,
                    'total' => isset($salesByDay[$date]) ? (float) $salesByDay[$date]->total : 0,
                    'count' => isset($salesByDay[$date]) ? (int)   $salesByDay[$date]->count : 0,
                    'isToday' => $date === today()->format('Y-m-d'),
                ];
            }
            $hourlyData = $chartData;
            $chartMode  = 'week';
        } else {
            // Ventas agrupadas por hora del día actual
            $salesByHour = Sale::whereDate('sale_time', today())
                ->where('sales.status', 'COMPLETED')
                ->select(
                    DB::raw('HOUR(sale_time) as hour'),
                    DB::raw('SUM(total_amount) as total'),
                    DB::raw('COUNT(*) as count')
                )
                ->groupBy(DB::raw('HOUR(sale_time)'))
                ->orderBy('hour')
                ->get()
                ->keyBy('hour');

            $currentHour = (int) now()->format('G');
            $allHours = [];
            for ($h = 0; $h < 24; $h++) {
                $allHours[] = [
                    'label'   => str_pad($h, 2, '0', STR_PAD_LEFT) . ':00',
                    'total'   => isset($salesByHour[$h]) ? (float) $salesByHour[$h]->total : 0,
                    'count'   => isset($salesByHour[$h]) ? (int)   $salesByHour[$h]->count : 0,
                    'isToday' => $h === $currentHour,
                ];
            }
            // Solo mostrar horas relevantes
            $hourlyData = collect($allHours)->filter(
                fn($h, $i) => $h['total'] > 0 || abs($i - $currentHour) <= 1
            )->values()->all();

            if (empty($hourlyData)) {
                $hourlyData = array_slice($allHours, 0, 8);
            }
            $chartMode = 'day';
        }

        // ── Top productos del período ────────────────────────────
        $topProducts = DB::table('sale_details')
            ->join('sales',    'sale_details.sale_id',    '=', 'sales.id')
            ->join('products', 'sale_details.product_id', '=', 'products.id')
            ->whereBetween('sales.sale_time', [$dateFrom, $dateTo])
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
            'topProducts',
            'mode',
            'isWeek',
            'label',
            'chartMode',
        ));
    }
}
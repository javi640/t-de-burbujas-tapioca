<?php

namespace App\Http\Controllers;

use App\Models\Shift;
use App\Services\DecisionTreeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AdminReportController extends Controller
{
    public function __construct(private DecisionTreeService $decisionTree) {}

    /**
     * Muestra el reporte de cierre diario.
     * El admin elige la fecha; por defecto muestra ayer (día más probable
     * de tener turnos cerrados) o hoy si hay turnos ya cerrados hoy.
     */
    public function dailyReport(Request $request): View
    {
        // Fecha seleccionada (por defecto: hoy)
        $date = $request->filled('fecha')
            ? \Carbon\Carbon::parse($request->fecha)->startOfDay()
            : today();

        // ── Turnos cerrados del día seleccionado ─────────────────
        $shifts = Shift::where('status', 'CLOSED')
            ->whereDate('start_time', $date)
            ->with([
                'user',
                'sales' => fn($q) => $q->with('details.product'),
                'cashMovements',
                'stock.product',
            ])
            ->orderBy('start_time')
            ->get();

        // ── Aplicar el árbol de decisiones a cada turno ──────────
        $shiftsWithDecision = $shifts->map(function (Shift $shift) {
            $expected = $shift->expectedCash();
            $reported = (float) ($shift->reported_cash ?? 0);

            return [
                'shift'    => $shift,
                'expected' => $expected,
                'reported' => $reported,
                'decision' => $this->decisionTree->evaluate($expected, $reported),
            ];
        });

        // ── Totales consolidados del día ─────────────────────────
        $totalCash = $shifts->sum(fn($s) =>
            $s->sales->where('payment_method', 'CASH')->where('status', 'COMPLETED')->sum('total_amount')
        );
        $totalQr = $shifts->sum(fn($s) =>
            $s->sales->where('payment_method', 'QR')->where('status', 'COMPLETED')->sum('total_amount')
        );
        $totalSales      = $shifts->sum(fn($s) => $s->sales->where('status', 'COMPLETED')->count());
        $totalVoided     = $shifts->sum(fn($s) => $s->sales->where('status', 'VOIDED')->count());
        $totalExpenses   = $shifts->sum(fn($s) =>
            $s->cashMovements->where('movement_type', 'EXPENSE')->sum('amount')
        );
        $totalIncome     = $shifts->sum(fn($s) =>
            $s->cashMovements->where('movement_type', 'INCOME')->sum('amount')
        );
        $netRevenue      = $totalCash + $totalQr - $totalExpenses + $totalIncome;

        // ── Resumen de decisiones del día ────────────────────────
        $decisionSummary = [
            'ok'      => $shiftsWithDecision->filter(fn($r) => $r['decision']['classification'] === 'SIN_INCONSISTENCIA')->count(),
            'leve'    => $shiftsWithDecision->filter(fn($r) => $r['decision']['classification'] === 'INCONSISTENCIA_LEVE')->count(),
            'critica' => $shiftsWithDecision->filter(fn($r) => $r['decision']['classification'] === 'INCONSISTENCIA_CRITICA')->count(),
        ];

        // ── Productos vendidos en el día (consolidado) ───────────
        $topProducts = DB::table('sale_details')
            ->join('sales',    'sale_details.sale_id',    '=', 'sales.id')
            ->join('products', 'sale_details.product_id', '=', 'products.id')
            ->join('shifts',   'sales.shift_id',          '=', 'shifts.id')
            ->whereDate('shifts.start_time', $date)
            ->where('shifts.status', 'CLOSED')
            ->where('sales.status', 'COMPLETED')
            ->select(
                'products.name',
                DB::raw('SUM(sale_details.quantity) as units_sold'),
                DB::raw('SUM(sale_details.subtotal) as revenue')
            )
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('units_sold')
            ->get();

        return view('admin.reports.daily', compact(
            'date',
            'shifts',
            'shiftsWithDecision',
            'totalCash',
            'totalQr',
            'totalSales',
            'totalVoided',
            'totalExpenses',
            'totalIncome',
            'netRevenue',
            'decisionSummary',
            'topProducts',
        ));
    }
}
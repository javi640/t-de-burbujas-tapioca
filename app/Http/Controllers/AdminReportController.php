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

    public function dailyReport(Request $request): View
    {
        $date = $request->filled('fecha')
            ? \Carbon\Carbon::parse($request->fecha)->startOfDay()
            : today();

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

        $shiftsWithDecision = $shifts->map(function (Shift $shift) {
            $expected = $shift->expectedCash();
            $reported = (float) ($shift->reported_cash ?? 0);

            return [
                'shift'    => $shift,
                'expected' => $expected,
                'reported' => $reported,
                'decision' => $this->decisionTree->evaluate($expected, $reported, $shift),
            ];
        });

        $totalsRow = DB::table('sales')
            ->join('shifts', 'sales.shift_id', '=', 'shifts.id')
            ->whereDate('shifts.start_time', $date)
            ->where('shifts.status', 'CLOSED')
            ->selectRaw("
                SUM(CASE WHEN sales.status = 'COMPLETED' AND sales.payment_method = 'CASH' THEN sales.total_amount ELSE 0 END) as total_cash,
                SUM(CASE WHEN sales.status = 'COMPLETED' AND sales.payment_method = 'QR'   THEN sales.total_amount ELSE 0 END) as total_qr,
                SUM(CASE WHEN sales.status = 'COMPLETED' THEN 1 ELSE 0 END) as total_sales,
                SUM(CASE WHEN sales.status = 'VOIDED'    THEN 1 ELSE 0 END) as total_voided
            ")
            ->first();

        $movementsRow = DB::table('cash_movements')
            ->join('shifts', 'cash_movements.shift_id', '=', 'shifts.id')
            ->whereDate('shifts.start_time', $date)
            ->where('shifts.status', 'CLOSED')
            ->selectRaw("
                SUM(CASE WHEN cash_movements.movement_type = 'EXPENSE' THEN cash_movements.amount ELSE 0 END) as total_expenses,
                SUM(CASE WHEN cash_movements.movement_type = 'INCOME'  THEN cash_movements.amount ELSE 0 END) as total_income
            ")
            ->first();

        $totalCash     = (float) ($totalsRow->total_cash    ?? 0);
        $totalQr       = (float) ($totalsRow->total_qr      ?? 0);
        $totalSales    = (int)   ($totalsRow->total_sales   ?? 0);
        $totalVoided   = (int)   ($totalsRow->total_voided  ?? 0);
        $totalExpenses = (float) ($movementsRow->total_expenses ?? 0);
        $totalIncome   = (float) ($movementsRow->total_income   ?? 0);
        $netRevenue    = $totalCash + $totalQr - $totalExpenses + $totalIncome;

        $decisionSummary = [
            'ok'      => $shiftsWithDecision->filter(fn($r) => $r['decision']['classification'] === 'SIN_INCONSISTENCIA')->count(),
            'leve'    => $shiftsWithDecision->filter(fn($r) => $r['decision']['classification'] === 'INCONSISTENCIA_LEVE')->count(),
            'critica' => $shiftsWithDecision->filter(fn($r) => $r['decision']['classification'] === 'INCONSISTENCIA_CRITICA')->count(),
        ];

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

    // ── PJ-25: Conciliación de Stock y Ventas ────────────────────
    public function stockReconciliation(Request $request): View
    {
        $date = $request->filled('fecha')
            ? \Carbon\Carbon::parse($request->fecha)->startOfDay()
            : today();

        // Turnos cerrados del día
        $shifts = Shift::where('status', 'CLOSED')
            ->whereDate('start_time', $date)
            ->with([
                'user',
                'stock.product',
                'sales' => fn($q) => $q->where('status', 'COMPLETED')->with('details'),
            ])
            ->orderBy('start_time')
            ->get();

        // Consolidado por producto — calculado en PHP sobre colecciones ya cargadas
        $productSummary = collect();

        foreach ($shifts as $shift) {
            foreach ($shift->stock as $stock) {
                $productId   = $stock->product_id;
                $productName = $stock->product->name ?? '—';

                $vendido = $shift->sales
                    ->flatMap->details
                    ->where('product_id', $productId)
                    ->sum('quantity');

                $salidaFisica = $stock->initial_quantity - $stock->remaining_quantity;

                if (!$productSummary->has($productId)) {
                    $productSummary->put($productId, [
                        'name'                  => $productName,
                        'total_initial'         => 0,
                        'total_remaining'       => 0,
                        'total_salida_fisica'   => 0,
                        'total_vendido_sistema' => 0,
                    ]);
                }

                $current = $productSummary->get($productId);
                $current['total_initial']         += $stock->initial_quantity;
                $current['total_remaining']       += $stock->remaining_quantity;
                $current['total_salida_fisica']   += $salidaFisica;
                $current['total_vendido_sistema'] += $vendido;
                $productSummary->put($productId, $current);
            }
        }

        // Calcular diferencia y convertir a objetos para la vista
        $productSummary = $productSummary
            ->map(function($row) {
                $row['diferencia'] = $row['total_salida_fisica'] - $row['total_vendido_sistema'];
                return (object) $row;
            })
            ->sortBy('name')
            ->values();

        return view('admin.reports.stock-reconciliation', compact(
            'date',
            'shifts',
            'productSummary',
        ));
    }
}
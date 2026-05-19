<?php

namespace App\Services;

use App\Models\Shift;
use App\Models\Sale;
use App\Models\CashMovement;

/**
 * ══════════════════════════════════════════════════════════════════
 *  APORTE ACADÉMICO — Árbol de Decisiones Multivariable
 *                     para Arqueo de Turno
 * ══════════════════════════════════════════════════════════════════
 *
 *  Este servicio implementa un árbol de decisiones que clasifica
 *  automáticamente el resultado del arqueo de cierre de turno,
 *  evaluando CINCO variables contextuales extraídas de la base
 *  de datos para producir una clasificación fundamentada.
 *
 *  Variables evaluadas:
 *   V1 — Diferencia de arqueo        (shifts.cash_difference)
 *   V2 — Reincidencia del cajero     (historial shifts por user_id)
 *   V3 — Tipo de diferencia          (faltante / sobrante)
 *   V4 — Proporción de ventas QR     (sales.payment_method = 'QR')
 *   V5 — Registro de egresos         (cash_movements.movement_type = 'EXPENSE')
 *
 *  Estructura del árbol:
 *
 *  [RAÍZ] ¿Hay diferencia en el arqueo?
 *    │
 *    ├── NO  → ✅ SIN INCONSISTENCIA
 *    │
 *    └── SÍ  → [V1] ¿La diferencia es leve (≤ THRESHOLD_LEVE)?
 *                │
 *                ├── SÍ (leve)
 *                │     └── [V2] ¿El cajero es reincidente?
 *                │               ├── SÍ → 🔴 CRÍTICA  (patrón repetido)
 *                │               └── NO → 🟡 LEVE
 *                │
 *                └── NO (alta)
 *                      └── [V3] ¿Es faltante?
 *                                │
 *                                ├── SÍ (faltante)
 *                                │     └── [V4] ¿Tiene ventas QR que explican el faltante?
 *                                │               ├── SÍ → 🟡 LEVE     (diferencia explicable por QR)
 *                                │               └── NO → 🔴 CRÍTICA
 *                                │
 *                                └── NO (sobrante)
 *                                      └── [V5] ¿Registró egresos en este turno?
 *                                                ├── SÍ → 🟡 LEVE     (sobrante explicable)
 *                                                └── NO → 🔴 CRÍTICA  (egresos no registrados)
 *
 *  Los umbrales son configurables en las constantes de la clase.
 * ══════════════════════════════════════════════════════════════════
 */
class DecisionTreeService
{
    // ── Umbrales de clasificación ────────────────────────────────

    /** Diferencia exactamente cero = turno cuadrado */
    const THRESHOLD_OK = 0;

    /** Diferencia (valor absoluto) hasta la cual se considera leve (Bs) */
    const THRESHOLD_LEVE = 20.00;

    /**
     * Cantidad de turnos anteriores a revisar para
     * determinar si el cajero es reincidente
     */
    const REINCIDENCE_WINDOW = 3;

    /**
     * Cuántos de esos turnos deben tener inconsistencia
     * para considerar al cajero reincidente
     */
    const REINCIDENCE_MIN = 2;

    /**
     * Porcentaje mínimo de ventas QR sobre el total
     * para que el faltante sea "explicable por QR"
     */
    const QR_THRESHOLD_PERCENT = 30.0;

    // ── Constantes de resultado ──────────────────────────────────
    const RESULT_OK      = 'SIN_INCONSISTENCIA';
    const RESULT_LEVE    = 'INCONSISTENCIA_LEVE';
    const RESULT_CRITICA = 'INCONSISTENCIA_CRITICA';

    // ─────────────────────────────────────────────────────────────
    //  PUNTO DE ENTRADA PRINCIPAL
    // ─────────────────────────────────────────────────────────────

    /**
     * Evalúa el cierre de turno con el árbol de decisiones multivariable.
     *
     * @param  float  $expectedCash  Efectivo que DEBERÍA haber (calculado)
     * @param  float  $reportedCash  Efectivo que el cajero DECLARÓ tener
     * @param  Shift  $shift         Turno que se está cerrando
     * @return array  Resultado completo con clasificación, path y recomendación
     */
    public function evaluate(float $expectedCash, float $reportedCash, Shift $shift): array
    {
        $difference = $reportedCash - $expectedCash;
        $absDiff    = abs($difference);

        // ── NODO RAÍZ: ¿Existe alguna diferencia? ───────────────
        if ($absDiff == self::THRESHOLD_OK) {
            return $this->buildResult(
                classification: self::RESULT_OK,
                difference:     $difference,
                shift:          $shift,
                path:           [
                    'V1 ¿Hay diferencia en el arqueo?' => 'No → Turno cuadrado',
                ],
            );
        }

        // ── NODO 1: ¿La diferencia es leve o alta? ──────────────
        $esLeve = $absDiff <= self::THRESHOLD_LEVE;

        if ($esLeve) {
            // ── NODO 2: ¿El cajero es reincidente? ──────────────
            $esReincidente = $this->isReincident($shift->user_id, $shift->id);

            if ($esReincidente) {
                return $this->buildResult(
                    classification: self::RESULT_CRITICA,
                    difference:     $difference,
                    shift:          $shift,
                    path:           [
                        'V1 ¿Hay diferencia?'         => "Sí (Bs {$absDiff})",
                        'V1 ¿Diferencia leve?'        => 'Sí (≤ Bs ' . self::THRESHOLD_LEVE . ')',
                        'V2 ¿Cajero reincidente?'     => 'Sí → Inconsistencias en ' . self::REINCIDENCE_MIN . '+ de los últimos ' . self::REINCIDENCE_WINDOW . ' turnos',
                    ],
                );
            }

            return $this->buildResult(
                classification: self::RESULT_LEVE,
                difference:     $difference,
                shift:          $shift,
                path:           [
                    'V1 ¿Hay diferencia?'         => "Sí (Bs {$absDiff})",
                    'V1 ¿Diferencia leve?'        => 'Sí (≤ Bs ' . self::THRESHOLD_LEVE . ')',
                    'V2 ¿Cajero reincidente?'     => 'No → Sin patrón de irregularidad',
                ],
            );
        }

        // La diferencia es alta (> THRESHOLD_LEVE)
        $esFaltante = $difference < 0;

        // ── NODO 3a: Faltante ────────────────────────────────────
        if ($esFaltante) {
            $qrExplica = $this->qrExplainsDifference($shift->id, $absDiff);

            if ($qrExplica) {
                return $this->buildResult(
                    classification: self::RESULT_LEVE,
                    difference:     $difference,
                    shift:          $shift,
                    path:           [
                        'V1 ¿Hay diferencia?'          => "Sí (Bs {$absDiff})",
                        'V1 ¿Diferencia leve?'         => 'No (> Bs ' . self::THRESHOLD_LEVE . ')',
                        'V3 ¿Es faltante?'             => 'Sí',
                        'V4 ¿Ventas QR explican el faltante?' => 'Sí → Diferencia atribuible a pagos QR pendientes de conciliación',
                    ],
                );
            }

            return $this->buildResult(
                classification: self::RESULT_CRITICA,
                difference:     $difference,
                shift:          $shift,
                path:           [
                    'V1 ¿Hay diferencia?'          => "Sí (Bs {$absDiff})",
                    'V1 ¿Diferencia leve?'         => 'No (> Bs ' . self::THRESHOLD_LEVE . ')',
                    'V3 ¿Es faltante?'             => 'Sí',
                    'V4 ¿Ventas QR explican el faltante?' => 'No → Faltante no justificado',
                ],
            );
        }

        // ── NODO 3b: Sobrante ────────────────────────────────────
        $tieneEgresos = $this->hasExpenses($shift->id);

        if ($tieneEgresos) {
            return $this->buildResult(
                classification: self::RESULT_LEVE,
                difference:     $difference,
                shift:          $shift,
                path:           [
                    'V1 ¿Hay diferencia?'       => "Sí (Bs {$absDiff})",
                    'V1 ¿Diferencia leve?'      => 'No (> Bs ' . self::THRESHOLD_LEVE . ')',
                    'V3 ¿Es faltante?'          => 'No (sobrante)',
                    'V5 ¿Registró egresos?'     => 'Sí → Sobrante posiblemente explicado por egresos registrados',
                ],
            );
        }

        return $this->buildResult(
            classification: self::RESULT_CRITICA,
            difference:     $difference,
            shift:          $shift,
            path:           [
                'V1 ¿Hay diferencia?'       => "Sí (Bs {$absDiff})",
                'V1 ¿Diferencia leve?'      => 'No (> Bs ' . self::THRESHOLD_LEVE . ')',
                'V3 ¿Es faltante?'          => 'No (sobrante)',
                'V5 ¿Registró egresos?'     => 'No → Sobrante sin justificación: posibles egresos no registrados',
            ],
        );
    }

    // ─────────────────────────────────────────────────────────────
    //  VARIABLES DEL ÁRBOL — métodos de consulta a la BD
    // ─────────────────────────────────────────────────────────────

    /**
     * V2 — ¿El cajero es reincidente?
     *
     * Consulta los últimos N turnos CERRADOS del cajero
     * (excluyendo el turno actual) y verifica cuántos
     * tuvieron inconsistencia leve o crítica.
     */
    private function isReincident(int $userId, int $currentShiftId): bool
    {
        $recentShifts = Shift::where('user_id', $userId)
            ->where('id', '!=', $currentShiftId)
            ->where('status', 'CLOSED')
            ->whereNotNull('inconsistency_class')
            ->orderBy('end_time', 'desc')
            ->limit(self::REINCIDENCE_WINDOW)
            ->pluck('inconsistency_class');

        $inconsistentCount = $recentShifts->filter(
            fn($class) => in_array($class, [self::RESULT_LEVE, self::RESULT_CRITICA])
        )->count();

        return $inconsistentCount >= self::REINCIDENCE_MIN;
    }

    /**
     * V4 — ¿Las ventas QR explican el faltante?
     *
     * Calcula el porcentaje de ventas QR sobre el total
     * del turno. Si supera QR_THRESHOLD_PERCENT se considera
     * que el faltante puede ser atribuible a pagos digitales
     * pendientes de conciliación manual.
     */
    private function qrExplainsDifference(int $shiftId, float $absDiff): bool
    {
        $totals = Sale::where('shift_id', $shiftId)
            ->where('status', 'COMPLETED')
            ->selectRaw("
                SUM(total_amount) as total_ventas,
                SUM(CASE WHEN payment_method = 'QR' THEN total_amount ELSE 0 END) as total_qr
            ")
            ->first();

        if (!$totals || $totals->total_ventas == 0) {
            return false;
        }

        $qrPercent = ($totals->total_qr / $totals->total_ventas) * 100;

        return $qrPercent >= self::QR_THRESHOLD_PERCENT;
    }

    /**
     * V5 — ¿El turno tiene egresos registrados?
     *
     * Verifica si existe al menos un movimiento de tipo
     * EXPENSE en el turno. Un sobrante en un turno con
     * egresos registrados es menos preocupante que uno
     * sin ningún egreso.
     */
    private function hasExpenses(int $shiftId): bool
    {
        return CashMovement::where('shift_id', $shiftId)
            ->where('movement_type', 'EXPENSE')
            ->exists();
    }

    // ─────────────────────────────────────────────────────────────
    //  CONSTRUCCIÓN DEL RESULTADO
    // ─────────────────────────────────────────────────────────────

    /**
     * Construye el array de resultado completo con todos los datos
     * necesarios para la vista, el reporte y el informe académico.
     */
    private function buildResult(
        string $classification,
        float  $difference,
        Shift  $shift,
        array  $path
    ): array {
        $absDiff    = abs($difference);
        $isFaltante = $difference < 0;
        $tipo       = $isFaltante ? 'Faltante' : ($difference > 0 ? 'Sobrante' : '—');

        return [
            // ── Clasificación final ──────────────────────────────
            'classification' => $classification,

            // ── Datos numéricos ──────────────────────────────────
            'difference'     => $difference,
            'abs_difference' => $absDiff,
            'type'           => $tipo,

            // ── Datos para la vista ──────────────────────────────
            'label'          => $this->getLabel($classification),
            'color'          => $this->getColor($classification),
            'icon'           => $this->getIcon($classification),
            'description'    => $this->getDescription($classification, $difference),
            'recommendation' => $this->getRecommendation($classification, $isFaltante),

            // ── Recorrido del árbol (informe académico) ──────────
            'decision_path'  => $path,

            // ── Contexto del turno evaluado ──────────────────────
            'shift_context'  => [
                'shift_id'    => $shift->id,
                'cajero_id'   => $shift->user_id,
                'evaluated_at' => now()->toDateTimeString(),
            ],

            // ── Umbrales usados (transparencia del modelo) ───────
            'thresholds' => [
                'ok'                => self::THRESHOLD_OK,
                'leve'              => self::THRESHOLD_LEVE,
                'reincidence_window'=> self::REINCIDENCE_WINDOW,
                'reincidence_min'   => self::REINCIDENCE_MIN,
                'qr_percent'        => self::QR_THRESHOLD_PERCENT,
            ],
        ];
    }

    // ─────────────────────────────────────────────────────────────
    //  HELPERS DE PRESENTACIÓN
    // ─────────────────────────────────────────────────────────────

    private function getLabel(string $classification): string
    {
        return match ($classification) {
            self::RESULT_OK      => 'Sin inconsistencia',
            self::RESULT_LEVE    => 'Inconsistencia leve',
            self::RESULT_CRITICA => 'Inconsistencia crítica',
        };
    }

    private function getColor(string $classification): string
    {
        return match ($classification) {
            self::RESULT_OK      => 'success',  // verde
            self::RESULT_LEVE    => 'warning',  // amarillo
            self::RESULT_CRITICA => 'danger',   // rojo
        };
    }

    private function getIcon(string $classification): string
    {
        return match ($classification) {
            self::RESULT_OK      => '✓',
            self::RESULT_LEVE    => '⚠',
            self::RESULT_CRITICA => '✕',
        };
    }

    private function getDescription(string $classification, float $difference): string
    {
        $absDiff = number_format(abs($difference), 2);
        $tipo    = $difference < 0 ? 'faltante' : 'sobrante';

        return match ($classification) {
            self::RESULT_OK =>
                'El efectivo declarado coincide exactamente con el esperado. El turno cierra sin diferencias.',

            self::RESULT_LEVE =>
                "Se detectó un {$tipo} de Bs {$absDiff}. " .
                'El análisis multivariable determinó que la diferencia tiene una justificación contextual aceptable.',

            self::RESULT_CRITICA =>
                "Se detectó un {$tipo} de Bs {$absDiff}. " .
                'El análisis multivariable no encontró una justificación suficiente para esta diferencia. Se requiere revisión inmediata.',
        };
    }

    private function getRecommendation(string $classification, bool $isFaltante): string
    {
        return match ($classification) {
            self::RESULT_OK =>
                'No se requiere acción. Archivar el cierre como conforme.',

            self::RESULT_LEVE => $isFaltante
                ? 'Registrar la observación. Verificar si hubo pagos QR pendientes de acreditación. El cajero puede continuar normalmente.'
                : 'Registrar el sobrante. Verificar egresos del turno con el cajero al inicio del siguiente turno.',

            self::RESULT_CRITICA => $isFaltante
                ? 'ACCIÓN INMEDIATA: Notificar al administrador. Revisar el detalle de ventas, egresos y pagos QR del turno. Considerar supervisión al cajero.'
                : 'REVISAR URGENTE: Sobrante alto sin egresos registrados puede indicar ventas no registradas. Auditar el historial completo del turno.',
        };
    }
}
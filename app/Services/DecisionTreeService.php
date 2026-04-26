<?php

namespace App\Services;

/**
 * ══════════════════════════════════════════════════════════════════
 *  APORTE ACADÉMICO — Árbol de Decisiones para Arqueo de Turno
 * ══════════════════════════════════════════════════════════════════
 *
 *  Este servicio implementa un árbol de decisiones que clasifica
 *  automáticamente el resultado del arqueo de cierre de turno en
 *  tres categorías de inconsistencia, siguiendo una lógica
 *  jerárquica de nodos y condiciones booleanas.
 *
 *  Estructura del árbol:
 *
 *  [RAÍZ] ¿Hay diferencia?
 *    ├── NO  → ✅ SIN INCONSISTENCIA
 *    └── SÍ  → ¿La diferencia es negativa (faltante)?
 *                ├── SÍ  → ¿El faltante supera el umbral crítico?
 *                │           ├── SÍ  → 🔴 CRÍTICA (faltante grave)
 *                │           └── NO  → 🟡 LEVE   (faltante menor)
 *                └── NO  → ¿El sobrante supera el umbral crítico?
 *                            ├── SÍ  → 🔴 CRÍTICA (sobrante grave)
 *                            └── NO  → 🟡 LEVE   (sobrante menor)
 *
 *  Los umbrales son configurables en las constantes de la clase.
 */
class DecisionTreeService
{
    // ── Umbrales de clasificación ────────────────────────────────
    // Diferencia máxima permitida para considerar el turno cuadrado (Bs)
    // Cualquier diferencia dentro de este margen = SIN INCONSISTENCIA
    const THRESHOLD_OK = 0;

    // Diferencia (en valor absoluto) hasta la cual se considera leve (Bs)
    const THRESHOLD_LEVE = 20.00;

    // Por encima de THRESHOLD_LEVE → inconsistencia CRÍTICA

    // ── Constantes de resultado ──────────────────────────────────
    const RESULT_OK       = 'SIN_INCONSISTENCIA';
    const RESULT_LEVE     = 'INCONSISTENCIA_LEVE';
    const RESULT_CRITICA  = 'INCONSISTENCIA_CRITICA';

    /**
     * Punto de entrada del árbol de decisiones.
     *
     * @param  float  $expectedCash   Efectivo que DEBERÍA haber (calculado)
     * @param  float  $reportedCash   Efectivo que el cajero DECLARÓ tener
     * @return array  Resultado con clasificación, descripción y recomendación
     */
    public function evaluate(float $expectedCash, float $reportedCash): array
    {
        // Diferencia = declarado - esperado
        // Negativo = faltante (el cajero tiene menos de lo que debería)
        // Positivo = sobrante (el cajero tiene más de lo que debería)
        $difference = $reportedCash - $expectedCash;
        $absDiff    = abs($difference);

        // ── NODO RAÍZ: ¿Existe alguna diferencia? ───────────────
        if ($absDiff == self::THRESHOLD_OK) {
            return $this->buildResult(
                classification: self::RESULT_OK,
                difference:     $difference,
                path:           ['¿Hay diferencia?' => 'No'],
            );
        }

        // ── NODO 2: ¿La diferencia es negativa (faltante)? ──────
        $isFaltante = $difference < 0;

        // ── NODO 3a: Faltante — ¿supera el umbral crítico? ──────
        if ($isFaltante) {
            if ($absDiff > self::THRESHOLD_LEVE) {
                return $this->buildResult(
                    classification: self::RESULT_CRITICA,
                    difference:     $difference,
                    path: [
                        '¿Hay diferencia?'          => 'Sí',
                        '¿Es faltante?'             => 'Sí',
                        '¿Supera umbral crítico?'   => "Sí (Bs {$absDiff} > Bs " . self::THRESHOLD_LEVE . ')',
                    ],
                );
            } else {
                return $this->buildResult(
                    classification: self::RESULT_LEVE,
                    difference:     $difference,
                    path: [
                        '¿Hay diferencia?'          => 'Sí',
                        '¿Es faltante?'             => 'Sí',
                        '¿Supera umbral crítico?'   => "No (Bs {$absDiff} ≤ Bs " . self::THRESHOLD_LEVE . ')',
                    ],
                );
            }
        }

        // ── NODO 3b: Sobrante — ¿supera el umbral crítico? ──────
        if ($absDiff > self::THRESHOLD_LEVE) {
            return $this->buildResult(
                classification: self::RESULT_CRITICA,
                difference:     $difference,
                path: [
                    '¿Hay diferencia?'          => 'Sí',
                    '¿Es faltante?'             => 'No (sobrante)',
                    '¿Supera umbral crítico?'   => "Sí (Bs {$absDiff} > Bs " . self::THRESHOLD_LEVE . ')',
                ],
            );
        }

        return $this->buildResult(
            classification: self::RESULT_LEVE,
            difference:     $difference,
            path: [
                '¿Hay diferencia?'          => 'Sí',
                '¿Es faltante?'             => 'No (sobrante)',
                '¿Supera umbral crítico?'   => "No (Bs {$absDiff} ≤ Bs " . self::THRESHOLD_LEVE . ')',
            ],
        );
    }

    /**
     * Construye el array de resultado con toda la información
     * necesaria para el reporte y el informe académico.
     */
    private function buildResult(string $classification, float $difference, array $path): array
    {
        $absDiff    = abs($difference);
        $isFaltante = $difference < 0;
        $tipo       = $isFaltante ? 'Faltante' : ($difference > 0 ? 'Sobrante' : '—');

        return [
            // Clasificación final
            'classification' => $classification,

            // Datos numéricos para el reporte
            'difference'     => $difference,
            'abs_difference' => $absDiff,
            'type'           => $tipo,         // "Faltante" | "Sobrante" | "—"

            // Datos para mostrar en la vista
            'label'          => $this->getLabel($classification),
            'color'          => $this->getColor($classification),
            'icon'           => $this->getIcon($classification),
            'description'    => $this->getDescription($classification, $difference),
            'recommendation' => $this->getRecommendation($classification, $isFaltante),

            // Recorrido del árbol (útil para el informe académico)
            'decision_path'  => $path,

            // Umbrales usados (para transparencia del modelo)
            'thresholds' => [
                'ok'     => self::THRESHOLD_OK,
                'leve'   => self::THRESHOLD_LEVE,
            ],
        ];
    }

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
            self::RESULT_OK      => 'El efectivo declarado coincide exactamente con el efectivo esperado. El turno cierra sin diferencias.',
            self::RESULT_LEVE    => "Se detectó un {$tipo} de Bs {$absDiff}. La diferencia es menor al umbral crítico de Bs " . self::THRESHOLD_LEVE . " y puede deberse a redondeos o errores menores.",
            self::RESULT_CRITICA => "Se detectó un {$tipo} significativo de Bs {$absDiff}. La diferencia supera el umbral de Bs " . self::THRESHOLD_LEVE . " y requiere revisión inmediata.",
        };
    }

    private function getRecommendation(string $classification, bool $isFaltante): string
    {
        return match ($classification) {
            self::RESULT_OK => 'No se requiere acción. Archivar el cierre como conforme.',

            self::RESULT_LEVE => $isFaltante
                ? 'Registrar la observación en el sistema. Verificar si hubo cambio que no se registró. El cajero puede continuar normalmente.'
                : 'Registrar el sobrante. Posible error de cambio o venta no registrada. Verificar con el cajero al inicio del siguiente turno.',

            self::RESULT_CRITICA => $isFaltante
                ? 'ACCIÓN INMEDIATA: Notificar al administrador. Revisar el detalle de ventas y egresos del turno. Cruzar con el listado de productos vendidos. Considerar suspender al cajero hasta aclarar.'
                : 'REVISAR URGENTE: Sobrante alto puede indicar ventas no registradas en el sistema. Auditar el historial completo del turno y comparar con el stock descontado.',
        };
    }
}
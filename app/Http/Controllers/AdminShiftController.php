<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Product;
use App\Models\Shift;
use App\Models\ShiftStock;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AdminShiftController extends Controller
{
    // ── Listado de turnos ────────────────────────────────────────

    public function index(): View
    {
        $shifts = Shift::with(['user', 'openedBy'])
            ->latest('start_time')
            ->paginate(20);

        return view('admin.shifts.index', compact('shifts'));
    }

    public function show(Shift $shift): View
    {
        $shift->load([
            'user',
            'openedBy',
            'sales.details.product',
            'sales.voidedBy',
            'stock.product',
            'cashMovements',
        ]);

        return view('admin.shifts.show', compact('shift'));
    }

    // ── Apertura de turno por el Admin ───────────────────────────

    /**
     * Formulario para que el admin abra un turno a un cajero.
     * El admin selecciona: cajero, hora programada, tolerancia,
     * efectivo inicial y stock inicial.
     */
    public function showOpenForCajero(): View
    {
        $cajeros = User::whereHas('role', fn($q) => $q->where('slug', 'cajero'))
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $products = Product::where('branch_id', $this->authUser()->branch_id)
            ->active()
            ->orderBy('name')
            ->get();

        // Cajeros que ya tienen turno abierto (para deshabilitar en el form)
        $cajerosConTurnoAbierto = Shift::where('status', 'OPEN')
            ->pluck('user_id')
            ->toArray();

        return view('admin.shifts.open-for-cajero', compact(
            'cajeros',
            'products',
            'cajerosConTurnoAbierto'
        ));
    }

    /**
     * El admin abre el turno para un cajero específico.
     *
     * Campos esperados del formulario:
     *   - cajero_id         (int, requerido)
     *   - initial_cash      (decimal, requerido)
     *   - scheduled_start   (HH:MM, requerido)  — hora programada
     *   - tolerance_minutes (int, default 0)     — minutos de tolerancia
     *   - stock             (array product_id => quantity)
     *   - notes             (string, opcional)
     */
    public function openForCajero(Request $request): RedirectResponse
    {
        $request->validate([
            'cajero_id'         => ['required', 'exists:users,id'],
            'initial_cash'      => ['required', 'numeric', 'min:0'],
            'scheduled_start'   => ['required', 'date_format:H:i'],
            'tolerance_minutes' => ['nullable', 'integer', 'min:0', 'max:120'],
            'stock'             => ['required', 'array', 'min:1'],
            'stock.*'           => ['required', 'integer', 'min:0'],
            'notes'             => ['nullable', 'string', 'max:500'],
        ], [
            'cajero_id.required'       => 'Debes seleccionar un cajero.',
            'cajero_id.exists'         => 'El cajero seleccionado no existe.',
            'initial_cash.required'    => 'El efectivo inicial es obligatorio.',
            'initial_cash.min'         => 'El efectivo inicial no puede ser negativo.',
            'scheduled_start.required' => 'La hora programada es obligatoria.',
            'scheduled_start.date_format' => 'El formato de hora debe ser HH:MM.',
            'stock.required'           => 'Debes ingresar el stock inicial.',
        ]);

        $admin  = $this->authUser();
        $cajero = User::findOrFail($request->cajero_id);

        // Verificar que el cajero no tenga ya un turno abierto
        if ($cajero->hasOpenShift()) {
            return back()->withErrors([
                'cajero_id' => "El cajero {$cajero->name} ya tiene un turno abierto."
            ]);
        }

        // Construir el timestamp de la hora programada (hoy + hora ingresada)
        $scheduledStart = now()->setTimeFromTimeString($request->scheduled_start);

        DB::transaction(function () use ($request, $admin, $cajero, $scheduledStart) {
            $shift = Shift::create([
                'user_id'           => $cajero->id,
                'opened_by'         => $admin->id,
                'status'            => 'OPEN',
                'start_time'        => now(),        // momento real de apertura
                'scheduled_start'   => $scheduledStart,
                'tolerance_minutes' => $request->tolerance_minutes ?? 0,
                'attendance_status' => 'PENDIENTE',  // el cajero aún no ha ingresado
                'initial_cash'      => $request->initial_cash,
                'notes'             => $request->notes,
            ]);

            // Registrar stock inicial
            foreach ($request->stock as $productId => $quantity) {
                if ($quantity > 0) {
                    ShiftStock::create([
                        'shift_id'         => $shift->id,
                        'product_id'       => $productId,
                        'initial_quantity' => $quantity,
                        'sold_quantity'    => 0,
                    ]);
                }
            }

            AuditLog::create([
                'user_id'    => $admin->id,
                'action'     => 'open_shift_for_cajero',
                'model'      => 'Shift',
                'model_id'   => $shift->id,
                'new_values' => [
                    'cajero_id'         => $cajero->id,
                    'cajero_name'       => $cajero->name,
                    'initial_cash'      => $request->initial_cash,
                    'scheduled_start'   => $scheduledStart->format('H:i'),
                    'tolerance_minutes' => $request->tolerance_minutes ?? 0,
                ],
                'ip_address' => request()->ip(),
            ]);
        });

        return redirect()
            ->route('admin.shifts.index')
            ->with('success', "Turno abierto correctamente para {$cajero->name}. Hora programada: {$request->scheduled_start}.");
    }

    // ── Turnos activos (vista rápida para el admin) ──────────────

    /**
     * Lista solo los turnos actualmente abiertos con su estado
     * de asistencia. Útil para que el admin monitoree quién
     * ya ingresó y quién está pendiente o llegó tarde.
     */
    public function activeShifts(): View
    {
        $activeShifts = Shift::with(['user', 'openedBy'])
            ->where('status', 'OPEN')
            ->orderBy('scheduled_start')
            ->get();

        return view('admin.shifts.active', compact('activeShifts'));
    }
}
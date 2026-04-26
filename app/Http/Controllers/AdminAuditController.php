<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminAuditController extends Controller
{
    // Etiquetas legibles para cada acción registrada en el sistema
    const ACTION_LABELS = [
        'login'       => 'Inicio de sesión',
        'logout'      => 'Cierre de sesión',
        'open_shift'  => 'Apertura de turno',
        'close_shift' => 'Cierre de turno',
        'void_sale'   => 'Anulación de venta',
    ];

    public function index(Request $request): View
    {
        // ── Filtros disponibles ──────────────────────────────────
        $query = AuditLog::with('user')
            ->orderByDesc('created_at');

        // Filtro por usuario
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Filtro por tipo de acción
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        // Filtro por fecha desde
        if ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->from);
        }

        // Filtro por fecha hasta
        if ($request->filled('to')) {
            $query->whereDate('created_at', '<=', $request->to);
        }

        $logs = $query->paginate(30);

        // Lista de usuarios para el select de filtro
        $users = User::orderBy('name')->get(['id', 'name']);

        // Contadores por tipo de acción
        $countQuery = clone $query->getQuery();
        $actionCounts = AuditLog::query()
            ->when($request->filled('user_id'),  fn($q) => $q->where('user_id', $request->user_id))
            ->when($request->filled('action'),   fn($q) => $q->where('action', $request->action))
            ->when($request->filled('from'),     fn($q) => $q->whereDate('created_at', '>=', $request->from))
            ->when($request->filled('to'),       fn($q) => $q->whereDate('created_at', '<=', $request->to))
            ->selectRaw('action, COUNT(*) as total')
            ->groupBy('action')
            ->pluck('total', 'action');

        return view('admin.audit.index', compact(
            'logs',
            'users',
            'actionCounts',
        ));
    }
}
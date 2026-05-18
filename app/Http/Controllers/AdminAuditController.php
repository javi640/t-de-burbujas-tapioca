<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminAuditController extends Controller
{
    const ACTION_LABELS = [
        'login'       => 'Inicio de sesión',
        'logout'      => 'Cierre de sesión',
        'open_shift'  => 'Apertura de turno',
        'close_shift' => 'Cierre de turno',
        'void_sale'   => 'Anulación de venta',
    ];

    public function index(Request $request): View
    {
        $baseQuery = AuditLog::query()
            ->when($request->filled('user_id'), fn($q) => $q->where('user_id', $request->user_id))
            ->when($request->filled('action'),  fn($q) => $q->where('action', $request->action))
            ->when($request->filled('from'),    fn($q) => $q->whereDate('created_at', '>=', $request->from))
            ->when($request->filled('to'),      fn($q) => $q->whereDate('created_at', '<=', $request->to));

        $logs = (clone $baseQuery)
            ->with('user')
            ->orderByDesc('created_at')
            ->paginate(30);

        $actionCounts = (clone $baseQuery)
            ->selectRaw('action, COUNT(*) as total')
            ->groupBy('action')
            ->pluck('total', 'action');

        $users = User::orderBy('name')->get(['id', 'name']);

        return view('admin.audit.index', compact(
            'logs',
            'users',
            'actionCounts',
        ));
    }
}
<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\CashMovementController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminSaleController;
use App\Http\Controllers\AdminShiftController;
use App\Http\Controllers\AdminReportController;
use App\Http\Controllers\AdminAuditController;
use Illuminate\Support\Facades\Route;

// ── Ruta raíz ───────────────────────────────────────────────────
Route::get('/', fn() => redirect()->route('login'));

// ── Auth ─────────────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login',  [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/forgot-password',        fn() => view('auth.forgot-password'))->name('password.request');
    Route::post('/forgot-password',       [AuthController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password/{token}', [AuthController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password',        [AuthController::class, 'resetPassword'])->name('password.update');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware(['auth', 'prevent-cache'])->name('logout');

// ── Cajero ────────────────────────────────────────────────────────
// NOTA: El cajero YA NO puede abrir turno. El turno lo abre el admin.
//       Al hacer login, si no tiene turno asignado → pantalla de espera.
Route::middleware(['auth', 'role:cajero', 'prevent-cache'])->prefix('cajero')->name('cajero.')->group(function () {
    // Pantalla de espera (sin turno asignado)
    Route::get('/turno/espera',          [ShiftController::class, 'waiting'])->name('shift.waiting');

    // Turno activo
    Route::get('/turno/actual',          [ShiftController::class, 'current'])->name('shift.current');
    Route::post('/turno/cerrar',         [ShiftController::class, 'close'])->name('shift.close');
    Route::get('/turno/{shift}/resumen', [ShiftController::class, 'summary'])->name('shift.summary');

    // Ventas y movimientos
    Route::get('/venta/nueva',           [SaleController::class, 'create'])->name('sales.create');
    Route::post('/venta',                [SaleController::class, 'store'])->name('sales.store');
    Route::post('/movimiento',           [CashMovementController::class, 'store'])->name('movements.store');
});

// ── Admin ─────────────────────────────────────────────────────────
Route::middleware(['auth', 'role:admin', 'prevent-cache'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Usuarios
    Route::get('/usuarios',                 [UserController::class, 'index'])->name('users.index');
    Route::get('/usuarios/crear',           [UserController::class, 'create'])->name('users.create');
    Route::post('/usuarios',                [UserController::class, 'store'])->name('users.store');
    Route::get('/usuarios/{user}/editar',   [UserController::class, 'edit'])->name('users.edit');
    Route::put('/usuarios/{user}',          [UserController::class, 'update'])->name('users.update');
    Route::patch('/usuarios/{user}/toggle', [UserController::class, 'toggle'])->name('users.toggle');

    // Ventas
    Route::get('/ventas',               [AdminSaleController::class, 'index'])->name('sales.index');
    Route::post('/venta/{sale}/anular', [SaleController::class, 'void'])->name('sales.void');

    // Turnos — el admin ahora puede ABRIR turnos para cajeros
    Route::get('/turnos',                    [AdminShiftController::class, 'index'])->name('shifts.index');
    Route::get('/turnos/activos',            [AdminShiftController::class, 'activeShifts'])->name('shifts.active');
    Route::get('/turno/abrir',               [AdminShiftController::class, 'showOpenForCajero'])->name('shifts.open');
    Route::post('/turno/abrir',              [AdminShiftController::class, 'openForCajero'])->name('shifts.store');
    Route::get('/turno/{shift}',             [AdminShiftController::class, 'show'])->name('shifts.show');

    // Reportes
    Route::get('/reportes/cierre-diario', [AdminReportController::class, 'dailyReport'])->name('reports.daily');
    Route::get('/reportes/conciliacion-stock', [AdminReportController::class, 'stockReconciliation'])->name('reports.stock');

    // Auditoría
    Route::get('/auditoria', [AdminAuditController::class, 'index'])->name('audit.index');
});
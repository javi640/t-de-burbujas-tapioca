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

// ── Ruta raíz ───────────────────────────────────────────────
Route::get('/', fn() => redirect()->route('login'));

// ── Auth ─────────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login',  [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/forgot-password',         fn() => view('auth.forgot-password'))->name('password.request');
    Route::post('/forgot-password',        [AuthController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password/{token}',  [AuthController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password',         [AuthController::class, 'resetPassword'])->name('password.update');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// ── Cajero ────────────────────────────────────────────────────
Route::middleware(['auth', 'role:cajero', 'no-cache'])->prefix('cajero')->name('cajero.')->group(function () {
    Route::get('/turno/abrir',           [ShiftController::class, 'showOpen'])->name('shift.open');
    Route::post('/turno/abrir',          [ShiftController::class, 'open']);
    Route::get('/turno/actual',          [ShiftController::class, 'current'])->name('shift.current');
    Route::post('/turno/cerrar',         [ShiftController::class, 'close'])->name('shift.close');
    Route::get('/turno/{shift}/resumen', [ShiftController::class, 'summary'])->name('shift.summary');
    Route::get('/venta/nueva',           [SaleController::class, 'create'])->name('sales.create');
    Route::post('/venta',                [SaleController::class, 'store'])->name('sales.store');
    Route::post('/movimiento',           [CashMovementController::class, 'store'])->name('movements.store');
});

// ── Admin ─────────────────────────────────────────────────────
Route::middleware(['auth', 'role:admin', 'no-cache'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Usuarios (PJ-7)
    Route::get('/usuarios',                  [UserController::class, 'index'])->name('users.index');
    Route::get('/usuarios/crear',            [UserController::class, 'create'])->name('users.create');
    Route::post('/usuarios',                 [UserController::class, 'store'])->name('users.store');
    Route::get('/usuarios/{user}/editar',    [UserController::class, 'edit'])->name('users.edit');
    Route::put('/usuarios/{user}',           [UserController::class, 'update'])->name('users.update');
    Route::patch('/usuarios/{user}/toggle',  [UserController::class, 'toggle'])->name('users.toggle');

    // Ventas (PJ-20)
    Route::get('/ventas',               [AdminSaleController::class, 'index'])->name('sales.index');
    Route::post('/venta/{sale}/anular', [SaleController::class, 'void'])->name('sales.void');

    // Turnos
    Route::get('/turnos',       [AdminShiftController::class, 'index'])->name('shifts.index');
    Route::get('/turno/{shift}',[AdminShiftController::class, 'show'])->name('shifts.show');

    // Reportes (PJ-17)
    Route::get('/reportes/cierre-diario', [AdminReportController::class, 'dailyReport'])->name('reports.daily');

    // Auditoría (HU16)
    Route::get('/auditoria', [AdminAuditController::class, 'index'])->name('audit.index');
});
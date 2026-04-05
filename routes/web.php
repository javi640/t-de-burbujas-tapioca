<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\CashMovementController;
use Illuminate\Support\Facades\Route;
 
Route::get('/', function () {
    return redirect()->route('login');
});

// ── Autenticación ────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});
 
Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');
 
// ── Cajero ───────────────────────────────────────────────────
Route::middleware(['auth', 'role:cajero'])->prefix('cajero')->name('cajero.')->group(function () {
 
    // Turnos
    Route::get('/turno/abrir', [ShiftController::class, 'showOpen'])->name('shift.open');
    Route::post('/turno/abrir', [ShiftController::class, 'open']);
    Route::get('/turno/actual', [ShiftController::class, 'current'])->name('shift.current');
    Route::post('/turno/cerrar', [ShiftController::class, 'close'])->name('shift.close');
    Route::get('/turno/{shift}/resumen', [ShiftController::class, 'summary'])->name('shift.summary');
 
    // Ventas
    Route::get('/venta/nueva', [SaleController::class, 'create'])->name('sales.create');
    Route::post('/venta', [SaleController::class, 'store'])->name('sales.store');
 
    // Movimientos de caja
    Route::post('/movimiento', [CashMovementController::class, 'store'])->name('movements.store');
});
 
// ── Administrador ────────────────────────────────────────────
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
 
    Route::get('/dashboard', fn() => view('admin.dashboard'))->name('dashboard');
 
    // Anular ventas
    Route::post('/venta/{sale}/anular', [SaleController::class, 'void'])->name('sales.void');
 
    // Turnos (vista admin — todos los turnos)
    Route::get('/turnos', [ShiftController::class, 'index'])->name('shifts.index');
    Route::get('/turno/{shift}', [ShiftController::class, 'summary'])->name('shifts.show');
});


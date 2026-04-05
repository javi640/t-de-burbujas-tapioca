<?php

namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
 
class Shift extends Model
{
    protected $fillable = [
        'user_id', 'status', 'start_time', 'end_time',
        'initial_cash', 'reported_cash', 'cash_difference', 'notes',
    ];
 
    protected $casts = [
        'start_time'      => 'datetime',
        'end_time'        => 'datetime',
        'initial_cash'    => 'decimal:2',
        'reported_cash'   => 'decimal:2',
        'cash_difference' => 'decimal:2',
    ];
 
    // ── Relaciones ──────────────────────────────────────────
 
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
 
    public function stock(): HasMany
    {
        return $this->hasMany(ShiftStock::class);
    }
 
    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class);
    }
 
    public function cashMovements(): HasMany
    {
        return $this->hasMany(CashMovement::class);
    }
 
    // ── Scopes ──────────────────────────────────────────────
 
    public function scopeOpen($query)
    {
        return $query->where('status', 'OPEN');
    }
 
    public function scopeClosed($query)
    {
        return $query->where('status', 'CLOSED');
    }
 
    // ── Helpers de negocio ──────────────────────────────────
 
    public function isOpen(): bool
    {
        return $this->status === 'OPEN';
    }
 
    /**
     * Calcula el total esperado en efectivo al cierre:
     * initial_cash + ventas CASH + ingresos extra - egresos
     */
    public function expectedCash(): float
    {
        $salesCash = $this->sales()
            ->where('payment_method', 'CASH')
            ->where('status', 'COMPLETED')
            ->sum('total_amount');
 
        $income = $this->cashMovements()
            ->where('movement_type', 'INCOME')
            ->sum('amount');
 
        $expense = $this->cashMovements()
            ->where('movement_type', 'EXPENSE')
            ->sum('amount');
 
        return (float) ($this->initial_cash + $salesCash + $income - $expense);
    }
 
    /**
     * Total recaudado por QR en este turno
     */
    public function totalQr(): float
    {
        return (float) $this->sales()
            ->where('payment_method', 'QR')
            ->where('status', 'COMPLETED')
            ->sum('total_amount');
    }
}
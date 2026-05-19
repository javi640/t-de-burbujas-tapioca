<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Shift extends Model
{
    protected $fillable = [
        'user_id',
        'opened_by',
        'status',
        'start_time',
        'end_time',
        'scheduled_start',
        'tolerance_minutes',
        'cajero_login_time',
        'attendance_status',
        'initial_cash',
        'reported_cash',
        'cash_difference',
        'inconsistency_class',
        'notes',
    ];

    protected $casts = [
        'start_time'        => 'datetime',
        'end_time'          => 'datetime',
        'scheduled_start'   => 'datetime',
        'cajero_login_time' => 'datetime',
        'initial_cash'      => 'decimal:2',
        'reported_cash'     => 'decimal:2',
        'cash_difference'   => 'decimal:2',
    ];

    protected function serializeDate(\DateTimeInterface $date): string
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function openedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'opened_by');
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

    public function scopeOpen($query)
    {
        return $query->where('status', 'OPEN');
    }

    public function scopeClosed($query)
    {
        return $query->where('status', 'CLOSED');
    }

    public function expectedCash(): float
    {
        $salesCash = \App\Models\Sale::where('shift_id', $this->id)
            ->where('payment_method', 'CASH')
            ->where('status', 'COMPLETED')
            ->sum('total_amount');

        $movements = \App\Models\CashMovement::where('shift_id', $this->id)
            ->selectRaw("
                SUM(CASE WHEN movement_type = 'INCOME'  THEN amount ELSE 0 END) as income,
                SUM(CASE WHEN movement_type = 'EXPENSE' THEN amount ELSE 0 END) as expense
            ")
            ->first();

        $income  = (float) ($movements->income  ?? 0);
        $expense = (float) ($movements->expense ?? 0);

        return (float) ($this->initial_cash + $salesCash + $income - $expense);
    }

    public function totalQr(): float
    {
        return (float) \App\Models\Sale::where('shift_id', $this->id)
            ->where('payment_method', 'QR')
            ->where('status', 'COMPLETED')
            ->sum('total_amount');
    }

    public function attendanceDeadline(): ?\Carbon\Carbon
    {
        if (!$this->scheduled_start) return null;

        return $this->scheduled_start->copy()
            ->addMinutes($this->tolerance_minutes);
    }

    public function isLate(): bool
    {
        if (!$this->cajero_login_time || !$this->scheduled_start) {
            return false;
        }

        return $this->cajero_login_time->gt($this->attendanceDeadline());
    }

    public function minutesLate(): int
    {
        if (!$this->isLate()) return 0;

        return (int) $this->cajero_login_time
            ->diffInMinutes($this->attendanceDeadline());
    }

    public function registerCajeroLogin(\Carbon\Carbon $loginTime): void
    {
        $status = 'PUNTUAL';

        if ($this->scheduled_start) {
            $deadline = $this->attendanceDeadline();
            $status   = $loginTime->gt($deadline) ? 'TARDANZA' : 'PUNTUAL';
        }

        $this->update([
            'cajero_login_time' => $loginTime,
            'attendance_status' => $status,
        ]);
    }

    public function isPendingCajero(): bool
    {
        return $this->status === 'OPEN'
            && $this->cajero_login_time === null;
    }
}
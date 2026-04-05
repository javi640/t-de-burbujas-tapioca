<?php

namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
 
class CashMovement extends Model
{
    protected $fillable = [
        'shift_id', 'created_by', 'movement_type', 'amount', 'description',
    ];
 
    protected $casts = [
        'amount' => 'decimal:2',
    ];
 
    public function shift(): BelongsTo
    {
        return $this->belongsTo(Shift::class);
    }
 
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
 
    public function isIncome(): bool
    {
        return $this->movement_type === 'INCOME';
    }
}
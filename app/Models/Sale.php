<?php

namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
 
class Sale extends Model
{
    protected $fillable = [
        'shift_id', 'total_amount', 'payment_method',
        'status', 'sale_time', 'voided_by', 'void_reason',
    ];
 
    protected $casts = [
        'total_amount' => 'decimal:2',
        'sale_time'    => 'datetime',
    ];
 
    public function shift(): BelongsTo
    {
        return $this->belongsTo(Shift::class);
    }
 
    public function details(): HasMany
    {
        return $this->hasMany(SaleDetail::class);
    }
 
    public function voidedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'voided_by');
    }
 
    public function scopeCompleted($query)
    {
        return $query->where('status', 'COMPLETED');
    }
 
    public function isVoided(): bool
    {
        return $this->status === 'VOIDED';
    }
}
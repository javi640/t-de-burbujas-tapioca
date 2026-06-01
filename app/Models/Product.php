<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = ['branch_id', 'name', 'price', 'category', 'is_active'];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function shiftStocks(): HasMany
    {
        return $this->hasMany(ShiftStock::class);
    }

    public function saleDetails(): HasMany
    {
        return $this->hasMany(SaleDetail::class);
    }

    // Scope: solo productos activos
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}

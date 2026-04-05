<?php

namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
 
class ShiftStock extends Model
{
    protected $table = 'shift_stock';
 
    protected $fillable = [
        'shift_id', 'product_id', 'initial_quantity', 'sold_quantity',
    ];
 
    public function shift(): BelongsTo
    {
        return $this->belongsTo(Shift::class);
    }
 
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
 
    public function remainingQuantity(): int
    {
        return $this->initial_quantity - $this->sold_quantity;
    }
 
    public function hasStock(int $quantity): bool
    {
        return $this->remainingQuantity() >= $quantity;
    }
}
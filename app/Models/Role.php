<?php

namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
 
class Role extends Model
{
    protected $fillable = ['name', 'slug'];
 
    const ADMIN  = 'admin';
    const CAJERO = 'cajero';
 
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
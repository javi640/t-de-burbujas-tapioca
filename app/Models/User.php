<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'branch_id', 'role_id', 'username', 'name', 'email', 'password', 'is_active',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'is_active' => 'boolean',
        'password'  => 'hashed',
    ];

    // ── Relaciones ──────────────────────────────────────────

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function shifts(): HasMany
    {
        return $this->hasMany(Shift::class);
    }

    // ── Turno activo del usuario ─────────────────────────────

    public function openShift(): HasOne
    {
        return $this->hasOne(Shift::class)->where('status', 'OPEN');
    }

    public function hasOpenShift(): bool
    {
        return $this->openShift()->exists();
    }

    // ── Helpers de rol ──────────────────────────────────────

    public function isAdmin(): bool
    {
        $this->loadMissing('role');
        return $this->role->slug === 'admin';
    }

    public function isCajero(): bool
    {
        $this->loadMissing('role');
        return $this->role->slug === 'cajero';
    }
}
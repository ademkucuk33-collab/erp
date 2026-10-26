<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = ['name', 'email', 'password', 'role', 'warehouse_id'];
    protected $hidden = ['password', 'remember_token'];
    protected function casts(): array { return ['email_verified_at' => 'datetime', 'password' => 'hashed']; }

    public function warehouse() { return $this->belongsTo(Warehouse::class); }
    public function isSuperAdmin(): bool { return $this->role === 'super_admin'; }
    public function canAccessWarehouse(?int $warehouseId): bool
    {
        return $this->isSuperAdmin() || ($warehouseId !== null && $this->warehouse_id !== null && (int) $this->warehouse_id === $warehouseId);
    }
}

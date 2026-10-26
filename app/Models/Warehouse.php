<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    protected $fillable = ['code', 'name', 'country_code', 'address', 'active'];
    protected function casts(): array { return ['active' => 'boolean']; }
    public function inventoryBalances() { return $this->hasMany(InventoryBalance::class); }
}

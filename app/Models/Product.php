<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['sku', 'name', 'barcode', 'weight_g', 'length_mm', 'width_mm', 'height_mm', 'cost_minor', 'cost_currency', 'active'];
    protected function casts(): array { return ['active' => 'boolean']; }
    public function balances() { return $this->hasMany(InventoryBalance::class); }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryBalance extends Model
{
    protected $fillable = ['warehouse_id', 'product_id', 'on_hand', 'reserved'];
    public function product() { return $this->belongsTo(Product::class); }
    public function warehouse() { return $this->belongsTo(Warehouse::class); }
    public function available(): int { return $this->on_hand - $this->reserved; }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockReservation extends Model
{
    protected $fillable = ['order_item_id', 'warehouse_id', 'product_id', 'quantity', 'status'];
    public function orderItem() { return $this->belongsTo(OrderItem::class); }
}

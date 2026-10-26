<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model
{
    protected $fillable = ['warehouse_id', 'product_id', 'user_id', 'delta', 'reason', 'reference_key'];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['channel_account_id', 'external_order_id', 'warehouse_id', 'destination_country', 'currency', 'total_minor', 'status', 'shipping_address', 'placed_at'];
    protected function casts(): array { return ['placed_at' => 'datetime', 'shipping_address' => 'encrypted:array']; }
    public function items() { return $this->hasMany(OrderItem::class); }
    public function warehouse() { return $this->belongsTo(Warehouse::class); }
    public function channelAccount() { return $this->belongsTo(ChannelAccount::class); }
}

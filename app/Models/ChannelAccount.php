<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChannelAccount extends Model
{
    protected $fillable = ['platform', 'name', 'country_code', 'external_account_id', 'credentials', 'active', 'last_synced_at'];
    protected $hidden = ['credentials'];
    protected function casts(): array { return ['credentials' => 'encrypted:array', 'active' => 'boolean', 'last_synced_at' => 'datetime']; }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MarketDepth extends Model
{
    protected $fillable = array('exchange', 'symbol', 'is_active', 'created_at', 'updated_at');

	protected $table = 'market_depth';
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Instruments extends Model
{
    protected $fillable = array('name', 'exchange', 'zerodha_symbol', 'zerodha_token', 'zerodha_instrumenttype', 'angel_symbol', 'angel_token', 'angel_instrumenttype', 'flyers_symbol', 'flyers_token', 'flyers_instrumenttype', 'upstox_symbol', 'upstox_token', 'upstox_instrumenttype', 'is_active', 'created_at', 'updated_at');

	protected $table = 'instruments';
}

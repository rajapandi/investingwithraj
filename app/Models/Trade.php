<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Trade extends Model
{
    protected $fillable = array('account_id', 'orderid', 'transaction_type', 'variety', 'exchange', 'trading_symbol', 'product_type', 'order_type', 'quantity', 'price', 'trigger_price', 'disclosed_qty', 'target', 'stoploss', 'trailing_stoploss','validity', 'amo', 'trade_status','is_active','created_at','updated_at');

	protected $table = 'trade';
}
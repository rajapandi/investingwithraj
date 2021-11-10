<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TradingAccount extends Model
{
    protected $fillable = array('stock_brocker', 'trading_platform', 'login_id', 'password', 'security_ans', 'api_key', 'token', 'access_token', 'public_token','enctoken', 'name', 'mobile', 'email_id', 'tpin','is_active','created_at','updated_at');

	protected $table = 'trading_account';
}
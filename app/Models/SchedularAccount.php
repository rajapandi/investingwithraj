<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchedularAccount extends Model
{
    protected $fillable = array('schedular_id', 'login_id', 'platform', 'symbol', 'symbol_token', 'is_active','created_at','updated_at');

	protected $table = 'schedular_account';
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GeneralSetting extends Model
{
    protected $fillable = array('trade_type', 'variety', 'product_type', 'quantity','created_at','updated_at');

	protected $table = 'general_setting';
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PriceShcedular extends Model
{
    protected $fillable = array('schedular_id', 'price', 'price_type', 'below_above', 'is_active', 'created_at', 'updated_at');

	protected $table = 'price_schedular';
}
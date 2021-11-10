<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PercentageSchedular extends Model
{
    protected $fillable = array('schedular_id', 'price', 'price_type', 'set_price', 'percentage', 'percentage_type', 'is_active', 'created_at', 'updated_at');

	protected $table = 'percentage_schedular';
}
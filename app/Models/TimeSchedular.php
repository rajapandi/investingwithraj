<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TimeSchedular extends Model
{
    protected $fillable = array('schedular_id', 'price', 'price_type', 'frequency_diff', 'is_active', 'created_at', 'updated_at');

	protected $table = 'time_schedular';
}
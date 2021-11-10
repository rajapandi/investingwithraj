<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedular extends Model
{
    protected $fillable = array('transaction_type', 'variety', 'exchange', 'symbol', 'product_type', 'order_type', 'quantity', 'price', 'validity_type', 'validity', 'no_of_order', 'executed_order', 'schedular_type', 'is_active', 'created_at', 'updated_at');

	protected $table = 'schedular_detail';

    public $PRICE = "PRICE";
    public $TIME = "TIME";
    public $PERCENTAGE = "PERCENTAGE";
}
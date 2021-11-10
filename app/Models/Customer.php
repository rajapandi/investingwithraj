<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = array('name', 'email', 'mobile', 'address', 'customer_code', 'password', 'jwt_token', 'refresh_token', 'feedToken','is_active','created_at','updated_at');

	protected $table = 'customers';
}
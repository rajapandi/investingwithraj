<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Login extends Model
{
    protected $fillable = array('name', 'email', 'mobile', 'password','is_active','created_at','updated_at');

	protected $table = 'login';
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Groups extends Model
{
    protected $fillable = array('name', 'description', 'is_active', 'created_at', 'updated_at');

	protected $table = 'groups';
}
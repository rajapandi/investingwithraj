<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroupDetail extends Model
{
    protected $fillable = array('group_id', 'account_id', 'is_active', 'created_at', 'updated_at');

	protected $table = 'group_detail';
}
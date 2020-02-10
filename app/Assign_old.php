<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Assign extends Model
{
    protected $fillable = ['driver_id', 'order_id'];
}

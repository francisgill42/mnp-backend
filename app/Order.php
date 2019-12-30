<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['customer_id', 'order_total', 'order_tax', 'order_gross', 'order_status_id', 'order_confirmed_date', 'order_shipped_date','order_delivered_date'];
}


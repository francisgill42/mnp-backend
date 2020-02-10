<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Discount_Product extends Model
{
    protected $table = "discount_products";
    protected $fillable = ['discount_id','product_id', 'amount', 'type'];
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class Discount extends Model
{
    protected $fillable = ['customer_category_id','discount_type','discount_title','discount_amount','product_id'];
    
    public function get_discounts_with_group(){
        $discounts = DB::table('discounts')
            ->join('customer_categories', 'discounts.customer_category_id', '=', 'customer_categories.id')
            ->select('discounts.*', 'customer_categories.id as category_id', 'customer_categories.customer_category_name')
            ->get();

            return $discounts;
    }
}

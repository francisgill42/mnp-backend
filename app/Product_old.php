<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Product extends Model
{
    protected $fillable = [
		'legacy_code_sku',
		'product_title',
		'product_price',
		'product_image',
		'product_category_id',
		'unit_of_measurement',
		'product_description',
		'unit_in_case',
		'weight',
		'expiry_date',
		'IsActive',
        ];

         public static function get_products_with_categories(){

	       return DB::table('products')
	       ->join('categories', 'products.product_category_id', '=', 'categories.id')
	       ->select('categories.id as product_category_id','categories.category','products.*')
	       ->orderBy('products.id','desc')
	       ->get();
         
		}

		//    return DB::table('products')
	    //    ->join('categories', 'products.product_category_id', '=', 'categories.id')
		//    ->select('categories.id as product_category_id','categories.category',
		//    'products.id as product_id',
		//    'products.id as product_title',
		//    'products.id as product_price',
		//    'products.expiry_date as product_expiry',
		//    'product_image'
		//    )
	    //    ->orderBy('products.id','desc')
	    //    ->get();
         

}

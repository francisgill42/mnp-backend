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
		'group_code',
		'group_description',
		'pack_code',
		'pack_description'
        ];

         public static function get_products_with_categories(){

	       return DB::table('products')
	       ->join('categories', 'products.product_category_id', '=', 'categories.id')
	       ->select('categories.id as product_category_id','categories.category','products.*')
	       ->orderBy('products.id','desc')
	       ->get();
         
		}
		
		public static function get_products_with_stock(){
			return DB::table('products')
			->join('stocks', 'products.id', '=', 'stocks.product_id')
			->select('stocks.stock','products.*')
			->get();
		  
		 }

}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class Stock extends Model
{
    protected $fillable = ['product_id','stock'];

    public function get_stock_with_product($from, $to, $product)
    {
        $args = array();
        $now = date('Y-m-d', strtotime('+1 day'));
        $bw = array('', $now);
        if($from && $to){
            $to = date('Y-m-d', strtotime($to.' +1 day'));
            $bw = array($from, $to);
        }
        if($product){
            $args[] = array('products.id', '=', $product);
         }
        $stock = DB::table('stocks')
            ->join('products', 'stocks.product_id', '=', 'products.id')
            ->select('stocks.id as stock_id', 'stocks.stock', 'stocks.created_at as stock_created', 'products.*')
            ->where($args)
            ->whereBetween('stocks.created_at', $bw)
            // ->orderBy($order_by, $sort_by)
            // ->paginate($per_page);
            ->get();

            return $stock;
    }
    public function get_stock_with_product_by_id($id)
    {
        $stock = DB::table('stocks')
            ->join('products', 'stocks.product_id', '=', 'products.id')
            ->select('stocks.id as stock_id', 'stocks.stock', 'stocks.created_at as stock_created', 'products.*')
            ->where('stocks.id', '=', $id)
            ->get();

            return $stock;
    }
    public function get_stock_with_product_by_p_id($id)
    {
        $stock = DB::table('stocks')
            ->join('products', 'stocks.product_id', '=', 'products.id')
            ->select('stocks.id as stock_id', 'stocks.stock', 'stocks.created_at as stock_created', 'products.*')
            ->where('stocks.product_id', '=', $id)
            ->first();

            return $stock;
    }
}

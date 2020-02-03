<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class Stockadj extends Model
{
    protected $fillable = ['admin_id', 'product_id', 'quantity', 'reason'];

    public function get_adjustments_with_admin_product_by_id($id){
        $stockadj = DB::table('stockadjs')
            ->join('users', 'stockadjs.admin_id', '=', 'users.id')
            ->join('products', 'stockadjs.product_id', '=', 'products.id')
            ->select('stockadjs.*', 'users.name', 'products.product_title')
            ->where('stockadjs.id', '=', $id)
            ->get();

            return $stockadj;
    }
    public function get_adjustments_with_filter($from, $to, $product, $reason){
        $args = array();
        $now = date('Y-m-d', strtotime('+1 day'));
        $bw = array('', $now);
        if($from && $to){
            $to = date('Y-m-d', strtotime($to.' +1 day'));
            $from = date('Y-m-d.h:i:s', strtotime($from));
            $to = date('Y-m-d.h:i:s', strtotime($to));
            $bw = array($from, $to);
        }
        if($product){
            $args[] = array('stockadjs.product_id', '=', $product);
         }
         if($reason){
            $args[] = array('stockadjs.reason', '=', $reason);
         }
    
        $stockadj = DB::table('stockadjs')
        ->join('users', 'stockadjs.admin_id', '=', 'users.id')
        ->join('products', 'stockadjs.product_id', '=', 'products.id')
        ->select('stockadjs.*', 'users.name', 'products.product_title')
        ->where($args)
        ->whereBetween('stockadjs.created_at', $bw)
        ->get();

        return $stockadj;   
    }
}

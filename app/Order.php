<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class Order extends Model
{
    protected $fillable = ['customer_id', 'order_total', 'order_tax', 'order_gross', 'order_status_id', 'order_confirmed_date', 'order_shipped_date','order_delivered_date'];
    
    public function fetch_orders_with_customer_and_status(){
        $orders = DB::table('orders')
            ->join('users', 'orders.customer_id', '=', 'users.id')
            ->join('statuses', 'orders.order_status_id', '=', 'statuses.id')
            ->select('orders.*', 'statuses.status', 'users.name')
            ->whereNotIn('statuses.status', ['closed'])
            ->get();

            return $orders;

            /*->join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->join('products', 'order_items.product_id', '=', 'products.id')*/
    }
    public function fetch_orderitems_with_quantity($order_id){
        $items = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->select('order_items.product_quantity', 'products.*')
            ->where('order_items.order_id', '=', $order_id)
            ->get();

            return $items;
    }
}


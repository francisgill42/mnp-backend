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
            ->join('customer_categories', 'users.customer_category_id', '=', 'customer_categories.id')
            ->join('states', 'users.state_id', '=', 'states.id')
            ->join('cities', 'users.city_id', '=', 'cities.id')
            ->join('statuses', 'orders.order_status_id', '=', 'statuses.id')
            ->select('orders.*', 'statuses.status', 'users.id as user_id', 'users.name', 'users.role_id', 'users.customer_category_id', 'users.master', 'users.email', 'users.phone_number', 'users.mobile_number', 'users.ntn', 'users.address', 'users.state_id', 'users.city_id', 'users.password', 'users.IsActive', 'customer_categories.customer_category_name', 'states.state_name', 'cities.city_name')
            ->whereNotIn('statuses.status', ['closed'])
            ->get();

            return $orders;

    }
    public function fetch_orderitems_with_quantity($order_id){
        $items = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->select('order_items.id as order_item_id', 'order_items.product_quantity', 'products.*')
            ->where('order_items.order_id', '=', $order_id)
            ->get();

            return $items;
    }
    public function fetch_orders_by_customer($customer_id){
        $orders = DB::table('orders')
            ->join('users', 'orders.customer_id', '=', 'users.id')
            ->join('customer_categories', 'users.customer_category_id', '=', 'customer_categories.id')
            ->join('states', 'users.state_id', '=', 'states.id')
            ->join('cities', 'users.city_id', '=', 'cities.id')
            ->join('statuses', 'orders.order_status_id', '=', 'statuses.id')
            ->select('orders.*', 'statuses.status', 'users.id as user_id', 'users.name', 'users.role_id', 'users.customer_category_id', 'users.master', 'users.email', 'users.phone_number', 'users.mobile_number', 'users.ntn', 'users.address', 'users.state_id', 'users.city_id', 'users.password', 'users.IsActive', 'customer_categories.customer_category_name', 'states.state_name', 'cities.city_name')
            ->where('orders.customer_id', '=', $customer_id)
            ->whereNotIn('statuses.status', ['closed'])
            ->get();

            return $orders;
    }
}


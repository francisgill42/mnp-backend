<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Order;
use App\Order_item;
use App\Status;
use App\Product;
use App\Driver;
use App\Assign;
class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $order = new Order;
        $orders_arr = array();
        $orders = $order->fetch_orders_with_customer_and_status();
        foreach($orders as $ord){
            $ord->products = $order->fetch_orderitems_with_quantity($ord->id);
            $orders_arr[] = $ord;
        }
        return response($orders_arr);
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $msg = "";
        $order_arr = array();
        $product_arr = array();
        $order = Order::create([
            'customer_id' => $request->customer_id,
            'order_total' => $request->order_total,
            'order_tax'   => $request->order_tax,
            'order_gross' => $request->order_gross
        ]);
        if(isset($order->id)){
            foreach($request->products as $item){
                $item = Order_item::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'product_quantity'   => $item['product_quantity']
                ]);
                if(isset($item->id)){
                    $msg = "Order Placed Successfully";
                }
                else{
                    $msg = "Error in add product. Try Again";
                }
            }
        }
        else{
            $msg = "Sorry! Order not Place. Try Again.";
        }
        $i = 0;
        $order_date = explode(" ",$order->updated_at);
        $date = date("d-m-Y", strtotime($order_date[0]));
        $time = date("h:i", strtotime($order_date[1]));
        $fetch_order = Order::find($order->id);
        $order_status = Status::find($fetch_order->order_status_id);
        $fetch_items = Order_item::where('order_id',$order->id)->get();
        foreach($fetch_items as $pro){
            $product = Product::find($pro->product_id);
            $product_arr[$i]['product_id'] = $product->id??0;
            $product_arr[$i]['product_title'] = $product->product_title??"";
            $product_arr[$i]['product_description'] = $product->product_description??"";
            $product_arr[$i]['expiry_date'] = $product->expiry_date??"";
            $product_arr[$i]['product_price'] = $product->product_price??"";
            $product_arr[$i]['product_quantity'] = $pro->product_quantity??"";
            $i++;
        }

        $order_arr["id"] = $order->id;
        $order_arr["order_date"] = $date;
        $order_arr["order_time"] = $time;
        $order_arr["order_total"] = $order->order_total;
        $order_arr["order_tax"] = $order->order_tax;
        $order_arr["order_gross"] = $order->order_gross;
        $order_arr["status"] = $order_status->status;
        $order_arr["order_confirmed_date"] = $fetch_order->order_confirmed_date;
        $order_arr["order_shipped_date"] = $fetch_order->order_shipped_date;
        $order_arr["order_delivered_date"] = $fetch_order->order_delivered_date;
        $order_arr["order_products"] = $product_arr;

        return response($order_arr);
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function status_change(Request $request)
    {
        $order_id = $request->order_id;
        $status_id = $request->status_id;
        $arr = array();
        if(!$order_id){
            $msg = "You must have to select order";
        }
        else if(!$status_id){
            $msg = "You must have to select Status";
        }
        else{
            $update = Order::where('id', $order_id)->update(['order_status_id'=>$status_id]);
            if($update){
                $status = Status::find($status_id);
                $arr['id'] = $status->id;
                $arr['status'] = $status->status;
                $msg = "Status Changed Successfully";
            }
            else{
                $msg = "Status not Changed. Try Again";
            }
        }
        return response(['msg'=>$msg, 'Status'=>$arr]);
    }

    public function get_orders_by_user(){

        $id = Auth::id();
        $order = new Order;
        $orders_arr = array();
        $orders = $order->fetch_orders_by_customer($id);
        foreach($orders as $ord){

            $order_date = explode(" ",$ord->updated_at);
            $date = date("d-m-Y", strtotime($order_date[0]));
            $time = date("h:i", strtotime($order_date[1]));
            $ord->order_date = $date;
            $ord->order_time = $time;

            settype($ord->order_total, "double");
            settype($ord->order_tax, "double");
            settype($ord->order_gross, "double");

            $ord->order_products = $order->fetch_orderitems_with_quantity($ord->id);
            $orders_arr[] = $ord;
        }
        return response($orders_arr);
    }

    public function change_order_item(Request $request)
    {
        $order_item_id = $request->order_item_id;
        $product_id = $request->product_id;
        $item_quantity = $request->item_quantity;
        $arr = array();
            $update = Order_item::where('id', $order_item_id)->update(['product_id'=>$product_id, 'product_quantity'=> $item_quantity]);
            if($update){
                $product = Product::find($product_id);
                $arr = $product;
                $msg = "Order Item Changed Successfully";
            }
            else{
                $msg = "Order Item not Changed. Try Again";
            }
        return response(['msg'=>$msg, 'Product'=>$arr]);
    }

    public function select_drivers(){

        $drivers = Driver::where('role_id',3)->get();
        $arr = array();
        $i = 0;
        if($drivers){
            foreach($drivers as $driver){
                $arr[$i]['driver_id'] = $driver->id;
                $arr[$i]['name'] = $driver->name;
                $i++;
            }
        }
        return response($arr);
    }

}

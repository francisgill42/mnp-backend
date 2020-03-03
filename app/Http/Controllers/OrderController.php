<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Mail;
use App\Mail\Emailsend;
use App\Order;
use App\Order_item;
use App\Status;
use App\Product;
use App\Driver;
use App\Assign;
use App\Stock;
use App\User;
use App\Invoice;
use App\Customer;
use App\State;
use App\City;
use App\Exports\UsersExport;
use Maatwebsite\Excel\Facades\Excel;
use PDF;
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
    public function index(Request $request)
    {
        $per_page = $request->per_page;
        $order_by = $request->order_by;
        $sort_by = $request->sort_by;
        $timestamp = $request->timestamp;
        $driver = $request->driver;
        $status = $request->status;
        $customer = $request->customer;
        $from = $request->from;
        $to = $request->to;
        $state = $request->state;
        $city = $request->city;
        $product = $request->product;

        $order = new Order;
        $orders_arr = array();
        $options = array();
        if(empty($driver) && empty($product)){
            $orders = $order->fetch_orders_with_customer_and_status($per_page,$order_by,$sort_by,$timestamp,$status,$customer,$from,$to,$state,$city);
        }
        else if($product){
            $orders = $order->get_orders_with_products($per_page,$order_by,$sort_by,$timestamp,$driver,$status,$customer,$from,$to,$state,$city,$product);
        }
        else if($driver){
            $orders = $order->fetch_orders_with_driver_and_status($per_page,$order_by,$sort_by,$timestamp,$driver,$status,$customer,$from,$to,$state,$city);
        }
        
        
        $opt = $orders->getOptions();
        $options['current_page'] = $orders->currentPage();
        $options['total'] = $orders->total();
        $options['count'] = $orders->count();
        $options['path'] = $opt['path'];
        $options['more_pages'] = $orders->hasMorePages();
        $options['last_page'] = $orders->lastPage();
        $options['next_page_url'] = $orders->nextPageUrl();
        $options['on_first_page'] = $orders->onFirstPage();
        $options['per_page'] = $orders->perPage();
        $options['previous_page_url'] = $orders->previousPageUrl();
        
        foreach($orders as $ord){
            $ord->driver = $order->fetch_assigned_driver_to_order($ord->id);
            $ord->products = $order->fetch_orderitems_with_quantity($ord->id);
            foreach($ord->products as $ord_items){
                $stock = Stock::where(['product_id'=>$ord_items->id])->first();
                if($stock){
                    $ord_items->stock = $stock->stock;
                }
                else{
                    $ord_items->stock = 'Stock does not exist';
                }
               
            }
            $orders_arr[] = $ord;
        }
        //dump($ord->products);
        $options['orders'] = $orders_arr;
        return response()->json($options, 200);
        
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
            'order_gross' => $request->order_gross,
            'discounted_price' => $request->discounted_price
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
            $product_arr[$i]['legacy_code_sku'] = $product->legacy_code_sku??"";
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
        $order_arr["discounted_price"] = $order->discounted_price;
        $order_arr["order_products"] = $product_arr;

        return response($order_arr);
        
    }

    public function search_order(Request $request, $q){
    	$per_page = $request->per_page;
        $order_by = $request->order_by;
        $sort_by = $request->sort_by;
    	 $order = new Order;
        $orders_arr = array();
        $options = array();
        $orders = $order->fetch_orders_with_customer_and_status_with_search($q, $order_by, $sort_by, $per_page);
        $opt = $orders->getOptions();
        $options['current_page'] = $orders->currentPage();
        $options['total'] = $orders->total();
        $options['count'] = $orders->count();
        $options['path'] = $opt['path'];
        $options['more_pages'] = $orders->hasMorePages();
        $options['last_page'] = $orders->lastPage();
        $options['next_page_url'] = $orders->nextPageUrl();
        $options['on_first_page'] = $orders->onFirstPage();
        $options['per_page'] = $orders->perPage();
        $options['previous_page_url'] = $orders->previousPageUrl();
        
        foreach($orders as $ord){
            $ord->products = $order->fetch_orderitems_with_quantity($ord->id);
            $orders_arr[] = $ord;
        }
        $options['orders'] = $orders_arr;
        return response()->json($options, 200);
    	
    }	

    

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
        $driver_id = $request->driver_id;
        $scheduling = $request->scheduling;
        $due_date = $request->payment_due_date;
        $arr = array();
        $flag = false;
        $stock_status = false;

            if($driver_id){

                $items = Order_item::where(['order_id'=>$order_id])->get();
                foreach($items as $item){   
                    $stock = Stock::where(['product_id'=> $item->product_id])->get();
                    if(!empty($stock[0])){
                        if($stock[0]->stock<$item->product_quantity || $stock[0]->stock == 0){
                            $msg = "Stock of this product has id:".$item->product_id." is less than your ordered quantity or equal to 0";
                            $stock_status = true;
                            break;
                        }
                       
                    }
                    else{
                        $msg = "Stock for this product has id:".$item->product_id." is not available";
                        $stock_status = true;
                        break;
                    }
                }
                if($stock_status){                 
                    return response(['response_status'=>false, 'message'=>$msg]);
                }
                else{
                    $ord_status = Order::find($order_id);
                    if($ord_status->order_status_id == 1){
                        foreach($items as $order_item){   
                            $stock2 = Stock::where(['product_id'=> $order_item->product_id])->get();
                            $cal = ($stock2[0]->stock-$order_item->product_quantity);
                            $update2 = Stock::where('id', $stock2[0]->id)->update(['stock'=>$cal]);
                        }
                    }
                }

                $ord_assign = new Assign;
                $driver = Assign::where('order_id',$order_id)->get();
               
                if(empty($driver[0])){
                    $assign = Assign::create(['driver_id'=>$driver_id, 'order_id'=>$order_id]);
                    if($assign){
                        $status_id = 2;
                        $flag = true;
                    }
                    
                }
                else{
                    $update = Assign::where('id', $driver[0]->id)->update(['driver_id'=>$driver_id, 'order_id'=>$order_id]);
                    if($update){
                        $status_id = 2;
                        $flag = true;
                    }
                }

                $cs = User::where(['role_id'=>2])->first();
                $get_inv = Invoice::orderby('id', 'desc')->first();
                if($get_inv){
                    $inv_nmbr = $get_inv->invoice_number+1;
                }
                else{
                    $inv_nmbr ='1000000';
                }

                $data = array();
                $order_info = new Order;
                $get_order = $order_info->fetch_orders_by_id($order_id);
                foreach($get_order as $ord){
                    $customer_email = $ord->email;
                    $ord->invoice_number = $inv_nmbr;
                    $ord->delivery_date = $scheduling;
                    $ord->payment_due_date = $due_date;
                    $ord->products = $order_info->fetch_orderitems_with_quantity($order_id);
                    $data = $ord;

                    $invoice = Invoice::create(['invoice_number'=>$inv_nmbr, 'order_id'=>$order_id, 'customer_id'=>$ord->user_id, 'invoice_status'=>1]);
                    $get = Invoice::find($invoice->id);
                    $ord->prefix = $get->prefix;
                }
                
                if(empty($driver[0])){
                // $pdf_data = array('order'=>$data);
                // $pdf = PDF::loadView('myPDF', $pdf_data);
                $inv_name = $data->prefix.''.$data->invoice_number;


                $params=['data'=>json_encode($data), 'inv_name'=>$inv_name, 'customer_email'=>$customer_email, 'cs'=>$cs->email];
                $defaults = array(
                CURLOPT_URL => 'https://orangeroomdigital.com/email_sender/public/api/send_email',
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => $params,
                );
                $ch = curl_init();
                curl_setopt_array($ch, ($defaults));
                $output = curl_exec($ch);
                curl_close($ch);  
                // print_r($output);
                // die;
                //$mail = Mail::to($customer_email)->bcc([$cs->email, 'customer.service@unitedcool.com'])->send(new Emailsend($data, $pdf, $inv_name));
                //$mail = Mail::to("aizaz.hussain@orangeroomdigital.com")->bcc("")->bcc("muhammad.idrees@orangeroomdigital.com")->send(new Emailsend($data, $pdf, $inv_name));
		        }
            }

            $date = date("d-m-Y", strtotime(NOW()));
            if($status_id == 2){
                $update = Order::where('id', $order_id)->update(['order_status_id'=>$status_id, 'order_confirmed_date'=>$date, 'delivery_date'=>$scheduling, 'payment_due_date'=>$due_date]);
                
                $get_order2 = $order_info->fetch_orders_by_id($order_id);
                foreach($get_order2 as $get_ord){
                    $get_ord->driver = $order_info->fetch_assigned_driver_to_order($order_id);
                    $get_ord->products = $order_info->fetch_orderitems_with_quantity($order_id);
                    foreach($get_ord->products as $ord_items){
                        $stock = Stock::where(['product_id'=>$ord_items->id])->first();
                        if($stock){
                            $ord_items->stock = $stock->stock;
                        }
                        else{
                            $ord_items->stock = 'Stock does not exist';
                        }
                    }
                    $data2 = $get_ord;
                }
            }
            else if($status_id == 3){
                $update = Order::where('id', $order_id)->update(['order_status_id'=>$status_id, 'order_shipped_date'=>$date]);
            }
            else if($status_id == 5){
                $update = Order::where('id', $order_id)->update(['order_status_id'=>$status_id, 'order_delivered_date'=>$date]);
            }
            else{
                $update = Order::where('id', $order_id)->update(['order_status_id'=>$status_id]);
            }
            
            if($update){
                $status = Status::find($status_id);
                $arr['id'] = $status->id;
                $arr['status'] = $status->status;
                $arr['keyword'] = $status->keyword;
                $res = true;
                if($flag){
                    $msg = "Order Assigned Successfully";
                }
                else{
                    $msg = "Status Changed Successfully";
                }
            }
            else{
                $res = false;
                $msg = "Status not Changed. Try Again";
            }
            if($status_id == 2){
                return response(['response_status'=>$res, 'message'=>$msg, 'updated_record'=>$data2]);
            }
            else{
                return response(['response_status'=>$res, 'message'=>$msg, 'updated_record'=>$arr]);
            }
        
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

    public function get_processing_orders_by_coldstorage(){
        
        $order = new Order;
        $orders_arr = array();
        $orders = $order->fetch_processing_orders_by_coldstorage(); 
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
            $ord->driver = $order->fetch_assigned_driver_to_order($ord->id);
            $orders_arr[] = $ord;
        }
        return response($orders_arr);
    }


    public function get_orders_by_coldstorage(){
        
        $order = new Order;
        $orders_arr = array();
        $orders = $order->fetch_orders_by_coldstorage(); 
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
            $ord->driver = $order->fetch_assigned_driver_to_order($ord->id);
            $orders_arr[] = $ord;
        }
        return response($orders_arr);
    }

    public function get_assigned_orders_to_driver(){
        
        $user = Auth::user();

        if($user->role_id == 3){
            $order = new Order;
            $orders_arr = array();
            $orders = $order->fetch_assigned_orders_to_driver($user->id); 
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
                $ord->driver = $order->fetch_assigned_driver_to_order($ord->id);
                $orders_arr[] = $ord;
            }
            return response($orders_arr);
        }
        else{
            return response(['msg'=>'Logged in user in not a driver.']);
        }
        
    }

    public function get_delivered_orders_by_driver(){
        $user = Auth::user();

        if($user->role_id == 3){
            $order = new Order;
            $orders_arr = array();
            $orders = $order->fetch_delivered_orders_by_driver($user->id); 
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
                $ord->driver = $order->fetch_assigned_driver_to_order($ord->id);
                $orders_arr[] = $ord;
            }
            return response($orders_arr);
        }
        else{
            return response(['msg'=>'Logged in user in not a driver.']);
        }
    }

    public function change_order_item(Request $request)
    {
        $order_id = $request->order_id;
        $order_item_id = $request->order_item_id;
        $product_id = $request->product_id;
        $item_quantity = $request->item_quantity;
        $arr = array();
        $gross = 0;
            $update = Order_item::where('id', $order_item_id)->update(['product_id'=>$product_id, 'product_quantity'=> $item_quantity]);
            if($update){
                $order = new Order;
                $order_items = $order->fetch_orderitems_with_quantity($order_id);
                foreach($order_items as $item){
                    $gross += $item->product_price*$item->product_quantity;
                    if($item->order_item_id == $order_item_id){
                        $stock = Stock::where(['product_id'=>$product_id])->first();
                            if($stock){
                                $item->stock = $stock->stock;
                            }
                            else{
                                $item->stock = 'Stock does not exist';
                            }
                        $items_arr = $item;
                    }
                }
                $tax = ($gross/100)*5;
                $total = $gross+$tax;
                $update = Order::where('id', $order_id)->update(['order_total'=>$total, 'order_tax'=>$tax, 'order_gross'=>$gross]);
                if($update){
                    $orders = $order->fetch_orders_by_id($order_id);  
                    foreach($orders as $ord){
                        $ord->products = $items_arr;
                    }
                }

                $msg = "Order Item Changed Successfully";
                $res = true;
            }
            else{
                $msg = "Order Item not Changed. Try Again";
                $res = false;
            }
        return response(['response_status'=>$res, 'message'=>$msg, 'updated_record'=>$ord]);
    }

    public function add_order_item(Request $request)
    {
        $order_id = $request->order_id;
        $product_id = $request->product_id;
        $item_quantity = $request->item_quantity;
        $arr = array();
        $gross = 0;
            $insert = Order_item::create(['order_id'=>$order_id,'product_id'=>$product_id, 'product_quantity'=> $item_quantity]);
            if($insert){
                $order_item_id = $insert->id;
                $order = new Order;
                $order_items = $order->fetch_orderitems_with_quantity($order_id);
                foreach($order_items as $item){
                    $gross += $item->product_price*$item->product_quantity;
                    if($item->order_item_id == $order_item_id){
                        $stock = Stock::where(['product_id'=>$product_id])->first();
                            if($stock){
                                $item->stock = $stock->stock;
                            }
                            else{
                                $item->stock = 'Stock does not exist';
                            }
                        $items_arr = $item;
                    }
                }
                $tax = ($gross/100)*5;
                $total = $gross+$tax;
                $update = Order::where('id', $order_id)->update(['order_total'=>$total, 'order_tax'=>$tax, 'order_gross'=>$gross]);
                if($update){
                    $orders = $order->fetch_orders_by_id($order_id);  
                    foreach($orders as $ord){
                        $ord->products = $items_arr;
                    }
                }

                $msg = "Order Item Added Successfully";
                $res = true;
            }
            else{
                $msg = "Order Item not Added. Try Again";
                $res = false;
            }
        return response(['response_status'=>$res, 'message'=>$msg, 'new_record'=>$ord]);
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
    
    public function send_email(){

        /*$order_id = 11;
        $driver_id = 7;
        $driver = User::find($driver_id);
        $cs = User::where(['role_id'=>2])->first();
        $inv_nmbr ='MNP_INV_'.time();
        $data = array();
        $order_info = new Order;
        $get_order = $order_info->fetch_orders_by_id($order_id);
        foreach($get_order as $ord){
            $customer_email = $ord->email;
            $ord->invoice_number = $inv_nmbr;
            $ord->products = $order_info->fetch_orderitems_with_quantity($order_id);
            $data["order"] = $ord;

            $invoice = Invoice::create(['invoice_number'=>$inv_nmbr, 'order_id'=>$order_id, 'customer_id'=>$ord->user_id, 'invoice_status'=>1]);
        }*/
 
        //$mail = Mail::to($customer_email)->bcc($cs->email)->bcc($driver->email)->send(new Emailsend($data));
        //$mail = Mail::to("aizaz.hussain@orangeroomdigital.com")->send(new Emailsend($data));
        //return view('invoice', $data);

    }
    public function daily_orders(){
        $orders = new Order;
        $todays = $orders->order_by_today();
        $data = array();
        $data['orders_count'] = count($todays);
        $amount = 0;
        foreach($todays as $ord){
            $amount += $ord->order_total;
        }
        $data['orders_amount'] = $amount;

        return response($data);
    }
    public function weekly_orders(){
		$orders = new Order;
        $todays = $orders->order_by_week();
        $data = array();
        $data['orders_count'] = count($todays);
        $amount = 0;
        foreach($todays as $ord){
            $amount += $ord->order_total;
        }
        $data['orders_amount'] = $amount;

        return response($data);
    }
    public function monthly_orders(){
		$orders = new Order;
        $todays = $orders->order_by_month();
        $data = array();
        $data['orders_count'] = count($todays);
        $amount = 0;
        foreach($todays as $ord){
            $amount += $ord->order_total;
        }
        $data['orders_amount'] = $amount;

        return response($data);
    }
    public function export() 
    {
        return Excel::download(new UsersExport, 'users.xlsx');
    }
    public function export_orders(Request $request)
    {
        $order_by = $request->order_by;
        $sort_by = $request->sort_by;
        $timestamp = $request->timestamp;
        $driver = $request->driver;
        $status = $request->status;
        $customer = $request->customer;
        $from = $request->from;
        $to = $request->to;
        $state = $request->state;
        $city = $request->city;
        $product = $request->product;

        $order = new Order;
        $orders_arr = array();
        //$options = array();
        if(empty($driver) && empty($product)){
            $orders = $order->export_orders_with_customer_and_status($order_by,$sort_by,$timestamp,$status,$customer,$from,$to,$state,$city);
        }
        else if($product){
            $orders = $order->export_orders_with_products($order_by,$sort_by,$timestamp,$driver,$status,$customer,$from,$to,$state,$city,$product);
        }
        else if($driver){
            $orders = $order->export_orders_with_driver_and_status($order_by,$sort_by,$timestamp,$driver,$status,$customer,$from,$to,$state,$city);
        }

        foreach($orders as $ord){
            $ord->driver = $order->fetch_assigned_driver_to_order($ord->id);
            $ord->products = $order->fetch_orderitems_with_quantity($ord->id);
            foreach($ord->products as $ord_items){
                $stock = Stock::where(['product_id'=>$ord_items->id])->first();
                if($stock){
                    $ord_items->stock = $stock->stock;
                }
                else{
                    $ord_items->stock = 'Stock does not exist';
                }
               
            }
            $orders_arr[] = $ord;
        }
        //dump($ord->products);
        //$options['orders'] = $orders_arr;
        return response()->json($orders_arr, 200);
        
    }

    public function recent_orders(){
        $order = new Order;
        $orders_arr = array();
        //$options = array();
        $orders = $order->recent_orders();
        foreach($orders as $ord){
            $ord->driver = $order->fetch_assigned_driver_to_order($ord->id);
            $ord->products = $order->fetch_orderitems_with_quantity($ord->id);
            foreach($ord->products as $ord_items){
                $stock = Stock::where(['product_id'=>$ord_items->id])->first();
                if($stock){
                    $ord_items->stock = $stock->stock;
                }
                else{
                    $ord_items->stock = 'Stock does not exist';
                }
               
            }
            $orders_arr[] = $ord;
        }
        //$options['orders'] = $orders_arr;
        return response()->json($orders_arr, 200);
    }
    public function pdf_export(){
        echo "pdf";
    }
    public function filter_listing(){
        $data = array();
        $products = Product::select('id','product_title')->get();
        $customers = Customer::where(['role_id'=>1])->select('id', 'company_name')->get();
        $drivers = Driver::where(['role_id'=>3])->select('id', 'name')->get();
        $status = Status::select('id','status')->get();
        $states = State::select('id','state_name')->get();
        $city = City::select('id','city_name')->get();

        $data['products'] = $products;
        $data['customers'] = $customers;
        $data['drivers'] = $drivers;
        $data['status'] = $status;
        $data['states'] = $states;
        $data['cities'] = $city;
        return $data;
    }
    public function orders_by_products(Request $request){
        $order_by = $request->order_by;
        $sort_by = $request->sort_by;
        $from = $request->from;
        $to = $request->to;
        $product = $request->product;
        $orders_arr = array();
        $order = new Order;
        $orders = $order->get_orders_by_products($order_by,$sort_by,$from,$to,$product);
        $i = 1;
        foreach($orders as $ord){
            $ord->order_id = $ord->id;
            $ord->id = $i++;
            $ord->driver = $order->fetch_assigned_driver_to_order($ord->id);
            $ord->products = $order->fetch_orderitems_with_quantity($ord->id);
            foreach($ord->products as $ord_items){
                $stock = Stock::where(['product_id'=>$ord_items->id])->first();
                if($stock){
                    $ord_items->stock = $stock->stock;
                }
                else{
                    $ord_items->stock = 'Stock does not exist';
                }
               
            }
            $orders_arr[] = $ord;
        }
        
        return response()->json($orders_arr, 200);

    }
    public function orders_by_customers(Request $request){
        $order_by = $request->order_by;
        $sort_by = $request->sort_by;
        $from = $request->from;
        $to = $request->to;
        $customer = $request->customer;
        $orders_arr = array();

        $order = new Order;
        $orders = $order->get_orders_by_customer($order_by,$sort_by,$from,$to,$customer);
        foreach($orders as $ord){
            $ord->driver = $order->fetch_assigned_driver_to_order($ord->id);
            $ord->products = $order->fetch_orderitems_with_quantity($ord->id);
            foreach($ord->products as $ord_items){
                $stock = Stock::where(['product_id'=>$ord_items->id])->first();
                if($stock){
                    $ord_items->stock = $stock->stock;
                }
                else{
                    $ord_items->stock = 'Stock does not exist';
                }
               
            }
            $orders_arr[] = $ord;
        }
        
        return response()->json($orders_arr, 200);

    }
    public function orders_by_drivers(Request $request){
        $order_by = $request->order_by;
        $sort_by = $request->sort_by;
        $from = $request->from;
        $to = $request->to;
        $driver = $request->driver;
        $orders_arr = array();

        $order = new Order;
        $orders = $order->get_orders_by_driver($order_by,$sort_by,$from,$to,$driver);
        foreach($orders as $ord){
            $ord->driver = $order->fetch_assigned_driver_to_order($ord->id);
            $ord->products = $order->fetch_orderitems_with_quantity($ord->id);
            foreach($ord->products as $ord_items){
                $stock = Stock::where(['product_id'=>$ord_items->id])->first();
                if($stock){
                    $ord_items->stock = $stock->stock;
                }
                else{
                    $ord_items->stock = 'Stock does not exist';
                }
               
            }
            $orders_arr[] = $ord;
        }
        
        return response()->json($orders_arr, 200);

    }
    public function orders_by_states(Request $request){
        $order_by = $request->order_by;
        $sort_by = $request->sort_by;
        $from = $request->from;
        $to = $request->to;
        $state = $request->state;
        $orders_arr = array();

        $order = new Order;
        $orders = $order->get_orders_by_state($order_by,$sort_by,$from,$to,$state);
        foreach($orders as $ord){
            $ord->driver = $order->fetch_assigned_driver_to_order($ord->id);
            $ord->products = $order->fetch_orderitems_with_quantity($ord->id);
            foreach($ord->products as $ord_items){
                $stock = Stock::where(['product_id'=>$ord_items->id])->first();
                if($stock){
                    $ord_items->stock = $stock->stock;
                }
                else{
                    $ord_items->stock = 'Stock does not exist';
                }
               
            }
            $orders_arr[] = $ord;
        }
        
        return response()->json($orders_arr, 200);

    }
    public function orders_by_cities(Request $request){
        $order_by = $request->order_by;
        $sort_by = $request->sort_by;
        $from = $request->from;
        $to = $request->to;
        $city = $request->city;
        $orders_arr = array();

        $order = new Order;
        $orders = $order->get_orders_by_city($order_by,$sort_by,$from,$to,$city);
        foreach($orders as $ord){
            $ord->driver = $order->fetch_assigned_driver_to_order($ord->id);
            $ord->products = $order->fetch_orderitems_with_quantity($ord->id);
            foreach($ord->products as $ord_items){
                $stock = Stock::where(['product_id'=>$ord_items->id])->first();
                if($stock){
                    $ord_items->stock = $stock->stock;
                }
                else{
                    $ord_items->stock = 'Stock does not exist';
                }
               
            }
            $orders_arr[] = $ord;
        }
        
        return response()->json($orders_arr, 200);

    }
}

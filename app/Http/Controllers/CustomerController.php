<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Customer;
use App\Discount;
use App\Discount_Product;
use App\Product;
class CustomerController extends Controller
{
public function __construct()
{
$this->middleware('auth:api');
}

public function index()
{
    $arr = array();
    $customers = Customer::get_customers_with_additionals();
    foreach($customers as $customer){
        $customer_category_id = $customer->customer_category_id;
        $discount = Discount::where(['customer_category_id'=>$customer_category_id])->first();
        if($discount){
            $pro_arr = array();
            $products = Discount_Product::where(['discount_id'=>$discount->id])->get();
            
            foreach($products as $product){
                $get_pro = Product::find($product->product_id);
                $get_pro->discount_amount = $product->amount;
                $pro_arr[] = $get_pro;
            }
            $customer->products = $pro_arr;
        }
        
            $arr[] = $customer;
    } 
    return $arr;
        
}


public function store(Request $request)
{
// return $request->all();
$validator = Validator::make($request->all(), [ 
'email' => 'required|email|unique:users', 
'password' => 'min:6', 
]); 
if ($validator->fails()) { 

return response()->json([
'response_status' => false,
'errors' => $validator->errors()
]); 

}

$arr = [
'address' => $request->address,
'city_id' => $request->city_id,
'customer_category_id' => $request->customer_category_id,
'email' => $request->email, //"francisgill1000@gmail.com"
'mobile_number' => $request->mobile_number,
'name' => $request->name,
'password' => bcrypt($request->password),
'ntn' => $request->ntn,
'phone_number' => $request->phone_number,
'state_id' => $request->state_id,
'IsActive' => $request->IsActive,
'company_name' => $request->company_name,
'trade_name' => $request->trade_name,
'contact_person_name' => $request->contact_person_name,
'delivery_from' => $request->delivery_from,
'delivery_to' => $request->delivery_to,
'payment_type' => $request->payment_type,
'master' => 0,
'role_id' => 1
];

$user = Customer::create($arr); 

$user =  \DB::table('users')
->join('customer_categories', 'users.customer_category_id', '=', 'customer_categories.id')
->join('states', 'users.state_id', '=', 'states.id')
->join('cities', 'users.city_id', '=', 'cities.id')
->select(
'users.*',
'customer_categories.customer_category_name',
'states.state_name',
'cities.city_name')

->where('users.id',$user->id)
->first();

return response()->json([
'response_status' => true,
'message' => 'user has been created',
'new_record' => $user,        
],200); 

}

public function show($id){
return $id;
}



public function update(Request $request, $id){




$validator = Validator::make($request->all(), [ 
'password' => 'min:6', 
]); 
if ($validator->fails()) { 

return response()->json([
'response_status' => false,
'errors' => $validator->errors()
]); 

}

$arr = [
    'address' => $request->address,
    'city_id' => $request->cities_id,
    'customer_category_id' => $request->customer_category_id,
    'email' => $request->email,
    'mobile_number' => $request->mobile_number,
    'name' => $request->name,
    'ntn' => $request->ntn,
    'phone_number' => $request->phone_number,
    'IsActive' => $request->IsActive,
    'company_name' => $request->company_name,
'trade_name' => $request->trade_name,
'contact_person_name' => $request->contact_person_name,
'delivery_from' => $request->delivery_from,
'delivery_to' => $request->delivery_to,
'payment_type' => $request->payment_type,
    'state_id' => $request->state_id,
    
];


$updated = \DB::table('users')->where('id',$id)->update($arr);



if($updated) {

$user =  \DB::table('users')
->join('customer_categories', 'users.customer_category_id', '=', 'customer_categories.id')
->join('states', 'users.state_id', '=', 'states.id')
->join('cities', 'users.city_id', '=', 'cities.id')
->select(
    'users.*',
    'customer_categories.id as customer_category_id','customer_categories.customer_category_name',
    'states.id as state_id','states.state_name',
    'cities.id as city_id','cities.city_name')

//->select('users.*','customer_categories.customer_category_name','states.state_name','cities.city_name')
->where('users.id',$id)
->first();

return response()->json([
'response_status' => true,
'message' => 'user has been updated', 
'updated_record' => $user
]);

}

else{
return response()->json([
'response_status' => false,
'message' => 'user cannot update', 
]); 
}


}


public function destroy($id){

return (Customer::find($id)->delete()) 
? [ 'response_status' => true, 'message' => 'user has been deleted' ] 
: [ 'response_status' => false, 'message' => 'user cannot delete' ];

}

public function update_password(Request $request,$id){


    $updated = \DB::table('users')->where('id',$id)->update([
        'password' => bcrypt($request->pwd)
        ]);

    if($updated){

        return response()->json([
        'response_status' => true,
        'message' => 'Password has been updated', 
        ]);

    }
    else{
        
        return response()->json([
        'response_status' => false,
        'message' => 'Password can not updated', 
        ]);
    }


}

}

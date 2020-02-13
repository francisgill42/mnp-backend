<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Discount;
use App\Discount_Product;
use App\CustomerCategory;
use App\Product;
class DiscountController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    public function index()
    {
        $data = array();
        $discounts = Discount::orderBy('id', 'desc')->get();
        foreach($discounts as $disc){
            $cat_arr = array();
            $cat_arr[] = CustomerCategory::find($disc->customer_category_id); 
            $disc->categories = $cat_arr;
            $pro_arr = array();
            $products = Discount_Product::where(['discount_id'=>$disc->id])->get();
            
            foreach($products as $product){
                $get_pro = Product::find($product->product_id);
                $get_pro->discount_amount = $product->amount;
                $pro_arr[] = $get_pro;
            }
            $disc->products = $pro_arr;
            $data[] = $disc;
        }
        return response($data);
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [ 
            'customer_category_id' => 'required', 
            'discount_type' => 'required', 
            'discount_title' => 'required'
            ]); 
            if ($validator->fails()) { 
            
            return response()->json([
            'response_status' => false,
            'errors' => $validator->errors()
            ]); 
            
            }


        $customer_category_id = $request->customer_category_id;
        $discount_type = $request->discount_type;
        $discount_title = $request->discount_title;
        
        $products = $request->products;
       
        $res = false;
        $msg = "";
        
        $insert = Discount::create(['customer_category_id'=>$customer_category_id, 'discount_type'=>$discount_type, 'discount_title'=>$discount_title]);
            if($insert){
                $id = $insert->id;
                $pro_arr = array();
                foreach($products as $product){
                    $discount_Products = Discount_Product::create(['discount_id'=>$id, 'product_id'=>$product['product_id'], 'amount'=>$product['amount']]);
                    $get_pro = Product::find($product['product_id']);
                    $get_pro->discount_amount = $product['amount'];
                    $pro_arr[] = $get_pro;
                }
                
                $res = true;
                $msg = "Discount Added Successfully";

                $cat_arr = array();
                $cat_arr[] = CustomerCategory::find($customer_category_id); 
                
                $insert->categories = $cat_arr;
                $insert->products = $pro_arr;
            }

        return response(['response_status'=>$res, 'message'=>$msg, 'new_record'=>$insert]);
    }


    public function update_discount(Request $request){
        
    }


    public function show($id){
        $data = array();
        $disc = Discount::find($id);
        if($disc){
    
            $cat = CustomerCategory::find($disc->customer_category_id); 
            $disc->customer_category_name = $cat->customer_category_name;
            $pro_arr = array();
            $products = Discount_Product::where(['discount_id'=>$disc->id])->select('product_id','amount')->get();
            
            foreach($products as $product){
                $get_pro = Product::find($product->product_id);
                $product->product_title = $get_pro->product_title;
                //$get_pro->amount = $product->amount;
                $pro_arr[] = $product;
            }
            $disc->products = $pro_arr;
            $data[] = $disc;
        }       
        return response($disc);
    }
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [ 
            'customer_category_id' => 'required', 
            'discount_type' => 'required', 
            'discount_title' => 'required'
            ]); 
            if ($validator->fails()) { 
            
            return response()->json([
            'response_status' => false,
            'errors' => $validator->errors()
            ]); 
            
            }

        $customer_category_id = $request->customer_category_id;
        $discount_type = $request->discount_type;
        $discount_title = $request->discount_title;

        $products = $request->products;
        $res = false;
        $msg = "";
        $data = array();
        
        $update = Discount::where('id', $id)->update(['customer_category_id'=>$customer_category_id, 'discount_type'=>$discount_type, 'discount_title'=>$discount_title]);
            if($update){
                $delete = Discount_Product::where(['discount_id'=>$id])->delete();
                $fetch = Discount::find($id);
                $pro_arr = array();
                foreach($products as $product){
                    // $pro = Discount_Product::where(['discount_id'=>$id, 'product_id'=>$product['product_id']])->first();
                    // if(empty($pro)){
                        $discount_Products = Discount_Product::create(['discount_id'=>$id, 'product_id'=>$product['product_id'], 'amount'=>$product['amount']]);
                        $get_pro = Product::find($product['product_id']);
                        $get_pro->discount_amount = $product['amount'];
                        $pro_arr[] = $get_pro;
                    // }
                    // else{
                    //     $discount_Products = Discount_Product::where('id', $pro->id)->update(['discount_id'=>$id, 'amount'=>$product['amount']]);
                    //     $get_pro = Product::find($product['product_id']);
                    //     $get_pro->discount_amount = $product['amount'];
                    //     $pro_arr[] = $get_pro;
                    // }
                }
                
                    $cat_arr = array();
                    $cat_arr[] = CustomerCategory::find($fetch->customer_category_id); 
                    $fetch->categories = $cat_arr;
                    $fetch->products = $pro_arr;
                    $data[] = $fetch;

                $res = true;
                $msg = "Discount Updated Successfully";
            }
            else{
                $msg = "Discount not Updated";
            }

        return response(['response_status'=>$res, 'message'=>$msg, 'updated_record'=>$fetch]);
    }
    public function destroy($id)
    {
        $delete = Discount::where('id', $id)->delete();
        if($delete){
            $msg = "Discount Deleted Successfully";
            $res = true;
        }
        else{
            $msg = "Discount not Deleted. Try Again";
            $res = false;
        }
        return response(['response_status'=>$res, 'message'=>$msg]);
    }
}

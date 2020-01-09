<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Discount;
use App\CustomerCategory;
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
            $categories = json_decode($disc->customer_category_id);
            $cat_arr = array();
            foreach($categories as $category){
                $cat_arr[] = CustomerCategory::find($category); 
            }
            $disc->categories = $cat_arr;
            $data[] = $disc;
        }
        return response($data);
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [ 
            'customer_category_ids' => 'required', 
            'discount_type' => 'required', 
            'discount_title' => 'required',
            'discount_amount' => 'required'
            ]); 
            if ($validator->fails()) { 
            
            return response()->json([
            'response_status' => false,
            'errors' => $validator->errors()
            ]); 
            
            }


        $customer_category_ids = json_encode($request->customer_category_ids);
        $discount_type = $request->discount_type;
        $discount_title = $request->discount_title;
        $discount_amount  =$request->discount_amount;
        $res = false;
        $msg = "";
        
        $insert = Discount::create(['customer_category_id'=>$customer_category_ids, 'discount_type'=>$discount_type, 'discount_title'=>$discount_title, 'discount_amount'=>$discount_amount]);
            if($insert){
                $res = true;
                $msg = "Discount Added Successfully";

                $categories = json_decode($customer_category_ids);
                $cat_arr = array();
                foreach($categories as $category){
                    $cat_arr[] = CustomerCategory::find($category); 
                }
                $insert->categories = $cat_arr;
            }

        return response(['response_status'=>$res, 'message'=>$msg, 'new_record'=>$insert]);
    }


    public function update_discount(Request $request){

    }


    public function show($id){
        
    }
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [ 
            'customer_category_ids' => 'required', 
            'discount_type' => 'required', 
            'discount_title' => 'required',
            'discount_amount' => 'required'
            ]); 
            if ($validator->fails()) { 
            
            return response()->json([
            'response_status' => false,
            'errors' => $validator->errors()
            ]); 
            
            }

        $customer_category_ids = json_encode($request->customer_category_ids);
        $discount_type = $request->discount_type;
        $discount_title = $request->discount_title;
        $discount_amount = $request->discount_amount;
        $res = false;
        $msg = "";
        $data = array();
        
        $update = Discount::where('id', $id)->update(['customer_category_id'=>$customer_category_ids, 'discount_type'=>$discount_type, 'discount_title'=>$discount_title, 'discount_amount'=>$discount_amount]);
            if($update){
                $fetch = Discount::find($id);
                
                    $categories = json_decode($fetch->customer_category_id);
                    $cat_arr = array();
                    foreach($categories as $category){
                        $cat_arr[] = CustomerCategory::find($category); 
                    }
                    $fetch->categories = $cat_arr;
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

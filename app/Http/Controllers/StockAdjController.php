<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Stockadj;
use App\Stock;
class StockAdjController extends Controller
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
        $from = $request->from;
        $to = $request->to;
        $product = $request->product;
        $reason = $request->reason;
        $stock_adj = new Stockadj;
        $data = array();
        $adj = $stock_adj->get_adjustments_with_filter($from, $to, $product, $reason);
        foreach($adj as $st){
            $stock = Stock::where(['product_id'=>$st->product_id])->first();
            if($stock){
                $st->stock = $stock->stock;
            }
            else{
                $st->stock = 'Stock does not exist';
            }
            $data[] = $st;
        }
        return $data;
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
        $validator = Validator::make($request->all(), [ 
            'product_id' => 'required', 
            'quantity' => 'required', 
            'reason' => 'required'
            ]); 
            if ($validator->fails()) { 
            
            return response()->json([
            'response_status' => false,
            'errors' => $validator->errors()
            ]); 
            
            }
            $admin_id = Auth::user()->id;
            $product_id = $request->product_id;
            $quantity = $request->quantity;
            $reason = $request->reason;
            $stock_status = false;
            $data = "";
            $stock = Stock::where(['product_id'=> $product_id])->first();
            if(!empty($stock)){
                if($stock->stock < $quantity || $stock->stock == 0){
                    $msg = "Stock of this product has id:".$product_id." is less than your ordered quantity or equal to 0";
                    $stock_status = true;
                }
            }
            else{
                $msg = "Stock for this product has id:".$product_id." is not available";
                $stock_status = true;
            }
            if($stock_status){                 
                return response(['response_status'=>false, 'message'=>$msg]);
            }
            else{
            $cut_stock = $stock->stock-$quantity;
            $stock_update = Stock::where('id', $stock->id)->update(['stock'=> $cut_stock]);    
            $adj = Stockadj::create(['admin_id'=>$admin_id, 'product_id'=>$product_id, 'quantity'=>$quantity, 'reason'=>$reason]);
                if($adj){
                    $res = true;
                    $msg = 'Product Stock Adjusted Successfully';
                    $id = $adj->id;
                    $stock_adj = new Stockadj;
                    $data = $stock_adj->get_adjustments_with_admin_product_by_id($id);
                    $data[0]->stock = $stock->stock;
                }
                else{
                    $res = false;
                    $msg = 'Product Stock does not Adjust! Try Again.';
                }
            }
            return response(['response_status'=>$res, 'message'=>$msg, 'new_record'=>$data]);
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
        $admin_id = Auth::user()->id;
        $product_id = $request->product_id;
        $quantity = $request->quantity;
        $reason = $request->reason;
        $data = "";
        $stock_status = false;
        $stock = Stock::where(['product_id'=> $product_id])->first();
        if(!empty($stock)){
            if($stock->stock < $quantity || $stock->stock == 0){
                $msg = "Stock of this product has id:".$product_id." is less than your ordered quantity or equal to 0";
                $stock_status = true;
            }
        }
        else{
            $msg = "Stock for this product has id:".$product_id." is not available";
            $stock_status = true;
        }
        if($stock_status){                 
            return response(['response_status'=>false, 'message'=>$msg]);
        }
        else{
        $cut_stock = $stock->stock-$quantity;
        $stock_update = Stock::where('id', $stock->id)->update(['stock'=> $cut_stock]);    
        $adj = Stockadj::where('id',$id)->update(['admin_id'=>$admin_id, 'product_id'=>$product_id, 'quantity'=>$quantity, 'reason'=>$reason]);
        if($adj){
            $res = true;
            $msg = 'Product Stock Adjusted Successfully';
            $stock_adj = new Stockadj;
            $data = $stock_adj->get_adjustments_with_admin_product_by_id($id);
            $data[0]->stock = $stock->stock;
        }
        else{
            $res = false;
            $msg = 'Product Stock does not Adjust! Try Again.';
        }
    }
    return $data;
    //return response(['response_status'=>$res, 'message'=>$msg, 'updated_record'=>$data]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $del = Stockadj::where('id', $id)->delete();
        if($del){
            $res = true;
            $msg = 'Adjustment Deleted Successfully';
        }
        else{
            $res = false;
            $msg = 'Adjustment not Deleted! Try Again.';
        }
        return response(['response_status'=>$res, 'message'=>$msg]);
    }
}

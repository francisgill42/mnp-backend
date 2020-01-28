<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Stock;
use App\Product;
class StockController extends Controller
{
    public function __construct()
    {
    $this->middleware('auth:api');
    }
    public function index(Request $request)
    {
        $from = $request->from;
        $to = $request->to;
        $product = $request->product;

        $stock = new Stock;
        return $stock->get_stock_with_product($from, $to, $product);
        // $data = array();
        // $stocks = Stock::orderBy('id', 'desc')->get();
        // foreach($stocks as $stock){
        //     $stock->product = Product::find($stock->product_id);
        //     $data[] = $stock;
        // }
        // return response($data);
    }

    public function store(Request $request)
    {
        $product = $request->product_id;
        $stock = $request->stock;
        $msg = "";
        $get = "";
        $pro = 0;
        $res = false;
        if(empty($product)){
            $msg = "Product field is Required";
        }
        else if(empty($stock)){
            $msg = "Stock field is Required";
        }
        else{
            $get = Stock::where(['product_id'=> $product])->get();
            if(!empty($get[0])){
                $msg = "Stock for this Product already available. If you want to change kindly update it.";
            }
            else{
                $pro = Stock::create([
                'product_id' => $product,
                'stock' => $stock
                ]);
                if($pro){
                    $get_stock = new Stock;
                    $pro = $get_stock->get_stock_with_product_by_id($pro->id);
                    $msg = "Stock for this Product Added Successfully";
                    $res = true;
                }
            }
        }
        return response(['response_status'=>$res, 'message'=>$msg, 'new_record'=>$pro]);
    }

    public function update(Request $request, $id)
    {
        $product = $request->product_id;
        $stock = $request->stock;
        $msg = "";
        $get = "";
        $pro = 0;
        $res = false;
        if(empty($product)){
            $msg = "Product field is Required";
        }
        else if(empty($stock)){
            $msg = "Stock field is Required";
        }
        else{
                $update = Stock::where('id', $id)->update(['product_id' => $product, 'stock' => $stock]);
                if($update){
                    $pro = Stock::find($id);
                    $pro->product = Product::find($product);
                    $msg = "Stock for this Product Updated Successfully";
                    $res = true;
                }
        }
        return response(['response_status'=>$res, 'message'=>$msg, 'updated_record'=>$pro]);
    }

    public function destroy($id)
    {
        $delete = Stock::where('id', $id)->delete();
        if($delete){
            $msg = "Stock Deleted Successfully";
            $res = true;
        }
        else{
            $msg = "Stock not Deleted. Try Again";
            $res = false;
        }
        return response(['response_status'=>$res, 'message'=>$msg]);
    }
}

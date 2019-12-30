<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Stock;
class StockController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
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
        $product = $request->product;
        $stock = $request->stock;
        $msg = "";
        $get = "";
        if(empty($product)){
            $msg = "Product field is Required";
        }
        else if(empty($stock)){
            $msg = "Stock field is Required";
        }
        else{
            $get = Stock::find($product);
            if($get){
                $pro = Stock::where('product_id', $product)->update(['stock'=>$stock]);
            }
            else{
                $pro = Stock::create([
                'product_id' => $product,
                'stock' => $stock
                ]);
            }

            if($pro){
                $msg = "Stock for this Product Added Successfully";
            }
        }
        return response(["msg"=>$msg, "product"=>$pro]);
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
    public function destroy($id)
    {
        //
    }
}

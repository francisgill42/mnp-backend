<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Stock;
use App\Product;
use App\Exports\UsersExport;
use App\Imports\StocksImport;
use Maatwebsite\Excel\Facades\Excel;
class ExcelController extends Controller
{
    public $flag;
    public function index(){
        return view('import');
    }
    public function import_data(Request $request){

        $data = Excel::toArray(new StocksImport, $request->file('file')); 
        $this->flag = false;
        collect(head($data))
        ->each(function ($row, $key) {

            $product = Product::find($row['product_id']);
            
            if($product){
                $this->flag = 'true';
                $get = Stock::where(["product_id"=>$row['product_id']])->first();
                if(empty($get)){
                    $add_stock = Stock::create(['product_id'=>$row['product_id'], 'stock'=>$row['stock']]);
                }
                else{
                    $stock_id = $get->id;
                    $quantity = $get->stock+$row['stock'];
                    $add_stock = Stock::where('id',$stock_id)->update(['stock'=>$quantity]);
                }
            }
        });
        
        // Excel::import(new StocksImport,$request->file('file'));
        //return redirect('import')->with('msg', 'Stock Added Successfully');
        if($this->flag){
           return response(['msg'=>'Stock Added Successfully']);
        }
    }
}

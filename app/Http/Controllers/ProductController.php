<?php

namespace App\Http\Controllers;

use App\Product;
use App\Stock;
use App\Discount;
use App\Discount_Product;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Str;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use Auth;

class ProductController extends Controller
{
     public function __construct()
    {
        $this->middleware('auth:api');
    }
    public function index()
    {
        $all_products = array();
      $products = (Auth::user()->master) ? Product::all() :
            Product::where('IsActive',1)->get();
      
      $role_id = Auth::user()->role_id;
      $user_id = Auth::user()->id;
      $customer_category_id = Auth::user()->customer_category_id;

        foreach($products as $product){
            $stocks = Stock::where('product_id',$product->id)->get();
            $product->stock = $stocks;
            if(!empty($stocks)){
                foreach($stocks as $stock){
                    $product->stock = $stock->stock;
                }
            }

        if($role_id == 1){
            $product->discount = null;
            $discount = Discount::where(['customer_category_id'=>$customer_category_id])->get();
            foreach($discount as $disc){
                $pro_discount = Discount_Product::where(['discount_id'=>$disc->id,'product_id'=>$product->id])->first();
                if($pro_discount){
                    $disc->discount_amount = $pro_discount->amount;
                    $product->discount = $disc;
                }
                else{
                    $product->discount = null;
                }
                
            }
        }
            $all_products[] = $product;
        }
        return $all_products;
             // 'id as product_id',
            // 'product_title',
            // 'product_description',	
            // 'product_price',
            // 'product_image',
            // 'expiry_date as product_expiry'
    }

    public function store(Request $request)
    {
        $product_image = 'no_image.png';
        if($request->hasFile('product_image')){
           $product_image = $request->product_image->getClientOriginalName();
           $request->product_image->move(public_path('uploads/product_images/'),$product_image);
           $product_image = asset('public/uploads/product_images/' . $product_image);
        }
            $data = Product::create($this->fields($request,$product_image));

            $retVal = ($data) 
            ?  ['response_status' => true, 'message' => 'record has been inserted', 'new_record' => Product::find($data->id)]
            :  ['response_status' => false,'message' => 'record cannot create'];

            return response()->json($retVal);
    }

     public function update(Request $request,$id){
        // return $request->all();
        if($request->hasFile('product_image')){
            $product_image = $request->product_image->getClientOriginalName();
            $request->product_image->move(public_path('uploads/product_images/'),$product_image);
            $product_image = asset('public/uploads/product_images/' . $product_image);
        }
        else{
            $product_image = $request->product_image;
        }


         $updated = DB::table('products')->where('id',$id)->update($this->fields($request,$product_image));

         $data = DB::table('products')->where('id',$id)->first();
        // $data = DB::table('categories')
        // ->join('products', 'categories.id', '=', 'products.product_category_id')
        // ->select('categories.id as product_category_id','categories.category','products.*')
        // ->where('products.id',$id)
        // ->first();

        if ($updated) {

         return response()->json([
            'response_status' => true,
            'message' => 'record has been updated', 
            'updated_record' => $data
         ]);

    }
    else{
        return response()->json([
            'response_status' => false,
            'message' => 'record cannot update', 
     ]);
    }

}

    public function destroy($id){
     //   return $id;
        return (Product::find($id)->delete()) 
                ? [ 'response_status' => true,  'message' => 'record has been deleted' ] 
                : [ 'response_status' => false, 'message' => 'record cannot delete' ];
            

    }

    public function fields($request,$product_image)
    {
        return [
             
            'product_title' => $request->product_title,
            'legacy_code_sku' => $request->legacy_code_sku,
            'product_price' => $request->product_price,
            'product_image' => $product_image,
            'unit_of_measurement' => $request->unit_of_measurement,
            'product_description' => $request->product_description,
            'unit_in_case' => $request->unit_in_case,
            'weight' => $request->weight,
            'expiry_date' => $request->expiry_date,
            'IsActive' => $request->IsActive,
            'group_code' => $request->group_code,
            'group_description' =>$request->group_description,
            'pack_code' =>$request->pack_code,
            'pack_description' =>$request->pack_description,
            'created_at' => now(),
            'updated_at' => now() 
        

        ];
    }
    public function product_form(){
        return view('add_product');
    }
    public function add_product(Request $request){
   
        $validate = $this->validate($request ,[
            'product_title'      => 'required',
            'sku_code'      => 'required',
            'product_price'  => 'required',
            'expiry_date' => 'required',
            'unit_of_measurement' => 'required',
            'unit_in_case' => 'required',
            'weight' => 'required',
            'description' => 'required',
            'file' => 'required',
            'group_code' => 'required',
            'pack_code' => 'required',      
        ]);


        if($request->status){
            $status = 1;
        }
        else{
            $status = 0;
        }

        $uploads = $request->file->move(public_path('uploads/product_images'), $request->file->getClientOriginalName());
        $product = Product::create([
            'legacy_code_sku'       => $request->sku_code,
            'product_title'         => $request->product_title,
            'product_price'         => $request->product_price,
            'product_image'         => $request->file->getClientOriginalName(),
            'unit_of_measurement'   => $request->unit_of_measurement,
            'product_description'   => $request->description,
            'unit_in_case'          => $request->unit_in_case,
            'weight'                => $request->weight,
            'expiry_date'           => $request->expiry_date,
            'IsActive'              => $status,
            'group_code'            => $request->group_code,
            'group_description'     => $request->group_description,
            'pack_code'             => $request->pack_code,
            'pack_description'      => $request->pack_description
       ]);
        
       if($product){
            return redirect('/')->with('msg', 'Your Product Added Successfully!');
        }
        else{
            return redirect('/')->with('msg', 'Product not Added. Try Again.....');
        }
    }
}

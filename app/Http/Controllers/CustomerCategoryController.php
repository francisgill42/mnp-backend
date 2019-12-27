<?php

namespace App\Http\Controllers;

use App\CustomerCategory;
use Illuminate\Http\Request;

class CustomerCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return CustomerCategory::all('id','customer_category_name');
    }

    public function store(Request $request)
    {
        $id = \DB::table('customer_categories')->insertGetId(['customer_category_name' => $request->customer_category_name]);
        if ($id) {
         return response()->json([
            'response_status' => true,
            'message' => 'record has been inserted', 
            'new_record' => CustomerCategory::find($id)
     ]);

    }
    else{
        return response()->json([
            'response_status' => false,
            'message' => 'record cannot create', 
     ]);
    }

    }

  
    public function update(Request $request,$id)
    {
    
    $updated = \DB::table('customer_categories')->where('id',$id)->update(['customer_category_name' => $request->customer_category_name]);

        if ($updated) {

         return response()->json([
            'response_status' => true,
            'message' => 'record has been updated', 
            'updated_record' => CustomerCategory::find($id)
         ]);

    }
    else{
        return response()->json([
            'response_status' => false,
            'message' => 'record cannot update', 
     ]);
    }
    }

    public function destroy($id)
    {

        return (CustomerCategory::find($id)->delete()) 
        ? [ 'response_status' => true, 'message' => 'user has been deleted' ] 
        : [ 'response_status' => false, 'message' => 'user cannot delete' ];
    }
}

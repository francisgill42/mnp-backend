<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api')->except('register','login','logout');
    }

    public function index()
    {
        return Category::all('id','category');
    }


    public function store(Request $request)
    {
        $id = \DB::table('categories')->insertGetId(['category' => $request->category]);
        if ($id) {
         return response()->json([
            'response_status' => true,
            'message' => 'record has been inserted', 
            'new_record' => Category::find($id)
     ]);

    }
    else{
        return response()->json([
            'response_status' => false,
            'message' => 'record cannot create', 
     ]);
    }

      
}

  


 public function update(Request $request, Category $category){

         $updated = \DB::table('categories')->where('id',$category->id)->update(['category' => $request->category]);

        if ($updated) {

         return response()->json([
            'response_status' => true,
            'message' => 'record has been updated', 
            'updated_record' => Category::find($category->id)->select('id','category')->get()
         ]);

    }
    else{
        return response()->json([
            'response_status' => false,
            'message' => 'record cannot update', 
     ]);
    }

}

    public function destroy(Category $category){


        return (Category::find($category->id)->delete()) 
                ? [ 'response_status' => true,  'message' => 'record has been deleted' ] 
                : [ 'response_status' => false, 'message' => 'record cannot delete'];
            

    }


}

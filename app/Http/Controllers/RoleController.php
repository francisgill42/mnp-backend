<?php

namespace App\Http\Controllers;

use App\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')->except('register','login','logout');
	}


    public function index()
    {
        return Role::all();
    }

    public function store(Request $request)
    {
        $id = \DB::table('roles')->insertGetId(['role' => $request->role]);
        if ($id) {
        return response()->json([
        'response_status' => true,
        'message' => 'record has been inserted', 
        'new_record' => Role::find($id)
        ]);

        }
        else{
        return response()->json([
        'response_status' => false,
        'message' => 'record cannot create', 
        ]);
        }

      
}


    public function update(Request $request, Role $role){

         $updated = \DB::table('roles')->where('id',$role->id)->update(['role' => $request->role]);

        if ($updated) {

         return response()->json([
            'response_status' => true,
            'message' => 'record has been updated', 
            'updated_record' => Role::find($role->id)
         ]);

    }
    else{
        return response()->json([
            'response_status' => false,
            'message' => 'record cannot update', 
     ]);
    }

}

    public function destroy(Role $role){


        return (Role::find($role->id)->delete()) 
                ? [ 'response_status' => true,  'message' => 'record has been deleted' ] 
                : [ 'response_status' => false, 'message' => 'record cannot delete' ];
            

    }
}

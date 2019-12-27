<?php

namespace App\Http\Controllers;

use App\State;
use App\City;
use Illuminate\Http\Request;

class StateController extends Controller
{
    
    public function index()
    {
        return State::all(); 
    }

    public function show($id){
        return City::where('state_id',$id)->select('id as c_id','city_name as c_name')->get();
    }
  

    public function store(Request $request)
    {
         $id = \DB::table('states')->insertGetId(['state_name' => $request->state_name]);
        if ($id) {
         return response()->json([
            'response_status' => true,
            'message' => 'record has been inserted', 
            'new_record' => State::find($id)
     ]);

    }
    else{
        return response()->json([
            'response_status' => false,
            'message' => 'record cannot create', 
     ]);
    }

    }

   
    public function update(Request $request, State $state)
    {
    $updated = \DB::table('states')->where('id',$state->id)->update(['state_name' => $request->state_name]);

        if ($updated) {

         return response()->json([
            'response_status' => true,
            'message' => 'record has been updated', 
            'updated_record' => State::find($state->id)
         ]);

    }
    else{
        return response()->json([
            'response_status' => false,
            'message' => 'record cannot update', 
     ]);
    }
    }

 
    public function destroy(State $state)
    {

        return (State::find($state->id)->delete()) 
                ? [ 'response_status' => true, 'message' => 'user has been deleted' ] 
                : [ 'response_status' => false, 'message' => 'user cannot delete' ];
        
    }
}

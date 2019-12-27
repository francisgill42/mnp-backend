<?php

namespace App\Http\Controllers;

use App\Status;
use Illuminate\Http\Request;

class StatusController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')->except('register','login','logout');
    }
    
    public function index()
    {
        return Status::all();
    }

   

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
         $id = \DB::table('statuses')->insertGetId(['status' => $request->status]);
        if ($id) {
         return response()->json([
            'response_status' => true,
            'message' => 'record has been inserted', 
            'new_record' => Status::find($id)
     ]);

    }
    else{
        return response()->json([
            'response_status' => false,
            'message' => 'record cannot create', 
     ]);
    }

    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Status  $status
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Status $status)
    {
    $updated = \DB::table('statuses')->where('id',$status->id)->update(['status' => $request->status]);

        if ($updated) {

         return response()->json([
            'response_status' => true,
            'message' => 'record has been updated', 
            'updated_record' => Status::find($status->id)
         ]);

    }
    else{
        return response()->json([
            'response_status' => false,
            'message' => 'record cannot update', 
     ]);
    }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Status  $status
     * @return \Illuminate\Http\Response
     */
    public function destroy(Status $status)
    {
         return (Status::find($status->id)->delete()) 
                ? [ 'response_status' => true,  'message' => 'record has been deleted' ] 
                : [ 'response_status' => false, 'message' => 'record cannot delete' ];
    }
}

<?php

namespace App\Http\Controllers;

use App\Feedback;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Feedback::get_feedbacks_with_users();
    }

  
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $success = Feedback::create([
            'feedback' => $request->feedback, 
            'user_id' => $request->user_id
        ]);

        return ($success) ? 
        response()->json([
        'response_status' => true,
        'message' => 'record has been inserted', 
        'new_record' => $success
        ]) : 404 ;
    }


    public function destroy($id)
    {
        $feedback = Feedback::find($id);
        if($feedback){
            $delete = Feedback::where('id',$id)->delete();
            if($delete){
                return response(['response_status' => true,'message' => 'record has been deleted']);
            }
            else{
                return response(['response_status' => false,'message' => 'record not deleted']);
            }
        }
    }
}

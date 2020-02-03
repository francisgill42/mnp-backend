<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Maintenanceuser;
use App\Status;
class MaintenanceUserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    public function index(Request $request){

        $from = $request->from;
        $to = $request->to;
        $status = $request->status;
        $customer = $request->customer;
        $scheduling = $request->scheduling;
        $type = $request->type;

        $customer_id = Auth::user()->id;
        $role_id = Auth::user()->role_id;
        $arr = array();
        $requests = new Maintenanceuser;
        if($role_id == 1){
            $get_requests = $requests->get_all_requests_by_customer($customer_id);
        }
        else if($role_id == 4){
            $get_requests = $requests->get_all_requests_by_maintenanceuser();
        }
        else{
            $get_requests = $requests->get_all_requests($from, $to, $status, $customer, $scheduling, $type);
        }

        foreach($get_requests as $request){
            $request_date = explode(" ",$request->created_at);
            $date = date("d-m-Y", strtotime($request_date[0]));
            $time = date("h:i", strtotime($request_date[1]));
            $request->date = $date;
            $request->time = $time;
            $arr[] = $request;
        }
        
        return $arr;
    }
    public function store(Request $request){
        $customer_id = $request->customer_id;
        $message = $request->message;
        $image = $request->image;
        $status = 1;
        $type = $request->type; // only two types occured (maintenance and repair)
        $filename = null;
        if($image){
        $filename = 'req_'.uniqid().'.jpg';
        
        $ifp = fopen( public_path('uploads/requests/'.$filename), 'wb' ); 
        //$data = explode( ',', $image);
        fwrite( $ifp, base64_decode( $image));
        fclose( $ifp );
        $filename = asset('uploads/requests/'.$filename); 
        }
        $insert = Maintenanceuser::create([
            'customer_id'=> $customer_id,
            'type' => $type,
            'message' => $message,
            'image' => $filename,
            'status' => $status
        ]);
        if($insert){
            $res = true;
            $msg = "Request Submitted Successfully.";
        }
        else{
            $res = false;
            $msg = "Request not Submitted. Try Again.";
        }
        return response(['response_status'=>$res, 'message'=>$msg, 'new_record'=>$insert]);
    }

    public function request_status_change(Request $request)
    {
        $request_id = $request->request_id;
        $status_id = $request->status_id;
        $scheduling = $request->scheduling;
        $arr = array();

            if(isset($scheduling)){
                //$status_id = 2;
                $update = Maintenanceuser::where('id', $request_id)->update(['status'=>$status_id, 'schedule'=>$scheduling]);
            }
            else{
                $update = Maintenanceuser::where('id', $request_id)->update(['status'=>$status_id]);
            }
            
            if($update){
                $req = Maintenanceuser::find($request_id);
                $status = Status::find($status_id);
                $arr['status_id'] = $status->id;
                $arr['keyword'] = $status->keyword;
                $arr['schedule'] = $req->schedule;
                $arr['request'] = $req;
                $res = true;
                $msg = "Status Changed Successfully";
            }
            else{
                $res = false;
                $msg = "Status not Changed. Try Again";
            }
        return response(['response_status'=>$res, 'message'=>$msg, 'updated_record'=>$arr]);
    }
    public function show($id){
        $delete = Maintenanceuser::where('id', $id)->delete();
        if($delete){
            $res = true;
            $msg = "Request Deleted Successfully";
        }
        else{
            $res = false;
            $msg = "Request not Deleted. Try Again";
        }
        return response(['response_status'=>$res, 'message'=>$msg]);
    }

    public function request_update(Request $request){
        $message = $request->message;
        $status = $request->status;
        $type = $request->type;        
        $id = $request->id;

        $image = null;
        if($request->hasFile('image')){
           //$image = $request->image->getClientOriginalName();
           $image = 'req_'.uniqid().'.jpg';
           $request->image->move(public_path('uploads/requests/'),$image);
           $image = asset('uploads/requests/'.$image); 
        }
        $update = Maintenanceuser::where('id', $id)->update(['type'=>$type, 'message'=>$message, 'status'=>$status, 'image'=>$image]);
        if($update){
            $res = true;
            $msg = "Request Updated Successfully";
        }
        else{
            $res = false;
            $msg = "Request not Updated. Try Again";
        }
        return response(['response_status'=>$res, 'message'=>$msg]);
    }
}

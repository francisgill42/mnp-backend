<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class Maintenanceuser extends Model
{
    protected $fillable = ['customer_id', 'type', 'message', 'image', 'status', 'schedule'];

    public function get_all_requests($from, $to, $status, $customer, $scheduling, $type){
        $args = array();
        $now = date('Y-m-d', strtotime('+1 day'));
        $bw = array('', $now);
        if($from && $to){
            $to = date('Y-m-d', strtotime($to.' +1 day'));
            $bw = array($from, $to);
        }
        if($status){
            $args[] = array('maintenanceusers.status', '=', $status);
         }
        if($customer){
            $args[] = array('maintenanceusers.customer_id', '=', $customer);
        }
        if($scheduling){
            $args[] = array('maintenanceusers.schedule', '=', $scheduling);
        }
        if($type){
            $args[] = array('maintenanceusers.type', '=', $type);
        }
        $requests = DB::table('maintenanceusers')
            ->join('users', 'maintenanceusers.customer_id', '=', 'users.id')
            ->join('statuses', 'maintenanceusers.status', '=', 'statuses.id')
            ->select('maintenanceusers.*', 'statuses.id as status_id', 'statuses.status as status_text', 'statuses.keyword', 'users.id as user_id', 'users.company_name', 'users.role_id', 'users.customer_category_id', 'users.master', 'users.email', 'users.phone_number', 'users.mobile_number', 'users.ntn', 'users.address', 'users.state_id', 'users.city_id', 'users.IsActive')
            ->where($args)
            ->whereBetween('maintenanceusers.created_at', $bw)
            ->orderBy('maintenanceusers.id', 'desc')
            ->get();

            return $requests;
    }

    public function get_all_requests_by_customer($customer_id){
        $requests = DB::table('maintenanceusers')
            ->join('users', 'maintenanceusers.customer_id', '=', 'users.id')
            ->join('statuses', 'maintenanceusers.status', '=', 'statuses.id')
            ->select('maintenanceusers.*', 'statuses.status as status_text', 'statuses.keyword', 'users.id as user_id', 'users.company_name', 'users.role_id', 'users.customer_category_id', 'users.master', 'users.email', 'users.phone_number', 'users.mobile_number', 'users.ntn', 'users.address', 'users.state_id', 'users.city_id', 'users.IsActive')
            ->where('maintenanceusers.customer_id', '=', $customer_id)
            ->whereNotIn('statuses.status', ['closed'])
            ->orderBy('maintenanceusers.id', 'desc')
            ->get();

            return $requests;
    }

    public function get_all_requests_by_maintenanceuser(){
        $requests = DB::table('maintenanceusers')
            ->join('users', 'maintenanceusers.customer_id', '=', 'users.id')
            ->join('statuses', 'maintenanceusers.status', '=', 'statuses.id')
            ->select('maintenanceusers.*', 'statuses.status as status_text', 'statuses.keyword', 'users.id as user_id', 'users.company_name', 'users.role_id', 'users.customer_category_id', 'users.master', 'users.email', 'users.phone_number', 'users.mobile_number', 'users.ntn', 'users.address', 'users.state_id', 'users.city_id', 'users.IsActive')
            ->whereIn('statuses.status', ['processing','delivered'])
            ->orderBy('maintenanceusers.id', 'desc')
            ->get();

            return $requests;
    }
}

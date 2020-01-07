<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class Maintenanceuser extends Model
{
    protected $fillable = ['customer_id', 'type', 'message', 'image', 'status'];

    public function get_all_requests(){
        $requests = DB::table('maintenanceusers')
            ->join('users', 'maintenanceusers.customer_id', '=', 'users.id')
            ->join('statuses', 'maintenanceusers.status', '=', 'statuses.id')
            ->select('maintenanceusers.*', 'statuses.status as status_text', 'users.id as user_id', 'users.name', 'users.role_id', 'users.customer_category_id', 'users.master', 'users.email', 'users.phone_number', 'users.mobile_number', 'users.ntn', 'users.address', 'users.state_id', 'users.city_id', 'users.IsActive')
            ->whereNotIn('statuses.status', ['closed'])
            ->orderBy('maintenanceusers.id', 'desc')
            ->get();

            return $requests;
    }

    public function get_all_requests_by_customer($customer_id){
        $requests = DB::table('maintenanceusers')
            ->join('users', 'maintenanceusers.customer_id', '=', 'users.id')
            ->join('statuses', 'maintenanceusers.status', '=', 'statuses.id')
            ->select('maintenanceusers.*', 'statuses.status as status_text', 'users.id as user_id', 'users.name', 'users.role_id', 'users.customer_category_id', 'users.master', 'users.email', 'users.phone_number', 'users.mobile_number', 'users.ntn', 'users.address', 'users.state_id', 'users.city_id', 'users.IsActive')
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
            ->select('maintenanceusers.*', 'statuses.status as status_text', 'users.id as user_id', 'users.name', 'users.role_id', 'users.customer_category_id', 'users.master', 'users.email', 'users.phone_number', 'users.mobile_number', 'users.ntn', 'users.address', 'users.state_id', 'users.city_id', 'users.IsActive')
            ->whereIn('statuses.status', ['processing','delivered'])
            ->orderBy('maintenanceusers.id', 'desc')
            ->get();

            return $requests;
    }
}

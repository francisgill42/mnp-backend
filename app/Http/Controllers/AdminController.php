<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Role;
use App\Order;
use App\Product;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Exports\UsersExport;
use Maatwebsite\Excel\Facades\Excel;
class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')->except('register','login','logout');
	}


    public function login(Request $request){ 

        if(Auth::attempt(['email' => $request->email, 'password' => $request->password, 'master' => 1])){ 
		$user = Auth::user(); 
		if($user->IsActive == 1){

			$user->user_type = Role::find($user->role_id)->role ?? '';

			return response()->json([
				'token' => $user->createToken('myApp')->accessToken,
				'user' => $user], 200); 
	
		}
		else{
			return response()->json(['error'=>'You account is deactivated. Please contact to admin'], 422); 
		}

		} 
		else{ 
		return response()->json(['error'=>'email or password is incorrect'], 422); 
		} 
	}
	public function counters(){
	/*------------- Counters -------------------- */	
		$customers = User::where(['role_id'=>1])->count();
		$orders = Order::count();
		$products = Product::count();
		$count = array();
		$count['customers'] = $customers;
		$count['orders'] = $orders;
		$count['products'] = $products;

	/*------------ Today's orders ---------------- */
		$orders = new Order;
        $todays = $orders->order_by_today();
        $datadaily = array();
        $datadaily['orders_count'] = count($todays);
        $daily = 0;
        foreach($todays as $orddaily){
            $daily += $orddaily->order_total;
        }
		$datadaily['orders_amount'] = round($daily);
		$count['daily_orders'] = $datadaily;

	/*------------ Weekly orders ---------------- */	
		$weeks = $orders->order_by_week();
        $dataweek = array();
        $dataweek['orders_count'] = count($weeks);
        $week = 0;
        foreach($weeks as $ordweek){
            $week += $ordweek->order_total;
        }
        $dataweek['orders_amount'] = round($week);
		$count['weekly_orders'] = $dataweek;

	/*------------ Monthly orders ---------------- */	
		$months = $orders->order_by_month();
        $datamonth = array();
        $datamonth['orders_count'] = count($months);
        $month = 0;
        foreach($months as $ordmonth){
            $month += $ordmonth->order_total;
        }
		$datamonth['orders_amount'] = round($month);
		$count['monthly_orders'] = $datamonth;
		
		return response($count);
	}
	
}

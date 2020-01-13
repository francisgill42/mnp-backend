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
		$customers = User::where(['role_id'=>1])->count();
		$orders = Order::count();
		$products = Product::count();
		$count = array();
		$count['customers'] = $customers;
		$count['orders'] = $orders;
		$count['products'] = $products;

		return response($count);
	}
}

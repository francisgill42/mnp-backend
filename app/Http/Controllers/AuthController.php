<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Role;
use Illuminate\Support\Facades\Auth;
use Validator;
class AuthController extends Controller
{
	public function __construct()
    {
        $this->middleware('auth:api')->except('register','login','logout');
	}


public function login(Request $request){ 

if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){ 
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




public function register(Request $request) { 
	$validator = Validator::make($request->all(), [ 
		'name' => 'required', 
		'email' => 'required|email|unique:users', 
		'password' => 'required', 
		'confirm_password' => 'required|same:password'
	]); 
	if ($validator->fails()) { 

		return response()->json([
		'success' => false,
		'errors' => $validator->errors()
	
	]); 

	}

	$input = $request->all(); 
	$input['master'] = 1;
	$input['password'] = bcrypt($input['password']); 
	$user = User::create($input); 
	$token = $user->createToken('myApp')->accessToken; 

	return response()->json([
		'success' => true,
		'data' => $user,
		'token' => $token
	],200); 

} 

	public function me(Request $request){
		return response()->json([
		'user' => Auth::user()		
	],200); 
	}


}





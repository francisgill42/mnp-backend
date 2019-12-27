<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\User;

class UserController extends Controller
{
public function __construct()
{
$this->middleware('auth:api')->except('register','login','logout');
}

public function index()
{
return response()->json(User::get_users_with_roles()); 
}


public function store(Request $request)
{
$validator = Validator::make($request->all(), [ 
'name' => 'required', 
'email' => 'required|email|unique:users', 
'password' => 'required', 
]); 
if ($validator->fails()) { 

return response()->json([
'response_status' => false,
'errors' => $validator->errors()


]); 

}

$input = $request->all(); 
$input['password'] = bcrypt($input['password']); 
$input['master'] = 0;
$user = User::create($input); 

$user =  \DB::table('users')
->join('roles', 'users.role_id', '=', 'roles.id')
->select('users.*','roles.id as role_id','roles.role')
->where('users.id',$user->id)
->first();

return response()->json([
'response_status' => true,
'message' => 'user has been created',
'new_record' => $user,        
],200); 

}

public function show($id){}

public function edit($id){}


public function update(Request $request, User $user){

$updated = \DB::table('users')->where('id',$user->id)->update([
'name' => $request->name,
'email' => $request->email,
'role_id' => $request->role_id
]);

if ($updated) {

$user =  \DB::table('users')
->join('roles', 'users.role_id', '=', 'roles.id')
->select('users.*','roles.id as role_id','roles.role')
->where('users.id',$user->id)
->first();

return response()->json([
'response_status' => true,
'message' => 'user has been updated', 
'updated_record' => $user
]);

}

else{
return response()->json([
'response_status' => false,
'message' => 'user cannot update', 
]); 
}


}


    public function destroy(User $user){

        return (User::find($user->id)->delete()) 
                ? [ 'response_status' => true, 'message' => 'user has been deleted' ] 
                : [ 'response_status' => false, 'message' => 'user cannot delete' ];
        
    }

}

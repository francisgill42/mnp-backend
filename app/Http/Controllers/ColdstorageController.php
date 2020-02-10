<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Coldstorage;

class ColdstorageController extends Controller
{
    public function __construct()
{
$this->middleware('auth:api');
}

public function index()
{
    return Coldstorage::get_coldstorage_with_additionals(); 
}


public function store(Request $request)
{
// return $request->all();
$validator = Validator::make($request->all(), [ 
'email' => 'required|email|unique:users', 
'password' => 'min:6', 
]); 
if ($validator->fails()) { 

return response()->json([
'response_status' => false,
'errors' => $validator->errors()
]); 

}


$arr = [
'address' => $request->address,
'city_id' => $request->city_id,
'customer_category_id' => null,
'email' => $request->email, //"francisgill1000@gmail.com"
'mobile_number' => $request->mobile_number,
'name' => $request->name,
'password' => bcrypt($request->password),
'ntn' => null,
'phone_number' => $request->phone_number,
'state_id' => $request->state_id,
'IsActive' => $request->IsActive,
'master' => 0,
'role_id' => 2
];

$user = Coldstorage::create($arr); 

$user =  \DB::table('users')
->join('states', 'users.state_id', '=', 'states.id')
->join('cities', 'users.city_id', '=', 'cities.id')
->select(
'users.*',
'states.state_name',
'cities.city_name')

->where('users.id',$user->id)
->first();

return response()->json([
'response_status' => true,
'message' => 'user has been created',
'new_record' => $user,        
],200); 

}

public function show($id){
return $id;
}



public function update(Request $request, $id){




$validator = Validator::make($request->all(), [ 
'password' => 'min:6', 
]); 
if ($validator->fails()) { 

return response()->json([
'response_status' => false,
'errors' => $validator->errors()
]); 

}

$arr = [
    'address' => $request->address,
    'city_id' => $request->cities_by_state_id,
    'customer_category_id' => null,
    'email' => $request->email,
    'mobile_number' => $request->mobile_number,
    'name' => $request->name,
    'ntn' => null,
    'phone_number' => $request->phone_number,
    'IsActive' => $request->IsActive,
    'state_id' => $request->state_id,
    
];

$updated = \DB::table('users')->where('id',$id)->update($arr);



if ($updated) {

$user =  \DB::table('users')
->join('states', 'users.state_id', '=', 'states.id')
->join('cities', 'users.city_id', '=', 'cities.id')
->select(
    'users.*',
    'states.id as state_id','states.state_name',
    'cities.id as city_id','cities.city_name')
->where('users.id',$id)
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


public function destroy($id){

return (Coldstorage::find($id)->delete()) 
? [ 'response_status' => true, 'message' => 'user has been deleted' ] 
: [ 'response_status' => false, 'message' => 'user cannot delete' ];

}

public function update_password(Request $request,$id){


    $updated = \DB::table('users')->where('id',$id)->update([
        'password' => bcrypt($request->pwd)
        ]);

    if($updated){

        return response()->json([
        'response_status' => true,
        'message' => 'Password has been updated', 
        ]);

    }
    else{
        
        return response()->json([
        'response_status' => false,
        'message' => 'Password can not updated', 
        ]);
    }


}

}

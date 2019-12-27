<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use DB;

class User extends Authenticatable
{
    use HasApiTokens,Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    
    public static function get_users_with_roles()
    {
       return DB::table('users')
       ->join('roles', 'users.role_id', '=', 'roles.id')
       ->select('users.*','roles.id as role_id','roles.role')
       ->get();
    }
    protected $fillable = [
        'name', 'email', 'password','master','role_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

}

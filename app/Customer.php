<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use DB;

class Customer extends Authenticatable
{
    use HasApiTokens,Notifiable;
    
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    
    public static function get_customers_with_additionals()
    {
       return DB::table('users')
       ->join('customer_categories', 'users.customer_category_id', '=', 'customer_categories.id')
       ->join('states', 'users.state_id', '=', 'states.id')
       ->join('cities', 'users.city_id', '=', 'cities.id')
       ->select(
           'users.*',
           'customer_categories.id as customer_category_id','customer_categories.customer_category_name',
           'states.id as state_id','states.state_name',
           'cities.id as city_id','cities.city_name')
           ->where('users.role_id', '=', 1)
           ->orderBy('users.id','desc')
          ->get();
    }
    protected $fillable = [
        'name', 'email', 'password','master','role_id','ntn','customer_category_id',
        'group','phone_number','mobile_number','address','state_id','city_id','IsActive','company_name','trade_name','contact_person_name','delivery_from','delivery_to','payment_type'
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

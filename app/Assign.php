<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class Assign extends Model
{
    protected $fillable = ['driver_id', 'order_id'];

    public function get_assigned_orders_to_driver($driver_id, $order_id){
        $record = DB::table('assigns')
            ->where(['driver_id' => $driver_id, 'order_id' => $order_id])
            ->get();

            return $record;
    }
}

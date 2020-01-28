<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Feedback extends Model
{
	protected $table = 'feedbacks';
    protected $fillable = ['feedback','user_id'];

         public static function get_feedbacks_with_users(){

	       return DB::table('feedbacks')
	       ->join('users', 'users.id', '=', 'feedbacks.user_id')
		   ->select('feedbacks.id as feedback_id','feedbacks.feedback','users.id as user_id','users.company_name')
	       ->get();
         
		}
}



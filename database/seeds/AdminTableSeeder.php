<?php

use Illuminate\Database\Seeder;

class AdminTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        $user = \App\User::create([
        	'name' => 'master',
        	'email' => 'master@erp.com',
        	'password' => bcrypt('secret'),
            'master' => 1,
            'IsActive' => 1
        ]);
        $user->createToken('myApp')->accessToken; 
    }
}

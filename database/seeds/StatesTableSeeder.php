<?php

use Illuminate\Database\Seeder;

class StatesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $states = ['Abu Dhabi','Ajman','Dubai','Al Fujayrah','Ras al-Khaymah','Al Sharjah','Umm al-Qaywayn'];
        
        foreach($states as $state){
            \DB::table('states')->insert([
                'state_name' => $state,
                'created_at' => now(),
                'updated_at' => now()
                ]);            
        }

    }
}

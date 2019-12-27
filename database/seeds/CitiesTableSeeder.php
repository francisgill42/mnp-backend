<?php

use Illuminate\Database\Seeder;

class CitiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
    
        $cities = ['Abu Dhabi','Al Ain','Ar Ruways','Muzayri`'];
        
        foreach($cities as $city_name){
            \DB::table('cities')->insert([
                'city_name' => $city_name,
                'state_id' => 1,
                'created_at' => now(),
                'updated_at' => now()
                ]);            
        }

        $cities = ['Ajman'];
        
        foreach($cities as $city_name){
            \DB::table('cities')->insert([
                'city_name' => $city_name,
                'state_id' => 2,
                'created_at' => now(),
                'updated_at' => now()
                ]);            
        }

        $cities = ['Dubai'];
        
        foreach($cities as $city_name){
            \DB::table('cities')->insert([
                'city_name' => $city_name,
                'state_id' => 3,
                'created_at' => now(),
                'updated_at' => now()
                ]);            
        }

        
        $cities = ['Al Fujayrah','Dibba Al-Fujairah','Dibba Al-Hisn'];
        
        foreach($cities as $city_name){
            \DB::table('cities')->insert([
                'city_name' => $city_name,
                'state_id' => 4,
                'created_at' => now(),
                'updated_at' => now()
                ]);            
        }

        
        $cities = ['Ras al-Khaimah'];
        
        foreach($cities as $city_name){
            \DB::table('cities')->insert([
                'city_name' => $city_name,
                'state_id' => 5,
                'created_at' => now(),
                'updated_at' => now()
                ]);            
        }

        
        $cities = ['Adh Dhayd','Khawr Fakkan','Sharjah'];
        
        foreach($cities as $city_name){
            \DB::table('cities')->insert([
                'city_name' => $city_name,
                'state_id' => 6,
                'created_at' => now(),
                'updated_at' => now()
                ]);            
        }

        $cities = ['Umm al Qaywayn'];
        
        foreach($cities as $city_name){
            \DB::table('cities')->insert([
                'city_name' => $city_name,
                'state_id' => 7,
                'created_at' => now(),
                'updated_at' => now()
                ]);            
        }

    }
}

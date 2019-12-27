<?php

use Illuminate\Database\Seeder;

class StatussesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $arr = ['pending','processing','loaded','on the way','delivered','cancelled'];
        foreach($arr as $a){
            \DB::table('statuses')->insert(['status' => $a]);
        }

    }
}

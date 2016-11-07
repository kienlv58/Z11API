<?php

use Illuminate\Database\Seeder;

class ProfilesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('profiles')->truncate();
        DB::table('profiles')->insert([
            'user_id'=>1,
            'image'=>'aaaa',
            'gender'=>'male'
        ]);
    }
}

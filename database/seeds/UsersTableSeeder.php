<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table("users")->truncate();
        DB::table("users")->insert([
            'grant_type'=>'password',
            'name'=>'kienlv',
            'email'=>'kienlv58@gmail.com',
            'password'=>encrypt('02101994'),
            'token_social'=>null,
            'active'=>0
        ]);
    }
}

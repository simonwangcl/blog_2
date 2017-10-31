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
        for ($i = 0; $i < 50; $i++) {
            $salt = str_random('32');
            DB::table('users')->insert([
                'name' => str_random(6),
                'email' => str_random(10) . '@qq.com',
                'phone' => '130' . rand(00000001, 99999999),
                'image' => str_random(30),
                'salt' => $salt,
                'password' => md5($salt . '123456')
            ]);
        }
    }
}

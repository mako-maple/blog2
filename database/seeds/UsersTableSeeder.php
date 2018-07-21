<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'loginid' => 'maple_nobu',
                'name' => 'メイプル　ノブ',
                'password' => Hash::make('password'),
            ],
        ]);
        factory(App\User::class, 100)->create();
    }
}

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
                'loginid' => 'maple_admin',
                'name' => 'メイプル　管理者',
                'password' => Hash::make('admin'),
                'role' => 5,
            ],
            [
                'loginid' => 'maple_user',
                'name' => 'メイプル　一般ユーザ',
                'password' => Hash::make('user'),
                'role' => 10,
            ],
        ]);
        factory(App\User::class, 100)->create();
    }
}

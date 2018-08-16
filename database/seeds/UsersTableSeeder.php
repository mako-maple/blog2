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
                'loginid' => 'root',
                'name' => 'root管理者',
                'password' => Hash::make('rootroot'),
                'role' => 5,
            ],
            [
                'loginid' => 'admin1',
                'name' => 'admin1管理者',
                'password' => Hash::make('adminadmin'),
                'role' => 5,
            ],
            [
                'loginid' => 'admin2',
                'name' => 'admin2管理者',
                'password' => Hash::make('adminadmin'),
                'role' => 5,
            ],
            [
                'loginid' => 'admin3',
                'name' => 'admin3管理者',
                'password' => Hash::make('adminadmin'),
                'role' => 5,
            ],
            [
                'loginid' => 'admin4',
                'name' => 'admin4管理者',
                'password' => Hash::make('adminadmin'),
                'role' => 5,
            ],
            [
                'loginid' => 'cmaneadmin',
                'name' => '管理者',
                'password' => Hash::make('cmaneadmin'),
                'role' => 5,
            ],
        ]);
        factory(App\User::class, 30)->create();
    }
}

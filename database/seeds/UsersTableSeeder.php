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
                'entry_date' => '2000/01/01',
            ],
            [
                'loginid' => 'admin1',
                'name' => 'admin1管理者',
                'password' => Hash::make('adminadmin'),
                'role' => 5,
                'entry_date' => '2000/01/01',
            ],
            [
                'loginid' => 'admin2',
                'name' => 'admin2管理者',
                'password' => Hash::make('adminadmin'),
                'role' => 5,
                'entry_date' => '2000/01/01',
            ],
            [
                'loginid' => 'admin3',
                'name' => 'admin3管理者',
                'password' => Hash::make('adminadmin'),
                'role' => 5,
                'entry_date' => '2000/01/01',
            ],
            [
                'loginid' => 'admin4',
                'name' => 'admin4管理者',
                'password' => Hash::make('adminadmin'),
                'role' => 5,
                'entry_date' => '2000/01/01',
            ],
            [
                'loginid' => 'cmaneadmin',
                'name' => '管理者',
                'password' => Hash::make('cmaneadmin'),
                'role' => 5,
                'entry_date' => '2017/12/30',
            ],
            [
                'loginid' => 'cmaneuser',
                'name' => '一般ユーザ',
                'password' => Hash::make('cmaneuser'),
                'role' => 99,
                'entry_date' => '2015/04/20',
            ],
        ]);
//        factory(App\User::class, 30)->create();
    }
}

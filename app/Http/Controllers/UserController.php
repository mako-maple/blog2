<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Services\CSV;


class UserController extends Controller
{
    public function index()
    {
       $users = User::all();
       return ['users' => $users];
    }

    public function download()
    {
       $users = User::get(['loginid', 'name', 'role'])->toArray();
       $header = ['ログインID', '名前', '権限'];
       $csv = new CSV;
       return $csv->download($users, $header, 'user.csv');
    }

}

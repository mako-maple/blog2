<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use App\Actlog;


class ActlogController extends Controller
{
    public function index()
    {
        $actlogs = Actlog::select('*','actlogs.id as actid', 'actlogs.created_at as accessdate')
                         ->where('status', '=', '200')
                         ->leftjoin('users', 'users.id', '=', 'actlogs.user_id')
                         ->orderBy('actlogs.id', 'desc')
                         ->get();
        return ['actlogs' => $actlogs];
    }
}

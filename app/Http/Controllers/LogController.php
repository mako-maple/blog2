<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use \Route;


class LogController extends Controller
{
    public function log(Request $request)
    {
        $r = Route::getCurrentRequest();
        Log::Debug('route name: '. Route::currentRouteName());
        Log::Debug('request IP: '. $request -> ip());
        Log::Debug('request IP: '. $r -> ip());
        Log::Debug('request UA: '. $request -> userAgent());
        return;
    }
}
 

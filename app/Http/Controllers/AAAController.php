<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class AAAController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function csvlist()
    {
        // ほとんどＳＱＬ書いてる・・ほかの方法勉強セネバ
//        $csvslips = CsvSlip::select(
 $ret = DB::table('csv_slips')->select(
              'users.name'
            , 'users.loginid'
            , 'csv_slips.id AS csvid'
            , 'csv_slips.target'
            , 'csv_slips.filename'
            , 'csv_slips.header'
            , 'csv_slips.line'
            , 'csv_slips.error'
            , 'csv_slips.upload_userid'
            , 'csv_slips.created_at'
          )
          ->join('users', 'users.id', '=', 'csv_slips.upload_userid')
          ->orderBy('csv_slips.target', 'desc')
          ->orderBy('csv_slips.id', 'desc')
          ->get();
        //return ['csvslips' => $csvslips];
        return ['csvslips' => $ret];
    }
}

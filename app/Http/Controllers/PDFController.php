<?php

namespace App\Http\Controllers;

use App\User;
use App\CsvSlip;
use App\PaySlip;
use App\Services\CSV;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use TCPDF;
use TCPDF_FONTS;

class PDFController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function slip(Request $request)
    {
        mb_internal_encoding("UTF8");
        Log::Debug('PDF : csv_id : '. $request['csv_id'] .  ' slipid : '. $request['slipid']);

        // GET DATA
        $csv  = CsvSlip::find($request['csv_id']);
        $slip = PaySlip::find($request['slipid']);
        $user = User::find($slip->user_id);
/*
Log::Debug('CSV :'. print_r($csv->toArray(), true));
Log::Debug('SLIP:'. print_r($slip->toArray(), true));
Log::Debug('USER:'. print_r($user->toArray(), true));
*/

        // SET DATA 
        $ym = substr($slip->target,0,4) .'年'. substr($slip->target,4,2) .'月';
        $data['pay_ym'] = mb_convert_kana($ym, 'N');
        $data['company'] = env('MIX_COMPANY_NAME', 'あいうえお株式会社');
        $data['name'] = $user->name;

        // SET BLANK
        $data['title'] = array_fill(0, count($csv->header), '');
        $data['data'] = array_fill(0, count($slip->slip), '');

        // SET HEADER
        $header = $csv->header;
        array_shift($header);  // 最初のユーザIDを削除
        array_shift($header);  // 次のファイル名指定を削除
        $cnt = 0;
        foreach($header as $v) {
          $data['title'][$cnt++] = $v;
        }

        // SET DATA
        $csvrow = $slip->slip; 
        array_shift($csvrow);  // 最初のユーザIDを削除
        array_shift($csvrow);  // 次のファイル名指定を削除
        $cnt = 0;
        foreach($csvrow as $v) {
          $data['data'][$cnt++] = $v;
        }

        // PDF用HTML生成 
        $html = view("document.slip2", $data)->render();

        // PDF 生成メイン　－　A4 縦に設定
        $pdf = new TCPDF("P", "mm", "A4", true, "UTF-8" );
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        // PDF 署名
        $pdf->SetAuthor(env('MIX_COMPANY_NAME', 'aiueo.inc.'));
        $pdf->SetCreator(env('MIX_SLIP_CREATER', 'laravel '));

        // 日本語フォント設定
        $pdf->setFont('kozminproregular','',11);

        // ページ追加
        $pdf->addPage();

        // HTMLを描画、viewの指定と変数代入 - document/pdf.blade.php
        $pdf->writeHTML($html);

        // 出力指定 ファイル名、拡張子、D(ダウンロード)
        $pdf->output('laravel.pdf', 'D');
        return;
    }
}

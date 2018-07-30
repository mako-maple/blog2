<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use TCPDF;
use TCPDF_FONTS;

class DocumentController extends Controller
{
    public function __construct(TCPDF $pdf)
    { 
    }

    public function downloadPdf()
    {

        // データ
        $data['company'] = "あいうえお株式会社";
        $data['pay_ym'] = "２０１８年０８月";
        $data['name'] = "あいう　えお";
        $data['total'] = "9999999";
        $data['total_100'] = "9999999";
        $data['total_200'] = "9999999";

        for( $i = 101; $i <= 117 ; $i++ ){
          $data['title_'. $i] = 'ＴＩＴＬＥ＿'. $i;
          $data['data_'. $i] = 9999000 + $i;
        }

        for( $i = 201; $i <= 217 ; $i++ ){
          $data['title_'. $i] = 'ＴＩＴＬＥ＿'. $i;
          $data['data_'. $i] = 9999000 + $i;
        }

        for( $i = 301; $i <= 310 ; $i++ ){
          $data['title_'. $i] = 'ＴＩＴＬＥ＿'. $i;
          $data['data_'. $i] = $i + 0.01;
        }

        // A4 縦に設定
        $pdf = new TCPDF("P", "mm", "A4", true, "UTF-8" );
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        $pdf->SetAuthor('caaaaaa.inc.');
        $pdf->SetCreator('kuririn');


        // フォント、スタイル、サイズ をセット
        $pdf->setFont('kozminproregular','',12);

        // ページを追加
        $pdf->addPage();

        // HTMLを描画、viewの指定と変数代入
        $pdf->writeHTML(view("document.pdf3", $data)->render());

        // 出力指定 ファイル名、拡張子、D(ダウンロード)
        $pdf->output('test' . '.pdf', 'D');
        return;
   }
}

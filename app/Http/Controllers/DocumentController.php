<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use TCPDF;
use TCPDF_FONTS;

class DocumentController extends Controller
{
    public function __construct(TCPDF $pdf)
    { 
    }

    public function downloadPdf()
    {
        mb_internal_encoding("UTF8");

        // ダミーデータ設定
        $data['company'] = "あいうえお株式会社";
        $data['pay_ym'] = "２０１８年０８月";
        $data['name'] = "あいう　えお";

        for( $i=0; $i<=60; $i++ ){
          $wk = sprintf('%02d', $i);
          $data['title'.$wk] = 'タイトル'.mb_convert_kana("$wk", 'N');  
          if($i<=40) $data['data'.$wk] = 99999000 + $i;
          else       $data['data'.$wk] = 999.00 + ($i/100);
        }

        $data['data60'] = "むかしむかし、あるところに、おじいさんとおばあさんが住んでいました。
　おじいさんは山へ柴刈りに、おばあさんは川へ洗濯に行きました。
　おばあさんが川で洗濯をしていると、ドンブラコ、ドンブラコと、大きな桃が流れてきました。
「おや、これは良いおみやげになるわ」
　おばあさんは大きな桃をひろいあげて、家に持ち帰りました。
　そして、おじいさんとおばあさんが桃を食べようと桃を切ってみると、なんと中から元気の良い男の赤ちゃんが飛び出してきました。
「これはきっと、神さまがくださったにちがいない」
";
//　子どものいなかったおじいさんとおばあさんは、大喜びです。
//　桃から生まれた男の子を、おじいさんとおばあさんは桃太郎と名付けました。
//　桃太郎はスクスク育って、やがて強い男の子になりました。
//";
        $html = view("document.slip1", $data)->render();
Log::Debug('HTML\n'.$html);

        // PDF 生成メイン　－　A4 縦に設定
        $pdf = new TCPDF("P", "mm", "A4", true, "UTF-8" );
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        $pdf->SetAuthor('aiueo.inc.');
        $pdf->SetCreator('aiu eo');


        // 日本語フォント設定
        $pdf->setFont('kozminproregular','',11);

        // ページ追加
        $pdf->addPage();

        // HTMLを描画、viewの指定と変数代入 - document/pdf.blade.php
        //$pdf->writeHTML(view("document.pdf3", $data)->render());
        $pdf->writeHTML($html);

        // 出力指定 ファイル名、拡張子、D(ダウンロード)
        $pdf->output('201808_aiueo' . '.pdf', 'D');
        return;
   }
}

<?php

namespace App\Http\Controllers;

use App\User;
use App\CsvSlip;
use App\PaySlip;
use App\Services\CSV;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use TCPDF;
use TCPDF_FONTS;

class SlipController extends Controller
{
    private const C_USERID = 'item0';
    private const C_FILENAME = 'item1';
    private const C_DEFAULT_FILENAME = '給与明細';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function csvlist()
    {
        // ほとんどＳＱＬ書いてる・・ほかの方法勉強セネバ
        $csvslips = CsvSlip::select(
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
        return ['csvslips' => $csvslips];
    }

    public function sliplist(Request $request)
    {
Log::Debug('sliplist ('. $request->id .')');
        // ほとんどＳＱＬ書いてる・・ほかの方法勉強セネバ
        $slips = PaySlip::select(
              'users.name'
            , 'pay_slips.id AS slipid'
            , 'pay_slips.csv_id'
            , 'pay_slips.line'
            , 'pay_slips.target'
            , 'pay_slips.user_id'
            , 'pay_slips.loginid'
            , 'pay_slips.slip'
            , 'pay_slips.filename'
            , 'pay_slips.download'
            , 'pay_slips.created_at'
          )
          ->join('users', 'users.id', '=', 'pay_slips.user_id')
          ->where('pay_slips.csv_id', $request->id)
          ->orderBy('pay_slips.line', 'asc')
          ->get();

        $ret = $slips->toArray();
        foreach($ret as $k => $v) {
          $wk = $v['slip'];
          unset($v['slip']);
          $r[] = array_merge($v, $wk);
        }

        return ['slips' => $r];
       // return ['slips' => $ret];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function upload(Request $request)
    {
       // CSV操作準備
        $csv = new CSV;

        // アップロードファイルに対してのバリデート
        $validator = $csv->validate($request);
        if ($validator->fails() === true){
            return ['errors' => $validator->errors()];
        }

        // アップロードファイル名の取得
        $filename = $request->file('csvfile')->getClientOriginalName();

        // 対象年月を取得
        $target = $this->get_target_YYYYMM($request);
        if ($target === false) {
            return ['errors' => '対象年月指定エラー'];
        }

        // CSVをパース - false を指定してヘッダーを残しておく
        $rows = $csv->parse($request, false);

        // １行ずつ処理
        $csvslip_id = 0;
        $headercnt = 0;
        $errcnt = 0;
        $inscnt = 0;
        foreach ($rows as $line => $value) {

            // １行目ならヘッダー情報を保存
            if ($line == 0) {
              $data = array();
              $data['target'] = $target;
              $data['filename'] = $filename;
              $data['header'] = $value;
              $data['upload_userid'] = Auth::id();

              $csvslip = CsvSlip::create($data);
              Log::Debug($csvslip);
              $csvslip_id = $csvslip['id'];
              $ret['csvslip_id'] = $csvslip['id'];

              $headercnt = count($value);
              continue;
            }

            // 行データのカラム数チェック
            if (count($value) != $headercnt) {
              $ret['errors'][] = [
                'line' => $line, 
                'error' => "ヘッダーの項目数（". $headercnt ."）と行の項目数(". count($value) .")が一致しません"
              ];
              Log::Debug('import data error:'. print_r($ret['errors'][count($ret['errors'])-1], true));
              continue;
            }

            // CSV行データ設定
            $data = array();
            $data['csv_id'] = $csvslip_id;
            $data['line'] = $line;
            $data['target'] = $target;
            $data['slip'] = $value;
            $data['loginid'] = trim($value[self::C_USERID]);

            // ＰＤＦファイル名設定 - 指定があれば指定ファイル名を設定
Log::Debug('UP CSV FILENAME: '. $value[self::C_FILENAME]);
Log::Debug('UP CSV FILENAME: '. trim($value[self::C_FILENAME]));
            if (trim($value[self::C_FILENAME]) != '') {
              $data['filename'] = trim($value[self::C_FILENAME]);
            }
            else {
              $data['filename'] = self::C_DEFAULT_FILENAME;
            }

            // CSVに指定されたユーザ(社員)が存在しなければエラー
            $user = User::where('loginid', $value[self::C_USERID])->first();
            if (!$user) {
              $ret['errors'][] = ['line' => $line, 'error' => "該当社員が見つかりませんでした"];
              Log::Debug('import data error:'. print_r($ret['errors'][count($ret['errors'])-1], true));
              $errcnt++;
              $data['user_id'] = '';
              continue;
            }
            else {
              $inscnt++;
              $data['user_id'] = $user['id'];
            }

            // CSV行データ保存
            //Log::Debug('INSERT PAY_SLIP:'. print_r($data,true));
Log::Debug('UP CSV ROW: '. print_r($data, true));
            PaySlip::create($data);
        }

        // CSV行データ読み込み結果保存（正常行数、エラー数)
        Log::Debug('csv slip id: '. $csvslip_id .' line:'. $inscnt .'  err:'. $errcnt);
        $csvslip = CsvSlip::find($csvslip_id);
        $csvslip->line = $inscnt;
        $csvslip->error = $errcnt;
        $csvslip->save();

        // 戻る
        return ['import' => $ret];
    }

    public function get_target_YYYYMM(Request $request)
    {
      $target = trim($request['target']);
      Log::Debug('get_target_YYYYMM:'. $target);

      // 2010～2099年01～12月であること
      if (preg_match('/^20([1-9]{1}[0-9]{1})(0[1-9]{1}|1[0-2]{1})$/', $target))
      return $target;
      return false;
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function pdf(Request $request)
    {
        mb_internal_encoding("UTF8");
Log::Debug('PDF : csv_id : '. $request['csv_id']);
Log::Debug('PDF : slipid : '. $request['slipid']);
        // GET DATA
        $csv  = CsvSlip::find($request['csv_id']);
        $slip = PaySlip::find($request['slipid']);
        $user = User::find($slip->user_id);
Log::Debug('CSV :'. print_r($csv->toArray(), true));
Log::Debug('SLIP:'. print_r($slip->toArray(), true));
Log::Debug('USER:'. print_r($user->toArray(), true));

        // SET DATA 
        $ym = substr($slip->target,0,4) .'年'. substr($slip->target,4,2) .'月';
        $data['company'] = "あいうえお株式会社";
        $data['pay_ym'] = mb_convert_kana($ym, 'N');
        $data['name'] = $user->name;

        // SET BLANK
        $data['title'] = array_fill(0, 61, '');
        $data['data'] = array_fill(0, 61, '');

        // SET HEADER
        $header = $csv->header;
        array_shift($header);
        array_shift($header);
        $cnt = 0;
        foreach($header as $v) {
          $data['title'][$cnt++] = $v;
        }

        // SET DATA
        $csvrow = $slip->slip; 
        array_shift($csvrow);
        array_shift($csvrow);
        $cnt = 0;
        foreach($csvrow as $v) {
          $data['data'][$cnt++] = $v;
        }

        $html = view("document.slip2", $data)->render();
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
//$AA =         $pdf->output('o' . '.pdf', 'S');
//          $pdf->output('o.pdf', 'I');
//        return k['pdf' => $S];
          
        return ['pdf' => $html];
     
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function html(Request $request)
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
          
//        return ['html' => $html];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\CsvSlip  $csvSlip
     * @return \Illuminate\Http\Response
     */
    public function destroy(CsvSlip $csvSlip)
    {
        //
    }
}

<?php

namespace App\Http\Controllers;

use App\User;
use App\CsvSlip;
use App\PaySlip;
use App\Services\CSV;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class CsvSlipController extends Controller
{
    private const PDF_FILENAME = '給与明細';
    private const COLUMN_COUNT = 70;
    private const C_USERID = 0;
    private const C_FILENAME = 1;
    private const C_MEMO = 2;
    private const C_DEFAULT_FILENAME = '給与明細';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // ほとんどＳＱＬ書いてる・・ほかの方法勉強セネバ
        $csvslips = CsvSlip::select('csv_slips.id as csvid'
                                  , 'target'
                                  , 'filename'
                                  , 'header'
                                  , 'line'
                                  , 'error'
                                  , 'upload_userid'
                                  , 'users.name'
                                  , 'loginid'
                                  , 'csv_slips.created_at')
                           ->join('users', 'users.id', '=', 'csv_slips.upload_userid')
                           ->orderBy('target', 'desc')
                           ->orderBy('csv_slips.id', 'desc')
                           ->get();
        return ['csvslips' => $csvslips];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function upload(Request $request)
    {

/*
    // 想定CSV構成
    // 1行目は印刷用タイトル
    // 2行目からデータ １人１行で明細情報の７０項目を指定

     1. ユーザID   :  社員を特定するID（メールアドレスを指定する想定）
     2. ファイル名 :  ファイル名に付け加える文字列（通常 YYYY年MM月給与明細.pdf -> YYYY年MM月ｘｘｘ.pdf になる）
     3. 通信事項   :  明細に出力したい文言を指定。改行する場合は ” ～～ ”でククルこと
     4. 振込額     :  差引支給額
     5. 総支給額   : 
     6. 総控除額   : 
     7. 予備１
     8. 予備２
     9. 予備３
    10. 予備４
    11. 支給０１        31. 控除０１        51. 勤怠０１
    12. 支給０２        32. 控除０２        52. 勤怠０２
    13. 支給０３        33. 控除０３        53. 勤怠０３
    14. 支給０４        34. 控除０４        54. 勤怠０４
    15. 支給０５        35. 控除０５        55. 勤怠０５
    16. 支給０６        36. 控除０６        56. 勤怠０６
    17. 支給０７        37. 控除０７        57. 勤怠０７
    18. 支給０８        38. 控除０８        58. 勤怠０８
    19. 支給０９        39. 控除０９        59. 勤怠０９
    20. 支給１０        40. 控除１０        60. 勤怠１０
    21. 支給１１        41. 控除１１        61. 勤怠１１
    22. 支給１２        42. 控除１２        62. 勤怠１２
    23. 支給１３        43. 控除１３        63. 勤怠１３
    24. 支給１４        44. 控除１４        64. 勤怠１４
    25. 支給１５        45. 控除１５        65. 勤怠１５
    26. 支給１６        46. 控除１６        66. 勤怠１６
    27. 支給１７        47. 控除１７        67. 勤怠１７
    28. 支給１８        48. 控除１８        68. 勤怠１８
    29. 支給１９        49. 控除１９        69. 勤怠１９
    30. 支給２０        50. 控除２０        70. 勤怠２０

ユーザID,ファイル名,通信事項,振込額,総支給額,総控除額,予備１,予備２,予備３,予備４,支給０１,支給０２,支給０３,支給０４,支給０５,支給０６,支給０７,支給０８,支給０９,支給１０,支給１１,支給１２,支給１３,支給１４,支給１５,支給１６,支給１７,支給１８,支給１９,支給２０,控除０１,控除０２,控除０３,控除０４,控除０５,控除０６,控除０７,控除０８,控除０９,控除１０,控除１１,控除１２,控除１３,控除１４,控除１５,控除１６,控除１７,控除１８,控除１９,控除２０,勤怠０１,勤怠０２,勤怠０３,勤怠０４,勤怠０５,勤怠０６,勤怠０７,勤怠０８,勤怠０９,勤怠１０,勤怠１１,勤怠１２,勤怠１３,勤怠１４,勤怠１５,勤怠１６,勤怠１７,勤怠１８,勤怠１９,勤怠２０,

*/
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
Log::Debug("123456789");
        $rows = $csv->parse($request, false);
Log::Debug("ABCDEFGHi");

        // １行ずつ処理
        $csvslip_id = 0;
        $errcnt = 0;
        $inscnt = 0;
        foreach ($rows as $line => $value) {

            // 行データのカラム数チェック
            if (count($value) < self::COLUMN_COUNT) {
              $ret['errors'][] = ['line' => $line+1, 'error' => "CSV項目数(". count($value) .")が ". self::COLUMN_COUNT ."ではありません"];
              Log::Debug('import data error:'. print_r($ret['errors'][count($ret['errors'])-1], true));
              if ($line == 0) break;  // １行目のヘッダーなら即座に終了
            }

            // １行目ならヘッダー情報を保存
            if ($line == 0) {
              $data = array();
              $data['target'] = $target;
              $data['filename'] = $filename;
              $data['header'] = $value;
              $data['upload_userid'] = Auth::id();

//Log::Debug('INSERT CSV_SLIP:'. print_r($data,true));
              $csvslip = CsvSlip::create($data);
              Log::Debug($csvslip);
              $csvslip_id = $csvslip['id'];
              $ret['csvslip_id'] = $csvslip['id'];
              continue;
            }

            // CSV行データ設定
            $data = array();
            $data['csv_id'] = $csvslip_id;
            $data['no'] = $line;
            $data['target'] = $target;
            $data['slip'] = Crypt::encrypt(serialize($value));
//            $data['slip'] = $value;
            $data['loginid'] = trim($value[self::C_USERID]);

            // ＰＤＦファイル名設定 - 指定がなければデフォルト設定
            if (trim($value[self::C_FILENAME]) == '') {
              $data['filename'] = C_DEFAULT_FILENAME;
            }
            else {
              $data['filename'] = trim($value[self::C_FILENAME]);
            }


            // CSVに指定されたユーザ(社員)が存在しなければエラー
            $user = User::where('loginid', $value[self::C_USERID])->first();
            if (!$user) {
              $ret['errors'][] = ['line' => $line, 'error' => "該当社員が見つかりませんでした"];
              Log::Debug('import data error:'. print_r($ret['errors'][count($ret['errors'])-1], true));
              $errcnt++;
              $data['user_id'] = '';
            }
            else {
              $inscnt++;
              $data['user_id'] = $user['id'];
            }

            // CSV行データ保存
            //Log::Debug('INSERT PAY_SLIP:'. print_r($data,true));
            PaySlip::create($data);
        }

        // CSV行データ読み込み結果保存（社員数、エラー数)
        CsvSlip::find($csvslip_id)->update(['line' => $inscnt, 'error' => $errcnt]);

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

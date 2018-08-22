<?php
namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Response;

use Goodby\CSV\Import\Standard\Lexer;
use Goodby\CSV\Import\Standard\Interpreter;
use Goodby\CSV\Import\Standard\LexerConfig;

class CSV
{

    public function __construct()
    {
    }

    /**
     * CSVダウンロード
     * @param array $list
     * @param array $header
     * @param string $filename
     * @return \Illuminate\Http\Response
     */
    public function download($list, $header, $filename)
    {
        if (count($header) > 0) {
            array_unshift($list, $header);
        }
        $stream = fopen('php://temp', 'r+b');
        foreach ($list as $row) {
            fputcsv($stream, $row);
        }
        rewind($stream);
        $csv = str_replace(PHP_EOL, "\r\n", stream_get_contents($stream));
        $csv = mb_convert_encoding($csv, 'SJIS-win', 'UTF-8');
        $headers = array(
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=$filename",
        );
        return \Response::make($csv, 200, $headers);
    }

    /**
      * CSV取り込み
     * @param Request $request
     * @param bool $header-flg
     * @return rows
     **/
    public function parse(Request $request, bool $headerFlg = true)
    {
        // CSVファイル取り込み
        $file = $request->file('csvfile');

        // Goodby CSVのconfig設定
        $config = new LexerConfig();
        $interpreter = new Interpreter();
        $lexer = new Lexer($config);

        // CharsetをUTF-8に変換
        $config->setToCharset("UTF-8");
        $config->setFromCharset("sjis-win");

        // CSVデータをパース
        $rows = array();
        $interpreter->addObserver(function(array $row) use (&$rows) {
            $rows[] = $row;
        });
        $lexer->parse($file, $interpreter);

        // １行ずつ処理
        $data = array();
        foreach ($rows as $key => $value) {
            // 最初の行はヘッダー
            if($key == 0) { 
                $header = $value;
                Log::Debug('header', $header); 
                if($headerFlg) continue; 
            }
            
            // 配列化 - ２行目以降はヘッダーに沿って配列に
            //   header-flg が true  なら、 data[999]['ヘッダー'] = データ
            //   header-flg が false なら、 data[999][item999] = データ
            foreach ($value as $k => $v) {
                if ($headerFlg) $data[$key][$header[$k]] = $v;
                else            $data[$key]['item'.$k] = $v;
            }
        }
    
        // ＣＳＶを配列で戻す
        Log::Debug('rows', $data);
        return $data;
    }

    /**
     * アップロードファイルのバリデート
     * （※本来はFormRequestClassで行うべき）
     *
     * @param Request $request
     * @return Illuminate\Validation\Validator
     */
    public function validate(Request $request)
    {
        return \Validator::make($request->all(), 
            [
                'csvfile' => 'required|file|mimetypes:text/plain|mimes:csv,txt', 
            ], 
            [
                'csvfile.required'  => 'ファイルを選択してください。',
                'csvfile.file'      => 'ファイルアップロードに失敗しました。',
                'csvfile.mimetypes' => 'ファイル形式が不正です。',
                'csvfile.mimes'     => 'ファイル拡張子が異なります。',
            ]
        );
    }
}

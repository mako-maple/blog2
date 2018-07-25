<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

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
        $header = ['loginid', 'name', 'role'];
        $csv = new CSV;
        return $csv->download($users, $header, 'user.csv');
    }

    public function upload(Request $request)
    {
        // CSV操作準備
        $csv = new CSV;

        // アップロードファイルに対してのバリデート
        $validator = $csv->validate($request);
        if ($validator->fails() === true){
            return ['error' => $validator->errors()];
        }

        // CSVをパース
        $rows = $csv->parse($request);

        // １行ずつ処理
        foreach ($rows as $key => $value) {

            // 行データに対してのバリデート（必須・内容の確認）
            $v = $this->upload_row_validate($value);

            // ＣＳＶに問題があればバリデートエラーを記録 => 処理は継続
            if ($v->fails()) {
              foreach ($v->errors()->all() as $message) {
                Log::Debug(__CLASS__.':'.__FUNCTION__.'import data error line :'. $key .' message: '. $message);
                $ret['errors'][] = ['line' => $key, 'error' => $message];
              }
            }

            // ＣＳＶに問題がなければ 更新 or 挿入
            else {
              // SELECT
              $user = User::where('loginid', $value['loginid'])->first();

              // 存在したら、更新
              if( $user ) { 
                Log::Debug(__CLASS__.':'.__FUNCTION__.'import update line :'. $key .' name: '. $value['name']);
                $user->fill($value)->save();
                $ret['update'][] = ['line' => $key, 'name' => $value['name']];
              }

              // ＤＢ未登録なら新規登録
              else { 
                Log::Debug(__CLASS__.':'.__FUNCTION__.'import insert line :'. $key .' name: '. $value['name']);
                $value['password'] = Hash::make($value['loginid']);
                User::create($value);
                $ret['insert'][] = ['line' => $key, 'name' => $value['name']];
              }
            }
        }
        return ['import' => $ret];
    }

    private function upload_row_validate($row) 
    {
        return \Validator::make($row, [
            'loginid' => 'required|string|max:100',
            'name' => 'required|string|max:255',
            'role' => [
                'required',
                'numeric',
                Rule::in([5, 10]),   // role値は 5 か 10 であること
            ],
        ]);
    }
}
 
